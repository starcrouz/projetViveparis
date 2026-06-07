<html>
<link rel="stylesheet" href="styles/afficherLieu.css" type="text/css">

<!-- Paramètres attendus : idLieu idmedia-->
<script language=javascript>window.focus();</script>
<?php
include("globalData.php");
include("fonctionsFront.php");
$db = connecterBdd();

// ------------------------------------------------------------------------------------------
// ---- On récupère les caractéristiques du lieu pour les afficher
// ------------------------------------------------------------------------------------------
// #### le lieu ####
$sql = "SELECT * FROM lieux WHERE id = $idLieu";
$resultat=mysql_query($sql, $db) or die ("La requête a échoué [$sql]");

// ---- On récupère les résultats
$lieu = mysql_fetch_object ($resultat);
// $lieu->lieu/numero/voie/rue
/*$nomDuLieu = $ligne->lieu;
$numero = $ligne->numero;
$voie = $ligne->voie;
$rue = $ligne->rue;
*/
// ------------------------------------------------------------------
// idMedia est le média par défaut à afficher aux utilisateurs
// ------------------------------------------------------------------
// s'il n'a été passé en paramètre (forcé) on lit celui qui a le plus haut poids en base
if ( isset ($idMedia) ) { // le média demandé
	$sql = "SELECT * FROM medias WHERE id = $idMedia";
} else { // le média de plus haut poid
	// SR : ici, mq filtrage sur les critères de l'utilisateur
	$sql = "SELECT medias.* FROM lieux_medias AS lm, medias WHERE lm.idlieu = $idLieu AND lm.idmedia=medias.id ORDER BY medias.poids DESC LIMIT 1";
	}
$resultat=mysql_query($sql, $db) or die ("La requête $sql a échoué");
if ( !($media = mysql_fetch_object ($resultat) ) ) die("Il n'y a pour l'instant aucun média pour ce lieu ! <a href='' onclick='return self.close();'>fermer</a>");
// $media->titremedia/fichier/repertoire/auteurm/poids/date/anecdote/auteura
// ---- On récupère les résultats

/*$titreMedia = $ligne->titremedia;
$fichier = $ligne->fichier;
$repertoire = $ligne->repertoire;
$auteurm = $ligne->auteurm;
$note = $ligne->poids;
$date = $ligne->date;
$anecdote = $ligne->anecdote;
$auteura = $ligne->auteura;
*/

?>
<!-- -------------- DEBUT DU BODY ----------------------------------- -->
<body>
<table border=1 width="99%" height="99%">
  <tr height="3">
    <td align="left" valign="top">
    <table>
<!--  // ------------------------------------------------------------------------------
    // affichage du lieu dans une plaque de rue 
    // ------------------------------------------------------------------------------ -->
      <tr><td>
      <table border="0" background="images/plaque%20de%20rue/Plaque-de-rue_15.gif" cellpadding="0" cellspacing="0">
        <tr>
          <td><img src="images/plaque%20de%20rue/Plaque-de-rue_08.gif" width="21" height="22"></td>
          <td><img src="images/plaque%20de%20rue/Plaque-de-rue_09.gif" width="100%" height="22"></td>
          <td><img src="images/plaque%20de%20rue/Plaque-de-rue_11.gif" width="21" height="22"></td>
        </tr>
        <tr>
          <td><img src="images/plaque%20de%20rue/Plaque-de-rue_14.gif" width="21" height="100%"></td>
          <td align="center" valign="middle"><b class="plaqueDeRue"> 
            <?php
           //  $toto = titresLieu("$idLieu","",$db); 
           // echo $toto[0]["titre"];
           echo $lieu->lieu;
            ?>
            </b></td>
          <td><img src="images/plaque%20de%20rue/Plaque-de-rue_17.gif" width="21" height="100%"></td>
        </tr>
        <tr>
          <td><img src="images/plaque%20de%20rue/Plaque-de-rue_26.gif" width="21" height="22"></td>
          <td><img src="images/plaque%20de%20rue/Plaque-de-rue_27.gif" width="100%" height="22"></td>
          <td><img src="images/plaque%20de%20rue/Plaque-de-rue_29.gif" width="21" height="22"></td>
        </tr>
      </table>
      </td>
<!--  // ------------------------------------------------------------------------------
      // affichage du titre du média
      // ------------------------------------------------------------------------------ -->
       <td>
       <b> &quot; 
      <?php echo $media->titremedia; ?>
      &quot;</b>
      </td></tr></table>
      </td>
    <td rowspan="3" width="50%" align="center">
    <?php $repertoire = $media->repertoire; $fichier = $media->fichier; ?>
      <a href="medias/<?php echo "$repertoire/$fichier"; ?>"><img src="medias/<?php echo "$repertoire/$fichier"; ?>" height=400 alt="<?php echo $media->titremedia ?>"></a><br>
      Imprimer (<a href="medias/<?php echo "$repertoire/$fichier"; ?>"><img src="images/logoImprimante.gif" width="33" height="24" align="absmiddle" border="0"></a>) 
      <?php if ($media->auteurm == ""){ echo " ce m&eacute;dia anonyme";} else { echo " le m&eacute;dia de <i>" .$media->auteurm. "</i>"; } ?>
      , noté <i> 
      <?php echo $media->poids; ?>
      /5.</i>
<p>Choisissez parmi tous les m&eacute;dias attach&eacute;s &agrave; ce lieu : <br />
        <?php
	// #### Tous les médias associés à ce lieu ####
	$sql = "SELECT * FROM lieux_medias AS lm, medias WHERE lm.idlieu = $idLieu AND lm.idmedia=medias.id ORDER BY medias.poids DESC";

	// ---- On lance de la requête
	$resultat=mysql_query($sql, $db) or die ("La requête a échoué [$sql]");
	while ($ligne = mysql_fetch_object ($resultat)) { // id des médias
		// AFFICHE LES VIGNETTES DES AUTRES IMAGES ASSOCIEES AU LIEU
		if ($ligne->id == $idMedia) { echo ">"; } // pointe l'imagette du média affiché
		echo "<a href='afficherLieu.php?idLieu=$idLieu&idMedia=$ligne->id'>";
		echo "<img src=\"medias/$ligne->repertoire/$ligne->fichier\" height=50 alt=\"$ligne->titremedia\">";
		echo "</a> ";
		/*
		$titreMedia = $ligne->titremedia;
		$fichier = $ligne->fichier;
		$auteurm = $ligne->auteurm;
		$note = $ligne->note;
		$date = $ligne->date;
		$anecdote = $ligne->anecdote;
		$auteura = $ligne->auteura;
		$sujet = $ligne->sujet;
		*/
		//$titreMedia = $ligne->soleil;
		}
?>
        </p>
    </td>
  </tr>
  <tr height="5">
    <td align="left" valign="top"> 
      <?php if ($media->anecdote == "") { echo "pas d'anecdote pour ce média";} else { echo $media->anecdote ."<br /><br />"; if ( $auteura == "" ) { echo "(Anecdote anonyme)";} else { echo "(Anecdote de <i>". $media->auteura ."</i>)";} } ?>
    </td>
  </tr>

</table>
<?php
fermerBdd($db);
?>
</body>
</html>