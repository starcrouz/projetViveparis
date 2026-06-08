<?php
include("globalData.php");
include("fonctions.php");
include("fonctionsFront.php");

$db = connecterBdd();

// Récupération sécurisée des variables d'URL
$zoom = isset($_GET['zoom']) ? (int)$_GET['zoom'] : 11;
$x = isset($_GET['x']) ? (int)$_GET['x'] : 0;
$y = isset($_GET['y']) ? (int)$_GET['y'] : 0;
$provenance = isset($_GET['provenance']) ? $_GET['provenance'] : '';
$PHP_SELF = $_SERVER['PHP_SELF'];

// Récupération de tous les lieux pour affichage dynamique
$lieux_data = [];
try {
    $sql = "SELECT * FROM lieux";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id = (int)$row['id'];
        $nbMedias = compterNbDeMedias($id, $db);
        
        // On n'affiche le lieu sur la carte que s'il y a des médias associés
        // ou si on est en train de créer un nouveau lieu
        if ($nbMedias > 0 || $provenance == 'nouveauLieu') {
            // Récupération de la catégorie et des pictos associés
            $sql_tdl = "SELECT tdl.* FROM lieux_typesdelieux AS lt, typesdelieux AS tdl WHERE lt.idtypedelieu=tdl.id AND lt.idlieu = :idLieu";
            $stmt_tdl = $db->prepare($sql_tdl);
            $stmt_tdl->execute(['idLieu' => $id]);
            $tdl = $stmt_tdl->fetch(PDO::FETCH_ASSOC);
            
            $couleur = ($tdl && !empty($tdl['couleur'])) ? $tdl['couleur'] : 'blanc.gif';
            $picto = ($tdl && !empty($tdl['picto'])) ? $tdl['picto'] : 'defaut.gif';
            
            // Titre formaté
            $titreLieu = titresLieu($id, '', $db);
            $titreLieuText = isset($titreLieu[0]['titre']) ? $titreLieu[0]['titre'] : 'Lieu sans nom';
            
            $lieux_data[] = [
                'id' => $id,
                'x' => (int)$row['x'],
                'y' => (int)$row['y'],
                'couleur' => $couleur,
                'picto' => $picto,
                'nbMedias' => $nbMedias,
                'titre' => $titreLieuText . " (" . $nbMedias . " médias)"
            ];
        }
    }
} catch (PDOException $e) {
    die("Erreur base de données dans zoom.php : " . $e->getMessage());
}

fermerBdd($db);

