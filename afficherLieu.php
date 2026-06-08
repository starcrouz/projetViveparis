<?php
session_start();
// Paramètres attendus par cette page : idLieu, idMedia
include("globalData.php");
include("fonctions.php");

$db = connecterBdd();
$PHP_SELF = $_SERVER['PHP_SELF'];

// Récupération sécurisée des paramètres
$idLieu = isset($_GET['idLieu']) ? (int)$_GET['idLieu'] : 0;
$idMedia = isset($_GET['idMedia']) ? (int)$_GET['idMedia'] : null;

// #### le lieu ####
$sql = "SELECT * FROM lieux WHERE id = :idLieu";
$stmt = $db->prepare($sql);
$stmt->execute(['idLieu' => $idLieu]);
$lieu = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$lieu) {
    die("Lieu non trouvé.");
}

// #### la catégorie du lieu ####
$sql = "SELECT tdl.* FROM lieux_typesdelieux AS lt, typesdelieux AS tdl WHERE tdl.id=lt.idtypedelieu AND lt.idlieu = :idLieu";
$stmt = $db->prepare($sql);
$stmt->execute(['idLieu' => $idLieu]);
$categorie = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$categorie) {
    $categorie = [
        'picto' => 'defaut.gif',
        'couleur' => 'blanc.gif',
        'titre' => 'Inconnu'
    ];
}

// #### le média ####
// s'il n'a pas été passé en paramètre (idMedia) on choisit celui de plus haut poids
if (!empty($idMedia)) { // le média demandé
    $sql = "SELECT * FROM medias WHERE id = :idMedia";
    $stmt = $db->prepare($sql);
    $stmt->execute(['idMedia' => $idMedia]);
} else { // le média de plus haut poids
    $sql = "SELECT medias.* FROM lieux_medias AS lm, medias WHERE lm.idlieu = :idLieu AND lm.idmedia=medias.id ORDER BY medias.poids DESC LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute(['idLieu' => $idLieu]);
}
$media = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$media) {
    die("Il n'y a pour l'instant aucun média pour ce lieu ! <a href='' onclick='return self.close();'>fermer</a>");
}
$idMedia = $media['id'];

// #### Traitement des votes ####
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote_note'])) {
    $voteNote = (int)$_POST['vote_note'];
    if ($voteNote >= 1 && $voteNote <= 10) {
        $currentPoids = (int)$media['poids'];
        $currentNoteCount = (int)$media['note'];
        
        $newNoteCount = $currentNoteCount + 1;
        $newPoids = round(($currentPoids * $currentNoteCount + $voteNote) / $newNoteCount);
        
        $sql_update = "UPDATE medias SET poids = :poids, note = :note WHERE id = :mediaId";
        $stmt_update = $db->prepare($sql_update);
        $stmt_update->execute([
            'poids' => $newPoids,
            'note' => $newNoteCount,
            'mediaId' => $idMedia
        ]);
        
        // Stocker en session que le vote a été effectué pour ce média
        $_SESSION['voted'][$idMedia] = true;
        
        // Redirection PRG (Post-Redirect-Get) pour éviter le double vote au rafraîchissement
        header("Location: " . $_SERVER['PHP_SELF'] . "?idLieu=" . $idLieu . "&idMedia=" . $idMedia);
        exit;
    }
}

// #### Détection du format portrait ####
$isPortrait = false;
$imgPath = "medias/" . $media['repertoire'] . "/" . $media['fichier'];
if (file_exists($imgPath)) {
    $size = getimagesize($imgPath);
    if ($size && $size[1] > $size[0]) {
        $isPortrait = true;
    }
}

// ### le nb de médias ###
$nbDeMedias = compterNbDeMedias($idLieu, $db);

// #### les imagettes ####
$sql = "SELECT * FROM lieux_medias AS lm, medias WHERE lm.idlieu = :idLieu AND lm.idmedia=medias.id ORDER BY medias.poids DESC";
$stmt = $db->prepare($sql);
$stmt->execute(['idLieu' => $idLieu]);
$imagette = [];
$i = 1;
$imagetteCentrale = 1;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $imagette[$i] = $row;
    if ($row['id'] == $idMedia) { // l'imagette du média affiché
        $imagetteCentrale = $i;
    }
    $i++;
}

fermerBdd($db);
?>

<html>
<head>
<title><?php echo htmlspecialchars($lieu['lieu'] . " : " . $media['titremedia']); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="styles/nouveau.css" type="text/css">

