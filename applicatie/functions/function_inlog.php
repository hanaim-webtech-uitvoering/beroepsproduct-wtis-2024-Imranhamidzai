<?php
/**
 * Controleer of een gebruiker geblokkeerd is op basis van foutpogingen.
 *
 * @param int $seconden Tijd in seconden dat iemand geblokkeerd blijft
 * @return bool true = nog steeds geblokkeerd
 */
function geblokkeerdGebruiker($seconden) {
    if (isset($_SESSION['pogingen']) && $_SESSION['pogingen'] >= 10) {
        if (!isset($_SESSION['blokkeertijd'])) {
            $_SESSION['blokkeertijd'] = time();
        }

        $verstreken = time() - $_SESSION['blokkeertijd'];

        if ($verstreken < $seconden) {
            return true;
        } else {
            // Reset als tijd verstreken is
            unset($_SESSION['blokkeertijd']);
            $_SESSION['pogingen'] = 0;
        }
    }

    return false;
}

/**
 * Verhoog het aantal loginpogingen met 1.
 */
function registreerLoginPoging() {
    if (!isset($_SESSION['pogingen'])) {
        $_SESSION['pogingen'] = 0;
    }
    $_SESSION['pogingen']++;
}

/**
 * Zet het aantal loginpogingen terug naar 0.
 */
function resetLoginPogingen() {
    $_SESSION['pogingen'] = 0;
    unset($_SESSION['blokkeertijd']);
}

/**
 * Controleer of de inloggegevens correct zijn en geef gebruiker terug.
 *
 * @param PDO $pdo Databaseverbinding
 * @param string $username Ingevoerde gebruikersnaam
 * @param string $password Ingevoerd wachtwoord
 * @return array|false Gebruiker als het klopt, anders false
 */
function controleerInlog($pdo, $username, $password) {
    $query = "SELECT username, password, role FROM [User ] WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':username' => $username]);
    $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gebruiker && password_verify($password, $gebruiker['password'])) {
        return $gebruiker;
    }

    return false;
}
?>
