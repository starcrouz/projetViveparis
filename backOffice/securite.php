<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialisation de la variable de session si non définie
if (!isset($_SESSION['utilisateur'])) {
    $_SESSION['utilisateur'] = "intrus";
}

// Récupération des entrées login/password
$login = isset($_POST['login']) ? $_POST['login'] : (isset($_GET['login']) ? $_GET['login'] : null);
$password = isset($_POST['password']) ? $_POST['password'] : (isset($_GET['password']) ? $_GET['password'] : null);

if (!empty($login) && !empty($password)) {
    $db = connecterBdd();	
    $sql = "SELECT * FROM utilisateurs WHERE login = :login AND password = :password";
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'login' => $login,
            'password' => md5($password)
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            echo "<script>alert(\"Désolé, votre login/password n'est pas reconnu...\");</script>";
            $_SESSION['utilisateur'] = "intrus";
        } else {
            echo "<script>alert('Bienvenue " . htmlspecialchars($login) . " !');</script>";
            $_SESSION['utilisateur'] = $login;
        }
    } catch (PDOException $e) {
        die("Erreur authentification : " . $e->getMessage());
    }
    fermerBdd($db);
}

// Expose la variable $utilisateur localement pour compatibilité
$utilisateur = $_SESSION['utilisateur'];
?>
