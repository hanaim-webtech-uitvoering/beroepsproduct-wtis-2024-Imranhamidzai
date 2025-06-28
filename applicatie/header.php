<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="main-header">
    <div class="header-container">
        <!-- Logo helemaal links -->
        <a href="index.php" class="logo-link">
            <img src="img/logo.png" alt="Logo" class="logo-img">
        </a>

        <!-- Titel in het midden -->
        <h1 class="header-title">Pizzeria Sole Machina</h1>

        <!-- Hamburger-menu helemaal rechts -->
        <nav class="main-nav">
            <details class="menu-dropdown">
                <summary class="hamburger-menu" title="Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </summary>
                <ul class="menu-items">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="menu.php">Menu</a></li>
                    <li><a href="winkelwagen.php">Winkelmandje</a></li>
                    <li><a href="registreren.php">Registreren</a></li>
                    <li><a href="inlog.php">Inloggen</a></li>
                    <li><a href="contactformulier.php">Contact</a></li>
                    <li><a href="profiel.php">Mijn profiel</a></li>
                </ul>
            </details>
        </nav>
    </div>
</header>
