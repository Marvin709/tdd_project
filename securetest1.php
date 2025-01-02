<?php

use PHPUnit\Framework\TestCase;

class mestests extends TestCase {

    /** @test */
    public function test_algorithme_de_hachage_securise() {
        $password = 'MonMotDePasse123';
        $hashedPassword =password_hash($password, PASSWORD_DEFAULT);

        // Vérifie que le mot de passe haché n'est pas en clair
        $this->assertNotEquals($password, $password, "Le mot de passe ne doit pas être stocké en clair.");

       
    }

    /** @test */
    public function test_salage_des_mots_de_passe() {
        $password = 'MotDePasseIdentique';
        $hashedPassword1 = password_hash($password, PASSWORD_DEFAULT);
        $hashedPassword2 = password_hash($password, PASSWORD_DEFAULT);

        // Les hachages doivent être différents grâce au salage
        $this->assertNotEquals($hashedPassword1, $hashedPassword2, "Chaque hachage doit être unique même pour un même mot de passe.");
    }

    /** @test */
    public function test_pas_de_stockage_en_clair() {
        $password = 'MonMotDePasse123';
        
        // Simule le hachage et l'insertion dans la base de données
        $hashedPassword = password_hash($password,PASSWORD_DEFAULT);
        $storedPassword = $hashedPassword; // Ce qui serait stocké en base

        // Vérifie que le mot de passe stocké n'est pas en clair
        $this->assertNotEquals($password, $storedPassword, "Le mot de passe ne doit pas être stocké en clair.");
    }

   
}

?>
