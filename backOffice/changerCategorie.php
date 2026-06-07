<?php
// parametres : idCategorie, categorie, description, picto, couleur 
include("../globalData.php");
include("../fonctions.php");

$idCategorie = isset($_GET['idCategorie']) ? (int)$_GET['idCategorie'] : (isset($_POST['idCategorie']) ? (int)$_POST['idCategorie'] : null);
$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : (isset($_POST['categorie']) ? $_POST['categorie'] : '');
$description = isset($_GET['description']) ? $_GET['description'] : (isset($_POST['description']) ? $_POST['description'] : '');
$picto = isset($_GET['picto']) ? $_GET['picto'] : (isset($_POST['picto']) ? $_POST['picto'] : '');
$couleur = isset($_GET['couleur']) ? $_GET['couleur'] : (isset($_POST['couleur']) ? $_POST['couleur'] : '');

$db = connecterBdd();

try {
    if (!empty($idCategorie)) { 
        $sql = "UPDATE categories SET categorie = :categorie, description = :description, picto = :picto, couleur = :couleur WHERE id = :idCategorie";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'categorie' => $categorie,
            'description' => $description,
            'picto' => $picto,
            'couleur' => $couleur,
            'idCategorie' => $idCategorie
        ]);
    } else {
        $sql = "INSERT INTO categories (categorie, description, picto, couleur) VALUES (:categorie, :description, :picto, :couleur)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'categorie' => $categorie,
            'description' => $description,
            'picto' => $picto,
            'couleur' => $couleur
        ]);
    } 
} catch (PDOException $e) {
    die("La modification de la catégorie a échoué : " . $e->getMessage());
}

fermerBdd($db);

// fermeture de la fenêtre et mise à jour de la fenêtre appelante
echo "<html><body>ok<script language='javascript'>opener.location.reload(); self.close();</script></body></html>";
?>
