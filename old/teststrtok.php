<html><body>
<?php
  $string = "ceci/est/une/chaîne/exemple.html";
  $table = explode("/",$string);
  $i = 0;
  $repertoire = "";
  while ($table[$i]) {
    $i++;
    if ($table[$i]) $repertoire .= $table[$i-1]."/";
    else {
    	$repertoire = substr ($repertoire, 0, strlen($repertoire)-1 ); // enlève le dernier '/'
    	$fichier = $table[$i-1];
    	}
  }
  echo "file=$fichier, rep=$repertoire";
?>
</body>
     </html>



