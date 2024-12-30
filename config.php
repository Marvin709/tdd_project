<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "tdd_project";

try
{
    $conn = new PDO('mysql:host=localhost','root','');
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    $conn->exec($sql);
}
catch(Exception $e)
{
    die('Erreur:' . $e->getMessage());
}


$conn = null;