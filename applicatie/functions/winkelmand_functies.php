<?php
/**
 * Werk de hoeveelheden in de winkelmand bij op basis van het doorgegeven formulier.
 *
 * @param array $nieuweHoeveelheden Associatieve array met index => aantal
 */
function werkWinkelmandBij($nieuweHoeveelheden) {
    foreach ($nieuweHoeveelheden as $index => $aantal) {
        if (isset($_SESSION['winkelmand'][$index])) {
            // Als de nieuwe hoeveelheid 0 of minder is, verwijder het item
            if ((int)$aantal <= 0) {
                unset($_SESSION['winkelmand'][$index]);
            } else {
                // Anders werk de hoeveelheid bij
                $_SESSION['winkelmand'][$index]['aantal'] = (int)$aantal;
            }
        }
    }
}

/**
 * Verwijder één specifiek product uit de winkelmand op basis van index.
 *
 * @param int|string $index Index van het te verwijderen product
 */
function verwijderUitWinkelmand($index) {
    if (isset($_SESSION['winkelmand'][$index])) {
        unset($_SESSION['winkelmand'][$index]);
    }
}

/**
 * Verwerk een bestelling door deze in de database op te slaan.
 *
 * @param PDO $pdo Databaseverbinding
 * @param string|null $gebruiker De gebruikersnaam indien ingelogd (anders null)
 * @param string $naam Naam van de klant
 * @param string $adres Adres van de klant
 */
function verwerkBestelling($pdo, $gebruiker, $naam, $adres) {
    if (empty($_SESSION['winkelmand'])) {
        return; // Geen producten om te verwerken
    }

    try {
        // Start transactie
        $pdo->beginTransaction();

        // Voeg bestelling toe aan bestellingen-tabel
        $sqlBestelling = "INSERT INTO bestellingen (gebruikersnaam, klant_naam, klant_adres, besteld_op)
                          VALUES (:gebruikersnaam, :klant_naam, :klant_adres, NOW())";
        $stmt = $pdo->prepare($sqlBestelling);
        $stmt->execute([
            ':gebruikersnaam' => $gebruiker,
            ':klant_naam' => $naam,
            ':klant_adres' => $adres
        ]);

        // Haal laatst ingevoegd bestelling_id op
        $bestellingId = $pdo->lastInsertId();

        // Voeg elk product toe aan bestelling_producten-tabel
        $sqlProduct = "INSERT INTO bestelling_producten (bestelling_id, product_naam, prijs_per_stuk, aantal)
                       VALUES (:bestelling_id, :product_naam, :prijs_per_stuk, :aantal)";
        $stmtProduct = $pdo->prepare($sqlProduct);

        foreach ($_SESSION['winkelmand'] as $product) {
            $stmtProduct->execute([
                ':bestelling_id'   => $bestellingId,
                ':product_naam'    => $product['product_naam'],
                ':prijs_per_stuk'  => $product['prijs'],
                ':aantal'          => $product['aantal']
            ]);
        }

        // Commit transactie
        $pdo->commit();

        // Leeg winkelmand na succesvolle bestelling
        $_SESSION['winkelmand'] = [];

    } catch (PDOException $e) {
        // Fout tijdens verwerken bestelling: rollback uitvoeren
        $pdo->rollBack();
        echo "<p>Er is een fout opgetreden bij het opslaan van je bestelling. Probeer het opnieuw.</p>";
    }
}
