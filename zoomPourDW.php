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
<?php
include("globalData.php");
include("fonctions.php");

// ---- On se connecte à mysql
$db = mysql_connect(DB_SERVER,DB_USER,DB_PWD) or die ("Impossible de se connecter à mysql");
mysql_select_db (DB_NAME) or die ("Impossible d'accéder à la base de données");


$XMINI = 1;
$XMAXI = 10;
$YMINI = 0;$YMAXI = 10;
$centrageX = 20;
$centrageY = 2;
$calageX = 112 + $centrageX;
$calageY = 94 + $centrageY;

/* pour mémoire, en zoom maxi (=1), le tableau complet d'images fait :
750x450 (taille d'une image) x 10x11 (nb de d'images à assembler)
d'où :
x = 750x10 = 7500
y = 450x11 = 4950
*/

// Encadrement graphique
?>
<div id='encadrement' style='position:absolute; width:958px; height:639px; z-index:5; ; visibility: visible'> 
  <center>
    <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
      <tr> 
        <td valign = 'middle' align = 'center'> <img src="images/encadrement.jpg" width="958" height="639" border="0"> 
      </tr>
    </table>
  </center>
</div>
<div id='plans' style='position:absolute; width:760px; height:470px; z-index:20; ; visibility: visible'> 
  <?php
