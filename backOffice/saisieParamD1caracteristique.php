<html>
<head>
<title>Saisie d'une caractéristique</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<form action='changerParamD1caracteristique.php' name='formulaire' method='post'>

<?php
include("../globalData.php");
include("../fonctions.php");
include("securite.php"); // assure la sécurité d'accès

$db = connecterBdd();

$idCaracteristique = isset($_GET['idCaracteristique']) ? (int)$_GET['idCaracteristique'] : (isset($_POST['idCaracteristique']) ? (int)$_POST['idCaracteristique'] : null);
$ligne = null;

if (!empty($idCaracteristique)) {
	$sql = "SELECT * FROM caracteristiques WHERE id = :id";
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $idCaracteristique]);
        $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        die("Erreur de base de données : " . $e->getMessage());
    }
}

if (!empty($idCaracteristique) && $ligne) {
	echo "
	<font size=5>Edition de la caracteristique \"".htmlspecialchars($ligne->titre)."\"</font>
	<input type='hidden' name='idCaracteristique' value='".htmlspecialchars($idCaracteristique)."'>
	";
} else {
	echo "<font size=5>Création d'une nouvelle caracteristique</font>";
}

$valTitre = $ligne ? htmlspecialchars($ligne->titre) : '';
$valDesc = $ligne ? htmlspecialchars($ligne->description) : '';
$valPicto = $ligne ? htmlspecialchars($ligne->picto) : '';

echo "
<table>
	<tr><td><br /><b>Titre de la caracteristique : </b><input type='text' name='titre' size=15 value='$valTitre'></td></tr>
	<tr><td><br /><b>Description : </b><input type='text' name='description' size=10 value='$valDesc'></td></tr>
  	<tr><td><br /><b>Picto : </b><input type='text' name='picto' size=10 value='$valPicto'></td></tr>
</table>
";

fermerBdd($db);
?>
<br />
<input type="submit" value="Valider" name="submit">
</form>
</body>
</html>
