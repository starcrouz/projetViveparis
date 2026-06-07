<html>

<!-- paramètres attendus : $repertoire $auteurM 
-->
<?php 
include("globalData.php");

if ( !isset($repertoire) ) { echo "Veuillez préciser un répertoire contenant des médias ! [?repertoire=toto]"; exit(); } 
?>
<head>
<title>Répertorier un nouveau média</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language=javascript>
<!--
function verifierSaisieUtilisateur() {

	if ( formulaire.idLieu.options[ formulaire.idLieu.selectedIndex ].value == '0' ){ 
		alert('Vous devez préciser un lieu !'); 
		return false;
	}
	
	if ( formulaire.categories.value == "" ) {
		if ( formulaire.categories2.options[ formulaire.categories2.selectedIndex ].value == '' ){ 
			alert('Vous devez préciser une categorie !'); 
			return false;
		} else {
			formulaire.categories.value = formulaire.categories2.options[ formulaire.categories2.selectedIndex ].value;	
		}
	} 
		
	if ( formulaire.date == "jj/mm/aa hh:mm:ss" ) { 
		alert('Vous devez préciser une date !');
		return false;
	}
}


//-->
</script>

</head>

<body bgcolor="#FFFFFF">

<?php 

// ------------------------------------------------------------------------------------------------
// ---- selectionne un média à répertorier
// ------------------------------------------------------------------------------------------------

if (!( $handle = opendir( "CHEMIN_MEDIAS/".urldecode($repertoire) ) ) ) {echo "erreur d'ouverture du repertoire passé en paramètres"; exit();} 

$mediaARepertorier = "";
$nbMediasDuRep = 0;
$nbMediasRestantsARepertorier = 0;

while (false !== ($file = readdir($handle))) {
    //echo ("<br>- $file");
    if ( $file != "." && $file != ".." /* && is_file($file) */ ) { // seulement les fichiers et evite les . et ..    
        $nbMediasDuRep++;
        
        // ---vérifie si ce média n'est pas déja répertorié en base
        
        // crée la requête
        $sql = "SELECT * FROM medias WHERE fichier = '$repertoire/$file'";
        
        // On se connecte à mysql et on lance la requête
		$db = mysql_connect(DB_SERVER,DB_USER,DB_PWD) or die ("Impossible de se connecter à mysql");
		mysql_select_db (DB_NAME) or die ("Impossible d'accéder à la base de données");
		$res=mysql_query($sql, $db) or die ("La requête a échoué [$sql]");
		
		// si 0 résultat, c que ce média n'est pas répertorié
		if ( mysql_num_rows ($res) == 0 ) { $mediaARepertorier = $file; $nbMediasRestantsARepertorier++; }
		}
	}
closedir($handle); 