if ( !isset( $zoom ) || !isset( $x ) || !isset( $y ) ) { $zoom = 11; $x = $y = 0;}
// zooms : 1 (maxi = 1 tranche), 3 (3x3 tranches), 5 (5x5), 7 (7x7), 11 (mini = 10x11 tranches en théorie = image totale paris)
if ($zoom > 5 || $zoom < 1) { // si pas de zoom ou zoom maxi dépassé
	// --------------------pas de zoom du tout : paris total---------------------------
	$zoomX = 10;
	$zoomY = 11;
	?> 
  <center>
    <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
      <tr> 
        <td valign = 'middle' align = 'center'> <img src="plans/parisComplet675x450.jpg" width="675" height="450" usemap="#Map" border="0"> 
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
            <area shape="rect" coords="0,0,233,154" href='<?php $x = 3; $y = 3; echo "zoom.php?x=$x&y=".($y-1)."&zoom=5&provenance=$provenance"; ?>'>
            <area shape="rect" coords="233,0,460,154" href='<?php $x = 5; $y = 3; echo"zoom.php?x=$x&y=".($y-1)."&zoom=5&provenance=$provenance"; ?>'>
            <area shape="rect" coords="460,0,674,154" href='<?php $x = 8; $y = 3; echo"zoom.php?x=$x&y=".($y-1)."&zoom=5&provenance=$provenance"; ?>'>
            <area shape="rect" coords="233,153,462,310" href='<?php $x = 5; $y = 6; echo"zoom.php?x=$x&y=".($y-1)."&zoom=5&provenance=$provenance"; ?>'>
            <area shape="rect" coords="460,154,675,310" href='<?php $x = 8; $y = 6; echo"zoom.php?x=$x&y=".($y-1)."&zoom=5&provenance=$provenance"; ?>'>
            <area shape="rect" coords="0,154,233,310" href='<?php $x = 3; $y = 6; echo"zoom.php?x=$x&y=".($y-1)."&zoom=5&provenance=$provenance"; ?>'>
            <area shape="rect" coords="0,310,233,449" href='<?php $x = 3; $y = 9; echo"zoom.php?x=$x&y=".($y-1)."&zoom=5&provenance=$provenance"; ?>'>
            <area shape="rect" coords="233,310,460,449" href='<?php $x = 5; $y = 9; echo"zoom.php?x=$x&y=".($y-1)."&zoom=5&provenance=$provenance"; ?>'>
            <area shape="rect" coords="460,310,674,449" href='<?php $x = 8; $y = 9; echo"zoom.php?x=$x&y=".($y-1)."&zoom=5&provenance=$provenance"; ?>'>
          </map>
      </tr>
    </table>
  </center>
  <?php
} else {
	// ------------------------------------------------------------------	
	// ------------ le cadre et les boutons autour du plan --------------
	// ------------------------------------------------------------------	
	
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
	
	// à gauche du plan
	if ( $x - 1 - $offset < $XMINI ) {
		// on est à fond à gauche du plan
	} else {
		?>
  <div id='aGauche' style='position:absolute; width:50px; height:50px; z-index:10; ; visibility: hidden'> 
    <a href='zoom.php?x=<?php echo ($x-1)."&y=$y&zoom=$zoom&provenance=$provenance"; ?> '><img border=0 src='images/flecheGauche.gif'></a> 
  </div>
  <?php
	}
	
	// en haut du plan
	?>
  <div id='enHaut' style='position:absolute; width:750px; height:50px; z-index:10; ; visibility: hidden'> 
    <table>
      <tr> 
        <td width='33%' valign='left'> <a href='zoom.php?x=<?php echo "$x&y=$y&zoom=" . ($zoom + 2) . "&provenance=$provenance"; ?> '><img border=0 src='images/dezoomer.gif' align='middle'></a> 
          <a href='zoom.php?zoom=9&provenance=<?php echo $provenance; ?> '>Voir 
          tout Paris</a> </td>
        <td width='33%' align='center'> 
          <?php
	if ( $y - 1 - $offset < $YMINI ) {
		// on est à fond en haut du plan
	} else {
		?>
          <a href='zoom.php?x=<?php echo "$x&y=".($y-1)."&zoom=$zoom&provenance=$provenance"; ?> '><img border=0 src='images/flecheHaut.gif'></a> 
          <?php
	}
	// LISTE DEROULANTE DES CATEGORIES
	?>
        </td>
        <td width='33%' align='center'> 
          <select name='categories[]' multiple size='1'>
            <?php
	$sql= "SELECT * FROM categories";
	// ---- On se connecte à mysql et on lance de la requête
	$resultat=mysql_query($sql, $db) or die ("La requête a échoué [$sql]");
	
	// ---- On récupère les résultats et on les affiche dans la liste déroulante
	while ($ligne = mysql_fetch_object ($resultat)) {
		echo "<option value=\"$ligne->id\">$ligne->categorie</option>";	
	}
	?>
          </select>
        </td>
      </tr>
    </table>
  </div>
  <?php
	// à droite du plan
	
	if ( $x + 1 + $offset > $XMAXI ) {
		// on est à fond à droite du plan
	} else {
		?>
  <div id='aDroite' style='position:absolute; width:50px; height:50px; z-index:10; ; visibility: hidden'> 
    <a href='zoom.php?x=<?php echo ($x+1)."&y=$y&zoom=$zoom&provenance=$provenance"; ?> '><img border=0 src='images/flecheDroite.gif'></a> 
  </div>
  <?php
	}		
	// en bas du plan
	if ( $y + 1 + $offset > $YMAXI ) {
		// on est à fond en bas du plan
	} else {
		?>
  <div id='boutons hauts' style='position:absolute; width:50px; height:50px; z-index:10; ; visibility: hidden'> 
    <a href='zoom.php?x=<?php echo "$x&y=".($y+1)."&zoom=$zoom&provenance=$provenance"; ?> '><img border=0 src='images/flecheBas.gif'></a> 
  </div>
  <?php
	}
	
	// message sous le plan
	?>
  <div id='message' style='position:absolute; width:383px; height:73px; z-index:10; ; visibility: hidden; left: 250px; top: 214px'> 
    <table>
      <tr> 
        <td width='100%' align='center'> Patience, le plan de Paris se charge 
          ! </td>
      </tr>
    </table>
  </div>
  <?php
	// ------------------------------------------------------------------	
	// ----------------construction du plan de Paris---------------------
	// ------------------------------------------------------------------	
	// on construit le tableau avec les images
	?>
  <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
    <?php
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
	?>
  </table>
  <?php
		
} // fin du else (donc s'il y a zoom)
?>
</div>
<?php // fin du calque plans (div) [ci-dessus]
// ------------------------------------------------------------------	
// ----------------placement des icones sur le plan------------------	
// ------------------------------------------------------------------	
if ($zoom > 5 OR $zoom < 1) { // si pas de zoom ou zoom maxi dépassé
	$sql = "SELECT * FROM lieux"; // tous les lieux de la carte !
} else {
	$sql = "SELECT * FROM lieux WHERE x >= " . ( ( $x-1 ) - $offset ) * 750 . " AND x < " . ( $x + $offset ) * 750 . " AND y >= " . ( $y - $offset ) * 450 . " AND y < " . ( ( $y+1 ) + $offset ) * 450;
}
//echo $sql;
// SR : mq les médias : quels icones ?

