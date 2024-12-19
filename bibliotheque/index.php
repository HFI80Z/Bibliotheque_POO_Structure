<?php
require_once 'db.php';

$stmt = $pdo->query("SELECT * FROM livres");
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Bibliothèque</title>
</head>
<body>
    <h1>Bibliothèque en ligne</h1>
    <nav>
        <a href="register.php">S'inscrire</a> |
        <a href="login.php">Se connecter</a>
    </nav>
    <h2>Livres disponibles</h2>
    <ul>
        <?php foreach($livres as $livre): ?>
            <li><?php echo htmlspecialchars($livre['titre']) . " - " . htmlspecialchars($livre['auteur']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