if ( $nbMediasDuRep == 0 ) { echo "désolé, il n'y a pas de média dans le répertoire \"$repertoire\"... Envoyez-en par FTP !<br><a href=''>retour</a>"; }
else if ($nbMediasRestantsARepertorier == 0) { echo "Il n'y a plus de médias non répertoriés dans le répertoire \"$repertoire\"<br><a href=''>retour</a>"; } 
else {
// -----------------------------------------------------------------------------------------------
// --- Le média $mediaARepertorier est près a être répertorié en base par l'utilisateur
// -----------------------------------------------------------------------------------------------
	echo "<form method='post' name='formulaire' action='insertNouveauxMedias.php' onSubmit='return verifierSaisieUtilisateur();'>\n";

	echo "<p>Il reste $nbMediasRestantsARepertorier média(s) à répertorier dans le répertoire \"$repertoire\" (celui-ci inclus).</p>\n";
	echo "<table><tr><td valign='center'>Voici \"$mediaARepertorier\", celui que vous allez répertorier :<br><br>\n";
	echo "<table>";
// ------------------------------------------------------------------------------------------------
// ---- affiche le média et un formulaire pour la saisie des caractéristiques de ce média
// ------------------------------------------------------------------------------------------------
	echo "<input type='hidden' name='fichier' value='$repertoire/$mediaARepertorier'>\n";
	echo "<input type='hidden' name='repertoire' value='$repertoire'>";
	echo "<tr><td>Titre</td><td><input type='texte' name='titre'></td></tr>\n";
	echo "<tr><td>Catégorie(s)</td><td>\n";
	?>
	<select name="categories2">
	<option value=""></option>
	<?php // LISTE DEROULANTE DES CATEGORIES DEJA REPERTORIES EN BASE
		
	$sql= "SELECT DISTINCT categories FROM medias";
	// ---- On se connecte à mysql et on lance de la requête
	$db = mysql_connect(DB_SERVER,DB_USER,DB_PWD) or die ("Impossible de se connecter à mysql");
	mysql_select_db (DB_NAME) or die ("Impossible d'accéder à la base de données");
	$resultat=mysql_query($sql, $db) or die ("La requête a échoué [$sql]");
	
	// ---- On récupère les résultats et on les affiche dans la liste déroulante
	while ($ligne = mysql_fetch_object ($resultat)) {
		echo "<option value=\"$ligne->categories\">$ligne->categories</option>";			
	}
	mysql_close($db);
	echo "</select> ou <input type='text' name='categories'>\n";
	echo "</td></tr>\n";
	echo "<tr><td>Note (0->5)</td><td><input type='texte' name='note' value='2' size=5></td></tr>\n";
	?>
	<tr><td><a href="javascript:alert('Date et heure de la capture du média (par exemple 31/12/2002 14:54:43). \nDans le cas d\'une photo prise avec un appareil numérique, l\'heure est automatiquement lue dans le fichier (donnée EXIF).');">Date et heure</a></td>
	<?php 
	
	// ------- données Exif de l'image (issues de l'appareil photo num : s304) --------
	 // la fct (read_exif_data) necessite la recompil de php4 avec une option exif...
	 // je l'ai donc redéveloppée pour mes besoins spécifiques.
	 
        $in = fopen("medias/$repertoire/$mediaARepertorier", "rb") or die ("pb d'ouverture de l'image pour lire les données EXIF");   
        $i=0;
        $nbDeZero = 0;
        while (!feof ($in) && $i<15000 && $nbDeZero < 9) {
	    	//$temp = readfile( "medias/$repertoire/$mediaARepertorier" );
	    	$i++;
	    	$temp = fgetc( $in);
	    	$chaine .= $temp;
	    	if ( ord( $temp ) == 0 ) {
		    	$nbDeZero++;
		    	if ( $nbDeZero == 9 ) {
		    		$date = "";
		    		for ($j=0;$j<19;$j++){
		    			$date .=fgetc( $in);
		    		}
		    	}
		    } else {
		    	$nbDeZero=0;
		    }
	    }
        fclose($in); 
	//echo "date exif : $date<br>";
 	// --- fin de la recup des données EXIF de l'image
	// formatage français de la date/heure
    	if ( $date <> "") {
    		if (ereg( "([0-9]{4}):([0-9]{1,2}):([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $date, $regs ) ) {
	    		$date = "$regs[3]/$regs[2]/$regs[1] $regs[4]:$regs[5]:$regs[6]";
	    	} else {
	    		$date = "jj/mm/aaaa hh:mm:ss";
	    	}
	    	//echo "<br>$date<br>";
	} else {
		$date = "jj/mm/aaaa hh:mm:ss";
	}
    	echo "<td><input type='texte' name='date' value='$date' size=18></td>";
	echo "</tr></table></td>";

	echo "<td><a href=\"medias/$repertoire/$mediaARepertorier\"><img height='250' src='medias/$repertoire/$mediaARepertorier'></a></td></tr></table>";
?>
<table><tr>
	<td>Lieu</td><td>
	<select name="idLieu">
		<option value="0"></option>
		<?php // LISTE DEROULANTE DES LIEUX DEJA REPERTORIES EN BASE
		// triée par $trilieu
		if ( !isset( $triLieu ) ) $triLieu='lieu';
		$sql= "SELECT * FROM lieux ORDER BY $triLieu";
		// ---- On se connecte à mysql et on lance de la requête
		$db = mysql_connect(DB_SERVER,DB_USER,DB_PWD) or die ("Impossible de se connecter à mysql");
		mysql_select_db (DB_NAME) or die ("Impossible d'accéder à la base de données");
		$resultat=mysql_query($sql, $db) or die ("La requête a échoué [$sql]");
		
		// ---- On récupère les résultats et on les affiche dans la liste déroulante
		while ($ligne = mysql_fetch_object ($resultat)) {
			// mise en forme de la description du lieu
			if ($ligne->lieu == "") { //lieu sans nom
				if ($ligne->numero == 0)
					$descriptionLieu = "$ligne->voie $ligne->rue";
				else	$descriptionLieu = "$ligne->numero $ligne->voie $ligne->rue";
			} else if ($ligne->numero == 0 && $ligne->voie == "" && $ligne->rue == "") { //lieu sans adresse
				$descriptionLieu = "$ligne->lieu";
			} else {
				if ($ligne->numero == 0)
					$descriptionLieu = "$ligne->lieu ($ligne->voie $ligne->rue)";
				else	$descriptionLieu = "$ligne->lieu ($ligne->numero $ligne->voie $ligne->rue)";
			}
			// affichage de cette description
			echo "<option value=\"$ligne->id\">$descriptionLieu</option>";			
		}
		mysql_close($db);
	echo "</select>\n";
	echo "(trier par <a href='$phpself?repertoire=" . urlencode($repertoire) . "&triLieu=lieu'>nom</a>/<a href='$phpself?repertoire=" . urlencode($repertoire) . "&triLieu=numero'>numéro</a>/<a href='$phpself?repertoire=" . urlencode($repertoire) . "&triLieu=voie'>voie</a>/<a href='$phpself?repertoire=" . urlencode($repertoire) . "&triLieu=rue'>rue</a>)";
	echo "<td colspan=3>OU <a href='nouveauLieu.php?repertoire=" . urlencode($repertoire) . "'>"; ?>Ajouter un nouveau lieu</a>
	</td>
	</tr><tr>
	<td>Anecdote</td><td colspan=4><textarea name="anecdote" rows="5" cols="80"></textarea></td>
	</tr><tr>
	<td>Auteur du média</td><td><input type="texte" name="auteurM" value="<?php echo $auteurM ?>"></td>
	<td>Auteur de l'anecdote</td><td><input type="texte" name="auteurA"></td>
	<td><input type=submit name=submit value='Ajouter en base'></td>
	</tr></table>
	</form>
	
<?php
	// on ferme le else du if ($nbMediasRestantsARepertorier == 0)	
	}
?> 

</body>
</html>
