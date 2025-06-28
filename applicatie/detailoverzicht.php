<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detailoverzicht Bestelling | Pizzeria Sole Machina</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

   <?php include 'header.php'; ?>
   
    <!-- Detailoverzicht Bestelling -->
    <section id="bestelling-detail">
        <h2>Detailoverzicht Bestelling #1234</h2>
        
        <!-- Klantgegevens -->
        <div class="klant-info">
            <h3>Klantinformatie</h3>
            <p><strong>Naam:</strong> Jan de Vries</p>
            <p><strong>Adres:</strong> Kerkstraat 12, 1234 AB, Amsterdam</p>
            <p><strong>Telefoonnummer:</strong> 0612345678</p>
            <p><strong>Email:</strong> jan.devries@example.com</p>
        </div>
        
        <!-- Bestellingsdetails -->
        <div class="bestelling-info">
            <h3>Bestellingsdetails</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Aantal</th>
                        <th>Prijs per Stuk</th>
                        <th>Totaal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pizza Margherita</td>
                        <td>1</td>
                        <td>€8,00</td>
                        <td>€8,00</td>
                    </tr>
                    <tr>
                        <td>Pizza Pepperoni</td>
                        <td>2</td>
                        <td>€10,00</td>
                        <td>€20,00</td>
                    </tr>
                    <tr>
                        <td>Cola</td>
                        <td>1</td>
                        <td>€2,00</td>
                        <td>€2,00</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Betalingsoverzicht -->
        <div class="betaling-info">
            <p><strong>Subtotaal:</strong> €30,00</p>
            <p><strong>Verzendkosten:</strong> €5,00</p>
            <p><strong>Totaal Bedrag:</strong> €35,00</p>
        </div>

        <!-- Bestelling Status -->
        <div class="bestelling-status">
            <h3>Bestelling Status</h3>
            <p><strong>Status:</strong> <span class="status status-in-progress">In Behandeling</span></p>
            <button class="status-wijzigen">Status Wijzigen</button>
        </div>

    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Pizzeria Sole Machina. Alle rechten voorbehouden.</p>
    </footer>

</body>
</html>
