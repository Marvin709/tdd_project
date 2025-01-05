<?php


use PHPUnit\Framework\TestCase;

class testsec4 extends TestCase
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
            nom_d_utilisateur TEXT,
            mot_de_passe TEXT,
            email TEXT
        )";
        $this->pdo->exec($sql);
    }

    public function testComplexiteMotDePasse()
    {
        // Define test data with a weak password
        $nomUtilisateur = "testuser";
        $motDePasse = "simple"; // Weak password
        $email = "test@example.com";

        // Attempt to register the user with the weak password
        $resultat = $this->registration->registerUser($nomUtilisateur, $motDePasse, $email);
        
        // Assert that registration fails due to weak password
        $this->assertFalse($resultat);
    }
}
?>
