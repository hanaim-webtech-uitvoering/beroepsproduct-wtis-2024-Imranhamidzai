<?php
require 'custom_db_connectie.php';
$pdo = maakVerbinding();

$melding = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = trim($_POST['username']);
    $wachtwoord = $_POST['password'];
    $voornaam = trim($_POST['first_name']);
    $achternaam = trim($_POST['last_name']);

    // Controleer of gebruikersnaam al bestaat
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM [dbo].[User] WHERE username = ?");
    $stmt->execute([$gebruikersnaam]);
    $aantal = $stmt->fetchColumn();

    if ($aantal > 0) {
        $melding = "Gebruikersnaam bestaat al. Kies een andere.";
    } else {
        $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO [dbo].[User] (username, password, first_name, last_name, address, role)
            VALUES (?, ?, ?, ?, NULL, 'Client')
        ");

        $stmt->execute([$gebruikersnaam, $hash, $voornaam, $achternaam]);

        header("Location: inlog.php?geregistreerd=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Registreren</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<main id="registratie">
    <div class="registratie-container">
        <h2>Account aanmaken</h2>

        <?php if ($melding): ?>
            <p style="color: red; text-align:center;"><?= $melding ?></p>
        <?php endif; ?>

        <form method="POST" action="registreren.php">
            <div class="form-group">
                <label for="username">Gebruikersnaam:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Wachtwoord:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="first_name">Voornaam:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Achternaam:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>

            <button type="submit">Registreren</button>
        </form>

        <p style="text-align: center; margin-top: 15px;">
            Al een account? <a href="inlog.php">Log in</a>
        </p>
    </div>
</main>

<footer>
    <a href="privacyverklaring.php">&copy; 2024 Pizzeria Sole Machina</a>
</footer>

</body>
</html>
