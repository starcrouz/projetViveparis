<html>
<!-- parametres attendus : $idLieu, $titre, $repertoire, $fichier, $auteurM, $poids, $date, $anecdote, $auteurA -->

<body>
<?php

print_r( $categorie );



// 2 cas : 
// 1. média non répertorié : INSERT medias
// 2. média déja répertorié : UPDATE medias
include("globalData.php");
// On se connecte à mysql
$db = mysql_connect(DB_SERVER,DB_USER,DB_PWD) or die ("Impossible de se connecter à mysql");
mysql_select_db (DB_NAME) or die ("Impossible d'accéder à la base de données");

// pour éviter des pb de caracteres, on passe tout en html
//echo "$auteurM";
$auteurM = htmlentities (stripslashes($auteurM),ENT_QUOTES);
$auteurA = htmlentities (stripslashes($auteurA),ENT_QUOTES);
$anecdote = htmlentities (stripslashes($anecdote),ENT_QUOTES);
$categories = htmlentities (stripslashes($categories),ENT_QUOTES);

// traduction de la date
// de jj/mm/aa hh:mm:ss vers date unix
ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{4}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $date, $regs );
$date = "$regs[3]/$regs[2]/$regs[1] $regs[4]:$regs[5]:$regs[6]";
// mktime (int hour, int minute, int second, int month, int day, int year, [int is_dst (heure dété ou d'hiver)] );
$date = mktime ($regs[4], $regs[5], $regs[6], $regs[2], $regs[1], $regs[3]);
//echo date("H:i:s", $date);
//echo date("d/m/Y", $date);

$sql  = "INSERT INTO medias( titremedia, fichier, auteurm, note, date, anecdote, auteura, categories, poids ) ";
$sql .= "VALUES( '$titre', '$fichier', '$auteurM', '$note', '$date', '$anecdote', '$auteurA', '$categories', '$poids')";        
$res = mysql_query($sql, $db) or die ("La requête a échoué [$sql] : <br>$sql");
// on récupère l'id du média créé en base
$sql = "SELECT max( id ) FROM medias";
$res = mysql_query($sql, $db) or die ("La requête a échoué [$sql] : <br>$sql");
$ligne = mysql_fetch_row( $res );
$idMedia = $ligne[0];
}
// on construit la table de jointure medias_categories
$sql3 = "INSERT INTO medias_categories( idmedia, idlieu) VALUES( $idMedia, $idLieu )";		
$res=mysql_query($sql3, $db) or die ("La requête a échoué [$sql] : <br>$sql3");

// On aiguille le client si tout s'est bien passé
echo "votre nouveau média ($titre) est désormais répertorié en base.<br>\n";
echo "Souhaitez-vous : <a href=repertorierNouveauxMedias.php?repertoire=" . urlencode($repertoire) . ">continuer</a> ou <a href=''>retourner au sommaire</a> ?\n";
}
mysql_close($db);
?>
</body></html>