<?php

include("config.php");

$message = '';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users ( username, password) VALUES ( :username, :password)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'username' => $username,
        'password' => $password
    ]);
    

    if ($result) {
        $message = 'Inscription réussie!';
        
    } else {
        $message = 'Erreur lors de l\'inscription.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title>Inscription</title>

        
</head>
<body>
    <h2>Inscription</h2>

    <?php if (!empty($message)): ?>
        <p style="color:red"><?= $message ?></p>
    <?php endif; ?>

    <form action="register.php" method="post">
        
        
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" id="username" name="username">
        

        
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password">
        

        
            <input type="submit" value="S'inscrire">
        
    </form>

</body>
</html>
