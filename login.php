<?php
session_start();
include("config.php");
include("authentification.php");


$message = '';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $login =new Authentification($pdo);
    if($login->loginuser($username,$password)){
        
        $_SESSION['user_id'] = $login->getUserId[$username];
        header("Location: tableau_de_bord.php");
        exit();
    } else {
        $message = 'Mauvais identifiants';
    }
}
    ?>

    
   

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title>Connexion</title>
</head>
<body>

<div class="login-container">
    <h2>Connexion</h2>

    <?php if (!empty($message)): ?>
        <p style="color:red"><?= $message ?></p>
    <?php endif; ?>

    <form action="login.php" method="post">
        <div>
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" id="username" name="username">
        </div>

        <div>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password">
        </div>

        <div>
            <input type="submit" value="Se connecter">
        </div>
    </form>
</div>

</body>
</html>
