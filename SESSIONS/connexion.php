<?

$host='localhost';
$user='root';
$pswd='';
$dbName='spip';

$conn=mysql_connect($host,$user,$pswd) or die ("Désolé, connexion échouée");
mysql_select_db($dbName) or die ("Désolé, impossible d'accéder à la base de données !");

?>
