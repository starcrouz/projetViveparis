<?php
// parametres : idCaracteristique, titre, description, picto
include("../globalData.php");
include("../fonctions.php");

$idCaracteristique = isset($_GET['idCaracteristique']) ? (int)$_GET['idCaracteristique'] : (isset($_POST['idCaracteristique']) ? (int)$_POST['idCaracteristique'] : null);
$titre = isset($_GET['titre']) ? $_GET['titre'] : (isset($_POST['titre']) ? $_POST['titre'] : '');
$description = isset($_GET['description']) ? $_GET['description'] : (isset($_POST['description']) ? $_POST['description'] : '');
$picto = isset($_GET['picto']) ? $_GET['picto'] : (isset($_POST['picto']) ? $_POST['picto'] : '');

$db = connecterBdd();

try {
    if (!empty($idCaracteristique)) { 
        $sql = "UPDATE caracteristiques SET titre = :titre, description = :description, picto = :picto WHERE id = :idCaracteristique";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'titre' => $titre,
            'description' => $description,
            'picto' => $picto,
            'idCaracteristique' => $idCaracteristique
        ]);
    } else {
        $sql = "INSERT INTO caracteristiques (titre, description, picto) VALUES (:titre, :description, :picto)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'titre' => $titre,
            'description' => $description,
            'picto' => $picto
        ]);
    } 
} catch (PDOException $e) {
    die("La modification de la caractéristique a échoué : " . $e->getMessage());
}

fermerBdd($db);

// fermeture de la fenêtre et mise à jour de la fenêtre appelante
echo "<html><body>ok<script language='javascript'>opener.location.reload(); self.close();</script></body></html>";
?>
