<?php
require_once __DIR__ . '/php/auth.php';
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Business - Banka</title>
</head>
<body>
    <header>
        <div class="logo">Banka</div>
        <nav class="nav">
            <div class="leftnav">
                <a href="individual.php">Individual</a>
                <a href="premium.php">Premium</a>
                <a href="business.php">Business</a>
                <button class="rightnav" onclick="window.location.href='aboutus.php'">About Us</button>
            </div>
            <div class="rightnav">
                <?php if ($user): ?>
                    <span class="greeting">Përshëndetje, <?php echo htmlspecialchars($user['name']); ?></span>
                    <button onclick="window.location.href='logout.php'">Logout</button>
                <?php else: ?>
                    <button onclick="window.location.href='login.php'">Login or Register</button>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>
        <section class="product-hero">
            <div class="container-narrow">
                <h2>Business Banking</h2>
                <p>Products for small and medium enterprises to manage cashflow, payroll and business growth.</p>
            </div>
        </section>

        <section class="product-cards container-narrow">
            <div class="product-card">
                <h3>Business Accounts</h3>
                <p>Multi-user account access and integrated payment services.</p>
            </div>
            <div class="product-card">
                <h3>Loans for Businesses</h3>
                <p>Flexible lending options to support your operational and expansion needs.</p>
            </div>
            <div class="product-card">
                <h3>Merchant Services</h3>
                <p>Card terminals, online payments and settlement solutions.</p>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-links">
            <a href="#">Branches &amp; ATMs</a>
            <a href="#">Contact</a>
            <a href="#">Security</a>
            <a href="#">Terms &amp; Privacy</a>
        </div>
        <p>&copy; 2025 Banka. All rights reserved.</p>
    </footer>
</body>
</html>
