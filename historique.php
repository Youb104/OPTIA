<?php
// Affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
try {
    $conn = new PDO('mysql:host=localhost;dbname=optia_db', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si l'utilisateur est connecté (vous devrez gérer la session pour les utilisateurs authentifiés)
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    echo "Veuillez vous connecter pour voir votre historique de recherche.";
    exit();
}

// Récupérer l'historique de l'utilisateur
$utilisateur_id = $_SESSION['utilisateur_id'];
$sql = "SELECT * FROM historique WHERE utilisateur_id = :utilisateur_id ORDER BY date_recherche DESC";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);

try {
    $stmt->execute();
    $historique = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique de recherche</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <img src="images/logo.png" alt="Logo de Mon Site Web" class="logo">
    </header>

    <h1 class="centered-title">Historique de vos recherches</h1>

    <div class="historique-container">
        <?php if (count($historique) > 0): ?>
            <ul>
                <?php foreach ($historique as $item): ?>
                    <li>
                        <strong>Critère : </strong><?php echo htmlspecialchars($item['critere']); ?> <br>
                        <strong>Valeur recherchée : </strong><?php echo htmlspecialchars($item['valeur']); ?> <br>
                        <strong>Date de la recherche : </strong><?php echo htmlspecialchars($item['date_recherche']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune recherche effectuée pour le moment.</p>
        <?php endif; ?>
    </div>

</body>
</html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la recherche</title>
    <link rel="stylesheet" href="style.css"> <!-- Assurez-vous que ce fichier existe et est lié -->
</head>