<html><body>
<?php
include("globalData.php");

        // on prépare presque toutes les requêtes
        $sql = "INSERT INTO lieux(lieu,numero,voie,rue, x, y) VALUES( '$lieu','$numero','$voie','$rue','$x', '$y' )";
        
        // On se connecte à mysql
		$db = mysql_connect(DB_SERVER,DB_USER,DB_PWD) or die ("Impossible de se connecter à mysql");
		mysql_select_db (DB_NAME) or die ("Impossible d'accéder à la base de données");
		
		// on lance les 2 insert dans médias et dans lieux
		$res=mysql_query($sql, $db) or die ("La requête a échoué [$sql] : <br>$sql");
		
		// On aiguille le client si tout s'est bien passé
		mysql_close($db);
		echo "votre nouveau lieu ($lieu)est désormais répertorié en base.<br>";
		echo "Souhaitez-vous : <a href=repertorierNouveauxMedias.php?repertoire=" . urlencode($repertoire) . ">continuer</a> ou <a href=''>retourner au sommaire</a> ?";
?>
</body></html>