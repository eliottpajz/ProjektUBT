<?php
require_once 'config.php';
$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '') {
        $errors['name'] = 'Name is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if (strlen($message) < 10) {
        $errors['message'] = 'Message must be at least 10 characters long.';
    }

    if (empty($errors)) {
        if ($contactModel->saveMessage($name, $email, $message)) {
            $success = 'Your message was sent successfully. Thank you!';
            $name = $email = $message = '';
        } else {
            $errors['general'] = 'Unable to send your message. Please try again later.';
        }
    }
}
require_once 'header.php';
?>
<section class="contact-section">
    <h1>Contact us</h1>
    <?php if (!empty($errors['general'])): ?>
        <p class="error"><?php echo htmlspecialchars($errors['general']); ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p class="success"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    <form id="contactForm" action="contact.php" method="POST" class="contact-form">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
        <div class="error"><?php echo htmlspecialchars($errors['name'] ?? ''); ?></div>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
        <div class="error"><?php echo htmlspecialchars($errors['email'] ?? ''); ?></div>

        <label for="message">Message</label>
        <textarea id="message" name="message" rows="5" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
        <div class="error"><?php echo htmlspecialchars($errors['message'] ?? ''); ?></div>

        <button type="submit" class="primary">Send message</button>
    </form>
</section>
<?php require_once 'footer.php';
