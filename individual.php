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
    <title>Individual - Banka</title>
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
                <h2>Individual Banking</h2>
                <p>Accounts, cards and personal finance tools designed for everyday banking.</p>
            </div>
        </section>

        <section class="product-cards container-narrow">
            <div class="product-card">
                <h3>Current Accounts</h3>
                <p>Manage payments, direct debit and transfers with ease.</p>
            </div>
            <div class="product-card">
                <h3>Debit & Credit Cards</h3>
                <p>Choose the card that fits your lifestyle with rewards and security features.</p>
            </div>
            <div class="product-card">
                <h3>Savings & Deposits</h3>
                <p>Competitive interest rates to grow your savings safely.</p>
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
