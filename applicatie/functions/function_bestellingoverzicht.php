<?php
function haalBestellingenOp($pdo) {
    $sql = "SELECT order_id, client_username, client_name, personnel_username, datetime, status, address
            FROM Pizza_Order
            ORDER BY datetime DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
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

function updateOrderStatus($pdo, $orderId, $newStatus) {
    $updateSql = "UPDATE Pizza_Order SET status = :newStatus WHERE order_id = :oid";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([
        ':newStatus' => $newStatus,
        ':oid' => $orderId
    ]);
}
?>