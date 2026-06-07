<?php
// parametres : idmedia et newPoids
include("../globalData.php");
include("../fonctions.php");

$idMedia = isset($_GET['idMedia']) ? (int)$_GET['idMedia'] : 0;
$newPoids = isset($_GET['newPoids']) ? (int)$_GET['newPoids'] : 0;

if ($idMedia > 0) {
    $db = connecterBdd();
    
    try {
        $sql = "UPDATE medias SET poids = :newPoids WHERE id = :idMedia";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'newPoids' => $newPoids,
            'idMedia' => $idMedia
        ]);
    } catch (PDOException $e) {
        die("La mise à jour du poids a échoué : " . $e->getMessage());
    }
    
    fermerBdd($db);
}

// fermeture de la fenêtre et mise à jour de la fenêtre appelante
echo "<html><body>ok<script language='javascript'>opener.location.reload(); self.close();</script></body></html>";
?>
