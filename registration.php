<?php

class Registration
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function registerUser(string $username, string $password, string $email): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO utilisateurs (nom_d_utilisateur, mot_de_passe, email) VALUES (:nom_d_utilisateur, :mot_de_passe, :email)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'nom_d_utilisateur' => $username,
            'mot_de_passe' => $hashedPassword,
            'email' => $email
        ]);
    }
}
?>