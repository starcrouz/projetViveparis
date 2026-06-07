<html>
<head>
<script language="JavaScript">
<!-- pour parrer au pb de layer avec ns
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
// -->
</script>

<script>
<!--

self.focus();

x = 2;
y = 2;

function deplace() {
	if ( y > 10 ) { alert("fin de la carte"); y--; return;}
	if ( y < 0 ) { alert("fin de la carte"); y++; return;}
	if ( x > 9 ) { alert("fin de la carte"); x--; return;}
	if ( x < 0 ) { alert("fin de la carte"); x++; return;}
	numero = y * 10 + x + 1;
	if ( numero < 10 ) { numero = "0" + numero;}
	//alert("numero="+numero);
	document.tranche.src = "plans/images2/planParis_" + numero + ".jpg";
}
// -->
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<?php
include("globalData.php");

$XMINI = 1;
$XMAXI = 10;
$YMINI = 0;$YMAXI = 10;
$calageX = 105;
$calageY = 30;


if ( !isset( $zoom ) || !isset( $x ) || !isset( $y ) ) { $zoom = 11;}
	// zooms : 1 (maxi = 1 tranche), 3 (3x3 tranches), 5 (5x5), 7 (7x7), 11 (mini = 10x11 tranches en théorie = image totale paris)
	if ($zoom > 5 || $zoom < 1) { // si pas de zoom ou zoom maxi dépassé
		// --------------------pas de zoom du tout : paris total---------------------------
		?>
		<div id='plans' style='position:absolute; width:750px; height:450px; z-index:2; <?php echo"left: ".$calageX."px; top: ".$calageY."px"; ?>'>
		<center><TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<tr><td valign = 'middle' align = 'center'>
		<img src="plans/parisComplet675x450.jpg" width="675" height="450" usemap="#Map" border="0"> 
		<!--
		en zoom 5,
		 -----------
		|3,3|5,3|8,3|
		 -----------
		|3,6|5,6|8,6|
		 -----------
		|3,9|5,9|8,9|
		 -----------
		--> 
		<map name="Map">
		<area shape="rect" coords="0,0,233,154" href='<?php $zoom = 5; $x = 3; $y = 3; echo "zoom.php?x=$x&y=".($y-1)."&zoom=$zoom&provenance=$provenance"; ?>'>
		<area shape="rect" coords="233,0,460,154" href='<?php $x = 5; $y = 3; echo"zoom.php?x=$x&y=".($y-1)."&zoom=$zoom&provenance=$provenance"; ?>'>
		<area shape="rect" coords="460,0,674,154" href='<?php $x = 8; $y = 3; echo"zoom.php?x=$x&y=".($y-1)."&zoom=$zoom&provenance=$provenance"; ?>'>
		<area shape="rect" coords="233,153,462,310" href='<?php $x = 5; $y = 6; echo"zoom.php?x=$x&y=".($y-1)."&zoom=$zoom&provenance=$provenance"; ?>'>
		<area shape="rect" coords="460,154,675,310" href='<?php $x = 8; $y = 6; echo"zoom.php?x=$x&y=".($y-1)."&zoom=$zoom&provenance=$provenance"; ?>'>
		<area shape="rect" coords="0,154,233,310" href='<?php $x = 3; $y = 6; echo"zoom.php?x=$x&y=".($y-1)."&zoom=$zoom&provenance=$provenance"; ?>'>
		<area shape="rect" coords="0,310,233,449" href='<?php $x = 3; $y = 9; echo"zoom.php?x=$x&y=".($y-1)."&zoom=$zoom&provenance=$provenance"; ?>'>
		<area shape="rect" coords="233,310,460,449" href='<?php $x = 5; $y = 9; echo"zoom.php?x=$x&y=".($y-1)."&zoom=$zoom&provenance=$provenance"; ?>'>
		<area shape="rect" coords="460,310,674,449" href='<?php $x = 8; $y = 9; echo"zoom.php?x=$x&y=".($y-1)."&zoom=$zoom&provenance=$provenance"; ?>'>
		</map> 		
		</tr></td></table></center></div>
<?php
} else {
		// ------------------------ le cadre et les boutons autour du plan -------------------
		echo"<div id='cadreEtBoutons' style='position:absolute; width:800px; height:500px; z-index:1; left: ".$calageX-50."px; top: ".$calageY+50."px'>\n";
		// TOUS LES CALCULS NECESSAIRES AU ZOOM
		// taille des tranches en pixels (sauf bords droit et bas)
		// zoom: 7=107x65(~15%),5=150x90(20%),3=250x150(~33%),1=750x450(100%)
		switch ( $zoom ) {
			//case 7 : $largeur = 107; $hauteur = 65; break;
			case 5 : $largeur = 150; $hauteur = 90; break;
			case 3 : $largeur = 250; $hauteur = 150; break;
			case 1 : $largeur = 750; $hauteur = 450; break;
			default: $largeur = 750/11; $hauteur = 45;  // A VERIFIER
		}
		$zoomX = 750 / $largeur;
		$zoomY = 450 / $hauteur;
		
		// on calcule le nombre d'image absolu ($offset) autour de l'image centrale
		$offset = floor($zoom / 2); // arrondi à l'entier inférieur
		// si une partie du zoom est hors de la carte, on recadre
		while ( $x - $offset < $XMINI ) { $x++;}
		while ( $x + $offset > $XMAXI ) { $x--;}
		while ( $y - $offset < $YMINI ) { $y++;}
		while ( $y + $offset > $YMAXI ) { $y--;}
		//echo "<br>Calcul => x=$x, y=$y";
		// FIN DES CALCULS
		echo "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>";
		echo "<tr>";
		echo "<td valign='middle' rowspan='".($zoom+2)."'>";
		if ( $x - 1 - $offset < $XMINI ) {
			// on est à fond à gauche du plan
		} else {
			echo "<a href='zoom.php?x=".($x-1)."&y=$y&zoom=$zoom&provenance=$provenance'><img border=0 src='images/flecheGauche.gif'></a>";
		}
		echo "</td><td align='center' colspan='".($zoom+2)."'>";
		
		// Bouton pour dézoomer
		echo "<a href='zoom.php?x=$x&y=$y&zoom=" . ($zoom + 2) . "&provenance=$provenance'>Dézoomer</a> <<<< ";
		
		if ( $y - 1 - $offset < $YMINI ) {
			// on est à fond en haut du plan
		} else {
			echo "<a href='zoom.php?x=$x&y=".($y-1)."&zoom=$zoom&provenance=$provenance'><img border=0 src='images/flecheHaut.gif'></a>";
		}
		echo " >>>> <a href='zoom.php?zoom=9&provenance=$provenance'>Voir tout Paris</a> ";
		echo "<br><br><br></td>";
		echo "<td valign='middle' align='right' rowspan='".($zoom+2)."'>";
		if ( $x + 1 + $offset > $XMAXI ) {
			// on est à fond à droite du plan
		} else {
			echo "<a href='zoom.php?x=".($x+1)."&y=$y&zoom=$zoom&provenance=$provenance'><img border=0 src='images/flecheDroite.gif'></a>";
		}		
		echo "</td></tr><tr><td width='760' height='410'>\n";
		
// ############# emplacement du plan de Paris ################		
		
		echo "</td></tr>";
		echo "<tr><td align='center' colspan='$zoom'><br>";
		if ( $y + 1 + $offset > $YMAXI ) {
			// on est à fond en bas du plan
		} else {
			echo "<a href='zoom.php?x=$x&y=".($y+1)."&zoom=$zoom&provenance=$provenance'><img border=0 src='images/flecheBas.gif'></a>";
		}
		echo "</tr></td></table></center>";
		echo "</div>

// ----------------construction du plan de Paris---------------------
		// on construit le tableau avec les images
		echo "<div id='plans' style='position:absolute; width:750px; height:450px; z-index:2; left: ".$calageX."px; top: ".$calageY."px'>\n";
		echo "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
		for ($i = $y - $offset; $i <= $y + $offset; $i++){
			echo "<tr>";
			for ($j = $x - $offset; $j <= $x + $offset; $j++){
				echo "<td>";
				if ( $zoom == 1 ) {
					if ( $provenance == 'nouveauLieu' ) { 
						// alors on récupère les coordonnées du clic de l'internaute (pour ajouter un lieu)
						echo "<a href='' onclick='opener.donnelieuxDuClic(event, $calageX - ( $x-1 ) * 750, $calageY - $y * 450); self.close(); return false;'>";
						}
				} else {
					echo "<a href='zoom.php?x=$j&y=$i&zoom=" . ($zoom - 2) . "&provenance=$provenance'>";
				}
				// ajoute un 0 devant le numero de l'image si < à 10
				if ( ($i == 0) && ($j <> 10) ) {$numero = "0".$j;} else {$numero = $i*10 + $j;}
				//echo "<img src='plans/tranches$zoom/planParis_$numero.jpg' width='$largeur' height='$hauteur' border='0'>";
				if ( $zoom == 9 ) {
					echo "<img src='plans/tranches1/planParis_$numero.jpg' width='$largeur' height='$hauteur' border='0'>";
				} else {
					echo "<img src='plans/tranches$zoom/planParis_$numero.jpg' border='0'>";
				}
				echo "</a>";
				echo "</td>";
			}
			echo "</tr>";
		}
		echo "</table></div>";
		
	} // fin du else (donc s'il y a zoom)

	