$centrageX = 0;
$centrageY = 0;
$calageX = 117 + $centrageX;
$calageY = 100 + $centrageY;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Plan de Paris - ViveParis</title>
<link rel="stylesheet" href="styles/styles.css" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div class="page-wrapper-carte">

  <!-- Bannière informative si en mode sélection de coordonnées -->
  <?php if ($provenance == 'nouveauLieu'): ?>
  <div class="mode-selection-banner">
    Mode sélection de coordonnées : Double-cliquez ou cliquez sur le plan pour positionner le lieu.
  </div>
  <?php endif; ?>

  <!-- Encadrement graphique de la carte -->
  <div id='encadrement' style='position:absolute; width:1024px; height:681px; z-index:5; left: <?php echo $centrageX; ?>px; top: <?php echo $centrageY; ?>px'>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr> 
        <td valign="middle" align="center">
          <img src="images/encadrement.png" width="1024" height="681" border="0"> 
        </td>
      </tr>
    </table>
  </div>

  <!-- Titre / Texte haut de page -->
  <div id='ZoneTexteHaute' style='position:absolute; width:250px; height:30px; z-index:15; left: <?php echo $calageX + 275; ?>px; top: <?php echo $calageY - 50; ?>px'>
    <p name='zoneTexteHauteHtml' class='zoneTexteHaute' style="margin: 0; text-align: center;">
      Visualisation interactive du plan de Paris
    </p>
  </div>
  
  <!-- Boutons de zoom et contrôles en haut -->
  <div id='boutonsHaut' style='position:absolute; width:800px; height:30px; z-index:10; left: <?php echo $calageX; ?>px; top: <?php echo $calageY - 30; ?>px'>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="55%" align="left" style="padding-left: 10px;">
          <a href="#" id="btn-zoom-in" class="map-btn">Zoomer (+)</a>
          &nbsp;/&nbsp;
          <a href="#" id="btn-zoom-out" class="map-btn">Dézoomer (-)</a>
          &nbsp;/&nbsp;
          <a href="#" id="btn-reset" class="map-btn">Voir tout Paris</a>
          &nbsp;|&nbsp;&nbsp;Plan :&nbsp;
          <a href="#" id="btn-map-1953" class="map-btn active">1953</a>
          &nbsp;/&nbsp;
          <a href="#" id="btn-map-2020" class="map-btn">2020</a>
        </td>
        <td width="45%" align="right" style="padding-right: 10px; font-family: Arial, sans-serif; font-size: 11px; color: #555;">
          Déplacez le plan en le glissant avec la souris.
        </td>
      </tr>
    </table>
  </div>

  <!-- Le conteneur principal du plan (Viewport) -->
  <div id="plans" style="position:absolute; width:800px; height:470px; z-index:20; left: <?php echo $calageX; ?>px; top: <?php echo $calageY; ?>px; overflow: hidden; background: #ffffff; border: 1px solid #ccc; box-sizing: border-box;">
    <div id="map-container" style="position: absolute; width: 675px; height: 445.5px; transform-origin: 0 0; cursor: grab; user-select: none; touch-action: none;">
      
      <!-- Image complète de Paris (basse-résolution / arrière-plan de chargement) -->
      <img id="map-complete" src="plans/1953/parisComplet675x450.jpg" style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; pointer-events: none;">
      
      <!-- Conteneur des tuiles haute-résolution chargées dynamiquement -->
      <div id="map-tiles" style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; pointer-events: none;"></div>
      
      <!-- Conteneur des pictogrammes/marqueurs de lieux -->
      <div id="map-places" style="position: absolute; left: 0; top: 0; width: 100%; height: 100%;"></div>
    </div>
  </div>

</div>

<!-- Script de contrôle cartographique JS -->
<script>
const lieuxData = <?php echo json_encode($lieux_data); ?>;
const provenance = <?php echo json_encode($provenance); ?>;
const initialParams = {
    x: <?php echo isset($_GET['x']) ? (int)$_GET['x'] : 'null'; ?>,
    y: <?php echo isset($_GET['y']) ? (int)$_GET['y'] : 'null'; ?>,
    zoom: <?php echo isset($_GET['zoom']) ? (int)$_GET['zoom'] : 'null'; ?>
};

window.ouvrirModalLieu = function(url) {
    const modal = document.getElementById('place-modal');
    const iframe = document.getElementById('modal-iframe');
    iframe.src = url;
    modal.classList.add('open');
};

window.fermerModalLieu = function() {
    const modal = document.getElementById('place-modal');
    const iframe = document.getElementById('modal-iframe');
    if (typeof window.fermerParentLightbox === 'function') {
        window.fermerParentLightbox();
    }
    modal.classList.remove('open');
    setTimeout(() => {
        iframe.src = 'about:blank';
    }, 300);
};

// Parent Lightbox Controls
var parentLightboxCallback = null;
var parentListeMedias = [];
var parentCurrentMediaId = null;

window.ouvrirParentLightbox = function(url, currentId, listeMedias, callback) {
    const lightbox = document.getElementById('parent-lightbox-modal');
    const img = document.getElementById('parent-lightbox-img');
    img.src = url;
    
    parentListeMedias = listeMedias;
    parentCurrentMediaId = currentId;
    parentLightboxCallback = callback;
    
    const prevBtn = lightbox.querySelector('.lightbox-prev');
    const nextBtn = lightbox.querySelector('.lightbox-next');
    if (parentListeMedias.length <= 1) {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
    } else {
        if (prevBtn) prevBtn.style.display = 'block';
        if (nextBtn) nextBtn.style.display = 'block';
    }
    
    lightbox.classList.add('open');
};

