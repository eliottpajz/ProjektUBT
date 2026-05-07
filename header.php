<?php
$current = basename($_SERVER['PHP_SELF']);
function activeLink($page)
{
    global $current;
    return $current === $page ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banka</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">Banka</div>
    <nav class="nav">
        <div class="leftnav">
            <a class="<?php echo activeLink('index.php'); ?>" href="index.php">Home</a>
            <a class="<?php echo activeLink('aboutus.php'); ?>" href="aboutus.php">About Us</a>
            <a class="<?php echo activeLink('cards.php'); ?>" href="cards.php">Products</a>
            <a class="<?php echo activeLink('news.php'); ?>" href="news.php">News</a>
            <a class="<?php echo activeLink('contact.php'); ?>" href="contact.php">Contact</a>
        </div>
        <div class="rightnav">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="user-name">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="dashboard.php">Dashboard</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <button onclick="window.location.href='login.php'">Login / Register</button>
            <?php endif; ?>
        </div>
    </nav>
</header>
<main>
