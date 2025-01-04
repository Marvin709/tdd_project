<?php
include("config.php");
include("set.php");
session_start();
$message='';

if (!isset($_SESSION['user_id'])) {
     $message = "Vous devez être connecté pour mettre à jour votre profil.";
      header("Location: login.php"); 
    }

    $user_id = $_SESSION['user_id'];
    if (isset($_POST['email'], $_POST['username']) && !empty($_POST['email']) && !empty($_POST['username'])) {
    $new_email = $_POST['email'];
    $new_name = $_POST['username'];


    $update= new Set($pdo);
    if ($update ->updateUser($new_username,$new_email)){

        $message="Votre profil a été mis à jour avec succès.";
    }
    else{
        $message ="Erreur lors de la mise à jour de votre profil";
    }
}
else{
    $message = "Veuillez remplir tous les champs.";
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title>Mise à jour du profil</title>
</head>
<body>
    
        <h2>Mettre à jour votre profil</h2>
        <form action="" method="post">
            <label for="name">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required>

            <label for="email"> Email</label>
            <input type="email" id="email" name="email" required>

            <input type="submit" value="Mettre à jour">
        </form>
</body>
</html>
