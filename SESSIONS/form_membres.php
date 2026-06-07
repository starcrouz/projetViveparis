<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">

<script src="pratique.js"></script>
<script language="JavaScript"> 
<!--
function verifier() {
if (document.formulaire.prenom.value == '') {
 alert ('veuillez indiquer votre prénom !!');
 formulaire.prenom.focus();
 return false;
 }

if (document.formulaire.nom.value == '') {
 alert ('veuillez indiquer votre nom !!');
 formulaire.nom.focus();
 return false;
 }
 
if (document.formulaire.email.value == '') {
 alert ('veuillez indiquer votre email !!');
 formulaire.email.focus();
 return false;
} 

email=document.formulaire.email.value
if (!(emailValide(email)) )
{
 alert('\'' + email + '\' n\'est pas une adresse électronique valide...');
 formulaire.email.focus();
 return false;
} 
 
pass = document.formulaire.pass.value 
if (pass == '') {
 alert ('veuillez indiquer votre mot de passe !!');
 formulaire.pass.focus();
 return false;
}
if(pass.length < 4) {
 alert ('veuillez indiquer un mot de passe de 4 caracteres min !!');
 formulaire.pass.focus();
 return false;
}

if (document.formulaire.pass_confirm.value == '') {
 alert ('veuillez confirmer votre mot de passe !!');
 formulaire.pass_confirm.focus();
 return false;
 }

if (document.formulaire.pass.value != document.formulaire.pass_confirm.value) {
 alert ('vos mots de passe ne sont pas identiques !!');
 formulaire.pass_confirm.focus();
 return false;
 }
 
if (document.formulaire.jour_naissance.value == ''){
 alert ('veuillez indiquer votre jour de naissance !');
 formulaire.jour_naissance.focus();
 return false;
}
if (document.formulaire.mois_naissance.value == ''){
 alert ('veuillez indiquer le mois de votre naissance !');
 formulaire.mois_naissance.focus();
 return false;
}
if (document.formulaire.annee_naissance.value == ''){
 alert ('veuillez indiquer l\'anneé de votre naissance !');
 formulaire.annee_naissance.focus();
 return false;
}


if (document.formulaire.role1.value == '') {
 alert ('veuillez indiquer votre rôle !!');
 formulaire.role1.focus();
 return false;
 }

if (document.formulaire.prenom_conj.value == '') {
 alert ('veuillez indiquer le prenom de votre conjoint !!');
 formulaire.prenom_conj.focus();
 return false;
 }

if (document.formulaire.nom_conj.value == '') {
 alert ('veuillez indiquer le nom de votre conjoint !!');
 formulaire.nom_conj.focus();
 return false;
 }
 
if (document.formulaire.jour_naissance_conj.value == ''){
 alert ('veuillez indiquer le jour de naissance de votre conjoint !');
 formulaire.jour_naissance_conj.focus();
 return false;
}
if (document.formulaire.mois_naissance_conj.value == ''){
 alert ('veuillez indiquer le mois naissance de votre conjoint !');
 formulaire.mois_naissance_conj.focus();
 return false;
}
if (document.formulaire.annee_naissance_conj.value == ''){
 alert ('veuillez indiquer l\'anneé de naissance de votre conjoint !');
 formulaire.annee_naissance_conj.focus();
 return false;
}

if (document.formulaire.jour_mariage.value == ''){
 alert ('veuillez indiquer le jour de votre mariage, c quand meme important :)');
 formulaire.jour_mariage.focus();
 return false;
}
if (document.formulaire.mois_mariage.value == ''){
 alert ('veuillez indiquer le mois de votre mariage, ohhh, vous oubliez beaucoup ;)');
 formulaire.mois_mariage.focus();
 return false;
}
if (document.formulaire.annee_mariage.value == ''){
 alert ('veuillez indiquer l\'anneé de votre mariage, Vous n\'êtes pas pressé ?!');
 formulaire.annee_mariage.focus();
 return false;
}

if (document.formulaire.region_mariage.value == '') {
 alert ('veuillez indiquer votre region de mariage !!');
 formulaire.region_mariage.focus();
 return false;
 } 
 
 budget_robe=document.formulaire.budget_robe.value;
 budget_mariage=document.formulaire.budget_mariage.value;
 budget_robe1=budget_robe*1;
 budget_mariage1=budget_mariage*1;
 if (budget_robe1 != budget_robe) {
  alert('veuillez taper un budget de robe valide !');
  formulaire.budget_robe.focus();
  formulaire.budget_robe.select()
  return false;
 }
 if (budget_mariage1 != budget_mariage) {
  alert('veuillez taper un budget de mariage valide !');
  formulaire.budget_mariage.focus();
  formulaire.budget_mariage.select()
  return false;
 }
 
 code_postal=document.formulaire.code_postal.value;
 code_postal_length=document.formulaire.code_postal.value.length;
 code_postal1=code_postal*1
 rue=document.formulaire.rue.value;
 ville=document.formulaire.ville.value;
 if (rue || ville || code_postal) {
  if ((code_postal) && ((code_postal1 != code_postal) || (code_postal_length != 5))) {
   alert('veuillez taper un code postal valide en cinq chiffres !');
   formulaire.code_postal.focus();
   return false;
  }
  if (!rue) {
   alert('veuillez indiquer votre rue !');
   formulaire.rue.focus();
   return false;  
  }
  if (!ville) {
   alert('veuillez indiquer votre ville !');
   formulaire.ville.focus();
   return false;
  } 
  if (!code_postal) {
   alert('veuillez indiquer votre code postal !'); 
   formulaire.code_postal.focus();
   return false;
  } 
 }  
}

