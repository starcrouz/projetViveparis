<html>
<!-- parametres attendus : $idLieu, $titre, $fichier, $auteurM, $note, $date, $anecdote, $auteurA, $categories, $soleil -->

<body>
<?php
include("globalData.php");
       // On se connecte à mysql
	$db = mysql_connect(DB_SERVER,DB_USER,DB_PWD) or die ("Impossible de se connecter à mysql");
	mysql_select_db (DB_NAME) or die ("Impossible d'accéder à la base de données");
	
	// on lance la requête
        $sql = "SELECT * FROM lieux_medias WHERE idlieu='$idLieu'";
        $res = mysql_query($sql, $db) or die ("La requête a échoué [$sql] : <br>$sql");
	
	// pour éviter des pb de caracteres, on passe tout en html
	//echo "$auteurM";
	$auteurM = htmlentities (stripslashes($auteurM),ENT_QUOTES);
	$auteurA = htmlentities (stripslashes($auteurA),ENT_QUOTES);
	$anecdote = htmlentities (stripslashes($anecdote),ENT_QUOTES);
	$categories = htmlentities (stripslashes($categories),ENT_QUOTES);
	
	// Si déja au moins un média associé à ce lieu, choisir lequel est principal
	if (mysql_num_rows($res) && !isset($idMediaPrincipal) ) {  
		// on propose tous les médias associés à ce lieu : choisir le principal
		echo "Vous voulez insérer un média dans un lieu qui en comporte déja.<br>\n";
		echo "Vous devez donc préciser le média principal de ce lieu.<br>\n";
		echo "Cliquez sur celui-ci dans la liste ci-dessous :<br>\n";
		echo "<form name='formulaire' method='post' action='$PHPSELF'>\n";
		echo "<input type='hidden' name='idLieu' value='$idLieu'>\n";
		echo "<input type='hidden' name='titre' value='$titre'>\n";
		echo "<input type='hidden' name='fichier' value='$fichier'>\n";
		echo "<input type='hidden' name='auteurM' value='$auteurM'>\n";
		echo "<input type='hidden' name='note' value='$note'>\n";
		echo "<input type='hidden' name='date' value='$date'>\n";
		echo "<input type='hidden' name='anecdote' value='$anecdote'>\n";
		echo "<input type='hidden' name='auteurA' value='$auteurA'>\n";
		echo "<input type='hidden' name='categories' value='$categories'>\n";
		echo "<input type='hidden' name='soleil' value='$soleil'>\n";
		echo "<input type='hidden' name='repertoire' value='$repertoire'>\n";
		echo "<input type='hidden' name='idMediaPrincipal'>\n";
		
		echo "<input type='submit' value='$titre' onclick='document.formulaire.idMediaPrincipal.value = 0;'>(le média que vous êtes en train d'insérer)<br>\n";
		while ($ligne = mysql_fetch_object ($res)) {
			$sql2 = "SELECT id,titremedia FROM medias WHERE id=" . $ligne->idmedia; 
			$res2 = mysql_query($sql2, $db) or die ("La requête a échoué [$sql] : <br>$sql2");	
			$ligne2 = mysql_fetch_object ($res2);
			echo "<input type='submit' value='$ligne2->titremedia' onclick='document.formulaire.idMediaPrincipal.value = $ligne2->id;'><br>\n";
			//echo "- <a href=\"$pageEtParametres&idMediaPrincipal=$ligne2->id\">$ligne2->titremedia</a><br>\n";
		}
		echo "</form>\n";
	} else {
		//--- on insère le nouveau média
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
		if ( !isset ($idMediaPrincipal) || $idMediaPrincipal <> 0) { // c'est le 1er média pour ce lieu ou c'est le média qui a été choisi comme principal.
			$idMediaPrincipal = $idMedia;
		}
		// on construit la table de jointure lieux_medias
		$sql3 = "INSERT INTO lieux_medias( idmedia, idlieu) VALUES( $idMedia, $idLieu )";		
		$res=mysql_query($sql3, $db) or die ("La requête a échoué [$sql] : <br>$sql3");
		// on met à jour le média principal associé au lieu
		// PB DE SYNTAXE
		//echo "idMediaPrincipal = $idMediaPrincipal";
		$sql = "UPDATE lieux SET idMediaPrincipal=$idMediaPrincipal WHERE id=$idLieu";
		$res=mysql_query($sql, $db) or die ("La requête a échoué [$sql] : <br>$sql");

		// On aiguille le client si tout s'est bien passé
		echo "votre nouveau média ($titre) est désormais répertorié en base.<br>\n";
		echo "Souhaitez-vous : <a href=repertorierNouveauxMedias.php?repertoire=" . urlencode($repertoire) . ">continuer</a> ou <a href=''>retourner au sommaire</a> ?\n";
	}
	mysql_close($db);
?>
</body></html>