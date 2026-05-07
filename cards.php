<?php
require_once 'config.php';
$products = $pageModel->getProducts();
require_once 'header.php';
?>
<h1>Card Tiers</h1>
<div class="cardContainer">
    <?php if (count($products) === 0): ?>
        <p>No product data is available yet.</p>
    <?php endif; ?>
    <?php foreach ($products as $product): ?>
        <div class="cards">
            <h2><?php echo htmlspecialchars($product['title']); ?></h2>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once 'footer.php';
