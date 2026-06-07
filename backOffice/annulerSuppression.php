<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../globalData.php");
include("../fonctions.php");

if (isset($_SESSION['last_deleted_media'])) {
    $data = $_SESSION['last_deleted_media'];
    $db = connecterBdd();
    
    try {
        $db->beginTransaction();
        
        // 1. Réinsère le média
        $media = $data['media'];
        $sql = "INSERT INTO medias (id, titremedia, fichier, repertoire, auteurm, poids, date, anecdote, auteura, note, soleil)
                VALUES (:id, :titremedia, :fichier, :repertoire, :auteurm, :poids, :date, :anecdote, :auteura, :note, :soleil)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'id' => $media['id'],
            'titremedia' => $media['titremedia'],
            'fichier' => $media['fichier'],
            'repertoire' => $media['repertoire'],
            'auteurm' => $media['auteurm'],
            'poids' => $media['poids'],
            'date' => $media['date'],
            'anecdote' => $media['anecdote'],
            'auteura' => $media['auteura'],
            'note' => $media['note'],
            'soleil' => $media['soleil']
        ]);
        
        // 2. Restaure les jointures
        if (!empty($data['lieux'])) {
            foreach ($data['lieux'] as $idLieu) {
                $db->prepare("INSERT INTO lieux_medias (idmedia, idlieu) VALUES (:idMedia, :idLieu)")
                   ->execute(['idMedia' => $media['id'], 'idLieu' => $idLieu]);
            }
        }
        if (!empty($data['categories'])) {
            foreach ($data['categories'] as $idCat) {
                $db->prepare("INSERT INTO medias_categories (idMedia, idCategorie) VALUES (:idMedia, :idCat)")
                   ->execute(['idMedia' => $media['id'], 'idCat' => $idCat]);
            }
        }
        if (!empty($data['caracteristiques'])) {
            foreach ($data['caracteristiques'] as $idCarac) {
                $db->prepare("INSERT INTO medias_caracteristiques (idMedia, idCaracteristique) VALUES (:idMedia, :idCarac)")
                   ->execute(['idMedia' => $media['id'], 'idCaracteristique' => $idCarac]);
            }
        }
        
        // 3. Déplace les fichiers depuis la corbeille
        if (!empty($data['trashFichier']) && file_exists($data['trashFichier'])) {
            $dir = dirname($data['originalFichier']);
            if (!file_exists($dir)) {
                @mkdir($dir, 0777, true);
            }
            @rename($data['trashFichier'], $data['originalFichier']);
        }
        if (!empty($data['trashImagette']) && file_exists($data['trashImagette'])) {
            $dir = dirname($data['originalImagette']);
            if (!file_exists($dir)) {
                @mkdir($dir, 0777, true);
            }
            @rename($data['trashImagette'], $data['originalImagette']);
        }
        
        $db->commit();
        
        // Nettoie la session
        unset($_SESSION['last_deleted_media']);
        
    } catch (Exception $e) {
        $db->rollBack();
        die("Erreur lors de l'annulation de la suppression : " . $e->getMessage());
    }
    fermerBdd($db);
}

// Redirection
$retour = isset($_SESSION['retour']) ? $_SESSION['retour'] : 'galerie.php';
header("Location: " . $retour);
exit();
?>
