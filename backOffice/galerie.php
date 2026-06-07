<?php
// pour savoir où revenir après la saisie
if (!isset($test)) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['retour'] = $_SERVER["REQUEST_URI"];
    $retour = $_SESSION['retour'];
}
?>
<html>
<!-- paramètres attendus : rien ou critere, repertoire, position et (optionnel) triLieu -->
<head>
<title>Galerie de médias</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript">
<!--
function MM_findObj(n, d) { //v4.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function MM_setTextOfLayer(objName,x,newText) { //v3.0
  if ((obj=MM_findObj(objName))!=null) with (obj)
    if (navigator.appName=='Netscape') {document.write(unescape(newText)); document.close();}
    else innerHTML = unescape(newText);
}

function popup(url){
	a = window.open('','coucou', 'menubar=no,scrollbars=no,status=no,width=350,height=300');
	a.location.href= url;
	a.focus();
}
//-->
</script>
<link rel="stylesheet" href="style.css" type="text/css">
<style type="text/css">
/* Simple modal styles */
.modal-overlay {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.8);
    align-items: center;
    justify-content: center;
}
.modal-content {
    max-width: 90%;
    max-height: 90%;
    border: 5px solid white;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0,0,0,0.5);
}
.modal-close {
    position: absolute;
    top: 20px;
    right: 30px;
    color: white;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
}
</style>
<script language="JavaScript">
function showImageModal(imageUrl) {
    var modal = document.getElementById('imageModal');
    var modalImg = document.getElementById('modalImage');
    modalImg.src = imageUrl;
    modal.style.display = 'flex';
}
function hideImageModal() {
    var modal = document.getElementById('imageModal');
    modal.style.display = 'none';
}
</script>
</head>
<body bgcolor="#ffffff">
<?php
include("../globalData.php");
include("../fonctions.php");
include("entete.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['last_deleted_media'])) {
    $deletedMediaName = htmlspecialchars($_SESSION['last_deleted_media']['media']['titremedia'] ?: $_SESSION['last_deleted_media']['media']['fichier']);
    echo "<div style='background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin: 10px 0; border-radius: 5px; text-align: center;'>";
    echo "Le média <strong>$deletedMediaName</strong> a été supprimé. ";
    echo "<a href='annulerSuppression.php' style='color: #155724; font-weight: bold; text-decoration: underline;'>Annuler la suppression (Undo)</a>";
    echo "</div>";
}

// connexion à la base
$db = connecterBdd();

// Récupération des paramètres
$critere = isset($_GET['critere']) ? $_GET['critere'] : null;
$repertoire = isset($_GET['repertoire']) ? $_GET['repertoire'] : null;
$position = isset($_GET['position']) ? (int)$_GET['position'] : 1;
$nbDeMediasALaFois = isset($_GET['nbDeMediasALaFois']) ? (int)$_GET['nbDeMediasALaFois'] : 4;
$triLieu = isset($_GET['triLieu']) ? $_GET['triLieu'] : '';
$idLieu = isset($_GET['idLieu']) ? (int)$_GET['idLieu'] : null;
$titreLieu = isset($_GET['titreLieu']) ? $_GET['titreLieu'] : '';
$PHP_SELF = $_SERVER['PHP_SELF'];
$phpself = $_SERVER['PHP_SELF'];

