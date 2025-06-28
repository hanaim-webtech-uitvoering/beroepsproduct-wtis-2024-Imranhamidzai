<?php
session_start();
require_once 'custom_db_connectie.php';

$pdo = maakVerbinding();

// Haal bestellingen op uit de database
$sql = "SELECT order_id, client_name, status FROM Pizza_Order ORDER BY datetime DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$bestellingen = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Als er een wijziging is doorgevoerd via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['nieuwe_status'])) {
    $orderId = $_POST['order_id'];
    $nieuweStatus = $_POST['nieuwe_status'];

    $updateSql = "UPDATE Pizza_Order SET status = :status WHERE order_id = :order_id";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([
        ':status' => $nieuweStatus,
        ':order_id' => $orderId
    ]);

    // Herlaad pagina om wijzigingen te tonen
    header("Location: bestelling_personeel.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Pizzeria Sole Machina</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include 'header.php'; ?>

  

    <main>
        <h2>Actieve Bestellingen</h2>
        <table>
            <thead>
                <tr>
                    <th>Bestelling ID</th>
                    <th>Naam</th>
                    <th>Status</th>
                    <th>Wijzig</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bestellingen as $bestelling): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($bestelling['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($bestelling['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($bestelling['status']); ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="order_id" value="<?php echo $bestelling['order_id']; ?>">
                                <select name="nieuwe_status">
                                    <option value="wachtend" <?php if ($bestelling['status'] === 'wachtend') echo 'selected'; ?>>Wachtend</option>
                                    <option value="in behandeling" <?php if ($bestelling['status'] === 'in behandeling') echo 'selected'; ?>>In Behandeling</option>
                                    <option value="klaar" <?php if ($bestelling['status'] === 'klaar') echo 'selected'; ?>>Klaar</option>
                                </select>
                                <button type="submit">Opslaan</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 Pizzeria Sole Machina. Alle rechten voorbehouden.</p>
        <a href="privacyverklaring.php" class="footer-link">Privacyverklaring</a>
    </footer>
</body>
</html>
