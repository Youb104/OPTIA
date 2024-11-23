<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

try {
    $conn = new PDO('mysql:host=143.47.179.70;port=443;dbname=db1', 'user1', 'user1');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => "Erreur de connexion : " . $e->getMessage()]));
}

$type = isset($_GET['type']) ? $_GET['type'] : '';
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (!$type || !$query) {
    echo json_encode([]);
    exit;
}

// Déterminer la table et le champ en fonction du type
$table = '';
$field = '';
switch ($type) {
    case 'modele':
        $table = 'modeleia';
        $field = 'Nom';
        break;
    case 'ressource':
        $table = 'ressourceutilisée';
        $field = 'Nom';
        break;
    case 'tache':
        $table = 'tache';
        $field = 'Nom';
        break;
    default:
        echo json_encode([]);
        exit;
}

// Préparer la requête pour obtenir les suggestions
$sql = "SELECT DISTINCT $field FROM $table WHERE $field LIKE :query LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);

try {
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode(['error' => "Erreur lors de l'exécution de la requête : " . $e->getMessage()]);
}
?>
