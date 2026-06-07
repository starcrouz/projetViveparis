<?
session_start();

include("connexion.php");

$date_enregistrement=date("H:i d/m/y");
$inserer=mysql_query("INSERT INTO cm_membres_temp (prenom, nom, email, pass, date_naissance, 
                      role, prenom_conj, nom_conj, date_naissance_conj, date_mariage, region_mariage, 
					  budget_robe, budget_mariage, newsletter, info_partenaires, brochures_partenaires, 
					  rue, ville, code_postal, date_enregistrement) 
				      VALUES ('$prenom', '$nom', '$email', '$pass', '$date_naissance', '$role', '$prenom_conj',
					          '$nom_conj', '$date_naissance_conj', '$date_mariage', '$region_mariage',
							  '$budget_robe', '$budget_mariage', '$newsletter', '$info_partenaires', 
							  '$brochures_partenaires', '$rue', '$ville', '$code_postal', '$date_enregistrement')");

$sujet="Cap-Mariage, confirmation d'inscription";
$message="Voici ce que vous avez saisi.....! cliques sur ce <a href=\"confirm.php?email=$email&nom=$nom\" lien </a> pour valider votre inscription";
$from="Content-type: text/html";
//mail($email,$sujet,$message,$from);
echo "<form action=\"confirm\" method=\"post\">
     cliquez sur ce <a href=\"confirm.php?email=$email&nom=$nom\"> lien </a> pour valider votre inscription
     <input type=\"submit\">";
session_unset();
session_destroy();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFCC">

Votre document a bien été insere ds notre base de donnees, vous receverez un email 
pour confirmer votre inscription.....

