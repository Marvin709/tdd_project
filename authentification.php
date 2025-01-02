<?php

    class Authentification
    {
        private $pdo;
        
        public function __construct(PDO $pdo){
            $this->pdo =$pdo;
        }
         public function loginuser(string $username, string $password): bool {
        $sql = "SELECT * FROM utilisateurs WHERE nom_d_utilisateur = :nom_d_utilisateur";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nom_d_utilisateur' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            return true;
        }

        return false;
    }

    public function getUserId(string $username): int {
        $sql = "SELECT id FROM utilisateurs WHERE nom_d_utilisateur = :nom_d_utilisateur";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nom_d_utilisateur' => $username]);
        $user = $stmt->fetch();

        return $user['id'] ?? 0;
    }
}
?>