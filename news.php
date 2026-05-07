<?php
require_once 'config.php';
$newsItems = $pageModel->getNewsItems(10);
require_once 'header.php';
?>
<section class="news-section">
    <h1>News</h1>
    <div class="news-grid">
        <?php if (count($newsItems) === 0): ?>
            <p>There are no news items yet. Please check back later.</p>
        <?php endif; ?>
        <?php foreach ($newsItems as $news): ?>
            <article class="news-card">
                <h2><?php echo htmlspecialchars($news['title']); ?></h2>
                <p><?php echo htmlspecialchars(substr($news['content'], 0, 220)) . '...'; ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once 'footer.php';
