<?php
require_once 'config.php';
$page = $pageModel->getPageBySlug('about');
$extras = $pageModel->getPageExtras('about');
require_once 'header.php';
?>
<section class="about-section">
    <h1><?php echo htmlspecialchars($page['title'] ?? 'Find out more about:'); ?></h1>
    <div class="about-grid">
        <?php
        $boxes = $extras['boxes'] ?? [
            'Management Board',
            'Prices & Evaluations',
            'Financial Statements',
            'Code of Ethics',
            'Board of Directors',
            'ESG Financing',
            'Annual Reports',
            'FATCA',
            'Mission and Vision',
            'Branch Network',
            'Community Investments',
            'Personal Data Protection',
        ];
        foreach ($boxes as $box): ?>
            <div class="about-box"><?php echo htmlspecialchars($box); ?></div>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once 'footer.php';
