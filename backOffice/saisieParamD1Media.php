<?php 
session_start();
?>
<html>
<!-- paramètres attendus : $repertoire, $fichier, $auteurM -->
<?php 
include("../globalData.php");
include("../fonctions.php");

$repertoire = isset($_GET['repertoire']) ? $_GET['repertoire'] : (isset($_POST['repertoire']) ? $_POST['repertoire'] : null);
$fichier = isset($_GET['fichier']) ? $_GET['fichier'] : (isset($_POST['fichier']) ? $_POST['fichier'] : null);

if (!$repertoire || !$fichier) {
    die("Veuillez préciser le répertoire et le fichier contenant le média ! [?repertoire=mesPhotos&fichier=maPhoto]"); 
} else {
    $repertoire = urldecode($repertoire);
    $fichier = urldecode($fichier);
}
?>
<head>
<title>Répertorier ou éditer un média</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
/* Simple modal styles */
.modal-overlay {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.8);
    align-items: center;
    justify-content: center;
}
.modal-content {
    max-width: 90%;
    max-height: 90%;
    border: 5px solid white;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0,0,0,0.5);
}
.modal-close {
    position: absolute;
    top: 20px;
    right: 30px;
    color: white;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
}
</style>
<script language="javascript">
<!--
function showImageModal(imageUrl) {
    var modal = document.getElementById('imageModal');
    var modalImg = document.getElementById('modalImage');
    modalImg.src = imageUrl;
    modal.style.display = 'flex';
}
function hideImageModal() {
    var modal = document.getElementById('imageModal');
    modal.style.display = 'none';
}

function verifierSaisieUtilisateur() {
	if ( document.formulaire.date.value == "jj/mm/aa hh:mm:ss" ) { 
		alert("Vous devez préciser une date !");
		return false;
	}	
	
	if ( document.formulaire.titre.value == "" ) { 
		alert("Vous devez préciser un titre pour le média !");
		return false;
	}
	
	if ( document.formulaire.auteurM.value == "" ) { 
		alert("Vous devez préciser un auteur du média !");
		return false;
	}
	
	if ( document.formulaire.auteurA.value == "" && !document.formulaire.anecdote.value == "") { 
		alert("Vous devez préciser un auteur de l'anecdote !");
		return false;
	}
	
	if ( !document.formulaire.auteurA.value == "" && document.formulaire.anecdote.value == "") { 
		document.formulaire.auteurA.value = "";
	}
}
//-->
</script>
</head>

<body bgcolor="#ffffff">
<?php 
include("entete.php");

$chemin = "../" . CHEMIN_MEDIAS . "/$repertoire";

// si le média existe déjà en base, on récupère tout ce qu'on en sait
$sql = "SELECT * FROM medias WHERE fichier = :fichier AND repertoire = :repertoire";
$db = connecterBdd();

