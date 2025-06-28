<?php
session_start();
require_once 'custom_db_connectie.php';
require_once 'functions/winkelmand_functies.php';

$pdo = maakVerbinding();

if (!isset($_SESSION['winkelmand'])) {
    $_SESSION['winkelmand'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bijwerken']) && isset($_POST['aantallen'])) {
        werkWinkelmandBij($_POST['aantallen']);
    }

    if (isset($_POST['verwijder_product'])) {
        verwijderUitWinkelmand($_POST['verwijder_product']);
    }

    if (isset($_POST['afronden_bestelling'])) {
        $naam = $_POST['klant_naam'] ?? '';
        $adres = $_POST['klant_adres'] ?? '';

        if (empty($naam) || empty($adres)) {
            $melding = "<p style='color:red;'>Vul zowel je naam als adres in om te bestellen.</p>";
        } else {
            $gebruikersnaam = $_SESSION['user']['username'] ?? null;
            verwerkBestelling($pdo, $gebruikersnaam, $naam, $adres);
            $melding = "<p style='color:green;'>Bedankt voor je bestelling!</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Winkelmandje | Pizzeria Sole Machina</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<main id="winkelmandje">
    <h2>Winkelmandje</h2>

    <?php if (isset($melding)) echo $melding; ?>

    <?php if (!empty($_SESSION['winkelmand'])): ?>
        <form method="post" action="winkelwagen.php">
            <div class="mandje-content">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Prijs</th>
                            <th>Aantal</th>
                            <th>Subtotaal</th>
                            <th>Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totaal = 0;
                        foreach ($_SESSION['winkelmand'] as $index => $product):
                            $subtotaal = $product['prijs'] * $product['aantal'];
                            $totaal += $subtotaal;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($product['product_naam']) ?></td>
                                <td>€<?= number_format($product['prijs'], 2, ',', '.') ?></td>
                                <td>
                                    <input type="number"
                                           name="aantallen[<?= $index ?>]"
                                           value="<?= $product['aantal'] ?>"
                                           min="0"
                                           style="width: 60px;">
                                </td>
                                <td>€<?= number_format($subtotaal, 2, ',', '.') ?></td>
                                <td>
                                    <button class="knop" type="submit" name="verwijder_product" value="<?= $index ?>">Verwijder</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="total-betaling">
                    <p>Totaalbedrag: €<?= number_format($totaal, 2, ',', '.') ?></p>
                    <button class="knop" type="submit" name="bijwerken">Winkelmand bijwerken</button>
                </div>
            </div>
        </form>
    <?php else: ?>
        <p class="mandje-content">Je hebt nog geen producten toegevoegd.</p>
    <?php endif; ?>

    <div class="bestelgegevens">
        <h2>Bezorggegevens</h2>
        <form method="post" action="">
            <div class="form-groep">
                <label for="klant_naam">Naam</label>
                <input type="text" id="klant_naam" name="klant_naam" placeholder="Bijv. Jan Jansen">
            </div>
            <div class="form-groep">
                <label for="klant_adres">Adres</label>
                <input type="text" id="klant_adres" name="klant_adres" placeholder="Straat 1, 1234 AB Plaats">
            </div>
            <button class="betaling-knop" type="submit" name="afronden_bestelling">Bestelling plaatsen</button>
        </form>
    </div>
</main>

<footer>
    <p>&copy; 2024 Pizzeria Sole Machina</p>
    <a class="footer-link" href="privacyverklaring.php">Privacyverklaring</a>
</footer>
</body>
</html>
