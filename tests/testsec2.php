<?php


use PHPUnit\Framework\TestCase;

class testsec2 extends TestCase
{
    private $pdo;
    private $authentification;

    protected function setUp(): void
    {
        
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->createTable();
        $this->authentification = new Authentification($this->pdo);
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

    public function testConnexion()
    {
        
        $nomUtilisateur = "testuser";
        $motDePasse = "Test@1234";
        $email = "test@example.com";
        
      
        $this->authentification->registerUser($nomUtilisateur, $motDePasse, $email);

       
        $resultat = $this->authentification->loginUser($nomUtilisateur, $motDePasse);
        $this->assertTrue($resultat);

      
        $userId = $this->authentification->getUserId($nomUtilisateur);
        $this->assertIsInt($userId);
    }
}
?>
