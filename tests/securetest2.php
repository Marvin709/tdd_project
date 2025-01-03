<?php

use PHPUnit\Framework\TestCase;

class securetest2 extends TestCase {

    private $pdo;

    protected function setUp(): void {
        //  base de données  en mémoire pour les tests
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Création d'une table fictive pour les tests
        $this->pdo->exec("CREATE TABLE utilisateurs (
            username TEXT NOT NULL,
            motdpass TEXT NOT NULL,
            
        )");
    }

    /** @test */
    public function test_requetes_preparees() {
        $username = "test_us";
        $password = password_hash("password123", PASSWORD_BCRYPT);

        // requêtes préparées
        $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (username, motdpass) VALUES (:username, :motdpass)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':motdpass', $password);
        $stmt->execute();

        // Vérification que l'utilisateur a été inséré
        $result = $this->pdo->query("SELECT * FROM utilisateurs WHERE username = 'tost_user'")->fetch();
        $this->assertNotEmpty($result, "L'utilisateur doit être inséré avec une requête préparée.");
    }

    /** @test */
    public function test_echappement_des_caracteres() {
        $username = "test'; DROP TABLE utilisateurs; --";
        $password = password_hash("password123", PASSWORD_BCRYPT);

        // Tentative d'injection SQL
        $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        
        $result = $this->pdo->query("SELECT * FROM utilisateurs WHERE username = 'test\'; DROP TABLE utilisateurs; --'")->fetch();
        $this->assertNotEmpty($result, "Les caractères spéciaux doivent être correctement échappés.");
    }

    /** @test */
    public function test_validation_des_entrees() {
        $username = ""; // Nom d'utilisateur invalide
        $password = password_hash("password123", PASSWORD_BCRYPT);

        // Vérifie que les entrées invalides sont rejetées avant l'insertion
        $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        $this->expectException(PDOException::class);
        $stmt->execute();
    }
}

?>
