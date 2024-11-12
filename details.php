<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $conn = new PDO('mysql:host=localhost;dbname=optia_db', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = (int)$_GET['id'];
    $type = $_GET['type'];

    if ($type === 'modele') {
        $sql = "SELECT * FROM modeleia WHERE IdModeleIA = :id";
    } elseif ($type === 'tache') {
        $sql = "SELECT * FROM tache WHERE id_tache = :id"; // Remplacez par le bon nom de table et colonne
    } elseif ($type === 'ressources') {
        $sql = "SELECT * FROM `ressource utilisée` WHERE idRessource = :id";
 // Remplacez par le bon nom de table et colonne
    } else {
        die("Type de recherche invalide.");
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $detail = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($detail) {
            echo "<h2>Détails</h2>";
            echo "<p><strong>Nom :</strong> " . htmlspecialchars($detail['Nom']) . "</p>";
            
            if ($type === 'modele') {
                echo "<p><strong>Architecture :</strong> " . htmlspecialchars($detail['Architecture']) . "</p>";
            } elseif ($type === 'tache') {
                echo "<p><strong>Description :</strong> " . htmlspecialchars($detail['details_tache']) . "</p>";
            } elseif ($type === 'ressources') {
                echo "<p><strong>CPU :</strong> " . htmlspecialchars($detail['CPU']) . "</p>";
            }
        } else {
            echo "<p>Aucun détail trouvé pour cet élément.</p>";
        }
    } catch (PDOException $e) {
        die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
    }
} else {
    echo "<p>Erreur : ID ou type non fourni.</p>";
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la recherche</title>
    <link rel="stylesheet" href="style.css"> <!-- Assurez-vous que ce fichier existe et est lié -->
</head>