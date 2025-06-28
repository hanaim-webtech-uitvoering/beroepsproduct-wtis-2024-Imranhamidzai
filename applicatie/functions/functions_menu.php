<?php
function voegProductToeAanWinkelwagen($naam, $prijs, $aantal) {
    if (!isset($_SESSION['winkelmand'])) {
        $_SESSION['winkelmand'] = [];
    }

    $naam = trim($naam); // Spaties verwijderen

    // Bestaat product al? Verhoog het aantal
    foreach ($_SESSION['winkelmand'] as &$item) {
        if ($item['product_naam'] === $naam) {
            $item['aantal'] += $aantal;
            return;
        }
    }

    // Nieuw product toevoegen
    $_SESSION['winkelmand'][] = [
        'product_naam' => $naam,
        'prijs' => $prijs,
        'aantal' => $aantal
    ];
}

function haalProductenOp($pdo) {
    $sql = "SELECT 
                P.name AS product_name,
                P.price,
                P.type_id AS category,
                STRING_AGG(ProdI.ingredient_name, ', ') AS ingredient_list
            FROM 
                Product P
            LEFT JOIN 
                Product_Ingredient ProdI 
            ON 
                P.name = ProdI.product_name
            GROUP BY 
                P.name, P.price, P.type_id
            ORDER BY 
                P.type_id, P.name";

    $statement = $pdo->prepare($sql);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function filterProducten($producten, $search) {
    if ($search) {
        return array_filter($producten, function($product) use ($search) {
            return stripos($product['product_name'], $search) !== false; // Case-insensitive zoekopdracht
        });
    }
    return $producten;
}
?>