window.fermerParentLightbox = function() {
    const lightbox = document.getElementById('parent-lightbox-modal');
    if (lightbox) lightbox.classList.remove('open');
};

window.naviguerParentLightbox = function(direction, event) {
    if (event) event.stopPropagation();
    if (parentListeMedias.length <= 1) return;
    
    var idx = parentListeMedias.findIndex(m => m.id === parentCurrentMediaId);
    if (idx === -1) return;
    
    var newIdx = idx + direction;
    if (newIdx < 0) {
        newIdx = parentListeMedias.length - 1;
    } else if (newIdx >= parentListeMedias.length) {
        newIdx = 0;
    }
    
    var newMedia = parentListeMedias[newIdx];
    parentCurrentMediaId = newMedia.id;
    
    const img = document.getElementById('parent-lightbox-img');
    if (img) img.src = newMedia.url;
    
    if (typeof parentLightboxCallback === 'function') {
        parentLightboxCallback(newMedia.id);
    }
};

document.addEventListener('keydown', (e) => {
    const lightbox = document.getElementById('parent-lightbox-modal');
    if (lightbox && lightbox.classList.contains('open')) {
        if (e.key === 'ArrowLeft') {
            window.naviguerParentLightbox(-1);
        } else if (e.key === 'ArrowRight') {
            window.naviguerParentLightbox(1);
        } else if (e.key === 'Escape') {
            window.fermerParentLightbox();
        }
    }
});

class ParisMap {
    constructor(containerEl, data, provenance, initialParams) {
        this.container = containerEl;
        this.mapContainer = containerEl.querySelector('#map-container');
        this.mapComplete = containerEl.querySelector('#map-complete');
        this.tileContainer = containerEl.querySelector('#map-tiles');
        this.placesContainer = containerEl.querySelector('#map-places');
        
        this.lieux = data;
        this.provenance = provenance;
        
        // Map Dimensions
        this.width = 7500;
        this.height = 4950;
        
        const plansEl = document.getElementById('plans');
        this.viewportW = plansEl ? plansEl.clientWidth : 800;
        this.viewportH = plansEl ? plansEl.clientHeight : 470;
        
        // Calculate the initial scale dynamically to completely cover the viewport and crop out margins (5% extra zoom)
        const fitScale = Math.max(this.viewportW / this.width, this.viewportH / this.height) * 1.05;
        
        this.currentMap = '1953';
        
        // If the fit scale is large (on big screens), use tranches5 tiles for the overview level to prevent pixelation
        const isLargeScreen = fitScale >= 0.13;
        const overviewZoomVal = isLargeScreen ? 5 : null;
        const overviewFolder1953 = isLargeScreen ? '1953/tranches5' : null;
        const overviewFolder2020 = isLargeScreen ? 'tranches5' : null;
        
        const rawLevels1953 = [
            { scale: fitScale, zoomVal: overviewZoomVal, folder: overviewFolder1953 }, // Paris complet
            { scale: 0.2, zoomVal: 5, folder: '1953/tranches5' },
            { scale: 0.333333, zoomVal: 3, folder: '1953/tranches3' },
            { scale: 1.0, zoomVal: 1, folder: '1953/tranches1' },
            { scale: 2.5, zoomVal: 2, folder: '1953/tranches2' },
            { scale: 6.4, zoomVal: 0, folder: '1953/tranches0' }
        ];
        
        const rawLevels2020 = [
            { scale: fitScale, zoomVal: overviewZoomVal, folder: overviewFolder2020 }, // Paris complet
            { scale: 0.2, zoomVal: 5, folder: 'tranches5' },
            { scale: 0.333333, zoomVal: 3, folder: 'tranches3' },
            { scale: 1.0, zoomVal: 1, folder: 'tranches1' }
        ];
        
        // Filter out intermediate levels that are smaller than or too close to fitScale
        this.zoomLevels1953 = [rawLevels1953[0]].concat(
            rawLevels1953.slice(1).filter(lvl => lvl.scale > fitScale * 1.1)
        );
        this.zoomLevels2020 = [rawLevels2020[0]].concat(
            rawLevels2020.slice(1).filter(lvl => lvl.scale > fitScale * 1.1)
        );
        
        this.zoomLevels = this.zoomLevels1953;
        this.currentLevelIndex = 0;
        
        // State
        this.scale = fitScale;
        this.tx = (this.viewportW - this.width * this.scale) / 2;
        this.ty = (this.viewportH - this.height * this.scale) / 2;
        this.isDragging = false;
        this.startX = 0;
        this.startY = 0;
        this.startTx = 0;
        this.startTy = 0;
        this.dragDistance = 0;
        this.isAnimating = false;
        
        // Parse legacy URL params if present
        this.initFromParams(initialParams);
        
        // Initialize
        this.renderMarkers();
        this.initEvents();
        this.update(false);
    }
    
