<?php
session_start();

// Vereiste bestanden inladen
require_once 'custom_db_connectie.php';
require_once 'functions/function_inlog.php';

// Maak databaseverbinding
$pdo = maakVerbinding();

// Stel blokkadetijd in (in seconden)
$blokkadeSeconden = 10;

// Altijd loginpogingen tellen
registreerLoginPoging();

// Verwerk formulier na POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (geblokkeerdGebruiker($blokkadeSeconden)) {
        $_SESSION['error'] = "Te veel mislukte pogingen. Probeer het over enkele seconden opnieuw.";
        header("Location: inloggen.php");
        exit;
    }

    // Formuliergegevens ophalen
    $gebruikersnaam = $_POST['username'] ?? '';
    $wachtwoord = $_POST['password'] ?? '';

    // Controleer gebruiker in database
    $gevondenGebruiker = controleerInlog($pdo, $gebruikersnaam, $wachtwoord);

    if ($gevondenGebruiker) {
        // Reset pogingen na succesvolle login
        resetLoginPogingen();

        // Zet gebruiker in sessie
        $_SESSION['user'] = [
            'username' => $gevondenGebruiker['username'],
            'role' => $gevondenGebruiker['role']
        ];

        // Redirect op basis van rol
        if ($gevondenGebruiker['role'] === 'Personnel') {
            header("Location: bestellingsoverzicht.php");
        } else {
            header("Location: profiel.php");
        }
        exit;
    } else {
        // Mislukte inlog: verhoog pogingen en toon melding
        registreerLoginPoging();
        $pogingenOver = 10 - ($_SESSION['login_attempts'] ?? 0);

        if ($pogingenOver <= 0) {
            $_SESSION['error'] = "Je bent tijdelijk geblokkeerd wegens te veel pogingen.";
        } else {
            $_SESSION['error'] = "Foutieve inloggegevens. Je hebt nog {$pogingenOver} poging(en).";
        }

        header("Location: inloggen.php");
        exit;
    }
}
?>
