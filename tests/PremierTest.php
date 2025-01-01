<?php declare(strict_types=1);
require "./register.php";
use PHPUnit\Framework\TestCase ;

    class PremierTest extends TestCase{

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
            $result =$registration->registerUser("Marvin","ishola80") ==="Inscription reussie !";
            $this->assertFalse($result);
        }
    }


?>