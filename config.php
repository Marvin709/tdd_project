<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "tdd_project";

try
{
    $conn = new PDO('mysql:host=localhost;dbname=tdd_project','root','');

}
catch(Exception $e)
{
    die('Erreur:' . $e->getMessage());
}

$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    echo "Base de données créée avec succès<br>";
} else {
    echo "Erreur de création de la base : " . $conn->error . "<br>";
}

$conn->select_db($database);

$sql = "CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_d_utilisateurs VARCHAR(100) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,)";

if ($conn->query($sql) === TRUE) {
    echo "Table utilisateurs créée avec succès<br>";
} else {
    echo "Erreur de création de la table : " . $conn->error . "<br>";
}

// Fermer la connexion
$conn->close();
?>