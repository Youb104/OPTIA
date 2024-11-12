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

// Récupération des valeurs de recherche
$valeur = isset($_GET['valeur']) ? $_GET['valeur'] : '';
$typeRecherche = isset($_GET['type_recherche']) ? $_GET['type_recherche'] : '';

// Préparation de la requête SQL en fonction du type de recherche
if ($typeRecherche === 'modele') {
    $sql = "SELECT * FROM modeleia WHERE Nom LIKE :valeur";
} elseif ($typeRecherche === 'tache') {
    $sql = "SELECT * FROM tache WHERE Nom LIKE :valeur"; // Remplacez "taches" et "Nom" par les bons noms de table et de colonnes
} elseif ($typeRecherche === 'ressources') {
    $sql = "SELECT * FROM `ressource utilisée` WHERE Nom LIKE :valeur"; // Remplacez "ressources" et "Nom" par les bons noms de table et de colonnes
} else {
    die("Type de recherche invalide.");
}

$stmt = $conn->prepare($sql);
$stmt->bindValue(':valeur', '%' . $valeur . '%', PDO::PARAM_STR);

try {
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
}

// Affichage des résultats
if (count($results) > 0) {
    echo "<h2>Résultats de la recherche :</h2>";
    echo "<ul>";
    foreach ($results as $result) {
        $id = htmlspecialchars($result['IdModeleIA'] ?? $result['id_tache'] ?? $result['idRessource']); // Remplacez par les bons noms de colonnes
        $nom = htmlspecialchars($result['Nom']);
        $details = htmlspecialchars($result['Architecture'] ?? $result['details_tache'] ?? $result['CPU']); // Adaptez selon les champs

        echo "<li><a href='details.php?id=$id&type=$typeRecherche'>";
        echo "<strong>Nom :</strong> $nom - <strong>Détails :</strong> $details";
        echo "</a></li>";
    }
    echo "</ul>";
} else {
    echo "<p>Aucun résultat trouvé pour la recherche.</p>";
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la recherche</title>
    <link rel="stylesheet" href="style.css"> <!-- Assurez-vous que ce fichier existe et est lié -->
</head>
