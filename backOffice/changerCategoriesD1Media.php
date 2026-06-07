<?php
// parametres : idmedia, categories
include("../globalData.php");
include("../fonctions.php");

$idMedia = isset($_GET['idMedia']) ? (int)$_GET['idMedia'] : (isset($_POST['idMedia']) ? (int)$_POST['idMedia'] : 0);
$categories = isset($_GET['categories']) ? $_GET['categories'] : (isset($_POST['categories']) ? $_POST['categories'] : []);

if ($idMedia > 0) {
    $db = connecterBdd();
    
    try {
        // Supprime les anciennes catégories du média
        $stmtDel = $db->prepare("DELETE FROM medias_categories WHERE idmedia = :idMedia");
        $stmtDel->execute(['idMedia' => $idMedia]);
        
        // Insère les nouvelles associations
        if (is_array($categories) && !empty($categories)) {
            $stmtIns = $db->prepare("INSERT INTO medias_categories (idMedia, idCategorie) VALUES (:idMedia, :idCategorie)");
            foreach ($categories as $idCategorie) {
                if (!empty($idCategorie)) {
                    $stmtIns->execute([
                        'idMedia' => $idMedia,
                        'idCategorie' => (int)$idCategorie
                    ]);
                }
            }
        }
    } catch (PDOException $e) {
        die("La mise à jour des catégories a échoué : " . $e->getMessage());
    }
    
    fermerBdd($db);
}

// fermeture de la fenêtre et mise à jour de la fenêtre appelante
echo "<html><body>ok<script language='javascript'>opener.location.reload(); self.close();</script></body></html>";
?>
