<?php
// parametres : idmedia, caracteristiques
include("../globalData.php");
include("../fonctions.php");

$idMedia = isset($_GET['idMedia']) ? (int)$_GET['idMedia'] : (isset($_POST['idMedia']) ? (int)$_POST['idMedia'] : 0);
$caracteristiques = isset($_GET['caracteristiques']) ? $_GET['caracteristiques'] : (isset($_POST['caracteristiques']) ? $_POST['caracteristiques'] : []);

if ($idMedia > 0) {
    $db = connecterBdd();
    
    try {
        // Supprime les anciennes caractéristiques du média
        $stmtDel = $db->prepare("DELETE FROM medias_caracteristiques WHERE idmedia = :idMedia");
        $stmtDel->execute(['idMedia' => $idMedia]);
        
        // Insère les nouvelles associations
        if (is_array($caracteristiques) && !empty($caracteristiques)) {
            $stmtIns = $db->prepare("INSERT INTO medias_caracteristiques (idMedia, idCaracteristique) VALUES (:idMedia, :idCaracteristique)");
            foreach ($caracteristiques as $idCaracteristique) {
                if (!empty($idCaracteristique)) {
                    $stmtIns->execute([
                        'idMedia' => $idMedia,
                        'idCaracteristique' => (int)$idCaracteristique
                    ]);
                }
            }
        }
    } catch (PDOException $e) {
        die("La mise à jour des caractéristiques a échoué : " . $e->getMessage());
    }
    
    fermerBdd($db);
}

// fermeture de la fenêtre et mise à jour de la fenêtre appelante
echo "<html><body>ok<script language='javascript'>opener.location.reload(); self.close();</script></body></html>";
?>
