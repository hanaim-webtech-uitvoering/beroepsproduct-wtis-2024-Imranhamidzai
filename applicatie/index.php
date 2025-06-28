<?php
session_start();
require_once 'custom_db_connectie.php';
require_once 'functions/functions_menu.php';

$pdo = maakVerbinding();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = (int) $_POST['quantity'];
    voegProductToeAanWinkelwagen($product_name, $price, $quantity);
}

$query = "SELECT TOP 3 name, price FROM product";
$stmt = $pdo->prepare($query);
$stmt->execute();
$producten = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css"> <!-- Jouw externe stylesheet -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welkom bij Sole Machina</title>
</head>
<body>

<?php include 'header.php'; ?>

<main>
    <h2 class="pagina-titel">Welkom bij Pizzeria Sole Machina</h2>

    <div class="actie-banner">
        🍕 <strong>Actie van de week:</strong> Bestel nu een Pizza voor slechts €5,99! Alleen deze week geldig.
    </div>

    <section>
        <h3 class="subtitel">Onze Aanraders</h3>
        <div class="product-grid">
            <?php foreach ($producten as $product): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p>Prijs: €<?= number_format($product['price'], 2, ',', '.') ?></p>
                    <form method="post" action="index.php">
                        <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                        <input type="hidden" name="price" value="<?= $product['price'] ?>">
                        <label for="quantity_<?= htmlspecialchars($product['name']) ?>">Aantal:</label>
                        <input type="number" id="quantity_<?= htmlspecialchars($product['name']) ?>" name="quantity" min="1" value="1">
                        <br><br>
                        <button class="bestel-knop" type="submit" name="add_to_cart">Bestel</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<footer>
    <p>Pizzeria Sole Machina &copy; 2024</p>
    <a class="footer-link" href="privacyverklaring.php">Privacyverklaring</a>
</footer>

</body>
</html>
