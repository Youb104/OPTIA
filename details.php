<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $conn = new PDO('mysql:host=143.47.179.70;port=443;dbname=db1', 'user1', 'user1');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérification des paramètres
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];  // Récupérer l'ID de la ressource

    // Requête SQL pour récupérer les détails de la ressource
    $sql = "SELECT * FROM `ressourceutilisée` WHERE idRessource = :id";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $resource = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resource) {
            echo "<h2>Détails de la Ressource</h2>";
            echo "<p><strong>Nom :</strong> " . htmlspecialchars($resource['Nom']) . "</p>";
            echo "<p><strong>CPU :</strong> " . htmlspecialchars($resource['CPU']) . "</p>";
            echo "<p><strong>GPU :</strong> " . htmlspecialchars($resource['GPU']) . "</p>";
            // Affichage de la mémoire
            echo "<p><strong>Mémoire :</strong> " . htmlspecialchars($resource['Mémoire']) . "</p>";  // Afficher la mémoire
            // Ajouter d'autres détails si nécessaires
        } else {
            echo "<p>Aucune ressource trouvée avec cet ID.</p>";
        }
    } catch (PDOException $e) {
        die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
    }
} else {
    echo "<p>Erreur : ID non spécifié.</p>";
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Ressource</title>
    <link rel="stylesheet" href="style.css">
</head>
