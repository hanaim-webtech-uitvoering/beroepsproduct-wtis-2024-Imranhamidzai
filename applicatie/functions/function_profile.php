<?php
function haalBestellingenOp($pdo, $username) {
    $sql = "SELECT order_id, client_username, client_name, personnel_username, datetime, status, address
            FROM Pizza_Order
            WHERE client_username = :username
            ORDER BY datetime DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function haalProductenOp($pdo, $orderId) {
    $prodSql = "SELECT product_name, quantity
                FROM Pizza_Order_Product
                WHERE order_id = :oid";
    $prodStmt = $pdo->prepare($prodSql);
    $prodStmt->execute([':oid' => $orderId]);
    return $prodStmt->fetchAll(PDO::FETCH_ASSOC);
}
?>