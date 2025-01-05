<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require './update.php';

class UpdateTest extends TestCase {
    private $pdoMock;
    private $set;

    protected function setUp(): void {
        $this->pdoMock = $this->getMockBuilder(PDO::class)
                              ->disableOriginalConstructor()
                              ->getMock();
        $this->set = new Set($this->pdoMock);
    }

    public function testUpdateUserSuccess(): void {
        $user_id = 1;
        $new_username = "newuser";
        $new_email = "newuser@example.com";

        $statementMock = $this->getMockBuilder(PDOStatement::class)
                              ->disableOriginalConstructor()
                              ->getMock();
        $statementMock->expects($this->once())
                      ->method('execute')
                      ->with([
                          ':email' => $new_email,
                          ':username' => $new_username,
                          ':id' => $user_id
                      ])
                      ->willReturn(true);

        $this->pdoMock->expects($this->once())
                      ->method('prepare')
                      ->with("UPDATE utilisateurs SET email = :email, nom = :username WHERE id = :id")
                      ->willReturn($statementMock);

        $result = $this->set->updateUser($user_id, $new_username, $new_email);

        $this->assertTrue($result);
    }

    public function testUpdateUserFailure(): void {
        $user_id = 1;
        $new_username = "newuser";
        $new_email = "newuser@example.com";

        $statementMock = $this->getMockBuilder(PDOStatement::class)
                              ->disableOriginalConstructor()
                              ->getMock();
        $statementMock->expects($this->once())
                      ->method('execute')
                      ->with([
                          ':email' => $new_email,
                          ':username' => $new_username,
                          ':id' => $user_id
                      ])
                      ->willReturn(false);

        $this->pdoMock->expects($this->once())
                      ->method('prepare')
                      ->with("UPDATE utilisateurs SET email = :email, nom = :username WHERE id = :id")
                      ->willReturn($statementMock);

        $result = $this->set->updateUser($user_id, $new_username, $new_email);

        $this->assertFalse($result);
    }
}
?>
