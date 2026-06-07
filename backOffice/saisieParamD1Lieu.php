<?php session_start(); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Saisie d'un lieu</title>
<script language="JavaScript">
<!--
var fenetrePlan = "";

function recevoirCoordonnees(x, y) {
    var form = document.forms['formulaire'];
    if (form) {
        form.x.value = x;
        form.y.value = y;
        var lieuVal = form.lieu ? form.lieu.value : '';
        alert("Le lieu (" + lieuVal + ") que vous venez d'ajouter, se situe en [" + x + "," + y + "].");
    }
}
//-->
</script>
</head>
<body bgcolor="#ffffff">
<?php
include("../globalData.php");
include("../fonctions.php");
include("entete.php");

$db = connecterBdd();

// Récupération sécurisée du paramètre idLieu
$idLieu = isset($_GET['idLieu']) ? (int)$_GET['idLieu'] : (isset($_POST['idLieu']) ? (int)$_POST['idLieu'] : null);
$ligne = null;
$titreLieu = null;
$currentTypeDeLieu = null;

if (!empty($idLieu)) {
    // Récupère les données du lieu
    $sql = "SELECT * FROM lieux WHERE id = :idLieu";
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute(['idLieu' => $idLieu]);
        $ligne = $stmt->fetch(PDO::FETCH_OBJ);
        
        // Récupère le type actuel du lieu
        $sqlType = "SELECT idtypedelieu FROM lieux_typesdelieux WHERE idlieu = :idLieu";
        $stmtType = $db->prepare($sqlType);
        $stmtType->execute(['idLieu' => $idLieu]);
        $currentTypeDeLieu = $stmtType->fetchColumn();
    } catch (PDOException $e) {
        die("Erreur de base de données : " . $e->getMessage());
    }

    $titreLieu = titresLieu($idLieu, '', $db);
}

if (!empty($idLieu) && $ligne) { ?>
	<font size="5">Edition des paramètres du lieu <?php echo htmlspecialchars($titreLieu[0]['titre']); ?></font>
<?php
} else {
	?>
	<font size="5">Création d'un nouveau lieu</font>
	<?php
	}
?>
<form action='changerParamD1Lieu.php' name='formulaire' method='post'>
<?php
if (!empty($idLieu)) echo "<input type='hidden' name='idLieu' value='".htmlspecialchars($idLieu)."'>";
?>
  <table>
    <tr> 
      <td><b>Nom du lieu : </b> 
        <input type='text' name='lieu' size="15" value='<?php echo $ligne ? htmlspecialchars($ligne->lieu) : ''; ?>'>
      </td>
    </tr>
    <tr> 
      <td><br />
        <b>Adresse : </b></td>
    </tr>
    <tr> 
      <td>Numéro : 
        <input type='text' name='numero' size="3" value='<?php echo $ligne ? htmlspecialchars($ligne->numero) : ''; ?>'>
        , type de voie : 
        <input type='text' name='voie' size="4" value='<?php echo $ligne ? htmlspecialchars($ligne->voie) : ''; ?>'>
        , nom de la voie : 
        <input type='text' name='rue' size="10" value='<?php echo $ligne ? htmlspecialchars($ligne->rue) : ''; ?>'>
      </td>
    </tr>
    <tr> 
      <td><br />
        <b>Coordonnées : </b></td>
    </tr>
    <tr> 
      <td>x : 
        <input type='text' name='x' size="5" value='<?php echo $ligne ? htmlspecialchars($ligne->x) : ''; ?>'>
        y : 
        <input type='text' name='y' size="5" value='<?php echo $ligne ? htmlspecialchars($ligne->y) : ''; ?>'>
        <a href='#' onClick="fenetrePlan = window.open('../zoom.php?provenance=nouveauLieu','fenetrePlan','toolbar=no,menubar=no,directories=no,scrollbars=yes,status=no,width=850,height=550'); return false;">(cliquez 
        ici pour choisir dans le plan)</a></td>
    </tr>
    <tr>
      <td height="37" valign="bottom"><b>Type de lieu :</b></td>
    </tr>
    <tr> 
      <td>
	  <select name="typeDeLieu">
	  <?php  
	  // affiche les différents types de lieux
	  $sqlTypes = "SELECT * FROM typesdelieux";
      try {
          $stmtTypes = $db->query($sqlTypes);
          while ($rowType = $stmtTypes->fetch(PDO::FETCH_OBJ)) {
              $selected = ($rowType->id == $currentTypeDeLieu) ? 'selected' : '';
              echo "<option value=\"" . htmlspecialchars($rowType->id) . "\" {$selected}>" . htmlspecialchars($rowType->titre) . "</option>\n";
          }
      } catch (PDOException $e) {
          echo "<option value=''>Erreur de chargement</option>";
      }
	  ?>
	  </select>
	  </td>
    </tr>
  </table>
<br />
<input type="submit" value="Valider" name="submit">
</form>

<?php 
fermerBdd($db);
include("piedDePage.php");
?>
</body>
</html>