<script language="javascript">
<!--
window.focus();

function MM_findObj(n, d) { //v4.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function MM_showHideLayers() { //v3.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
    obj.visibility=v; }
}

function retournerAuPlan() {
  if (window.parent !== window && typeof window.parent.fermerModalLieu === 'function') {
    window.parent.fermerModalLieu();
  } else if (window.opener) {
    window.close();
  } else {
    window.location.href = 'index.php';
  }
}

function imprimerFiche() {
  window.focus();
  window.print();
}

var listeMedias = [
  <?php
  $js_medias = [];
  foreach ($imagette as $m) {
      $full_url = "medias/" . $m['repertoire'] . "/" . $m['fichier'];
      $js_medias[] = "{ id: " . (int)$m['id'] . ", url: '" . addslashes($full_url) . "' }";
  }
  echo implode(",", $js_medias);
  ?>
];
var currentMediaId = <?php echo (int)$idMedia; ?>;

function ouvrirLightbox(url) {
  if (window.parent && typeof window.parent.ouvrirParentLightbox === 'function') {
    window.parent.ouvrirParentLightbox(url, currentMediaId, listeMedias, (newId) => {
      const newUrl = window.location.pathname + "?idLieu=" + <?php echo $idLieu; ?> + "&idMedia=" + newId;
      loadMedia(newUrl, true);
    });
    return;
  }

  const lightbox = document.getElementById('lightbox-modal');
  const img = document.getElementById('lightbox-img');
  img.src = url;
  
  const prevBtn = document.querySelector('.lightbox-prev');
  const nextBtn = document.querySelector('.lightbox-next');
  if (listeMedias.length <= 1) {
    if (prevBtn) prevBtn.style.display = 'none';
    if (nextBtn) nextBtn.style.display = 'none';
  } else {
    if (prevBtn) prevBtn.style.display = 'block';
    if (nextBtn) nextBtn.style.display = 'block';
  }
  
  lightbox.classList.add('open');
}

function fermerLightbox() {
  const lightbox = document.getElementById('lightbox-modal');
  if (lightbox) lightbox.classList.remove('open');
}

function naviguerLightbox(direction, event) {
  if (event) event.stopPropagation();
  if (listeMedias.length <= 1) return;
  
  var idx = listeMedias.findIndex(m => m.id === currentMediaId);
  if (idx === -1) return;
  
  var newIdx = idx + direction;
  if (newIdx < 0) {
    newIdx = listeMedias.length - 1;
  } else if (newIdx >= listeMedias.length) {
    newIdx = 0;
  }
  
  var newMedia = listeMedias[newIdx];
  currentMediaId = newMedia.id;
  
  const img = document.getElementById('lightbox-img');
  if (img) img.src = newMedia.url;
  
  const url = window.location.pathname + "?idLieu=" + <?php echo $idLieu; ?> + "&idMedia=" + newMedia.id;
  loadMedia(url, true);
}

document.addEventListener('keydown', (e) => {
  const lightbox = document.getElementById('lightbox-modal');
  if (lightbox && lightbox.classList.contains('open')) {
    if (e.key === 'ArrowLeft') {
      naviguerLightbox(-1);
    } else if (e.key === 'ArrowRight') {
      naviguerLightbox(1);
    } else if (e.key === 'Escape') {
      fermerLightbox();
    }
  }
});

function ouvrirVoteModal() {
  const modal = document.getElementById('vote-modal');
  if (modal) modal.classList.add('open');
}

function fermerVoteModal() {
  const modal = document.getElementById('vote-modal');
  if (modal) modal.classList.remove('open');
}

// Intercepter le clic sur les imagettes pour charger les médias fluidement
document.addEventListener('DOMContentLoaded', () => {
  bindMediaLinks();
  window.addEventListener('popstate', () => {
    loadMedia(window.location.href, false);
  });
});

function bindMediaLinks() {
  document.querySelectorAll('a[href*="idMedia="]').forEach(link => {
    const url = new URL(link.href, window.location.origin);
    if (url.pathname.endsWith('afficherLieu.php')) {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        loadMedia(link.href, true);
      });
    }
  });
}

