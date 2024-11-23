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

// Récupération des critères de recherche
$modele = isset($_GET['modele']) ? trim($_GET['modele']) : '';
$ressource = isset($_GET['ressource']) ? trim($_GET['ressource']) : '';
$tache = isset($_GET['tache']) ? trim($_GET['tache']) : '';

// Construction de la requête SQL en fonction des critères renseignés
$conditions = [];
$params = [];

// Ajout de conditions dynamiques
if ($modele !== '') {
    $conditions[] = "m.Nom LIKE :modele";
    $params[':modele'] = '%' . $modele . '%';
}

if ($ressource !== '') {
    $conditions[] = "r.Nom LIKE :ressource";
    $params[':ressource'] = '%' . $ressource . '%';
}

if ($tache !== '') {
    $conditions[] = "t.Nom LIKE :tache";
    $params[':tache'] = '%' . $tache . '%';
}

// Vérification qu'au moins un critère est renseigné
if (count($conditions) > 0) {
    $sql = "SELECT m.Nom AS Modele, r.Nom AS Ressource, t.Nom AS Tache, r.CPU, r.GPU, r.Mémoire
            FROM modeleia m
            LEFT JOIN tache t ON m.id_tache = t.id_tache
            LEFT JOIN classressource cr ON m.IdModeleIA = cr.idModeleIA
            LEFT JOIN ressourceutilisée r ON cr.idRessource = r.idRessource
            WHERE " . implode(' AND ', $conditions);
} else {
    die("<p>Veuillez renseigner au moins un critère de recherche.</p>");
}

// Préparation et exécution de la requête
$stmt = $conn->prepare($sql);

try {
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
}

// Affichage des résultats
if (count($results) > 0) {
    echo "<h2>Résultats de la recherche :</h2>";

    // Affichage d'un titre spécifique selon le critère de recherche
    if ($modele !== '') {
        echo "<h3>Modèle recherché : " . htmlspecialchars($modele);
        // Afficher la tâche entre parenthèses à côté du modèle
        if (!empty($results[0]['Tache'])) {
            echo " (" . htmlspecialchars($results[0]['Tache']) . ")";
        }
        echo "</h3>";
        echo "<h3>Voici les ressources capables de faire tourner le modèle :</h3>";
    } elseif ($ressource !== '') {
        echo "<h3>Ressource recherchée : " . htmlspecialchars($ressource) . "</h3>";
        echo "<h3>Voici les modèles qui peuvent être exécutés sur cette ressource :</h3>";
    } elseif ($tache !== '') {
        echo "<h3>Tâche recherchée : " . htmlspecialchars($tache) . "</h3>";
        echo "<h3>Voici les modèles et ressources associés à cette tâche :</h3>";
    }

    // Début du tableau
    echo "<table border='1'>";
    echo "<tr>";

    // Ajouter dynamiquement les colonnes selon les critères non spécifiés
    if ($modele === '') {
        echo "<th>Modèle</th>";
    }
    if ($ressource === '') {
        echo "<th>Ressource</th>";
    }
    if ($tache === '') {
        echo "<th>Tâche</th>";
    }

    // Colonnes fixes pour les caractéristiques
    echo "<th>CPU</th><th>GPU</th><th>Mémoire</th>";
    echo "</tr>";

    // Affichage des lignes
    foreach ($results as $result) {
        echo "<tr>";
        if ($modele === '') {
            echo "<td>" . htmlspecialchars($result['Modele']) . "</td>";
        }
        if ($ressource === '') {
            echo "<td>" . htmlspecialchars($result['Ressource']) . "</td>";
        }
        if ($tache === '') {
            echo "<td>" . htmlspecialchars($result['Tache']) . "</td>";
        }
        echo "<td>" . htmlspecialchars($result['CPU']) . "</td>";
        echo "<td>" . htmlspecialchars($result['GPU']) . "</td>";
        echo "<td>" . htmlspecialchars($result['Mémoire']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>Aucun résultat trouvé pour les critères spécifiés.</p>";
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Ressource</title>
    <link rel="stylesheet" href="style.css">
</head>
