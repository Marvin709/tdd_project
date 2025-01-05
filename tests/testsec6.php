<?php
require 'Registration.php';

use PHPUnit\Framework\TestCase;

class testsec6 extends TestCase
{
    private $pdo;
    private $registration;

    protected function setUp(): void
    {
        // Set up in-memory SQLite database for testing
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->createTable();
        $this->registration = new Registration($this->pdo);
    }

    private function createTable()
    {
        // Create table with proper column length limits
        $sql = "
        CREATE TABLE utilisateurs (
            id INTEGER PRIMARY KEY,
            nom_d_utilisateur TEXT,
            mot_de_passe TEXT,
            email TEXT
        )";
        $this->pdo->exec($sql);
    }

    public function testLimiteLongueurChamps()
    {
        // Create a username that exceeds the 255 character limit
        $nomUtilisateur = str_repeat('a', 256);
        $motDePasse = "Test@1234";
        $email = "test@example.com";

        // Attempt to register the user with an excessively long username
        $resultat = $this->registration->registerUser($nomUtilisateur, $motDePasse, $email);
        
        // Assert that registration fails due to exceeding the length limit for the username
        $this->assertFalse($resultat);
    }
}
?>
