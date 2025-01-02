<?php declare(strict_types=1);
require "./authentification.php";
use PHPUnit\Framework\TestCase ;
class StandardloginTest extends TestCase{
    public function test_de_connexion_standard(): void {
                $pdomock= $this ->getMockBuilder(\PDO::class)
                                ->disableOriginalConstructor()
                                ->getMock();
                $statementMock= $this->getMockBuilder(\PDOStatement::class)
                                     ->disableOriginalConstructor()
                                     ->getMock();
                $statementMock  ->expects($this->any())
                                ->method('execute')
                                ->willReturn(true);
                $statementMock ->expects($this->any())
                               ->method('fetch')
                               ->willReturn(['id'=>1,'nom_d_utilisateur'=>'Poutine','mot_de_passe'=>password_hash('Suspect91',PASSWORD_DEFAULT)]);
                          $pdomock ->expects($this->any())
                                   ->method('prepare')
                                   ->willReturn($statementMock);
                $login = new Authentification($pdomock);
                $result= $login->loginuser("Poutine","Suspect91");
                $this->assertTrue($result, "L'utilisateur devrait être authentifié.");

    }
}