    initFromParams(params) {
        if (params.zoom !== null) {
            const legacyZoom = params.zoom;
            const legacyCol = params.x !== null ? params.x : 5;
            const legacyRow = params.y !== null ? params.y : 5;
            
            let targetLvl = 0;
            if (legacyZoom === 5) targetLvl = 1;
            else if (legacyZoom === 3) targetLvl = 2;
            else if (legacyZoom === 1) targetLvl = 3;
            
            this.currentLevelIndex = targetLvl;
            this.scale = this.zoomLevels[targetLvl].scale;
            
            // Center viewport on the legacy column/row
            const targetX = (legacyCol - 0.5) * 750;
            const targetY = (legacyRow + 0.5) * 450;
            
            this.tx = this.viewportW / 2 - targetX * this.scale;
            this.ty = this.viewportH / 2 - targetY * this.scale;
            this.constrainBounds();
        }
    }
    
    renderMarkers() {
        this.placesContainer.innerHTML = '';
        this.lieux.forEach(lieu => {
            const marker = document.createElement('div');
            marker.className = 'map-marker';
            marker.style.left = `${(lieu.x / this.width) * 100}%`;
            marker.style.top = `${(lieu.y / this.height) * 100}%`;
            marker.title = `${lieu.titre}`;
            
            const dot = document.createElement('img');
            dot.src = `images/pictosLieux/${lieu.couleur}`;
            dot.className = 'marker-dot';
            
            const picto = document.createElement('img');
            picto.src = `images/pictosLieux/${lieu.picto}`;
            picto.className = 'marker-picto';
            
            const link = document.createElement('a');
            if (this.provenance === 'nouveauLieu') {
                link.href = '#';
                link.onclick = (e) => {
                    e.preventDefault();
                    this.selectCoords(lieu.x, lieu.y);
                };
            } else {
                link.href = '#';
                link.onclick = (e) => {
                    e.preventDefault();
                    window.ouvrirModalLieu(`afficherLieu.php?idLieu=${lieu.id}`);
                };
            }
            
            link.addEventListener('pointerdown', (e) => e.stopPropagation());
            
            link.appendChild(dot);
            link.appendChild(picto);
            marker.appendChild(link);
            this.placesContainer.appendChild(marker);
        });
    }
    
