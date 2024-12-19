<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

if (isset($_POST['ajouter_livre'])) {
    $titre = trim($_POST['titre'] ?? '');
    $auteur = trim($_POST['auteur'] ?? '');

    if (empty($titre) || empty($auteur)) {
        $message = "Veuillez remplir tous les champs pour ajouter un livre.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO livres (titre, auteur, utilisateur_id) VALUES (?, ?, ?)");
        $stmt->execute([$titre, $auteur, $user_id]);
        $message = "Livre ajouté avec succès !";
    }
}

if (isset($_POST['add_fav']) && isset($_POST['livre_id'])) {
    $livre_id = (int)$_POST['livre_id'];
    $stmt = $pdo->prepare("SELECT id FROM favoris WHERE utilisateur_id = ? AND livre_id = ?");
    $stmt->execute([$user_id, $livre_id]);
    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO favoris (utilisateur_id, livre_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $livre_id]);
    }
}

$stmt = $pdo->query("SELECT * FROM livres");
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT livres.titre, livres.auteur FROM favoris JOIN livres ON favoris.livre_id = livres.id WHERE favoris.utilisateur_id = ?");
$stmt->execute([$user_id]);
$favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
</head>
<body>
<h1>Bonjour <?php echo htmlspecialchars($_SESSION['user_nom']); ?> !</h1>
<nav>
    <a href="lougout.php">Se déconnecter</a>
</nav>

<?php if (!empty($message)): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<h2>Ajouter un livre</h2>
<form method="post">
    <div>
        <label>Titre :</label>
        <input type="text" name="titre" value="">
    </div>
    <div>
        <label>Auteur :</label>
        <input type="text" name="auteur" value="">
    </div>
    <button type="submit" name="ajouter_livre">Ajouter</button>
</form>

<h2>Liste des livres</h2>
<ul>
    <?php foreach($livres as $livre): ?>
        <li>
            <?php echo htmlspecialchars($livre['titre']) . " - " . htmlspecialchars($livre['auteur']); ?>
            <form method="post" style="display:inline;">
                <input type="hidden" name="livre_id" value="<?php echo $livre['id']; ?>">
                <button type="submit" name="add_fav">Ajouter aux favoris</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>

<h2>Mes favoris</h2>
<ul>
    <?php foreach($favoris as $fav): ?>
        <li><?php echo htmlspecialchars($fav['titre']) . " - " . htmlspecialchars($fav['auteur']); ?></li>
    <?php endforeach; ?>
</ul>
</body>
</html>
