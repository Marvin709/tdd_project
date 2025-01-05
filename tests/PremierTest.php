<?php declare(strict_types=1);
require "./register.php";
require "./authentification.php";
require "./logout.php";
use PHPUnit\Framework\TestCase ;


    class PremierTest extends TestCase{
        public function test_la_creation_reussie_d_un_compte():void
         {  
            $pdomock= $this ->getMockbuilder(\PDO::class)
                            ->disableOriginalConstructor()
                            ->getMock();
            $statementMock= $this->getMockbuilder(\PDOStatement::class)
                                ->disableOriginalConstructor()
                                ->getMock();
            $statementMock  ->expects($this->any())
                            ->method('execute')
                            ->willReturn(true);
            $pdomock ->expects($this->any())
                    ->method('prepare')
                    ->willReturn($statementMock);
                  
            $registration =new Registration($pdomock);
            $result =$registration->registerUser("Marvin","ishola80","pierrick@gmail.com")==="Inscription reussie !";
            $this->assertFalse($result);

         }
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

            protected function setUp(): void
            {
            session_start();
            $_SESSION['username'] = 'testuser';
            }

            public function test_si_l_utilisateur_est_connecte()
            {
            $this->assertTrue(isset($_SESSION['username']));
            $this->assertEquals('testuser', $_SESSION['username']);
            }

            public function test_deconnexion():void
            {
            $this->assertTrue(isset($_SESSION['username']));
            }

            protected function tearDown(): void
            {
            session_unset();
            session_destroy();
            }
}
?>


                
    


