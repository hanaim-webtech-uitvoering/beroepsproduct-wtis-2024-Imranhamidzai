<?php
session_start();

// Bestanden met databaseverbinding en functies laden
require_once 'custom_db_connectie.php';
require_once 'functions/function_inlog.php';

// Maak verbinding met de database
$verbinding = maakVerbinding();

// Tijd in seconden voor blokkade na te veel fouten
$blokkadeDuur = 10;

// Start of verhoog het aantal mislukte pogingen
registreerLoginPoging();

// Controleer of formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Controleer of gebruiker tijdelijk geblokkeerd is
    if (geblokkeerdGebruiker($blokkadeDuur)) {
        $_SESSION['error'] = "U bent tijdelijk geblokkeerd. Probeer het later opnieuw.";
        header("Location: inloggen.php");
        exit;
    }

    // Haal formuliergegevens op
    $inlognaam = $_POST['username'] ?? '';
    $wachtwoord = $_POST['password'] ?? '';

    // Verifieer gebruiker
    $gebruiker = controleerInlog($verbinding, $inlognaam, $wachtwoord);

    if ($gebruiker) {
        // Inlog succesvol: reset pogingen
        resetLoginPogingen();

        // Zet gebruiker in sessie
        $_SESSION['user'] = [
            'username' => $gebruiker['username'],
            'role' => $gebruiker['role']
        ];

        // Doorverwijzen op basis van rol
        $doelpagina = ($gebruiker['role'] === 'Personnel') ? 'bestellingsoverzicht.php' : 'profiel.php';
        header("Location: $doelpagina");
        exit;
    } else {
        // Verkeerde combinatie: verhoog teller
        registreerLoginPoging();
        $over = 10 - ($_SESSION['login_attempts'] ?? 0);

        $_SESSION['error'] = ($over <= 0)
            ? "U heeft het maximum aantal pogingen bereikt. Wacht even voordat u opnieuw probeert."
            : "Ongeldige combinatie. U heeft nog $over poging(en) over.";

        header("Location: inloggen.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inloggen | Sole Machina</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<main>
    <h2 class="pagina-titel">Inloggen</h2>

    <div class="reg-inl-wrapper">
        <div class="reg-inl-container">

            <?php if (!empty($_SESSION['error'])): ?>
                <p class="foutmelding"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form class="reg-inl-form" action="inloggen.php" method="POST">
                <div class="form-groep">
                    <label for="username">Gebruikersnaam</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-groep">
                    <label for="password">Wachtwoord</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <p><a href="registreren.php">Nog geen account? Registreer hier.</a></p>

                <button class="account-knop" type="submit">Inloggen</button>
            </form>
        </div>
    </div>
</main>

<footer>
    <p>&copy; 2024 Pizzeria Sole Machina</p>
    <a class="footer-link" href="privacyverklaring.php">Privacyverklaring</a>
</footer>

</body>
</html>