    initEvents() {
        // Pointer down
        this.mapContainer.addEventListener('pointerdown', (e) => {
            if (this.isAnimating) return;
            this.isDragging = true;
            this.startX = e.clientX;
            this.startY = e.clientY;
            this.startTx = this.tx;
            this.startTy = this.ty;
            this.dragDistance = 0;
            this.mapContainer.setPointerCapture(e.pointerId);
            this.mapContainer.style.cursor = 'grabbing';
        });
        
        // Pointer move
        this.mapContainer.addEventListener('pointermove', (e) => {
            if (!this.isDragging) return;
            const dx = e.clientX - this.startX;
            const dy = e.clientY - this.startY;
            this.dragDistance += Math.abs(dx) + Math.abs(dy);
            this.tx = this.startTx + dx;
            this.ty = this.startTy + dy;
            this.constrainBounds();
            this.update(false);
        });
        
        // Pointer up
        const endDrag = (e) => {
            if (!this.isDragging) return;
            this.isDragging = false;
            this.mapContainer.releasePointerCapture(e.pointerId);
            this.mapContainer.style.cursor = 'grab';
            
            if (this.dragDistance < 5) {
                this.handleMapClick(e);
            } else {
                this.loadTiles();
            }
        };
        
        this.mapContainer.addEventListener('pointerup', endDrag);
        this.mapContainer.addEventListener('pointercancel', endDrag);
        
        // Mouse Wheel zoom (only when hovering the map container)
        const plansEl = document.getElementById('plans');
        if (plansEl) {
            plansEl.addEventListener('wheel', (e) => {
                e.preventDefault();
                if (this.isAnimating) return;
                const zoomIn = e.deltaY < 0;
                this.zoomStep(zoomIn, e.clientX, e.clientY);
            }, { passive: false });
        }
        
        // Control buttons
        const btnIn = document.getElementById('btn-zoom-in');
        if (btnIn) {
            btnIn.addEventListener('click', (e) => {
                e.preventDefault();
                this.zoomStep(true);
            });
        }
        
        const btnOut = document.getElementById('btn-zoom-out');
        if (btnOut) {
            btnOut.addEventListener('click', (e) => {
                e.preventDefault();
                this.zoomStep(false);
            });
        }
        
        const btnReset = document.getElementById('btn-reset');
        if (btnReset) {
            btnReset.addEventListener('click', (e) => {
                e.preventDefault();
                this.zoomToLevel(0);
            });
        }
        
        const btnMap1953 = document.getElementById('btn-map-1953');
        if (btnMap1953) {
            btnMap1953.addEventListener('click', (e) => {
                e.preventDefault();
                this.switchMap('1953');
            });
        }
        
        const btnMap2020 = document.getElementById('btn-map-2020');
        if (btnMap2020) {
            btnMap2020.addEventListener('click', (e) => {
                e.preventDefault();
                this.switchMap('2020');
            });
        }
    }
    
    switchMap(mapName) {
        if (this.currentMap === mapName) return;
        this.currentMap = mapName;
        
        const btn1953 = document.getElementById('btn-map-1953');
        const btn2020 = document.getElementById('btn-map-2020');
        if (mapName === '1953') {
            if (btn1953) btn1953.classList.add('active');
            if (btn2020) btn2020.classList.remove('active');
            this.zoomLevels = this.zoomLevels1953;
            this.mapComplete.src = 'plans/1953/parisComplet675x450.jpg';
        } else {
            if (btn1953) btn1953.classList.remove('active');
            if (btn2020) btn2020.classList.add('active');
            this.zoomLevels = this.zoomLevels2020;
            this.mapComplete.src = 'plans/parisComplet675x450.jpg';
        }
        
        this.tileContainer.innerHTML = '';
        
        if (this.currentLevelIndex >= this.zoomLevels.length) {
            const targetLvl = this.zoomLevels.length - 1;
            const targetX_screen = this.viewportW / 2;
            const targetY_screen = this.viewportH / 2;
            const centerX_abs = (targetX_screen - this.tx) / this.scale;
            const centerY_abs = (targetY_screen - this.ty) / this.scale;
            
            this.currentLevelIndex = targetLvl;
            this.scale = this.zoomLevels[targetLvl].scale;
            
            this.tx = targetX_screen - centerX_abs * this.scale;
            this.ty = targetY_screen - centerY_abs * this.scale;
        } else {
            this.scale = this.zoomLevels[this.currentLevelIndex].scale;
        }
        
        this.constrainBounds();
        this.update(false);
    }
    
