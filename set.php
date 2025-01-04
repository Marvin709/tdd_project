<?php

    class Set
    {
        private $pdo;

        public function __construct(PDO $pdo)
        {
            $this->pdo = $pdo;
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
          
        

        public function updateUser(int $user_id,string $new_username,string $new_email):bool{
            $sql = "UPDATE utilisateurs SET email = :email, nom = :username WHERE id = :id";
            $stmt=$this->pdo->prepare($sql);

            return $stmt->execute([
                ':email' => $new_email,
                ':username' => $new_username,
                ':id' => $user_id,
        ]);
        }
    }
?>