// ----------------placement des icones sur le plan------------------	
$sql = "SELECT * FROM lieux WHERE x >= " . ( ( $x-1 ) - $offset ) * 750 . " AND x < " . ( $x + $offset ) * 750 . " AND y >= " . ( $y - $offset ) * 450 . " AND y < " . ( ( $y+1 ) + $offset ) * 450;
//echo $sql;
// SR : mq les médias : quels icones ?

// ---- On se connecte à mysql et on lance de la requête
$db = mysql_connect(DB_SERVER,DB_USER,DB_PWD) or die ("Impossible de se connecter à mysql");
mysql_select_db (DB_NAME) or die ("Impossible d'accéder à la base de données");
$resultat=mysql_query($sql, $db) or die ("La requête a échoué [$sql]");

// ---- On récupère les résultats et on affiche les icones de ces lieux
while ($ligne = mysql_fetch_object ($resultat)) {
	echo "<div id='$ligne->id' style='position:absolute; width:" . 23 / $zoomX . "px; height:" . 24 / $zoomY . "px; z-index:10; left: " . $xx=($calageX + ( $ligne->x / $zoomX ) - ( ( ( $x-1 ) - $offset ) * 750 / $zoomX ) ) . "px; top: " . $yy=($calageY + ( $ligne->y / $zoomY ) - ( ( $y - $offset ) * 450 / $zoomY ) ) . "px'>";
	//echo "<br><font color=red>xx=$xx , yy=$yy</font><br>";
	// ---- On formate la description du lieu
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
	echo "<a href='afficherLieu.php?idLieu=$ligne->id' target='lieu'>";
	echo "<img src='images/iconeMusee.jpg' alt=\"$descriptionLieu\" width=" . 23 / $zoomX . " height=" . 24 / $zoomY . ">";
	echo "</a></div>";
}
mysql_close($db);

//	<div id="icone2" style="position:absolute; width:43px; height:42px; z-index:3; left: 569px; top: 342px"><img src="images/iconeEglise.jpg"></div>
//	<div id="icone3" style="position:absolute; width:43px; height:42px; z-index:4; left: 269px; top: 142px"><img src="images/iconeParc.jpg"></div>
?>
	
</BODY>
</HTML>