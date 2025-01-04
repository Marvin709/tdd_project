<?php


use PHPUnit\Framework\TestCase;

class testsec5 extends TestCase
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
        // Create table for users
        $sql = "
        CREATE TABLE utilisateurs (
            id INTEGER PRIMARY KEY,
            nom_d_utilisateur TEXT UNIQUE,
            mot_de_passe TEXT,
            email TEXT UNIQUE
        )";
        $this->pdo->exec($sql);
    }

    public function testUtilisateurDuplique()
    {
        // Define test user data
        $nomUtilisateur = "testuser";
        $motDePasse = "Test@1234";
        $email = "test@example.com";

        // Register the first user
        $this->registration->registerUser($nomUtilisateur, $motDePasse, $email);

        // Attempt to register the second user with the same username and email
        $resultat = $this->registration->registerUser($nomUtilisateur, $motDePasse, $email);
        
        // Assert that the registration fails due to duplicate username or email
        $this->assertFalse($resultat);
    }
}
?>
