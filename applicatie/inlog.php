<?php
session_start();
require 'custom_db_connectie.php';

$pdo = maakVerbinding();
$melding = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = $_POST['username'];
    $wachtwoord = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM [dbo].[User] WHERE username = ?");
    $stmt->execute([$gebruikersnaam]);
    $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gebruiker && password_verify($wachtwoord, $gebruiker['password'])) {
        // Opslaan van de volledige gebruiker in session (zoals profiel.php verwacht)
        $_SESSION['user'] = $gebruiker;

        // Redirect op basis van rol
        if (strtolower($gebruiker['role']) === 'client') {
            header('Location: index.php');
        } else {
            header('Location: detailoverzicht.php');
        }
        exit;
    } else {
        $melding = "Ongeldige inloggegevens.";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen - Pizzeria Sole Machina</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<main>
    <section id="login">
        <h2>Inloggen</h2>

        <?php if (!empty($melding)): ?>
            <p class="foutmelding"><?= htmlspecialchars($melding) ?></p>
        <?php endif; ?>

        <form method="POST" action="inlog.php">
            <div class="form-groep">
                <label for="username">Gebruikersnaam</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-groep">
                <label for="password">Wachtwoord</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="account-knop">Inloggen</button>

            <p><a href="registreren.php">Nog geen account? Registreer hier</a></p>
        </form>
    </section>
</main>

<footer>
    <a class="footer-link" href="privacyverklaring.php">&copy; 2024 Pizzeria Sole Machina</a>
</footer>

</body>
</html>
