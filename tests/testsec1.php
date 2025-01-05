<?php
require 'Registration.php';

use PHPUnit\Framework\TestCase;

class testsec1 extends TestCase
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

    public function testInscription()
    {
        
        $nomUtilisateur = "testuser";
        $motDePasse = "Test@1234";
        $email = "test@example.com";
        
    
        $resultat = $this->registration->registerUser($nomUtilisateur, $motDePasse, $email);
        $this->assertTrue($resultat);

       
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE nom_d_utilisateur = :nom_d_utilisateur");
        $stmt->execute([':nom_d_utilisateur' => $nomUtilisateur]);
        $utilisateur = $stmt->fetch();
        
        
        $this->assertNotFalse($utilisateur);
        $this->assertSame($nomUtilisateur, $utilisateur['nom_d_utilisateur']);
        $this->assertSame($email, $utilisateur['email']);
        
    
        $this->assertTrue(password_verify($motDePasse, $utilisateur['mot_de_passe']));
    }
}
?>
