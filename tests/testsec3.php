<?php


use PHPUnit\Framework\TestCase;

class testsec3 extends TestCase
{
    private $pdo;
    private $registration;

    protected function setUp(): void
    {
        
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->createTable();
        $this->registration = new Registration($this->pdo);
    }

    private function createTable()
    {
        
        $sql = "
        CREATE TABLE utilisateurs (
            id INTEGER PRIMARY KEY,
            nom_d_utilisateur TEXT,
            mot_de_passe TEXT,
            email TEXT
        )";
        $this->pdo->exec($sql);
    }

    public function testFormatEmailInvalide()
    {
        
        $nomUtilisateur = "testuser";
        $motDePasse = "Test@1234";
        $email = "emailinvalid"; 

       
        $resultat = $this->registration->registerUser($nomUtilisateur, $motDePasse, $email);
        
       
        $this->assertFalse($resultat);
    }
}
?>
