<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['utilisateur'] = "intrus";
session_destroy();
header("Location: galerie.php");
exit();
?>
