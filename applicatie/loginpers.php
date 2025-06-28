<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medewerkers Inloggen | Pizzeria Sole Machina</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

   <?php include 'header.php'; ?>

    <!-- Inlog Sectie -->
    <section id="login">
        <div class="login-container">
            <h2>Inloggen als Medewerker</h2>
            <form action="dashboard.html" method="POST">
                <label for="username">Gebruikersnaam:</label>
                <input type="text" id="username" name="username" placeholder="Voer je gebruikersnaam in" required>

                <label for="password">Wachtwoord:</label>
                <input type="password" id="password" name="password" placeholder="Voer je wachtwoord in" required>

                <button type="submit">Inloggen</button>
            </form>
            <p><a href="#">Wachtwoord vergeten?</a></p>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Pizzeria Sole Machina. Alle rechten voorbehouden.</p>
        <a href="privacy.html" class="btn-privacy">Privacyverklaring</a>
    </footer>

</body>
</html>
