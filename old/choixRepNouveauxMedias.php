<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<p><b>Pour r&eacute;pertorier de nouveaux médias, choisissez un r&eacute;pertoire :</b></p>

<?php 
// AFFICHE LA LISTE DES REPERTOIRES
if (!( $handle = opendir('medias') )) {echo "erreur d'ouverture du repertoire";} 
while ( false !== ( $file = readdir($handle) ) ) { 
    if ( $file != "." && $file != ".." /* && !is_file($file) */ ) { // affiche seulement les répertoires et evite les . et ..
        echo "<a href=repertorierNouveauxMedias.php?repertoire=".urlencode($file).">$file</a><br>\n"; 
    } 
}
closedir($handle); 
?> 
<p>&nbsp; </p>
</body>
</html>
