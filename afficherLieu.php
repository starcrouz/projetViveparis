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

function ouvrirLightbox(url) {
  const lightbox = document.getElementById('lightbox-modal');
  const img = document.getElementById('lightbox-img');
  img.src = url;
  lightbox.classList.add('open');
}

function fermerLightbox() {
  const lightbox = document.getElementById('lightbox-modal');
  lightbox.classList.remove('open');
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" background="images/fondAnecdote.jpg">
<div class="page-wrapper-lieu">
<div id="btnRetour" style="position: absolute; right: 20px; top: 15px; z-index: 100;">
  <a href="#" onclick="retournerAuPlan(); return false;" class="bouton-fermer" title="Retour au plan de Paris">&times;</a>
</div>
<div id="grpAnecdote" style="position:absolute; width:359px; height:337px; z-index:10; left: 24px; top: 73px"> 
  <div id="signature" style="position:absolute; width:313px; height:42px; z-index:20; left: 33px; top: 262px">Une 
    anecdote de <a href="#"><?php echo htmlspecialchars($media['auteura']); ?></a>. <img src="images/photosEtSignaturesAuteurs/alainSignature.gif" width="86" height="80" align="absmiddle">.</div>
  <div id="fondAnecdote" style="position:absolute; width:331px; height:281px; z-index:6; left: 5px; top: 6px"> 
    <div id="ascenseur" style="position:absolute; width:59px; height:259px; z-index:4; left: -1px; top: 16px"><img src="images/ascenseur.gif" width="35" height="268"></div>
    <div id="Anecdote" style="position:absolute; width:278px; height:250px; z-index:1; left: 42px; top: 18px; overflow: hidden"> 
      <p><span class="texteAnecdote"><?php echo nl2br(htmlspecialchars($media['anecdote'])); ?></span> </p>
      </div>
  </div>
</div>
<div id="grpLieu" style="position:absolute; width:344px; height:74px; z-index:11; left: 11px; top: 15px"> 
  <div id="pictoLieu" style="position:absolute; width:60px; height:60px; z-index:14; left: 2px; top: -10px"> 
    <table width="100%" height="100%"><tr><td align="center" valign="middle"><img src="<?php echo CHEMIN_PICTOS."/".htmlspecialchars($categorie['picto']); ?>"></td></tr></table></div>
  <div id="fondPictoLieu" style="position:absolute; width:60px; height:60px; z-index:13; left: 2px; top: -10px">
    <table width="100%" height="100%"><tr><td align="center" valign="middle"><img src="<?php echo CHEMIN_PICTOS."/".htmlspecialchars($categorie['couleur']); ?>"></td></tr></table></div>
  <div id="titreLieuOmbre" style="position:absolute; width:255px; height:22px; z-index:20; left: 85px; top: 8px"><span class="titreLieu"><font color="#FFCCCC"><?php echo htmlspecialchars($lieu['lieu']); ?></font></span></div>
  <div id="aile" style="position:absolute; width:144px; height:46px; z-index:12; left: 9px; top: 7px"><img src="images/titreLieu.gif" width="283" height="54"></div>
  <div id="titreLieu" style="position:absolute; width:212px; height:61px; z-index:21; left: 83px; top: 6px" class="titreLieu"><?php echo htmlspecialchars($lieu['lieu']); ?></div>
</div>
<div id="grpMedia" class="<?php echo $isPortrait ? 'portrait' : ''; ?>" style="position:absolute; width:600px; height:482px; z-index:9; left: 374px; top: 18px"> 
  <div id="encadrement" style="position:absolute; width:574px; height:462px; z-index:5; left: 15px; top: 11px"> 
    <img src="images/cadreMediaOk.gif" width="576" height="445"></div>
  <div id="media" style="position:absolute; width:438px; height:337px; z-index:2; left: 102px; top: 66px">
    <div class="media-container" onclick="ouvrirLightbox('medias/<?php echo htmlspecialchars($media['repertoire'] .'/'. $media['fichier']); ?>')">
      <img src="medias/<?php echo htmlspecialchars($media['repertoire'] ."/". $media['fichier']); ?>">
    </div>
  </div>
  <div id="photographe" style="position:absolute; width:391px; height:39px; z-index:10; left: 119px; top: 418px"> 
    <p><font face="Times New Roman, Times, serif">Une photo de <a href="#"> 
      <?php echo htmlspecialchars($media['auteurm']); ?>
      </a> <img src="images/photosEtSignaturesAuteurs/soniaPhoto.gif" width="27" height="26" align="absmiddle"> 
      , Not&eacute;e <b> 
      <?php echo htmlspecialchars($media['poids']); ?>
      /10</b> par les internautes (<?php echo (int)$media['note']; ?> votes). 
      <?php if (isset($_SESSION['voted'][$idMedia])): ?>
        <span style="color: #666; font-style: italic; margin-left: 5px;">(Merci pour votre vote !)</span>
      <?php else: ?>
        <a href="#" onclick="document.getElementById('vote-form').style.display='inline-block'; this.style.display='none'; return false;">Votez !</a>
        <span id="vote-form" style="display:none; margin-left: 5px;">
          <form method="post" action="" style="display:inline;">
            <select name="vote_note" onchange="this.form.submit()" style="font-family: Arial, sans-serif; font-size: 11px;">
              <option value="">Note...</option>
              <?php for($n=1; $n<=10; $n++): ?>
                <option value="<?php echo $n; ?>"><?php echo $n; ?>/10</option>
              <?php endfor; ?>
            </select>
          </form>
        </span>
      <?php endif; ?>
      </font></p>
  </div>
  <div id="titreMedia" style="position:absolute; width:337px; height:34px; z-index:12; top: 12px; left: 72px"><b><font size="4">&quot;<?php echo htmlspecialchars($media['titremedia']); ?>&quot;</font></b></div>
  <div id="titreMediaOmbre" style="position:absolute; width:318px; height:44px; z-index:11; top: 13px; left: 74px"><font size="4"><b><font color="#FFCCCC">&quot;<?php echo htmlspecialchars($media['titremedia']); ?>&quot;</font></b></font></div>
</div>
<?php if ($nbDeMedias > 1): ?>
<div id="grpFilm" style="position:absolute; width:333px; height:150px; z-index:8; left: 2px; top: 416px">
  <div id="imagette1" style="position:absolute; width:91px; height:90px; z-index:5; left: 46px; top: 45px"> 
    <table width="100%" border="0" height="100%" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td align="center" valign="middle">
          <?php if (isset($imagette[$imagetteCentrale-1])): ?>
          <a href="<?php echo htmlspecialchars($PHP_SELF ."?idLieu=$idLieu&idMedia=". $imagette[$imagetteCentrale-1]['id']); ?>"><img id="imagette1img" style="max-width: 100%; max-height: 68px; width: auto; height: auto; object-fit: contain;" border="0" src="<?php echo CHEMIN_MEDIAS ."/". htmlspecialchars($imagette[$imagetteCentrale-1]['repertoire'] ."/". CHEMIN_IMAGETTES ."/". $imagette[$imagetteCentrale-1]['fichier']); ?>"></a>
          <?php endif; ?>
        </td>
      </tr>
    </table>
  </div>
  <div id="imagette2" style="position:absolute; width:94px; height:90px; z-index:4; left: 139px; top: 45px; "> 
    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" height="100%">
      <tr>
        <td align="center" valign="middle">
          <?php if (isset($imagette[$imagetteCentrale])): ?>
          <img id="imagette2img" style="max-width: 100%; max-height: 68px; width: auto; height: auto; object-fit: contain;" border="0" src="<?php echo CHEMIN_MEDIAS ."/". htmlspecialchars($imagette[$imagetteCentrale]['repertoire'] ."/". CHEMIN_IMAGETTES ."/". $imagette[$imagetteCentrale]['fichier']); ?>">
          <?php endif; ?>
        </td>
      </tr>
    </table>
  </div>
  <div id="imagette3" style="position:absolute; width:90px; height:89px; z-index:3; left: 235px; top: 46px; "> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%" align="center">
      <tr>
        <td align="center" valign="middle">
          <?php if (isset($imagette[$imagetteCentrale+1])): ?>
          <a href="<?php echo htmlspecialchars($PHP_SELF ."?idLieu=$idLieu&idMedia=". $imagette[$imagetteCentrale+1]['id']); ?>"><img id="imagette3img" style="max-width: 100%; max-height: 68px; width: auto; height: auto; object-fit: contain;" border="0" src="<?php echo CHEMIN_MEDIAS ."/". htmlspecialchars($imagette[$imagetteCentrale+1]['repertoire'] ."/". CHEMIN_IMAGETTES ."/". $imagette[$imagetteCentrale+1]['fichier']); ?>"></a>
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
  <div id="film" style="position:absolute; width:270px; height:115px; z-index:1; left: -1px; top: 23px"><img src="images/filmClair.gif" width="374" height="138" border="0" usemap="#map"> 
    <map name="map"> 
      <area shape="poly" coords="337,54,337,81,372,64" href="#">
      <area shape="poly" coords="36,50,36,82,-2,68" href="#">
    </map>
  </div>
  <div id="legendeFilm" style="position:absolute; width:293px; height:23px; z-index:2; left: 39px; top: 1px"> 
    <div align="center"><font size="3">Choisissez parmi les <b><?php echo $nbDeMedias; ?></b> m&eacute;dias 
      de ce lieu</font></div>
  </div>
</div>
<?php endif; ?>
<div id="grpImprimer" onclick="imprimerFiche();" style="position:absolute; width:178px; height:39px; z-index:5; left: 493px; top: 524px; cursor: pointer;"> 
  <div id="logoImprimer" style="position:absolute; width:63px; height:37px; z-index:2; left: 115px; top: 4px"><img src="images/logoImprimante.gif" width="55" height="30" align="absmiddle"></div>
  <div id="texte" style="position:absolute; width:115px; height:22px; z-index:4; left: 4px; top: 11px"><b>Imprimer 
    cette page</b></div>
  <div id="cercle" style="position:absolute; width:66px; height:65px; z-index:1; left: 111px; top: -15px; visibility: hidden"><img src="images/cercle.gif" width="60" height="61"></div>
  <div id="texteOmbre" style="position:absolute; width:119px; height:25px; z-index:3; left: 6px; top: 12px"><b><font color="#FFCCCC">Imprimer 
    cette page</font></b> </div>
</div>
<div id="logoViveParis" style="position:absolute; width:145px; height:31px; z-index:1; left: 770px; top: 520px"> 
  <div id="ligne" style="position:absolute; width:149px; height:30px; z-index:12; left: 24px; top: 22px"> 
    <hr>
  </div>
  <div id="logo" style="position:absolute; width:142px; height:31px; z-index:11; left: 34px"><img src="images/logoViveParis.gif" width="135" height="28"></div>
  <div id="texteLogo" style="position:absolute; width:139px; height:26px; z-index:10; left: 32px; top: 32px">Copyleft 
    ViveParis 2003 &copy;</div>
</div>
<!-- Lightbox pour voir la photo en grand -->
<div id="lightbox-modal" class="lightbox-overlay" onclick="fermerLightbox();">
  <span class="lightbox-close">&times;</span>
  <img id="lightbox-img" src="" alt="Photo en grand" onclick="event.stopPropagation();">
</div>

</body>
</html>
