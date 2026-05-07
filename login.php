<?php
require_once 'config.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $errors['username'] = 'Username is required.';
    }
    if ($password === '') {
        $errors['password'] = 'Password is required.';
    }

    if (empty($errors) && $auth->login($username, $password)) {
        header('Location: index.php');
        exit;
    }
    if (empty($errors)) {
        $errors['form'] = 'Login failed. Please check username and password.';
    }
}
require_once 'header.php';
?>
<main class="auth-page">
    <section class="auth-card">
        <h1>Login</h1>
        <?php if (!empty($errors['form'])): ?>
            <p class="error"><?php echo htmlspecialchars($errors['form']); ?></p>
        <?php endif; ?>
        <form id="loginForm" action="login.php" method="POST" novalidate>
            <label for="username">Username</label>
            <input id="username" name="username" type="text" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
            <div class="error"><?php echo htmlspecialchars($errors['username'] ?? ''); ?></div>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
            <div class="error"><?php echo htmlspecialchars($errors['password'] ?? ''); ?></div>

            <button type="submit" class="primary">Login</button>
        </form>
        <p>Don't have an account? <a class="btn-link" href="register.php">Register here</a></p>
        <p><a class="btn-link" href="index.php">← Back to homepage</a></p>
    </section>
</main>
<?php require_once 'footer.php';
