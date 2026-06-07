<?php
// parametres : idlieu, newIdLieu et idmedia
include("../globalData.php");
include("../fonctions.php");

$idMedia = isset($_GET['idMedia']) ? (int)$_GET['idMedia'] : 0;
$idLieu = isset($_GET['idLieu']) ? (int)$_GET['idLieu'] : 0;
$newIdLieu = isset($_GET['newIdLieu']) ? (int)$_GET['newIdLieu'] : 0;

if ($idMedia > 0) {
    $db = connecterBdd();
    
    try {
        if ($newIdLieu == 0) { // on veut désaffecter ce média de tout lieu
            $sql = "DELETE FROM lieux_medias WHERE idmedia = :idMedia AND idlieu = :idLieu";
            $stmt = $db->prepare($sql);
            $stmt->execute(['idMedia' => $idMedia, 'idLieu' => $idLieu]);
        } else if ($idLieu == 0) { // on veut affecter ce média à un lieu alors qu'il ne l'était à aucun autre
            $sql = "INSERT INTO lieux_medias (idmedia, idlieu) VALUES (:idMedia, :newIdLieu)";
            $stmt = $db->prepare($sql);
            $stmt->execute(['idMedia' => $idMedia, 'newIdLieu' => $newIdLieu]);
        } else { // on veut réaffecter ce média à un autre lieu
            $sql = "UPDATE lieux_medias SET idlieu = :newIdLieu WHERE idmedia = :idMedia";
            $stmt = $db->prepare($sql);
            $stmt->execute(['idMedia' => $idMedia, 'newIdLieu' => $newIdLieu]);
        }
    } catch (PDOException $e) {
        die("La modification du lieu a échoué : " . $e->getMessage());
    }
    
    fermerBdd($db);
}

// fermeture de la fenêtre et mise à jour de la fenêtre appelante
echo "<html><body>ok<script language='javascript'>opener.location.reload(); self.close();</script></body></html>";
?>
