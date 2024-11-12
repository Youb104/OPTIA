<?php
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    echo json_encode(["error" => "Non authentifié"]);
    exit();
}

try {
    $conn = new PDO('mysql:host=localhost;dbname=optia_db', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les informations de l'utilisateur
    $utilisateur_id = $_SESSION['utilisateur_id'];
    $stmt = $conn->prepare("SELECT nom, email FROM utilisateurs WHERE id = :id");
    $stmt->bindValue(':id', $utilisateur_id, PDO::PARAM_INT);
    $stmt->execute();
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupérer l'historique de recherche
    $stmt = $conn->prepare("SELECT critere, valeur, date_recherche FROM historique WHERE utilisateur_id = :id ORDER BY date_recherche DESC");
    $stmt->bindValue(':id', $utilisateur_id, PDO::PARAM_INT);
    $stmt->execute();
    $historique = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Organiser les résultats en tableau
    $historique_html = "";
    foreach ($historique as $item) {
        $historique_html .= "<p><strong>" . htmlspecialchars($item['critere']) . " :</strong> " . htmlspecialchars($item['valeur']) . " (le " . htmlspecialchars($item['date_recherche']) . ")</p>";
    }

    echo json_encode([
        "nom" => $utilisateur['nom'],
        "historique" => $historique_html
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de la base de données: " . $e->getMessage()]);
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la recherche</title>
    <link rel="stylesheet" href="style.css"> <!-- Assurez-vous que ce fichier existe et est lié -->
</head>