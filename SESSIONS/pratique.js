// Fonctions pratiques en JavaScript

// Convertit un texte en minuscule en MAJUSCULE
/* La méthode "toUpperCase" focntionne très mal avec la langue française :
   elle transforme les caractères minuscules accentués en majuscules accentuées. 
   Or, en Français, les majuscules ne comportent pas d'accent.
*/
function enMajuscule(text)
{
    var minus = "aàâäbcçdeéèêëfghiîïjklmnoôöpqrstuùûvwxyz"        
    var majus = "AAAABCCDEEEEEFGHIIIJKLMNOOOPQRSTUUUVWXYZ"
    var entree = text;
    var sortie = "";
    for (var i = 0 ; i < entree.length ; i++)
    { 
      var car = entree.substr(i, 1);
      sortie += (minus.indexOf(car) != -1) ? majus.substr(minus.indexOf(car), 1) : car;
    }
    return sortie;
}

// Vérification d'un mail
function emailValide(email)
{
     var reg = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}[.][a-zA-Z0-9]{2,4}$/
     var reg2 = /[.@]{2,}/
     return ((reg.exec(email)!=null) && (reg2.exec(email)==null));
}


