<?php declare(strict_types=1);
require "./register.php";
require "./authentification.php";
require "./logout.php";
require "./update.php";
use PHPUnit\Framework\TestCase ;


    class PremierTest extends TestCase{
        protected function void(): void
        {
            session_start();
            $_SESSION['username'] = 'testuser';
            $_SESSION['user_id'] = 1;
    
            $this->utilisateurs = [
                1 => ['email' => 'oldemail@example.com', 'nom_d_utilisateur' => 'Old Username']
            ];
        }
    

        public function test_la_creation_reussie_d_un_compte(): void 
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
                  
            $registration = new Registration($pdomock);
            $result =$registration->registerUser("Marvin","ishola80","pierrick@gmail.com") ==="Inscription reussie !";
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
     public function test_de_deconnexion():void{
    function setUp(): void
    {
        
        //session_start();
        $_SESSION['username'] = 'testuser';
    }

     function testUserIsLoggedIn()
    {
        $this->assertTrue(isset($_SESSION['username']));
        $this->assertEquals('testuser', $_SESSION['username']);
    }
     function tearDown(): void
    {
        session_unset();
        session_destroy();
    }
     function testLogout()
    {
        
        $this->assertTrue(isset($_SESSION['username']));
    }
}
    public function test_de_modification_des_informations():void{
        function testProfileUpdate()
    {
        $_POST['email'] = 'newemail@example.com';
        $_POST['username'] = 'New username';
        ob_start();
        include('update.php');
        ob_end_clean();

        $this->assertEquals('newemail@example.com', $this->utilisateurs[1]['email']);
        $this->assertEquals('New username', $this->utilisateurs[1]['nom_d_utilisateur']);
    }

    

     function getUserById($id)
    {
        return $this->utilisateurs[$id];
    }

}
    }
    
      
?>

                
    