// fonctions
function imagette($chemin, $fichier) {
	$chemin = "../" . CHEMIN_MEDIAS . "/" . $chemin;
	if (!file_exists("$chemin/$fichier")) {
		die("\npb ! le fichier $chemin/$fichier n'existe pas...\n");
	}		 
	$ext = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
	
	switch ($ext) {
		case "jpg":
		case "jpeg":
		case "png":
		case "gif":
			@mkdir("$chemin/imagettes", 0777, true);
			clearstatcache();
			if (!file_exists("$chemin/imagettes/$fichier")) {
				if ($ext == 'jpg' || $ext == 'jpeg') {
					$src_im = @imagecreatefromjpeg("$chemin/$fichier");
				} else if ($ext == 'png') {
					$src_im = @imagecreatefrompng("$chemin/$fichier");
				} else if ($ext == 'gif') {
					$src_im = @imagecreatefromgif("$chemin/$fichier");
				}
				
				if (!$src_im) {
					return "$chemin/$fichier";
				}
				
				$src_w = imagesx($src_im);
				$src_h = imagesy($src_im);
				$proportion = $src_h / $src_w;
				
				if ($proportion > 1) {
					$dst_w = (int)(90 / $proportion);
					$dst_h = 90;
				} else {
					$dst_w = 90;
					$dst_h = (int)(90 * $proportion);
				}
				
				if (function_exists('imagecreatetruecolor')) {
					$dst_im = imagecreatetruecolor($dst_w, $dst_h);
					if ($ext == 'png' || $ext == 'gif') {
						imagealphablending($dst_im, false);
						imagesavealpha($dst_im, true);
						$transparent = imagecolorallocatealpha($dst_im, 255, 255, 255, 127);
						imagefilledrectangle($dst_im, 0, 0, $dst_w, $dst_h, $transparent);
					}
					imagecopyresampled($dst_im, $src_im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
				} else {
					$dst_im = imagecreate($dst_w, $dst_h);
					imagecopyresized($dst_im, $src_im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
				}
				
				if ($ext == 'png') {
					imagepng($dst_im, "$chemin/imagettes/$fichier", 9);
				} else if ($ext == 'gif') {
					imagegif($dst_im, "$chemin/imagettes/$fichier");
				} else {
					imagejpeg($dst_im, "$chemin/imagettes/$fichier", 90);
				}
				
				imagedestroy($src_im);
				imagedestroy($dst_im);
			}
			return "$chemin/imagettes/$fichier";
		default:
			return "";
	}
}

$cheminMedias = "../" . CHEMIN_MEDIAS;

// PREMIERE PASSE : L'utilisateur choisi son type de galerie d'images
if (!$critere) {
	echo "
	Afficher une galerie d'images :
	<ul>- seulement celles non répertoriées (que vous venez surement d'uploader)</ul>
	<ul>- <a href='$PHP_SELF?critere=repertoire'>par répertoire au choix</a> (celui dans lequel vous avez uploadé vos médias)</ul>
	<ul>- <a href='$PHP_SELF?critere=lieu'>par lieu au choix</a></ul>
	<ul>- par ordre alphabétique</ul>
	<ul>- par catégorie au choix</ul>
	";
}
// ------------------------------------------------------------------------------------------------
// GALERIE PAR REPERTOIRE
// ------------------------------------------------------------------------------------------------
else if ($critere == 'repertoire') {
	if (!$repertoire) {
		echo "<p><b>De quel répertoire voulez-vous afficher les images</b></p>\n";
		if (!($handle = opendir($cheminMedias))) {
            echo "erreur d'ouverture du repertoire $cheminMedias";
        } else {
            while (false !== ($file = readdir($handle))) { 
                if ($file != "." && $file != ".." && is_dir("$cheminMedias/$file")) {
                    echo "<a href=\"$PHP_SELF?critere=$critere&repertoire=" . urlencode($file) . "\">$file</a><br>\n"; 
                } 
            }
            closedir($handle); 
        }
	} else {
		if (!($handle = opendir("$cheminMedias/$repertoire"))) {
            echo "erreur d'ouverture du repertoire passé en paramètres"; 
            exit();
        } 
		echo "<font size=\"5\">Galerie des médias du répertoire \"" . htmlspecialchars($repertoire) . "\"</font>\n";
		$mediaARepertorier = "";
		$nbMediasDuRep = 0;
		$nbMediasRestantsARepertorier = 0;
		
		$listeLieux = titresLieu("", $triLieu, $db);
		
		// boucle pour chaque média
		echo "<form name='formulaire'><table border='1' cellpadding='5'>";
								
		while (false !== ($fichier = readdir($handle))) {
			$ext = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
			if (is_file("$cheminMedias/$repertoire/$fichier") && in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
				$nbMediasDuRep++;
				// --- système pour afficher les médias x par x ---
				if (!(($nbMediasDuRep < $position) || ($nbMediasDuRep >= $position + $nbDeMediasALaFois))) {
					echo "<tr>";
					// ------------- affiche les imagettes des médias de ce répertoire
					echo "<td align='middle'><a href='#' onclick=\"showImageModal('" . htmlspecialchars("$cheminMedias/$repertoire/$fichier") . "'); return false;\"><img src='" . htmlspecialchars(imagette($repertoire, $fichier)) . "' border='0'></a><br>";
					
					// ---vérifie si ce média n'est pas déja répertorié en base
					$sql = "SELECT * FROM medias WHERE fichier = :fichier AND repertoire = :repertoire";
                    try {
                        $stmt = $db->prepare($sql);
                        $stmt->execute([
                            'fichier' => $fichier,
                            'repertoire' => $repertoire
                        ]);
                        $rowMedia = $stmt->fetch(PDO::FETCH_OBJ);
                    } catch (PDOException $e) {
                        die("Erreur sql : " . $e->getMessage());
                    }
	
					echo "[" . htmlspecialchars($fichier) . "]</td>";
                    
					if (!$rowMedia) { 
						$mediaARepertorier = $fichier; 
						$nbMediasRestantsARepertorier++; 
						echo "<td align='middle'><a href='saisieParamD1Media.php?fichier=" . urlencode($fichier) . "&repertoire=" . urlencode($repertoire) . "'><b>Média non répertorié</b></a></td>";
					} else { 
						// --------------- affichage des infos : titre média / lieu
						echo "<td align='middle'><a href='saisieParamD1Media.php?fichier=" . urlencode($fichier) . "&repertoire=" . urlencode($repertoire) . "'>" . htmlspecialchars($rowMedia->titremedia) . "</a></td>";
						
						$idMedia = $rowMedia->id;
						$sqlLieu = "SELECT idlieu FROM lieux_medias WHERE idmedia = :idMedia";
                        try {
                            $stmtLieu = $db->prepare($sqlLieu);
                            $stmtLieu->execute(['idMedia' => $idMedia]);
                            $rowLieu = $stmtLieu->fetch(PDO::FETCH_OBJ);
                        } catch (PDOException $e) {
                            die("Erreur sql : " . $e->getMessage());
                        }
						
						$idLieu = $rowLieu ? $rowLieu->idlieu : 0;
						
						echo "<td align='middle'>Lieu associé au média<br>";
						echo "<select class='formBO' name='idLieu$idMedia'>";
						echo "<option value='0'>*** Aucun lieu associé ! ***</option>\n";
						
						foreach ($listeLieux as $z) {
							if ($z['id'] == $idLieu) {
								echo "<option value=\"" . htmlspecialchars($z['id']) . "\" selected>" . htmlspecialchars($z['titre']) . "</option>\n";
							} else {
								echo "<option value=\"" . htmlspecialchars($z['id']) . "\">" . htmlspecialchars($z['titre']) . "</option>\n";
							}
						}
						echo "</select>\n";
                        
						echo "<a href='#' onclick=\"var newIdLieu = document.formulaire.idLieu$idMedia.options[document.formulaire.idLieu$idMedia.selectedIndex].value;
						if ( newIdLieu == $idLieu ) { alert('Rien à mettre à jour !'); } 
						else {
							a = window.open('','coucou', 'menubar=no,scrollbars=no,status=no,width=10,height=10');
				   			a.location.href='changerLieuD1Media.php?idMedia=$idMedia&idLieu=$idLieu&newIdLieu=' + newIdLieu;
					   	}
					   	return false;
				   		\">Valider</a><br><a href='saisieParamD1Lieu.php?idLieu=$idLieu'>Editer ce lieu</a> ou <a href='saisieParamD1Lieu.php'>Créer un nouveau lieu</a>";
						
                        echo "</td><td>";
						echo "<a href='#' onclick=\"
						if (confirm('Voulez-vous vraiment supprimer définitivement ce média et son fichier image ?')) {
							a = window.open('','coucou', 'menubar=no,scrollbars=no,status=no,width=10,height=10');
							a.location.href='supprimer1Media.php?idMedia=$idMedia';
						}
						return false;
						\">Supprimer</a>";
						
						echo "</td><td align='middle'>";
						// ------- CARACTERISTIQUES DU MEDIA ---------
						$sqlMC = "SELECT * FROM medias_caracteristiques WHERE idMedia = :idMedia";
                        $CaracteristiqueSelectionnee = [];
                        try {
                            $stmtMC = $db->prepare($sqlMC);
                            $stmtMC->execute(['idMedia' => $idMedia]);
                            while ($rowMC = $stmtMC->fetch(PDO::FETCH_OBJ)) {
                                $CaracteristiqueSelectionnee[$rowMC->idCaracteristique] = 1;
                            }
                        } catch (PDOException $e) {
                            die("Erreur sql : " . $e->getMessage());
                        }
	
						echo "Caracteristique(s)<br>
						<form action='changerCaracteristiquesD1Media.php'>
						<input type=hidden name=idMedia value=$idMedia>
						<select class='formBO' name='caracteristiques[]' multiple size='2'>
						";
                        
						$sqlC = "SELECT * FROM caracteristiques ORDER BY titre";
                        try {
                            $stmtC = $db->query($sqlC);
                            $debutListe = "<option>popup de choix + cool</option>";
                            $suiteListe = "";
                            while ($rowC = $stmtC->fetch(PDO::FETCH_OBJ)) {
                                if (isset($CaracteristiqueSelectionnee[$rowC->id]) && $CaracteristiqueSelectionnee[$rowC->id] == 1) {
                                    $debutListe .= "<option value=\"" . htmlspecialchars($rowC->id) . "\" selected>" . htmlspecialchars($rowC->titre) . "</option>";
                                } else {
                                    $suiteListe .= "<option value=\"" . htmlspecialchars($rowC->id) . "\">" . htmlspecialchars($rowC->titre) . "</option>";
                                }
                            }
                        } catch (PDOException $e) {
                            echo "<option>Erreur sql</option>";
                        }
						print($debutListe . $suiteListe);
						echo "</select><br>
						<a href='javascript:submit()'>Valider</a> ou 
						<a href='javascript:popup(\"saisieParamD1caracteristique.php\")'>créer une nouvelle caractéristique</a> 				
						</td>
						";
					}
					echo "</tr>";
				}
			}
	    }
		echo "</table></form>";    
		closedir($handle);
		 
		$menu = "Voir vos $nbMediasDuRep médias $nbDeMediasALaFois par $nbDeMediasALaFois (+-): ";
		if ($nbMediasDuRep > $nbDeMediasALaFois) {
			if ($position > 1) $menu .= "< "; 
			for ($i = 1; $i <= $nbMediasDuRep; $i += $nbDeMediasALaFois) {
                $maxShow = min($i + $nbDeMediasALaFois - 1, $nbMediasDuRep);
				if ($i == $position) {
					$menu .= "<font color=red>$i à $maxShow</font> ";	
                } else {
					$menu .= "<a href='$PHP_SELF?critere=$critere&repertoire=" . urlencode($repertoire) . "&position=$i'>$i à $maxShow</a> ";
                }
			}
			if ($i > ($position + $nbDeMediasALaFois)) $menu .= ">";
			echo $menu;
		}
		
		echo "
		<script>
		if (document.all) MM_setTextOfLayer('grpMenu','',\"$menu\");
		</script>
		";
		
		if ($nbMediasDuRep == 0) { 
            echo "désolé, il n'y a pas de média dans le répertoire \"" . htmlspecialchars($repertoire) . "\"... Envoyez-en par FTP !<br><a href='galerie.php'>retour</a>"; 
        } else {
			echo "<br><br>Cliquez sur l'imagette pour voir le media en taille réelle, sur son titre pour l'éditer, sur <a href='saisieParamD1Lieu.php'>Créer un nouveau lieu</a> ou sur <a href='galerie.php'>retour</a>";
			echo "<br>(trier les lieux par <a href='$PHP_SELF?critere=repertoire&repertoire=" . urlencode($repertoire) . "&triLieu=lieu'>nom</a>/<a href='$PHP_SELF?critere=repertoire&repertoire=" . urlencode($repertoire) . "&triLieu=numero'>numéro</a>/<a href='$PHP_SELF?critere=repertoire&repertoire=" . urlencode($repertoire) . "&triLieu=voie'>voie</a>/<a href='$PHP_SELF?critere=repertoire&repertoire=" . urlencode($repertoire) . "&triLieu=rue'>rue</a>)";
		}
   	}
} else if ($critere == 'lieu') {
// ------------------------------------------------------------------------------------------------
// GALERIE PAR LIEU
// ------------------------------------------------------------------------------------------------
	if (!$idLieu) {
		echo "<p><b>De quel lieu voulez-vous afficher les images</b></p>\n";
	
		$sql = "SELECT * FROM lieux";
        try {
            $stmt = $db->query($sql);
            $count = $stmt->rowCount();
            if ($count == 0) {
                die("Il n'existe aucun lieu !");
            }
            while ($ligne = $stmt->fetch(PDO::FETCH_OBJ)) {
                $idLieu = $ligne->id;
                echo "<a href=\"$PHP_SELF?critere=$critere&idLieu=$idLieu&titreLieu=" . urlencode($ligne->lieu) . "\">" . htmlspecialchars($ligne->lieu) . "</a><br>\n"; 
            } 
        } catch (PDOException $e) {
            die("Erreur sql : " . $e->getMessage());
        }
	} else {
		echo "<font size=\"5\">Galerie des médias du lieu \"" . htmlspecialchars(urldecode($titreLieu)) . "\"</font>\n";
		
        $sql = "SELECT medias.* FROM medias, lieux_medias AS lm WHERE lm.idlieu = :idLieu AND medias.id = lm.idmedia ORDER BY medias.poids DESC";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute(['idLieu' => $idLieu]);
            if ($stmt->rowCount() == 0) {
                die("<br><br>Aucun média n'est associé à ce lieu ! <a href='galerie.php'>Retour</a>");
            }
            echo "<form name='formulaire'><table border='1' cellpadding='5'>";
            while ($ligne = $stmt->fetch(PDO::FETCH_OBJ)) {
                $idMedia = $ligne->id;
                $poidsMedia = $ligne->poids;
                $repertoireMedia = $ligne->repertoire;
                $fichier = $ligne->fichier;
                
                echo "<tr>";
                echo "<td align='middle'><a href='#' onclick=\"showImageModal('" . htmlspecialchars("$cheminMedias/$repertoireMedia/$fichier") . "'); return false;\"><img src='" . htmlspecialchars(imagette($repertoireMedia, $fichier)) . "' border='0'></a></td>";
                echo "<td align='middle'><a href='saisieParamD1Media.php?fichier=" . urlencode($fichier) . "&repertoire=" . urlencode($repertoireMedia) . "'>" . htmlspecialchars($ligne->titremedia) . "</a></td>";
                echo "<td align='middle'><input class='formBO' name='poids$idMedia' type='text' size='3' value='" . htmlspecialchars($poidsMedia) . "'>
                <a href='#' onclick=\"
                if ( document.formulaire.poids$idMedia.value == $poidsMedia ) { alert('Rien à mettre à jour !'); }
                else {
                a = window.open('','coucou', 'menubar=no,scrollbars=no,status=no,width=10,height=10');
                a.location.href='changerPoidsD1Media.php?idMedia=$idMedia&newPoids='+document.formulaire.poids$idMedia.value;
                }
                \">Valider</a>
                </td>";
                echo "<td><a href='#' onclick=\"
                a = window.open('','coucou', 'menubar=no,scrollbars=no,status=no,width=10,height=10');
                a.location.href='changerLieuD1Media.php?idMedia=$idMedia&idLieu=$idLieu&newIdLieu=0';
                return false;
                \">Désalouer</a> / <a href='#' onclick=\"
                if (confirm('Voulez-vous vraiment supprimer définitivement ce média et son fichier image ?')) {
                    a = window.open('','coucou', 'menubar=no,scrollbars=no,status=no,width=10,height=10');
                    a.location.href='supprimer1Media.php?idMedia=$idMedia';
                }
                return false;
                \">Supprimer</a></td>";
                echo "</tr>";
            }
            echo "</table></form>";    
            echo "<br><br>Cliquez sur le média que vous voulez éditer ou sur <a href='galerie.php'>retour</a>";
        } catch (PDOException $e) {
            die("Erreur sql : " . $e->getMessage());
        }
  	}
}

fermerBdd($db);
?> 

<div id="imageModal" class="modal-overlay" onclick="hideImageModal()">
    <a class="modal-close" href="javascript:void(0)">&times;</a>
    <img class="modal-content" id="modalImage" src="" onclick="event.stopPropagation()">
</div>
<?php include("piedDePage.php"); ?>
</body>
</html>
