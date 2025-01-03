<?php

class Logout
{
    public function logoutUser()
    {
        session_start();

        $_SESSION = array();

       session_unset();

        session_destroy();

        header('Location: login.php');
        exit;
    }
}
?>