    selectCoords(x, y) {
        if (window.opener && !window.opener.closed) {
            window.opener.recevoirCoordonnees(Math.round(x), Math.round(y));
            window.close();
        }
    }
    
    handleMapClick(e) {
        const rect = this.mapContainer.getBoundingClientRect();
        const absoluteX = (e.clientX - rect.left) / this.scale;
        const absoluteY = (e.clientY - rect.top) / this.scale;
        
        if (this.provenance === 'nouveauLieu') {
            this.selectCoords(absoluteX, absoluteY);
        } else {
            if (this.currentLevelIndex < this.zoomLevels.length - 1) {
                this.zoomStep(true, e.clientX, e.clientY, false);
            }
        }
    }
    
    zoomStep(zoomIn, clientX = null, clientY = null, centerPoint = false) {
        let nextLvl = this.currentLevelIndex + (zoomIn ? 1 : -1);
        nextLvl = Math.max(0, Math.min(this.zoomLevels.length - 1, nextLvl));
        if (nextLvl === this.currentLevelIndex) return;
        
        this.zoomToLevel(nextLvl, clientX, clientY, centerPoint);
    }
    
    zoomToLevel(levelIndex, clientX = null, clientY = null, centerPoint = false) {
        this.isAnimating = true;
        this.mapContainer.classList.add('animating');
        
        const targetLvl = this.zoomLevels[levelIndex];
        const nextScale = targetLvl.scale;
        
        let centerX_abs, centerY_abs;
        let targetX_screen, targetY_screen;
        
        if (clientX !== null && clientY !== null) {
            const mapRect = this.mapContainer.getBoundingClientRect();
            centerX_abs = (clientX - mapRect.left) / this.scale;
            centerY_abs = (clientY - mapRect.top) / this.scale;
            
            if (centerPoint) {
                targetX_screen = this.viewportW / 2;
                targetY_screen = this.viewportH / 2;
            } else {
                const plansEl = document.getElementById('plans');
                const plansRect = plansEl.getBoundingClientRect();
                targetX_screen = clientX - plansRect.left;
                targetY_screen = clientY - plansRect.top;
            }
        } else {
            targetX_screen = this.viewportW / 2;
            targetY_screen = this.viewportH / 2;
            
            centerX_abs = (targetX_screen - this.tx) / this.scale;
            centerY_abs = (targetY_screen - this.ty) / this.scale;
        }
        
        this.currentLevelIndex = levelIndex;
        this.scale = nextScale;
        
        this.tx = targetX_screen - centerX_abs * this.scale;
        this.ty = targetY_screen - centerY_abs * this.scale;
        
        this.constrainBounds();
        this.update(true);
        
        const onTransitionEnd = () => {
            this.isAnimating = false;
            this.mapContainer.classList.remove('animating');
            this.loadTiles(true);
            this.mapContainer.removeEventListener('transitionend', onTransitionEnd);
        };
        this.mapContainer.addEventListener('transitionend', onTransitionEnd);
    }
    
    constrainBounds() {
        const mapW = this.width * this.scale;
        const mapH = this.height * this.scale;
        
        if (mapW >= this.viewportW) {
            this.tx = Math.max(this.viewportW - mapW, Math.min(0, this.tx));
        } else {
            this.tx = (this.viewportW - mapW) / 2;
        }
        
        if (mapH >= this.viewportH) {
            this.ty = Math.max(this.viewportH - mapH, Math.min(0, this.ty));
        } else {
            this.ty = (this.viewportH - mapH) / 2;
        }
    }
    
