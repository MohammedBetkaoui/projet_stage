<?php
$host = 'localhost'; 
$dbname = 'projet_stages'; 
$username = 'root'; 
$password = ''; 

$conn = new mysqli($host, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
?>
