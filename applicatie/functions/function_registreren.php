<?php
/**
 * Controleert of de opgegeven gebruikersnaam al bestaat in de database.
 *
 * @param PDO $pdo - De actieve databaseverbinding
 * @param string $username - De gebruikersnaam die gecontroleerd moet worden
 * @return array|false - Retourneert de gebruikersgegevens als deze bestaat, anders false
 */
function controleerGebruikersnaam($pdo, $username) {
    // SQL-query om te zoeken naar een bestaande gebruikersnaam
    $check_sql = "SELECT username FROM gebruikers WHERE username = :username";
    $check_statement = $pdo->prepare($check_sql);
    $check_statement->execute([':username' => $username]);

    // Haal één rij op of false als er geen bestaat
    return $check_statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * Registreert een nieuwe gebruiker in de database.
 *
 * @param PDO $pdo - De actieve databaseverbinding
 * @param string $username - De gekozen gebruikersnaam
 * @param string $password - Het gekozen wachtwoord (wordt gehasht)
 * @param string $first_name - Voornaam van de gebruiker
 * @param string $last_name - Achternaam van de gebruiker
 * @param string $address - Adres van de gebruiker
 */
function registreerGebruiker($pdo, $username, $password, $first_name, $last_name, $address) {
    // Hash het wachtwoord voor veilige opslag
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL-query om een nieuwe gebruiker toe te voegen
    $insert_sql = "INSERT INTO gebruikers (username, password, first_name, last_name, address, role)
                   VALUES (:username, :password, :first_name, :last_name, :address, :role)";
    $insert_statement = $pdo->prepare($insert_sql);

    // Voer de insert uit met de meegegeven gegevens
    $insert_statement->execute([
        ':username' => $username,
        ':password' => $hashed_password,
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':address' => $address,
        ':role' => 'Client' // standaardrol voor nieuwe gebruikers
    ]);
}
?>