$res=mysql_query($sql, $db) or die ("La requête $sql a échoué");

// ---- On récupère les lieux dans le périmetre et on affiche pour chacun l'icone de sa catégorie
while ($ligne = mysql_fetch_object ($res)) {
	// on récupère la catégorie et son picto
	$sql = "SELECT c.* FROM lieux_categories AS lc, categories AS c WHERE lc.idlieu=c.id AND c.id=$ligne->id";
	$res2 = mysql_query($sql, $db) or die ("La requête $sql a échoué");
	$ligne2 = mysql_fetch_object ($res2);
	// couleur par defaut si besoin
	if ( ( $couleur = $ligne2->couleur) =="" ) $couleur = 'FFFFFF';
	// placement précis du layer sur le plan
	if ($zoom > 5 || $zoom < 1) { // si pas de zoom ou zoom maxi dépassé
		echo "<div id='$ligne->idbouton' style='position:absolute; width:5px; height:5px; z-index:30; left: " . floor( $xx=($calageX + ( $ligne->x / $zoomX ) ) ). "px; top: " . floor( $yy=($calageY + ( $ligne->y / $zoomY ) ) ). "px'>
		<img border='0' src='".CHEMIN_PICTOS."/$ligne2->couleur' width='5' height='5'>
		</div>
		";
		echo "<div id='$ligne->id' style='position:absolute; width:5px; height:5px; z-index:40; left: " . floor( $xx=($calageX + ( $ligne->x / $zoomX ) ) ). "px; top: " . floor( $yy=($calageY + ( $ligne->y / $zoomY ) ) ). "px'>";
	} else {
		echo "<div id='$ligne->idbouton' style='position:absolute; width:" . ($largeur = (floor(36 / $zoomX)+10) ) . "px; height:" . ($hauteur = (floor(38 / $zoomY)+10) ) . "px; z-index:30; left: " . floor( $xx=($calageX + ( $ligne->x / $zoomX ) - ( ( ( $x-1 ) - $offset ) * 750 / $zoomX ) ) ) . "px; top: " . floor( $yy=($calageY + ( $ligne->y / $zoomY ) - ( ( $y - $offset ) * 450 / $zoomY ) ) ). "px'>
		<img border='0' src='".CHEMIN_PICTOS."/$ligne2->couleur' width='$largeur' height='$hauteur'>
		</div>
		";
		if ($zoom == 3) { 
			$calagePicto = 3;
		} else { // donc si zoom = 1
			$calagePicto = 5;
			}
		echo "<div id='$ligne->id' style='position:absolute; width: ".(floor(26 / $zoomX)+10)." px; height: ".(floor(28 / $zoomY)+10)." px; z-index:40; left: " . floor( $xx + $calagePicto). "px; top: " . floor( $yy + $calagePicto). "px'>";
		}
	
	// mise en forme du nom du lieu
	$titreLieu = titresLieu($ligne->id, '', $db);
	// picto par defaut si besoin
	if ( ( $picto = $ligne2->picto) =="" ) $picto = 'defaut.gif';
	echo "<a href='afficherLieu.php?idLieu=$ligne->id' target='lieu'>";
	// on affiche la pastille
	// on n'affiche le picto que si le zoom est maxi pour qu'il reste visible
	if ( $zoom == 1 OR $zoom == 3) echo "<img border='0' src='".CHEMIN_PICTOS."/$picto' alt=\"".$titreLieu[0]['titre']."\" width='" . (floor(26 / $zoomX)+10) . "' height='" . (floor(28 / $zoomY)+10) . "'>";
	else echo "&nbsp";
	echo "</a></div>\n";
}
mysql_close($db);

//	<div id="icone2" style="position:absolute; width:43px; height:42px; z-index:3; left: 569px; top: 342px"><img src="images/iconeEglise.jpg"></div>
//	<div id="icone3" style="position:absolute; width:43px; height:42px; z-index:4; left: 269px; top: 142px"><img src="images/iconeParc.jpg"></div>
?>
</BODY>
</HTML>