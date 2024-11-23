<?php
header('Content-Type: application/json');

if (isset($_GET['model'])) {
    $modelName = trim($_GET['model']);

    try {
        $conn = new PDO('mysql:host=143.47.179.70;port=443;dbname=db1', 'user1', 'user1');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête pour récupérer la tâche associée au modèle
        $stmt = $conn->prepare('SELECT t.Nom AS task FROM modeleia m LEFT JOIN tache t ON m.id_tache = t.id_tache WHERE m.Nom LIKE :model LIMIT 1');
        $stmt->execute([':model' => '%' . $modelName . '%']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retourner la tâche associée au modèle ou null si non trouvé
        echo json_encode(['task' => $result ? $result['task'] : null]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur de connexion : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Modèle non spécifié']);
}
?>
