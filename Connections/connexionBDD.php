<?php 
/* FileName="Connection_php_direct.htm" "driver=org.gjt.mm.mysql.Driver|url=jdbc:mysql://localhost/viveparis|uid=root|pword=toto" 
 Type="JDBC" 
 Catalog="" 
 Schema=""
 HTTP="false" 
*/
	if(!isset($PHP_SELF)){ 
		$PHP_SELF=getenv("SCRIPT_NAME"); 
	}
	if (!isset($QUERY_STRING)){
		$QUERY_STRING="";
	}
	if (!isset($REQUEST_URI)){
		$REQUEST_URI=$PHP_SELF;
	}
   $MM_connexionBDD_HOSTNAME = "localhost";
   $MM_connexionBDD_DBTYPE = "mysql";
   $MM_connexionBDD_DATABASE = "viveparis";
   $MM_connexionBDD_USERNAME = "root";
   $MM_connexionBDD_PASSWORD = "toto";
   ADOLoadCode($MM_connexionBDD_DBTYPE);
   $connexionBDD=&ADONewConnection($MM_connexionBDD_DBTYPE);
   if($MM_connexionBDD_DBTYPE == "access" || $MM_connexionBDD_DBTYPE == "odbc"){
   		$connexionBDD->PConnect($MM_connexionBDD_DATABASE, $MM_connexionBDD_USERNAME,$MM_connexionBDD_PASSWORD);
   } else if($MM_connexionBDD_DBTYPE == "ibase") {
   		$connexionBDD->PConnect($MM_connexionBDD_HOSTNAME.":".$MM_connexionBDD_DATABASE,$MM_connexionBDD_USERNAME,$MM_connexionBDD_PASSWORD);
   } else {
   		$connexionBDD->PConnect($MM_connexionBDD_HOSTNAME,$MM_connexionBDD_USERNAME,$MM_connexionBDD_PASSWORD,$MM_connexionBDD_DATABASE);
   }
?>
