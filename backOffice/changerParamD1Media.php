<?php
session_start();
include("../globalData.php");
include("../fonctions.php");

// Récupération sécurisée des paramètres POST
$idMedia = isset($_POST['idMedia']) ? (int)$_POST['idMedia'] : null;
$titre = isset($_POST['titre']) ? $_POST['titre'] : '';
$repertoire = isset($_POST['repertoire']) ? $_POST['repertoire'] : '';
$fichier = isset($_POST['fichier']) ? $_POST['fichier'] : '';
$auteurM = isset($_POST['auteurM']) ? $_POST['auteurM'] : '';
$poids = isset($_POST['poids']) ? (int)$_POST['poids'] : 0;
$dateInput = isset($_POST['date']) ? $_POST['date'] : '';
$anecdote = isset($_POST['anecdote']) ? $_POST['anecdote'] : '';
$auteurA = isset($_POST['auteurA']) ? $_POST['auteurA'] : '';
$idLieuInput = isset($_POST['idLieu']) ? (int)$_POST['idLieu'] : 0;
$retour = isset($_POST['retour']) ? $_POST['retour'] : 'galerie.php';

// Connexion BDD
$db = connecterBdd();

// Nettoyage et formatage
$auteurM = htmlspecialchars(stripslashes($auteurM), ENT_QUOTES, 'UTF-8');
$auteurA = htmlspecialchars(stripslashes($auteurA), ENT_QUOTES, 'UTF-8');
$anecdote = htmlspecialchars(stripslashes($anecdote), ENT_QUOTES, 'UTF-8');

// Traduction de la date : de jj/mm/aaaa hh:mm:ss vers date unix
$dateUnix = time();
if (preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $dateInput, $regs)) {
    // mktime(hour, minute, second, month, day, year)
    $dateUnix = mktime((int)$regs[4], (int)$regs[5], (int)$regs[6], (int)$regs[2], (int)$regs[1], (int)$regs[3]);
}

try {
    if (empty($idMedia)) { // Nouveau média : INSERT
        $sql = "INSERT INTO medias (titremedia, fichier, repertoire, auteurm, poids, date, anecdote, auteura) 
                VALUES (:titre, :fichier, :repertoire, :auteurM, :poids, :dateUnix, :anecdote, :auteurA)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'titre' => $titre,
            'fichier' => $fichier,
            'repertoire' => $repertoire,
            'auteurM' => $auteurM,
            'poids' => $poids,
            'dateUnix' => $dateUnix,
            'anecdote' => $anecdote,
            'auteurA' => $auteurA
        ]);
        $idMedia = $db->lastInsertId();
    } else { // Média existant : UPDATE
        $sql = "UPDATE medias SET titremedia = :titre, fichier = :fichier, repertoire = :repertoire, 
                auteurm = :auteurM, poids = :poids, date = :dateUnix, anecdote = :anecdote, auteura = :auteurA 
                WHERE id = :idMedia";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'titre' => $titre,
            'fichier' => $fichier,
            'repertoire' => $repertoire,
            'auteurM' => $auteurM,
            'poids' => $poids,
            'dateUnix' => $dateUnix,
            'anecdote' => $anecdote,
            'auteurA' => $auteurA,
            'idMedia' => $idMedia
        ]);
    }

    // Gestion de l'association avec un lieu
    $sqlCheck = "SELECT idlieu FROM lieux_medias WHERE idmedia = :idMedia";
    $stmtCheck = $db->prepare($sqlCheck);
    $stmtCheck->execute(['idMedia' => $idMedia]);
    $currentIdLieu = $stmtCheck->fetchColumn();

    if ($currentIdLieu === false) {
        // Aucune association existante
        if ($idLieuInput > 0) {
            $sqlInsertLieu = "INSERT INTO lieux_medias (idmedia, idlieu) VALUES (:idMedia, :idLieu)";
            $db->prepare($sqlInsertLieu)->execute(['idMedia' => $idMedia, 'idLieu' => $idLieuInput]);
        }
    } else {
        // Une association existe déjà
        if ($idLieuInput == 0) {
            // Désassociation
            $sqlDeleteLieu = "DELETE FROM lieux_medias WHERE idmedia = :idMedia";
            $db->prepare($sqlDeleteLieu)->execute(['idMedia' => $idMedia]);
        } else if ($idLieuInput != $currentIdLieu) {
            // Modification du lieu
            $sqlUpdateLieu = "UPDATE lieux_medias SET idlieu = :idLieu WHERE idmedia = :idMedia";
            $db->prepare($sqlUpdateLieu)->execute(['idMedia' => $idMedia, 'idLieu' => $idLieuInput]);
        }
    }
} catch (PDOException $e) {
    die("La requête a échoué : " . $e->getMessage());
}

fermerBdd($db);

// Redirection
header("Location: " . $retour);
echo "<html><body>";
echo "Votre média (" . htmlspecialchars($titre) . ") est répertorié et modifié en base.<br>\n";
echo "Vous pouvez <a href='" . htmlspecialchars($retour) . "'>retourner au sommaire</a>";
echo "</body></html>";
?>
