<?php

class Registration
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function registerUser(string $username, string $password): bool
    {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO utilisateurs (nom_d_utilisateur, mot_de_passe) VALUES (:nom_d_utilisateur, :mot_de_passe)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'nom_d_utilisateur' => $username,
            'mot_de_passe' => $hashedPassword
        ]);
    }
}
?>