function change(){
 role1=document.formulaire.role1.value
 if (role1 == 'autres') 
 {
  reponse=prompt('Veuillez preciser votre rôle ci-dessous', '');
  
  while(!reponse){
   alert('Votre rôle s\'il vous plait !');
   reponse = prompt('Veuillez preciser votre rôle ci-dessous', '');
   if(reponse == null) break; 
  }
  document.formulaire.role.value = reponse;
 } 
 else if (role1 != 'autres')
  document.formulaire.role.value = role1;
}

//-->
</script>
</head>

<body bgcolor="#FFFFCC">
<span class="titre">Devenez membres... <br>
Veuillez saisir les informations vous concerant (les champs suivis du caract&egrave;re
<span class="etoile"> * </span> sont obligatoir). </span> 
<form name="formulaire" action="confirmation.php" method="post" onSubmit="return verifier()">
  <table width="750" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="150" height="30"><strong>Vous :</strong></td>
      <td width="450">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Pr&eacute;nom <span class="etoile">*</span></td>
      <td><input name="prenom" type="text" id="prenom" size="30" maxlength="25"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Nom <span class="etoile">*</span></td>
      <td><input name="nom" type="text" id="nom" size="30" maxlength="25"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Email <span class="etoile">*</span></td>
      <td><input name="email" type="text" id="email" size="30" maxlength="45"></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="top"> 
      <td height="55" colspan="3"><span class="titre2">Sert de login &agrave; 
        l'espace membre</span><br>
        <span class="titreGras">Attention ! Votre adresse email doit &ecirc;tre 
        valide ! Vous recevrez un email dont vous devez confirmer le re&ccedil;u, 
        avant de pouvoir vous rendre sur la section membre du site ! </span></td>
    </tr>
    <tr> 
      <td height="30">Mot de passe <span class="etoile">*</span></td>
      <td><input name="pass" type="password" id="pass" size="30" maxlength="20"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Confirmez le <span class="etoile">*</span></td>
      <td><input name="pass_confirm" type="password" id="pass_confirm" size="30" maxlength="20"></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="top"> 
      <td height="30" colspan="3" class="desc1">Sert de mot de passe &agrave; 
        l'espace membre</td>
    </tr>
    <tr> 
      <td height="30">Votre date de naissance <span class="etoile">*</span></td>
      <td><select name="jour_naissance">
          <option value="">Jour</option>
		  <? 
		   for($jour_naissance=1; $jour_naissance<32; $jour_naissance++): 
		    echo "<option value=\"$jour_naissance\">$jour_naissance</option>";
		   endfor;
		  ?>
		  </select> 
		  
		  <select name="mois_naissance">
          <option value="">Mois</option>
		  <option value="01">Janvier</option>
          <option value="02">Fevrier</option>
          <option value="03">Mars</option>
          <option value="04">Avril</option>
          <option value="05">Mai</option>
          <option value="06">Juin</option>
          <option value="07">Juillet</option>
          <option value="08">Ao&ucirc;t</option>
          <option value="09">Septembre</option>
          <option value="10">Octobre</option>
          <option value="11">Novembre</option>
          <option value="12">D&eacute;cembre</option>
		  </select> 
		  
		  <select name="annee_naissance">
          <option value="">Année</option>
		  <? 
		    $annee_naissance=(date(Y)-12);
			$annee_naissance_min=(date(Y)-86);
				
		    for($annee_naissance; $annee_naissance>$annee_naissance_min; $annee_naissance--): 
		     echo "<option value=\"$annee_naissance\">$annee_naissance</option>";
			endfor;
		   ?>
		  </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Votre r&ocirc;le <span class="etoile">*</span></td>
      <td><select name="role1" id="role1" onChange="change()";>
          <option value="">Choisissez votre rôle</option>
          <option value="La mariée">La mari&eacute;e</option>
          <option value="Le marié">Le mari&eacute;</option>
		  <option value="autres">Autres</option>
        </select></td> 
      <td><input name="role" type="hidden" size="30" maxlength="25"></td>
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
      <td height="30">Pr&eacute;nom <span class="etoile">*</span></td>
      <td><input name="prenom_conj" type="text" id="prenom_conj" size="30" maxlength="25"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Nom <span class="etoile">*</span></td>
      <td><input name="nom_conj" type="text" id="nom_conj" size="30" maxlength="25"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Sa date de naissance <span class="etoile">*</span></td>
      <td><select name="jour_naissance_conj">
        <option value="">Jour</option>
		<? 
		  for($jour_naissance_conj=1; $jour_naissance_conj<32; $jour_naissance_conj++): 
		   echo "<option value=\"$jour_naissance_conj\">$jour_naissance_conj</option>";
		  endfor;
		?>
		</select> 
		
		<select name="mois_naissance_conj">
          <option value="">Mois</option>
		  <option value="01">Janvier</option>
          <option value="02">Fevrier</option>
          <option value="03">Mars</option>
          <option value="04">Avril</option>
          <option value="05">Mai</option>
          <option value="06">Juin</option>
          <option value="07">Juillet</option>
          <option value="08">Ao&ucirc;t</option>
          <option value="09">Septembre</option>
          <option value="10">Octobre</option>
          <option value="11">Novembre</option>
          <option value="12">D&eacute;cembre</option>
		  </select>
		
		<select name="annee_naissance_conj">
        <option value="">Année</option>
		<? 
		  $annee_naissance_conj=(date(Y)-12);
		  $annee_naissance_conj_min=(date(Y)-86);
		  for($annee_naissance_conj; $annee_naissance_conj>$annee_naissance_conj_min; $annee_naissance_conj--): 
		   echo "<option value=\"$annee_naissance_conj\">$annee_naissance_conj</option>";
		  endfor;
		   ?>
		</select></td>
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
      <td height="30">Date de mariage <span class="etoile">*</span></td>
      <td><select name="jour_mariage">
           <option value="">Jour</option>
		   <? 
		    for($jour_mariage=1; $jour_mariage<32; $jour_mariage++): 
		     echo "<option value=\"$jour_mariage\">$jour_mariage</option>";
			endfor;
		   ?>
		</select> 
		<select name="mois_mariage">
          <option value="">Mois</option>
		  <option value="01">Janvier</option>
          <option value="02">Fevrier</option>
          <option value="03">Mars</option>
          <option value="04">Avril</option>
          <option value="05">Mai</option>
          <option value="06">Juin</option>
          <option value="07">Juillet</option>
          <option value="08">Ao&ucirc;t</option>
          <option value="09">Septembre</option>
          <option value="10">Octobre</option>
          <option value="11">Novembre</option>
          <option value="12">D&eacute;cembre</option>
		  </select> 
		<select name="annee_mariage">
         <option value="">Année</option>
		 <? $annee_mariage=(date(Y)-3);
		    $annee_mariage_max=(date(Y)+3);
		   for($annee_mariage; $annee_mariage<$annee_mariage_max; $annee_mariage++){
		    echo "<option value=\"$annee_mariage\">$annee_mariage</option>";
		   }
		 ?>
		 
        </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">R&eacute;gion du mariage <span class="etoile">*</span></td>
      <td><select name="region_mariage" id="region_mariage">
           <option value="">Choisissez votre region</option>
           <option value="Alsace">Alsace </option>
           <option value="Aquitaine">Aquitaine</option>
           <option value="Auvergne">Auvergne</option>
		   <option value="Basse-Normandie">Basse-Normandie </option>
           <option value="Bourgogne">Bourgogne</option>
           <option value="Bretagne">Bretagne</option>
		   <option value="Centre">Centre </option>
           <option value="Champagne-Ardenne">Champagne-Ardenne</option>
           <option value="Corse">Corse</option>
		   <option value="Franche-Comté">Franche-Comté </option>
           <option value="Guadeloupe">Guadeloupe</option>
           <option value="Guyane">Guyane</option>
		   <option value="Haute-Normandie">Haute-Normandie </option>
           <option value="Ile-de-France">Ile-de-France</option>
           <option value="Languedoc-Roussillon">Languedoc-Roussillon</option>
		   <option value="Limousin">Limousin </option>
           <option value="Lorraine">Lorraine</option>
           <option value="Martinique">Martinique</option>
		   <option value="Midi-Pyrénées">Midi-Pyrénées </option>
           <option value="Nord-Pas-de-Calais">Nord-Pas-de-Calais</option>
           <option value="Pays de la Loire">Pays de la Loire</option>
		   <option value="Picardie">Picardie</option>
           <option value="Poitou-Charentes">Poitou-Charentes</option>
		   <option value="Provence-Alpes-Côte d'Azur">Provence-Alpes-Côte d'Azur </option>
           <option value="Réunion">Réunion</option>
           <option value="Rhône-Alpes">Rhône-Alpes</option>
        </select>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Budget du mariage</td>
      <td><input name="budget_mariage" type="text" id="budget_mariage" size="30"> &euro;
	  </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Budget de la robe</td>
      <td><input name="budget_robe" type="text" id="budget_robe" size="30"> &euro;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30" colspan="2">Je souhaite recevoir la newsletter de Capmariage.com</td>
      <td><input name="newsletter" type="checkbox" id="newsletter" value="1" checked></td>
    </tr>
    <tr> 
      <td height="30" colspan="2">Je souhaite recevoir des informations des partenaires 
        de Capmariage.com</td>
      <td><input name="info_partenaires" type="checkbox" id="info_partenaires" value="1" checked></td>
    </tr>
    <tr> 
      <td height="30" colspan="2">Je souhaite recevoir des brochures des partenaires 
        de Capmariage.com</td>
      <td><input name="brochures_partenaires" type="checkbox" id="brochures_partenaires" value="1" checked></td>
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
      <td><input name="rue" type="text" id="rue" size="30" maxlength="50"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Ville</td>
      <td><input name="ville" type="text" id="ville" size="30" maxlength="25"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="30">Code postal</td>
      <td><input name="code_postal" type="text" id="code_postal" size="30" maxlength="25"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="50" valign="bottom"><input name="valider" type="submit" id="valider" value="Valider"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
 </form>

</body>
</html>