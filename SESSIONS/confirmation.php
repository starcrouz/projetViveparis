<?
 session_start(); 
 session_register('prenom', 'nom', 'email', 'pass', 'date_naissance', 'role', 'prenom_conj', 
                  'nom_conj', 'date_naissance_conj', 'date_mariage', 'region_mariage', 'budget_robe',
				  'budget_mariage', 'newsletter', 'info_partenaires', 'brochures_partenaires',
				  'rue', 'ville', 'code_postal'); 

function affiche_mois($mois) {
 if ($mois == 1)
  $mois = Janvier;
 elseif($mois == 2)
  $mois = Fevrier;
 elseif($mois == 3)
  $mois = Mars;
 elseif($mois == 4)
  $mois = Avril;
 elseif($mois == 5)
  $mois = Mai;
 elseif($mois == 6)
  $mois = Juin;
 elseif($mois == 7)
  $mois = Juillet;
 elseif($mois == 8)
  $mois = Août;
 elseif($mois == 9)
  $mois = Septembre;
 elseif($mois == 10)
  $mois = Octobre;
 elseif($mois == 11)
  $mois = Novembre;
 elseif($mois == 12)
  $mois = Decembre;

return $mois;
}
?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFCC">

<span class="titre">Voici ce que vous avez redigé :</span>

<form name="confirmation" action="insertion.php" method="post">
<table width="750" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="150" height="30"><strong>Vous :</strong></td>
      <td width="450">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Pr&eacute;nom</td>
      <td class="init"><? echo $prenom; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Nom</td>
      <td class="maj"><? echo $nom; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Email</td>
      <td><? echo $email; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Mot de passe </td>
      <td><? echo $pass_confirm; 
	         $pass = md5($pass_confirm);      
		  ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Votre ann&eacute;e de naissance</td>
      <td><? $mois_naissance1 = affiche_mois($mois_naissance);
			 
	         $date_naissance = $jour_naissance." ".$mois_naissance1." ".$annee_naissance;       
			 echo $date_naissance;
			 $date_naissance = $annee_naissance."-".$mois_naissance."-".$jour_naissance;
		  ?>
	  </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Votre r&ocirc;le </td>
      <td><? echo $role; ?>
	  </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30"><strong>Votre conjoint :</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Pr&eacute;nom </td>
      <td class="init"><? echo $prenom_conj; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Nom </td>
      <td class="maj"><? echo $nom_conj; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Son ann&eacute;e de naissance</td>
      <td><? $mois_naissance_conj1 = affiche_mois($mois_naissance_conj);
			 
	         $date_naissance_conj = $jour_naissance_conj." ".$mois_naissance_conj1." ".$annee_naissance_conj;       
			 echo $date_naissance_conj;
			 $date_naissance_conj = $annee_naissance."-".$mois_naissance."-".$jour_naissance;
		  ?>
	  </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30"><strong>Votre mariage :</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Date de mariage </td>
      <td><? $mois_mariage1 = affiche_mois($mois_mariage);
			 
	         $date_mariage = $jour_mariage." ".$mois_mariage1." ".$annee_mariage;       
			 echo $date_mariage;
			 $date_mariage = $annee_mariage."-".$mois_mariage."-".$jour_mariage;
		  ?>
	  </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Budget de la robe</td>
      <td><? echo $budget_robe; ?> &euro; 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Budget du mariage</td>
      <td><? echo $budget_mariage; ?> &euro;
	  </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Région du mariage</td>
      <td><? echo $region_mariage; ?> </td>
	  <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30" colspan="2"> <? if($newsletter==0)
	                                   echo "Vous ne souhaitez <b>pas</b> recevoir la newsletter de Capmariage.com";
									  else
									   echo "Vous souhaitez recevoir la newsletter de Capmariage.com";
								   ?>
	  </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30" colspan="2"> <? if($info_partenaires==0)
	                                   echo "Vous ne souhaitez <b>pas</b> recevoir des informations des partenaires 
                                             de Capmariage.com";
									  else
									   echo "Vous souhaitez recevoir des informations des partenaires de Capmariage.com";
								   ?>
	  </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30" colspan="2"> <? if($brochures_partenaires==0)
	                                   echo "Vous ne souhaitez <b>pas</b> recevoir des brochures des partenaires 
                                             de Capmariage.com";
									  else
									   echo "Vous souhaitez recevoir des brochures des partenaires de Capmariage.com";
								   ?>
	  </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30" colspan="2">Votre adresse (pour recevoir des informations) 
        :</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Rue</td>
      <td><? echo $rue; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Ville</td>
      <td class="init"><? echo $ville; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Code postal</td>
      <td><? echo $code_postal; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="50" valign="bottom"><input name="valider" type="submit" id="valider" value="Valider"></td>
      <td valign="bottom" align="left"><input name="modifier" type="button" id="modifier" value="Modifier" onClick="javascript: history.go(-1)"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
 </form>

</body>
</html>