try {
    $stmt = $db->prepare($sql);
    $stmt->execute([
        'fichier' => $fichier,
        'repertoire' => $repertoire
    ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur base de données : " . $e->getMessage());
}

$idMedia = 0;
$titreMedia = "";
$auteurMedia = "";
$noteMedia = 0;
$dateMedia = 0;
$anecdoteMedia = "";
$auteurAnecdote = "";
$poidsMedia = 0;
$dateExif = "";
$idLieu = 0;

if (!$row) {
?>
	<font size="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Vous pouvez répertorier ce <a href="javascript:alert('Nom du fichier : <?php echo htmlspecialchars("$repertoire/$fichier"); ?>');">nouveau média</a></font>
<?php
} else { 
?>
	<font size="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Vous pouvez éditer les paramètres du <a href="javascript:alert('Nom du fichier : <?php echo htmlspecialchars("$repertoire/$fichier"); ?>');">média</a></font>
<?php
	$idMedia = $row["id"];
	$titreMedia = $row["titremedia"];
	$auteurMedia = $row["auteurm"];
	$noteMedia = $row["note"];
	$dateMedia = $row["date"];
	$anecdoteMedia = $row["anecdote"];
	$auteurAnecdote = $row["auteura"];
	$poidsMedia = $row["poids"];

    // on récupère tout sur son lieu
    $sqlLieu = "SELECT * FROM lieux AS l, lieux_medias AS lm WHERE lm.idmedia = :idMedia AND lm.idlieu = l.id";
    try {
        $stmtLieu = $db->prepare($sqlLieu);
        $stmtLieu->execute(['idMedia' => $idMedia]);
        $ligneLieu = $stmtLieu->fetch(PDO::FETCH_OBJ);
        if ($ligneLieu) {
            $titreLieu = $ligneLieu->lieu;
            $numeroLieu = $ligneLieu->numero;
            $voieLieu = $ligneLieu->voie;
            $rueLieu = $ligneLieu->rue;
            $idLieu = $ligneLieu->id;
        }
    } catch (PDOException $e) {
        die("Erreur liaison lieu : " . $e->getMessage());
    }
}

$listeLieux = titresLieu("", "", $db);

fermerBdd($db);

// ------- données Exif de l'image (issues de l'appareil photo num) --------
$dateExif = "";
if (file_exists("$chemin/$fichier")) {
    $in = @fopen("$chemin/$fichier", "rb");
    if ($in) {
        $i = 0;
        $nbDeZero = 0;
        $chaine = "";
        while (!feof($in) && $i < 15000 && $nbDeZero < 9) {
            $i++;
            $temp = fgetc($in);
            if (ord($temp) == 0) {
                $nbDeZero++;
                if ($nbDeZero == 9) {
                    $dateExif = "";
                    for ($j = 0; $j < 19; $j++) {
                        $dateExif .= fgetc($in);
                    }
                }
            } else {
                $nbDeZero = 0;
            }
        }
        fclose($in);
    }
}

// formatage français de la date/heure
if ($dateExif != "") {
    if (preg_match("/([0-9]{4}):([0-9]{1,2}):([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $dateExif, $regs)) {
        $dateExif = "$regs[3]/$regs[2]/$regs[1] $regs[4]:$regs[5]:$regs[6]";
    } else {
        $dateExif = "jj/mm/aaaa hh:mm:ss";
    }
} else {
    $dateExif = "jj/mm/aaaa hh:mm:ss";
}

if ($dateMedia > 0) {
    $date = date("d/m/Y G:i:s", $dateMedia);
} else {
    $date = $dateExif;
}

if ($dateExif == "jj/mm/aaaa hh:mm:ss") {
    $exempleDate = "31/12/2002 14:54:43";
} else {
    $exempleDate = $dateExif . " d'après les données exif de votre photo";
}
?>

<form method='post' name='formulaire' action='changerParamD1Media.php' onSubmit='return verifierSaisieUtilisateur();'>
  <table width="90%" align="center">
    <input type='hidden' name='fichier' value='<?php echo htmlspecialchars($fichier); ?>'>
    <input type='hidden' name='repertoire' value='<?php echo htmlspecialchars($repertoire); ?>'>
    <input type='hidden' name='idMedia' value='<?php echo htmlspecialchars($idMedia); ?>'>
    <tr> 
      <td>Titre</td>
      <td> 
        <input type='text' name='titre' value='<?php echo htmlspecialchars($titreMedia); ?>' size="25">
      </td>
      <td rowspan="7" align="right">
        <a href="#" onclick="showImageModal('<?php echo htmlspecialchars("$chemin/$fichier"); ?>'); return false;"><img height='250' src='<?php echo htmlspecialchars("$chemin/$fichier"); ?>'></a>
      </td>
    </tr>
    <?php if ($auteurMedia == "" || !isset($auteurMedia)) $auteurMedia = $utilisateur; ?>
    <tr> 
      <td height="31">Auteur du média</td>
      <td height="31"> 
        <input type='text' name='auteurM' value='<?php echo htmlspecialchars($auteurMedia); ?>' size="25">
      </td>
    </tr>
    <tr> 
      <td>Poids (0->100)</td>
      <td> 
        <input type='text' name='poids' value='<?php echo htmlspecialchars($poidsMedia); ?>' size="3">
      </td>
    </tr>
    <tr> 
      <td>Lieu associé</td>
      <td> 
        <select class="formBO" name="idLieu">
          <option value="0">*** Aucun lieu associé ! ***</option>
          <?php
          foreach ($listeLieux as $z) {
              $selected = ($z['id'] == $idLieu) ? 'selected' : '';
              echo "<option value=\"" . htmlspecialchars($z['id']) . "\" $selected>" . htmlspecialchars($z['titre']) . "</option>\n";
          }
          ?>
        </select>
        (ou <a href='saisieParamD1Lieu.php'>créer un nouveau lieu</a>)
      </td>
    </tr>
    <tr> 
      <td><a href="javascript:alert('Date et heure de la capture du média (par exemple <?php echo $exempleDate; ?>). \nDans le cas d\'une photo prise avec un appareil numérique, l\'heure est automatiquement lue dans le fichier (donnée EXIF).');">Date 
        et heure</a></td>
      <td> 
        <input type='text' name='date' value='<?php echo htmlspecialchars($date); ?>' size="25">
      </td>
    </tr>
    <tr> 
      <td> 
        <?php
        $son = substr($fichier, 0, strlen($fichier) - 4) . ".WAV";
        if (file_exists("$chemin/$son")) {
            echo "<embed src='" . htmlspecialchars("$chemin/$son") . "' width='80' height='40'></embed>";
        }
        ?>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Anecdote</td>
      <td> 
        <textarea name="anecdote" rows="5" cols="40"><?php echo htmlspecialchars($anecdoteMedia); ?></textarea>
      </td>
    </tr>
    <tr> 
      <td>
        <?php if ($auteurAnecdote == "" || !isset($auteurAnecdote)) $auteurAnecdote = $utilisateur; ?>
        Auteur de l'anecdote
      </td>
      <td> 
        <input type='text' name='auteurA' value='<?php echo htmlspecialchars($auteurAnecdote); ?>' size="25">
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right"> 
        <input type="submit" name="submit" value='Valider'>
      </td>
    </tr>
  </table>
</form>
<div id="imageModal" class="modal-overlay" onclick="hideImageModal()">
    <a class="modal-close" href="javascript:void(0)">&times;</a>
    <img class="modal-content" id="modalImage" src="" onclick="event.stopPropagation()">
</div>
<?php include("piedDePage.php"); ?>
</body>
</html>
