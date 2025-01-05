<?php
use PHPUnit\Framework\TestCase;

class testsec7 extends TestCase
{
    protected function setUp(): void
    {
        // Start the session before each test
        session_start();
    }

    public function testGestionSession()
    {
        // Set a session variable
        $_SESSION['user_id'] = 1;
        $this->assertSame(1, $_SESSION['user_id']);

        // Unset the session variable and destroy the session
        unset($_SESSION['user_id']);
        $this->assertArrayNotHasKey('user_id', $_SESSION);

        // Destroy the session completely
        session_destroy();
    }
}
?>
