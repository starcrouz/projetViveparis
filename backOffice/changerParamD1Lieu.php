<?php session_start(); ?>
<html>
<body>
<?php
include("../globalData.php");
include("../fonctions.php");

// Récupération sécurisée des données POST
$idLieu = isset($_POST['idLieu']) ? (int)$_POST['idLieu'] : null;
$lieu = isset($_POST['lieu']) ? $_POST['lieu'] : '';
$numero = isset($_POST['numero']) ? (int)$_POST['numero'] : 0;
$voie = isset($_POST['voie']) ? $_POST['voie'] : '';
$rue = isset($_POST['rue']) ? $_POST['rue'] : '';
$x = isset($_POST['x']) ? (int)$_POST['x'] : 0;
$y = isset($_POST['y']) ? (int)$_POST['y'] : 0;
$typeDeLieu = isset($_POST['typeDeLieu']) ? (int)$_POST['typeDeLieu'] : 0;
$retour = isset($_POST['retour']) ? $_POST['retour'] : 'galerie.php';

$db = connecterBdd();

try {
    if (!empty($idLieu)) {
        // Mode UPDATE
        $sql = "UPDATE lieux SET lieu = :lieu, numero = :numero, voie = :voie, rue = :rue, x = :x, y = :y WHERE id = :idLieu";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'lieu' => $lieu,
            'numero' => $numero,
            'voie' => $voie,
            'rue' => $rue,
            'x' => $x,
            'y' => $y,
            'idLieu' => $idLieu
        ]);
        
        // Mise à jour de la liaison type de lieu
        $stmtDel = $db->prepare("DELETE FROM lieux_typesdelieux WHERE idlieu = :idLieu");
        $stmtDel->execute(['idLieu' => $idLieu]);
        
        $stmtIns = $db->prepare("INSERT INTO lieux_typesdelieux (idlieu, idtypedelieu) VALUES (:idLieu, :typeDeLieu)");
        $stmtIns->execute(['idLieu' => $idLieu, 'typeDeLieu' => $typeDeLieu]);
        
    } else {
        // Mode INSERT
        $sql = "INSERT INTO lieux (lieu, numero, voie, rue, x, y) VALUES (:lieu, :numero, :voie, :rue, :x, :y)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'lieu' => $lieu,
            'numero' => $numero,
            'voie' => $voie,
            'rue' => $rue,
            'x' => $x,
            'y' => $y
        ]);
        
        // Récupère l'ID du lieu créé
        $idLieu = $db->lastInsertId();
        
        // Insère la liaison type de lieu
        $stmtIns = $db->prepare("INSERT INTO lieux_typesdelieux (idlieu, idtypedelieu) VALUES (:idLieu, :typeDeLieu)");
        $stmtIns->execute(['idLieu' => $idLieu, 'typeDeLieu' => $typeDeLieu]);
    }

    echo "Votre lieu (" . htmlspecialchars($lieu) . ") est désormais répertorié en base.<br>";
} catch (PDOException $e) {
    die("La requête a échoué : " . $e->getMessage());
}

fermerBdd($db);
echo "<a href='" . htmlspecialchars($retour) . "'>Retour</a>";
?>
</body>
</html>
