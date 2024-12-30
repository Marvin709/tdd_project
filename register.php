<?php

include("config.php");

$message = '';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO utilisateurs ( nom_d_utilisateur, mot_de_passe) VALUES ( :nom_d_utilisateur, :mot_de_passe)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'nom_d_utilisateur' => $username,
        'mot_de_passe' => $password
    ]);
    

    if ($result) {
        $message = 'Inscription réussie!';
        header('Location: login.php');
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
