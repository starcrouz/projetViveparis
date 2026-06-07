<?php
// parametres : idmedia 
include("../globalData.php");
include("../fonctions.php");

$idMedia = isset($_GET['idMedia']) ? (int)$_GET['idMedia'] : (isset($_POST['idMedia']) ? (int)$_POST['idMedia'] : 0);

if ($idMedia > 0) {
    $db = connecterBdd();
    
    try {
        // Récupère toutes les données du média avant suppression
        $stmtFile = $db->prepare("SELECT * FROM medias WHERE id = :idMedia");
        $stmtFile->execute(['idMedia' => $idMedia]);
        $mediaData = $stmtFile->fetch(PDO::FETCH_ASSOC);
        
        if ($mediaData) {
            $fichier = $mediaData['fichier'];
            $repertoire = $mediaData['repertoire'];
            $cheminFichier = "../" . CHEMIN_MEDIAS . "/$repertoire/$fichier";
            $cheminImagette = "../" . CHEMIN_MEDIAS . "/$repertoire/imagettes/$fichier";
            
            // Récupère les liaisons avant suppression
            $stmtLieux = $db->prepare("SELECT idlieu FROM lieux_medias WHERE idmedia = :idMedia");
            $stmtLieux->execute(['idMedia' => $idMedia]);
            $lieux = $stmtLieux->fetchAll(PDO::FETCH_COLUMN);
            
            $stmtCats = $db->prepare("SELECT idCategorie FROM medias_categories WHERE idMedia = :idMedia");
            $stmtCats->execute(['idMedia' => $idMedia]);
            $cats = $stmtCats->fetchAll(PDO::FETCH_COLUMN);
            
            $stmtCaracs = $db->prepare("SELECT idCaracteristique FROM medias_caracteristiques WHERE idMedia = :idMedia");
            $stmtCaracs->execute(['idMedia' => $idMedia]);
            $caracs = $stmtCaracs->fetchAll(PDO::FETCH_COLUMN);
            
            // Déplacement des fichiers vers la corbeille temporaire (.trash)
            $trashDir = "../" . CHEMIN_MEDIAS . "/.trash";
            if (!file_exists($trashDir)) {
                @mkdir($trashDir, 0777, true);
            }
            
            $trashFichier = "";
            $trashImagette = "";
            
            if (file_exists($cheminFichier)) {
                $trashFichier = "$trashDir/" . uniqid() . "_" . $fichier;
                @rename($cheminFichier, $trashFichier);
            }
            if (file_exists($cheminImagette)) {
                $trashImagette = "$trashDir/thumb_" . uniqid() . "_" . $fichier;
                @rename($cheminImagette, $trashImagette);
            }
            
            // Stocke les infos de suppression dans la session pour l'option "Undo"
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['last_deleted_media'] = [
                'media' => $mediaData,
                'lieux' => $lieux,
                'categories' => $cats,
                'caracteristiques' => $caracs,
                'trashFichier' => $trashFichier,
                'originalFichier' => $cheminFichier,
                'trashImagette' => $trashImagette,
                'originalImagette' => $cheminImagette
            ];
            
            // Supprime des jointures de la base de données
            $db->prepare("DELETE FROM lieux_medias WHERE idmedia = :idMedia")->execute(['idMedia' => $idMedia]);
            $db->prepare("DELETE FROM medias_categories WHERE idMedia = :idMedia")->execute(['idMedia' => $idMedia]);
            $db->prepare("DELETE FROM medias_caracteristiques WHERE idMedia = :idMedia")->execute(['idMedia' => $idMedia]);
            
            // Supprime le média de la base de données
            $db->prepare("DELETE FROM medias WHERE id = :idMedia")->execute(['idMedia' => $idMedia]);
        }
        
    } catch (PDOException $e) {
        die("La suppression a échoué : " . $e->getMessage());
    }
    
    fermerBdd($db);
}

// fermeture de la fenêtre et mise à jour de la fenêtre appelante
echo "<html><body>ok<script language='javascript'>opener.location.reload(); self.close();</script></body></html>";
?>