    update(animated) {
        this.mapContainer.style.width = `${this.width * this.scale}px`;
        this.mapContainer.style.height = `${this.height * this.scale}px`;
        this.mapContainer.style.transform = `translate(${this.tx}px, ${this.ty}px)`;
        this.container.style.setProperty('--map-scale', this.scale);
        
        this.container.className = 'page-wrapper-carte';
        if (this.currentLevelIndex === 0) {
            this.container.classList.add('zoom-level-0');
        } else if (this.currentLevelIndex === 1) {
            this.container.classList.add('zoom-level-1');
        } else {
            this.container.classList.add('zoom-level-2');
        }
        
        this.loadTiles(!animated);
    }
    
    loadTiles(onlyCurrent = true) {
        const currentLvl = this.zoomLevels[this.currentLevelIndex];
        const zoomVal = currentLvl.zoomVal;
        const folder = currentLvl.folder;
        
        const visibleTileIds = new Set();
        
        if (zoomVal !== null) {
            const buffer = 300; // preload buffer in absolute pixels
            const viewportLeft = -this.tx / this.scale - buffer;
            const viewportTop = -this.ty / this.scale - buffer;
            const viewportRight = (this.viewportW - this.tx) / this.scale + buffer;
            const viewportBottom = (this.viewportH - this.ty) / this.scale + buffer;
            
            const minCol = Math.max(1, Math.floor(viewportLeft / 750) + 1);
            const maxCol = Math.min(10, Math.ceil(viewportRight / 750));
            const minRow = Math.max(0, Math.floor(viewportTop / 450));
            const maxRow = Math.min(10, Math.ceil(viewportBottom / 450));
            
            for (let r = minRow; r <= maxRow; r++) {
                for (let c = minCol; c <= maxCol; c++) {
                    const tileId = `tile-${this.currentMap}-${zoomVal}-${c}-${r}`;
                    visibleTileIds.add(tileId);
                    
                    let img = document.getElementById(tileId);
                    if (!img) {
                        img = document.createElement('img');
                        img.id = tileId;
                        const cellIndex = r * 10 + c;
                        const paddedIndex = cellIndex < 10 ? `0${cellIndex}` : cellIndex;
                        img.src = `plans/${folder}/planParis_${paddedIndex}.jpg`;
                        img.className = 'map-tile';
                        img.style.left = `${(c - 1) * 10}%`;
                        img.style.top = `${r * (100 / 11)}%`;
                        img.style.width = '10%';
                        img.style.height = `${100 / 11}%`;
                        img.style.position = 'absolute';
                        img.style.zIndex = 10 + this.currentLevelIndex;
                        img.style.pointerEvents = 'none';
                        this.tileContainer.appendChild(img);
                    } else {
                        img.style.display = 'block';
                    }
                }
            }
        }
        
        if (onlyCurrent) {
            const allTiles = this.tileContainer.getElementsByTagName('img');
            for (let i = allTiles.length - 1; i >= 0; i--) {
                const tile = allTiles[i];
                if (!visibleTileIds.has(tile.id)) {
                    tile.style.display = 'none';
                }
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.parisMap = new ParisMap(
        document.querySelector('.page-wrapper-carte'),
        lieuxData,
        provenance,
        initialParams
    );
});
</script>

<!-- Fenêtre modale pour afficher les détails d'un lieu -->
<div id="place-modal" class="modal-overlay" onclick="if(event.target === this) window.fermerModalLieu();">
  <div class="modal-content">
    <iframe id="modal-iframe" name="lieu-iframe" src="about:blank"></iframe>
  </div>
</div>

<!-- Lightbox parent pour voir la photo en grand (pleine page) -->
<div id="parent-lightbox-modal" class="lightbox-overlay" onclick="fermerParentLightbox();">
  <span class="lightbox-close" onclick="fermerParentLightbox();">&times;</span>
  <span class="lightbox-prev" onclick="naviguerParentLightbox(-1, event);">&lt;</span>
  <img id="parent-lightbox-img" src="" alt="Photo en grand" onclick="event.stopPropagation();">
  <span class="lightbox-next" onclick="naviguerParentLightbox(1, event);">&gt;</span>
</div>

</body>
</html>
