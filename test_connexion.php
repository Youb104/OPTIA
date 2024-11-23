<?php
// Paramètres de connexion
$host = '143.47.179.70';
$port = '443';
$dbname = 'db1';
$username = 'user1';
$password = 'user1';

try {
    // Tentative de connexion à la base de données
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    
    // Configuration du mode d'erreur pour afficher les exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion réussie à la base de données!";
} catch (PDOException $e) {
    // Affiche un message en cas d'erreur
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
