<?php
session_start();
require_once 'custom_db_connectie.php';
require_once 'functions/functions_menu.php';

$pdo = maakVerbinding();

// Toevoegen aan winkelwagen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $naam = trim($_POST['product_name']);
    $prijs = $_POST['price'];
    $aantal = (int) $_POST['quantity'];
    voegProductToeAanWinkelwagen($naam, $prijs, $aantal);
}

// Zoekterm ophalen
$zoekwoord = $_GET['search'] ?? '';

// Producten ophalen en filteren
$producten = filterProducten(haalProductenOp($pdo), $zoekwoord);

// Indelen op categorie
$categorieën = [];
foreach ($producten as $item) {
    $categorieën[$item['category']][] = $item;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Menukaart | Sole Machina</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<main>
    <h2 class="pagina-titel">Menukaart</h2>

    <!-- Zoekformulier -->
    <form method="GET" action="menu.php">
        <input type="text" name="search" placeholder="Zoek een product..." value="<?= htmlspecialchars($zoekwoord) ?>">
        <button type="submit">Zoek</button>
    </form>

    <!-- Menu per categorie -->
    <div id="menu">
        <?php foreach ($categorieën as $categorie => $lijst): ?>
            <section class="menu-categorie">
                <h3><?= htmlspecialchars($categorie) ?></h3>
                <div class="menu-items">
                    <?php foreach ($lijst as $product): ?>
                        <div class="menu-item">
                            <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                            <p class="prijs">€<?= number_format($product['price'], 2, ',', '.') ?></p>

                            <?php if (!empty($product['ingredient_list'])): ?>
                                <p><?= htmlspecialchars($product['ingredient_list']) ?></p>
                            <?php endif; ?>

                            <form method="POST" action="menu.php">
                                <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>">
                                <input type="hidden" name="price" value="<?= $product['price'] ?>">

                                <label for="hoeveelheid_<?= $product['product_name'] ?>">Aantal:</label>
                                <input type="number" name="quantity" id="hoeveelheid_<?= $product['product_name'] ?>" min="1" value="1">

                                <button class="bestel-knop" type="submit" name="add_to_cart">Bestel</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>
</main>

<footer>
    <p>&copy; 2024 Pizzeria Sole Machina. Alle rechten voorbehouden.</p>
    <a class="footer-link" href="privacyverklaring.php">Privacyverklaring</a>
</footer>

</body>
</html>
