<?php
// données inclues dans de nombreuses pages

define ("CHEMIN_MEDIAS", "medias");
define ("CHEMIN_IMAGETTES", "imagettes");
define ("CHEMIN_PICTOS", "images/pictosLieux");

$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

// Retire le port si présent (ex: "localhost:8000" devient "localhost")
if (($pos = strpos($host, ':')) !== false) {
    $host = substr($host, 0, $pos);
}

if (file_exists('/.dockerenv')) {
    define ("DB_SERVER", "db");
    define ("DB_USER", "root");
    define ("DB_PWD", "toto");
    define ("DB_NAME", "viveparis");
} else if ($host == "localhost" || $host == "127.0.0.1" || empty($host)) {
    define ("DB_SERVER", "localhost");
    define ("DB_USER", "root");
    define ("DB_PWD", "toto");
    define ("DB_NAME", "viveparis");
} else {
    define ("DB_SERVER", "sql.free.fr");
    define ("DB_USER", "www.viveparis");
    define ("DB_PWD", "pdvelh");    
    define ("DB_NAME", "www.viveparis");
}
?>