<?php
require_once 'config.php';
$auth->requireAdmin();
$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add_product') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $imageUrl = trim($_POST['image_url'] ?? '');

        if ($title === '') {
            $errors['title'] = 'Product title is required.';
        }
        if ($description === '') {
            $errors['description'] = 'Product description is required.';
        }

        if (empty($errors)) {
            if ($pageModel->addProduct($title, $description, $imageUrl)) {
                header('Location: dashboard.php?success=product_added');
                exit;
            }
            $errors['form'] = 'Unable to add the product. Please try again.';
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete_product') {
        $productId = (int) ($_POST['product_id'] ?? 0);
        if ($productId <= 0) {
            $errors['form'] = 'Invalid product selected for deletion.';
        } elseif ($pageModel->deleteProduct($productId)) {
            header('Location: dashboard.php?success=product_deleted');
            exit;
        } else {
            $errors['form'] = 'Unable to delete the product. Please try again.';
        }
    }
}

$messages = $contactModel->getMessages();
$products = $pageModel->getProducts();
require_once 'header.php';
?>
<section class="dashboard-section">
    <h1>Administrator Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>.</p>

    <?php if (!empty($_GET['success'])): ?>
        <p class="success">
            <?php echo $_GET['success'] === 'product_added' ? 'Product added successfully.' : 'Product deleted successfully.'; ?>
        </p>
    <?php endif; ?>
    <?php if (!empty($errors['form'])): ?>
        <p class="error"><?php echo htmlspecialchars($errors['form']); ?></p>
    <?php endif; ?>

    <section class="admin-products">
        <h2>Manage products</h2>
        <form action="dashboard.php" method="POST" class="product-form">
            <input type="hidden" name="action" value="add_product">
            <label for="title">Title</label>
            <input id="title" name="title" type="text" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
            <div class="error"><?php echo htmlspecialchars($errors['title'] ?? ''); ?></div>

            <label for="description">Description</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            <div class="error"><?php echo htmlspecialchars($errors['description'] ?? ''); ?></div>

            <label for="image_url">Image URL (optional)</label>
            <input id="image_url" name="image_url" type="url" value="<?php echo htmlspecialchars($_POST['image_url'] ?? ''); ?>">
            <button type="submit" class="primary">Add product</button>
        </form>

        <?php if (empty($products)): ?>
            <p>No products exist yet.</p>
        <?php else: ?>
            <div class="admin-product-list">
                <?php foreach ($products as $product): ?>
                    <article class="product-card">
                        <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <form action="dashboard.php" method="POST" onsubmit="return confirm('Delete this product?');">
                            <input type="hidden" name="action" value="delete_product">
                            <input type="hidden" name="product_id" value="<?php echo (int) $product['id']; ?>">
                            <button type="submit" class="secondary">Delete</button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <h2>Contact messages</h2>
    <?php if (empty($messages)): ?>
        <p>No messages have been received yet.</p>
    <?php else: ?>
        <div class="message-list">
            <?php foreach ($messages as $message): ?>
                <article class="message-card">
                    <strong><?php echo htmlspecialchars($message['name']); ?></strong>
                    <span><?php echo htmlspecialchars($message['email']); ?></span>
                    <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                    <small><?php echo htmlspecialchars($message['created_at']); ?></small>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php require_once 'footer.php';