function loadMedia(url, pushState = true) {
  try {
    const parsedUrl = new URL(url, window.location.origin);
    const mediaIdParam = parsedUrl.searchParams.get('idMedia');
    if (mediaIdParam) {
      currentMediaId = parseInt(mediaIdParam, 10);
      
      // Update local lightbox image if open
      const lightbox = document.getElementById('lightbox-modal');
      if (lightbox && lightbox.classList.contains('open')) {
        const activeMedia = listeMedias.find(m => m.id === currentMediaId);
        if (activeMedia) {
          const img = document.getElementById('lightbox-img');
          if (img && img.src !== activeMedia.url) {
            img.src = activeMedia.url;
          }
        }
      }

      // Update parent lightbox image if open
      if (window.parent && typeof window.parent.parentCurrentMediaId !== 'undefined') {
        window.parent.parentCurrentMediaId = currentMediaId;
        const parentLightbox = window.parent.document.getElementById('parent-lightbox-modal');
        if (parentLightbox && parentLightbox.classList.contains('open')) {
          const activeMedia = listeMedias.find(m => m.id === currentMediaId);
          if (activeMedia) {
            const parentImg = window.parent.document.getElementById('parent-lightbox-img');
            if (parentImg && parentImg.src !== activeMedia.url) {
              parentImg.src = activeMedia.url;
            }
          }
        }
      }
    }
  } catch (e) {
    console.error(e);
  }

  const targets = ['#grpMedia', '#grpAnecdote', '#grpFilm'];
  targets.forEach(selector => {
    const el = document.querySelector(selector);
    if (el) el.classList.add('fade-out-transition');
  });

  fetch(url)
    .then(response => response.text())
    .then(html => {
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');

      setTimeout(() => {
        document.title = doc.title;

        targets.forEach(selector => {
          const oldEl = document.querySelector(selector);
          const newEl = doc.querySelector(selector);
          if (oldEl && newEl) {
            oldEl.innerHTML = newEl.innerHTML;
            oldEl.className = newEl.className;
          }
        });

        // Mettre à jour l'action et le contenu du formulaire de vote si besoin
        const oldVoteForm = document.getElementById('modal-vote-form');
        const newVoteForm = doc.getElementById('modal-vote-form');
        if (oldVoteForm && newVoteForm) {
          oldVoteForm.innerHTML = newVoteForm.innerHTML;
        }

        bindMediaLinks();
        
        if (pushState) {
          history.pushState(null, '', url);
        }

        targets.forEach(selector => {
          const el = document.querySelector(selector);
          if (el) {
            el.classList.remove('fade-out-transition');
            el.classList.add('fade-in-transition');
            setTimeout(() => {
              el.classList.remove('fade-in-transition');
            }, 300);
          }
        });
      }, 200);
    })
    .catch(err => {
      console.error('Erreur de transition AJAX:', err);
      window.location.href = url;
    });
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" background="images/fondAnecdote.jpg">
<div class="page-wrapper-lieu">
<div id="btnRetour">
  <a href="#" onclick="retournerAuPlan(); return false;" class="bouton-fermer" title="Retour au plan de Paris">&times;</a>
</div>
<div id="grpAnecdote"> 
  <div id="signature">Une 
    anecdote de <a href="#"><?php echo htmlspecialchars($media['auteura']); ?></a>. <img src="images/photosEtSignaturesAuteurs/alainSignature.gif" width="86" height="80" align="absmiddle">.</div>
  <div id="fondAnecdote"> 
    <div id="ascenseur"><img src="images/ascenseur.gif" width="35" height="268"></div>
    <div id="Anecdote"> 
      <p><span class="texteAnecdote"><?php echo nl2br(htmlspecialchars($media['anecdote'])); ?></span> </p>
      </div>
  </div>
</div>
<div id="grpLieu"> 
  <div id="pictoLieu"> 
    <table width="100%" height="100%"><tr><td align="center" valign="middle"><img src="<?php echo CHEMIN_PICTOS."/".htmlspecialchars($categorie['picto']); ?>"></td></tr></table></div>
  <div id="fondPictoLieu">
    <table width="100%" height="100%"><tr><td align="center" valign="middle"><img src="<?php echo CHEMIN_PICTOS."/".htmlspecialchars($categorie['couleur']); ?>"></td></tr></table></div>
  <div id="titreLieuOmbre"><span class="titreLieu"><font color="#FFCCCC"><?php echo htmlspecialchars($lieu['lieu']); ?></font></span></div>
  <div id="aile"><img src="images/titreLieu.gif" width="283" height="54"></div>
  <div id="titreLieu" class="titreLieu"><?php echo htmlspecialchars($lieu['lieu']); ?></div>
</div>
<div id="grpMedia" class="<?php echo $isPortrait ? 'portrait' : ''; ?>"> 
  <div id="encadrement"> 
    <img src="images/cadreMedia.png" width="535" height="738"></div>
  <div id="media">
    <div class="media-container" onclick="ouvrirLightbox('medias/<?php echo htmlspecialchars($media['repertoire'] .'/'. $media['fichier']); ?>')">
      <img src="medias/<?php echo htmlspecialchars($media['repertoire'] ."/". $media['fichier']); ?>">
    </div>
  </div>
  <div id="photographe"> 
    <p><font face="Times New Roman, Times, serif">Une photo de <a href="#"> 
      <?php echo htmlspecialchars($media['auteurm']); ?>
      </a> <img src="images/photosEtSignaturesAuteurs/soniaPhoto.gif" width="27" height="26" align="absmiddle"> 
      <?php if ((int)$media['note'] > 0): ?>
        , Not&eacute;e <b><?php echo htmlspecialchars($media['poids']); ?>/10</b> (<?php echo (int)$media['note']; ?> votes). 
      <?php else: ?>
        , Pas encore not&eacute;e. 
      <?php endif; ?>
      <?php if (isset($_SESSION['voted'][$idMedia])): ?>
        <span style="color: #666; font-style: italic; margin-left: 5px;">(Merci pour votre vote !)</span>
      <?php else: ?>
        <a href="#" onclick="ouvrirVoteModal(); return false;">Votez !</a>
      <?php endif; ?>
      </font></p>
  </div>
  <div id="titreMedia"><b><font size="4">&quot;<?php echo htmlspecialchars($media['titremedia']); ?>&quot;</font></b></div>
  <div id="titreMediaOmbre"><font size="4"><b><font color="#FFCCCC">&quot;<?php echo htmlspecialchars($media['titremedia']); ?>&quot;</font></b></font></div>
</div>
<?php if ($nbDeMedias > 1): ?>
<div id="grpFilm">
  <div id="imagette1"> 
    <table width="100%" border="0" height="100%" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td align="center" valign="middle">
          <?php if (isset($imagette[$imagetteCentrale-1])): ?>
          <a href="<?php echo htmlspecialchars($PHP_SELF ."?idLieu=$idLieu&idMedia=". $imagette[$imagetteCentrale-1]['id']); ?>"><img id="imagette1img" border="0" src="<?php echo CHEMIN_MEDIAS ."/". htmlspecialchars($imagette[$imagetteCentrale-1]['repertoire'] ."/". CHEMIN_IMAGETTES ."/". $imagette[$imagetteCentrale-1]['fichier']); ?>"></a>
          <?php endif; ?>
        </td>
      </tr>
    </table>
  </div>
  <div id="imagette2"> 
    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" height="100%">
      <tr>
        <td align="center" valign="middle">
          <?php if (isset($imagette[$imagetteCentrale])): ?>
          <img id="imagette2img" border="0" src="<?php echo CHEMIN_MEDIAS ."/". htmlspecialchars($imagette[$imagetteCentrale]['repertoire'] ."/". CHEMIN_IMAGETTES ."/". $imagette[$imagetteCentrale]['fichier']); ?>">
          <?php endif; ?>
        </td>
      </tr>
    </table>
  </div>
  <div id="imagette3"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%" align="center">
      <tr>
        <td align="center" valign="middle">
          <?php if (isset($imagette[$imagetteCentrale+1])): ?>
          <a href="<?php echo htmlspecialchars($PHP_SELF ."?idLieu=$idLieu&idMedia=". $imagette[$imagetteCentrale+1]['id']); ?>"><img id="imagette3img" border="0" src="<?php echo CHEMIN_MEDIAS ."/". htmlspecialchars($imagette[$imagetteCentrale+1]['repertoire'] ."/". CHEMIN_IMAGETTES ."/". $imagette[$imagetteCentrale+1]['fichier']); ?>"></a>
          <?php endif; ?>
        </td>
      </tr>
    </table>
  </div>
  <script>
   <?php echo "imagetteCentrale=$imagetteCentrale;\n"; // passage de param php vers javascript (pour imagemap) 
   echo "imagette = new Array();\n";
   $i=0;
   while (isset($imagette[++$i])) {
        $url = $PHP_SELF ."?idLieu=$idLieu&idMedia=". $imagette[$i]['id'];
        $image = CHEMIN_MEDIAS ."/". $imagette[$i]['repertoire'] ."/". CHEMIN_IMAGETTES ."/". $imagette[$i]['fichier'];
        echo "imagette[$i] = new Array('".addslashes($url)."','".addslashes($image)."');\n";
   }
   if(!isset($imagette[$imagetteCentrale-1])) echo "MM_showHideLayers('imagette1','','hide');\n"; 
   if(!isset($imagette[$imagetteCentrale+1])) echo "MM_showHideLayers('imagette3','','hide');\n"; 
   ?>
  </script>
  <div id="film">
    <div id="left-arrow-cover" style="position: absolute; left: 0px; top: 48px; width: 38px; height: 36px; background: url('images/fondAnecdote.jpg'); z-index: 2; display: <?php echo isset($imagette[$imagetteCentrale-1]) ? 'none' : 'block'; ?>;"></div>
    <img src="images/filmClair.gif" width="374" height="138" border="0" usemap="#map" style="position: relative; z-index: 1;"> 
    <div id="right-arrow-cover" style="position: absolute; left: 336px; top: 48px; width: 38px; height: 36px; background: url('images/fondAnecdote.jpg'); z-index: 2; display: <?php echo isset($imagette[$imagetteCentrale+1]) ? 'none' : 'block'; ?>;"></div>
    <map name="map"> 
      <?php if (isset($imagette[$imagetteCentrale+1])): ?>
      <area shape="poly" coords="337,54,337,81,372,64" href="<?php echo htmlspecialchars($PHP_SELF ."?idLieu=$idLieu&idMedia=". $imagette[$imagetteCentrale+1]['id']); ?>" onclick="event.preventDefault(); loadMedia(this.href, true);">
      <?php else: ?>
      <area shape="poly" coords="337,54,337,81,372,64" href="#" onclick="return false;">
      <?php endif; ?>

      <?php if (isset($imagette[$imagetteCentrale-1])): ?>
      <area shape="poly" coords="36,50,36,82,-2,68" href="<?php echo htmlspecialchars($PHP_SELF ."?idLieu=$idLieu&idMedia=". $imagette[$imagetteCentrale-1]['id']); ?>" onclick="event.preventDefault(); loadMedia(this.href, true);">
      <?php else: ?>
      <area shape="poly" coords="36,50,36,82,-2,68" href="#" onclick="return false;">
      <?php endif; ?>
    </map>
  </div>
  <div id="legendeFilm"> 
    <div align="center"><font size="3">Choisissez parmi les <b><?php echo $nbDeMedias; ?></b> m&eacute;dias 
      de ce lieu</font></div>
  </div>
</div>
<?php endif; ?>
<div id="grpImprimer" onclick="imprimerFiche();"> 
  <div id="logoImprimer"><img src="images/logoImprimante.gif" width="55" height="30" align="absmiddle"></div>
  <div id="texte"><b>Imprimer 
    cette page</b></div>
  <div id="cercle"><img src="images/cercle.gif" width="60" height="61"></div>
  <div id="texteOmbre"><b><font color="#FFCCCC">Imprimer 
    cette page</font></b> </div>
</div>
<div id="logoViveParis"> 
  <div id="ligne"> 
    <hr>
  </div>
  <div id="logo"><img src="images/logoViveParis.gif" width="135" height="28"></div>
  <div id="texteLogo">Copyleft 
    ViveParis 2003 &copy;</div>
</div>
<!-- Lightbox pour voir la photo en grand -->
<div id="lightbox-modal" class="lightbox-overlay" onclick="fermerLightbox();">
  <span class="lightbox-close" onclick="fermerLightbox();">&times;</span>
  <span class="lightbox-prev" onclick="naviguerLightbox(-1, event);">&lt;</span>
  <img id="lightbox-img" src="" alt="Photo en grand" onclick="event.stopPropagation();">
  <span class="lightbox-next" onclick="naviguerLightbox(1, event);">&gt;</span>
</div>

<!-- Modale de Vote -->
<div id="vote-modal" class="vote-overlay" onclick="fermerVoteModal();">
  <div class="vote-modal-content" onclick="event.stopPropagation();">
    <span class="vote-close" onclick="fermerVoteModal();">&times;</span>
    <h3>Évaluer cette photo</h3>
    <p>Attribuez une note de 1 à 10 :</p>
    <form id="modal-vote-form" method="post" action="">
      <div class="vote-options">
        <?php for($n=1; $n<=10; $n++): ?>
          <button type="submit" name="vote_note" value="<?php echo $n; ?>" class="vote-btn"><?php echo $n; ?></button>
        <?php endfor; ?>
      </div>
    </form>
  </div>
</div>

</body>
</html>
