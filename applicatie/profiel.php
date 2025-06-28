<?php
session_start();
require_once 'custom_db_connectie.php';

$pdo = maakVerbinding();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user'])) {
    header('Location: inlog.php');
    exit;
}

$gebruikersnaam = $_SESSION['user']['username'];

// Haal volledige gebruikersgegevens op uit de database
$stmt = $pdo->prepare("SELECT username, first_name, last_name, address FROM [User] WHERE username = ?");
$stmt->execute([$gebruikersnaam]);
$gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

// Als gebruiker niet gevonden is (zou niet moeten gebeuren als sessie klopt)
if (!$gebruiker) {
    echo "<p>Gebruiker niet gevonden.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Mijn Profiel | Pizzeria Sole Machina</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<main class="profielpagina">
    <div class="profiel-container">
        <h2>Mijn Profiel</h2>
        <p><strong>Gebruikersnaam:</strong> <?= htmlspecialchars($gebruiker['username']) ?></p>
        <p><strong>Voornaam:</strong> <?= htmlspecialchars($gebruiker['first_name']) ?></p>
        <p><strong>Achternaam:</strong> <?= htmlspecialchars($gebruiker['last_name']) ?></p>
        <p><strong>Adres:</strong> <?= $gebruiker['address'] ? htmlspecialchars($gebruiker['address']) : 'Geen adres opgegeven' ?></p>
    </div>
</main>

<footer>
    <a class="footer-link" href="privacyverklaring.php">&copy; 2024 Pizzeria Sole Machina</a>
</footer>

</body>
</html>
