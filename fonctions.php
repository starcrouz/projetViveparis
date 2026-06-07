<?php
// ---- On se connecte via PDO et on sélectionne la base ---- 
function connecterBdd(){
    try {
        $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PWD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $db;
    } catch (PDOException $e) {
        die("Impossible de se connecter à la base de données : " . $e->getMessage());
    }
}

function fermerBdd($db) {
    // Avec PDO, la déconnexion se fait en détruisant la variable $db
    return true;
}

function compterNbDeMedias($idLieu, $db){
    // on récupère le nb de médias du lieu
    $sql = "SELECT COUNT(*) FROM lieux_medias AS lm WHERE lm.idlieu = :idLieu";
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute(['idLieu' => (int)$idLieu]);
        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        die("La requête [$sql] a échoué : " . $e->getMessage());
    }
}

function titresLieu($idLieu, $tri, $db) { 
    // -------------------------------------------------------------------------------------
    // cette fonction renvoie la liste des lieux (titre + id) avec un titre bien présenté
    // ou seulement un titre de lieu si $idLieu est passé en paramètre 
    // -------------------------------------------------------------------------------------

    // Tri par défaut et validation des colonnes de tri autorisées pour éviter les injections SQL
    if (empty($tri)) {
        $tri = 'lieu';
    }
    $allowed_tri = ['lieu', 'id', 'numero', 'voie', 'rue'];
    if (!in_array($tri, $allowed_tri)) {
        $tri = 'lieu';
    }

    try {
        if (empty($idLieu)) {
            $sql = "SELECT * FROM lieux ORDER BY $tri ASC";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        } else {
            $sql = "SELECT * FROM lieux WHERE id = :idLieu";
            $stmt = $db->prepare($sql);
            $stmt->execute(['idLieu' => (int)$idLieu]);
        }

        // ---- On récupère les résultats et on les met dans un tableau
        $titreLieu = [];
        $i = 0;
        while ($ligne = $stmt->fetch(PDO::FETCH_OBJ)) {
            // mise en forme de la description du lieu
            if (empty($ligne->lieu)) { // lieu sans nom
                if ($ligne->numero == 0) {
                    $descriptionLieu = "$ligne->voie $ligne->rue";
                } else {
                    $descriptionLieu = "$ligne->numero $ligne->voie $ligne->rue";
                }
            } else if (($ligne->numero == 0 && empty($ligne->voie) && empty($ligne->rue)) || !empty($idLieu)) { // lieu sans adresse
                $descriptionLieu = "$ligne->lieu";
            } else {
                if ($ligne->numero == 0) {
                    $descriptionLieu = "$ligne->lieu ($ligne->voie $ligne->rue)";
                } else {
                    $descriptionLieu = "$ligne->lieu ($ligne->numero $ligne->voie $ligne->rue)";
                }
            }
            // affichage de cette description
            $titreLieu[$i]["titre"] = $descriptionLieu;
            $titreLieu[$i++]["id"] = $ligne->id;
        }
        return $titreLieu;
    } catch (PDOException $e) {
        die("La requête a échoué : " . $e->getMessage());
    }
}
?>