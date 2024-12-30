<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "tdd_project";
$port = '3306';
$dsn = "mysql:host=$servername;port=$port;dbname=$db;";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try
{
    $pdo = new PDO($dsn,$username,$password,$options);
}
catch(Exception $e)
{
    die('Erreur:' . $e->getMessage());
}


$conn = null;