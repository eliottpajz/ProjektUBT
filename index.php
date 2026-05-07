<?php
require_once 'config.php';
$page = $pageModel->getPageBySlug('home');
$extras = $pageModel->getPageExtras('home');
$products = $pageModel->getProducts();
$newsItems = $pageModel->getNewsItems(3);
require_once 'header.php';
?>
<section class="hero">
    <div class="hero-text">
        <h1><?php echo htmlspecialchars($extras['hero_title'] ?? 'Banking made simple.'); ?></h1>
        <p><?php echo htmlspecialchars($extras['hero_text'] ?? 'Open an account in minutes, manage your money 24/7, and reach your goals faster.'); ?></p>
        <div class="hero-actions">
            <button class="primary" onclick="window.location.href='register.php'">Open an account</button>
            <button class="secondary" onclick="window.location.href='cards.php'">Compare cards</button>
        </div>
    </div>
</section>
<section class="container">
    <div class="slider-wrapper">
        <div class="slider">
            <img id="slide-1" src="https://www.consumerslaw.com/blog/wp-content/uploads/2020/02/Getting-a-Loan.jpg" alt="Getting a Loan">
            <img id="slide-2" src="https://www.creditonebank.com/content/dam/cob-corp-acquisition/images/articles/2024/08/240357_DA_HowOftenShouldYouApplyCCRefresh_SEOA_FINAL.jpg" alt="Applying for a Credit Card">
            <img id="slide-3" src="https://ps.w.org/exchange-rates/assets/banner-1544x500.jpg?rev=3052666" alt="Exchange Rates">
        </div>
        <div class="slider-nav">
            <a href="#slide-1"></a>
            <a href="#slide-2"></a>
            <a href="#slide-3"></a>
        </div>
    </div>
</section>
<section class="pytja-section">
    <div class="pytja">
        <h2><?php echo htmlspecialchars($extras['feature_title'] ?? 'What am I looking for?'); ?></h2>
    </div>
    <div class="sherbimet">
        <?php
        $features = $extras['features'] ?? [
            'I want to apply for a credit card',
            'I want to take out a loan over 10,000 €',
            'I want to take out a loan over 30,000 €',
            'I want to see the exchange rates of the day',
            'I want to update my info',
        ];
        foreach ($features as $feature): ?>
            <div class="box"><?php echo htmlspecialchars($feature); ?></div>
        <?php endforeach; ?>
    </div>
</section>
<section class="quick-actions">
    <h2>Quick actions</h2>
    <div class="actions-grid">
        <button>Pay a bill</button>
        <button>Transfer money</button>
        <button>Exchange rates</button>
        <button>Book an appointment</button>
    </div>
</section>
<section class="products">
    <h2>Our products</h2>
    <div class="products-grid">
        <?php foreach (array_slice($products, 0, 3) as $product): ?>
            <article class="product-card">
                <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <button onclick="window.location.href='cards.php'">Learn more</button>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<section class="support">
    <h2>Need help?</h2>
    <p>Call us 24/7 at <strong>+383 45 295 529</strong> or visit your nearest branch.</p>
</section>
<section class="news-preview">
    <h2>Latest news</h2>
    <div class="news-grid">
        <?php if (count($newsItems) === 0): ?>
            <p>No news available yet.</p>
        <?php endif; ?>
        <?php foreach ($newsItems as $news): ?>
            <article class="news-card">
                <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                <p><?php echo htmlspecialchars(substr($news['content'], 0, 110)) . '...'; ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once 'footer.php';
