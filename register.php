<?php
require_once 'config.php';
$errors = [];
$success = '';
$debugPost = ($_SERVER['REQUEST_METHOD'] === 'POST');
if ($debugPost) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($username === '' || strlen($username) < 3) {
        $errors['username'] = 'Username must be at least 3 characters.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $errors['password'] = 'Password must be at least 8 chars, with 1 uppercase, 1 digit and 1 symbol.';
    }
    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        if ($auth->usernameExists($username)) {
            $errors['username'] = 'This username is already taken.';
        }
        if ($auth->emailExists($email)) {
            $errors['email'] = 'This email is already registered.';
        }
    }

    if (empty($errors)) {
        if ($auth->register($username, $email, $password)) {
            header('Location: login.php?registered=1');
            exit;
        }
        $errors['form'] = 'Registration failed. Please try again later.';
    }
}
require_once 'header.php';
?>
<main class="auth-page">
    <section class="auth-card">
        <h1>Create account</h1>
        <?php if (!empty($errors) && empty($success)): ?>
            <p class="error">Please correct the fields below and try again.</p>
        <?php endif; ?>
        <?php if (!empty($errors['form'])): ?>
            <p class="error"><?php echo htmlspecialchars($errors['form']); ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <form id="registerForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" novalidate>
            <label for="username">Username</label>
            <input id="username" name="username" type="text" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
            <div id="usernameError" class="error"><?php echo htmlspecialchars($errors['username'] ?? ''); ?></div>

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            <div id="emailError" class="error"><?php echo htmlspecialchars($errors['email'] ?? ''); ?></div>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
            <div id="passwordError" class="error"><?php echo htmlspecialchars($errors['password'] ?? ''); ?></div>

            <label for="confirm_password">Confirm Password</label>
            <input id="confirm_password" name="confirm_password" type="password" required>
            <div id="confirmPasswordError" class="error"><?php echo htmlspecialchars($errors['confirm_password'] ?? ''); ?></div>

            <button id="registerButton" type="submit" class="primary">Register</button>
        </form>
        <?php if (!empty($debugPost)): ?>
            <div class="success">
                <p>POST request received by server.</p>
                <pre style="background:#f3f4f6;padding:12px;border-radius:10px;color:#111;">
Method: <?php echo htmlspecialchars($_SERVER['REQUEST_METHOD']); ?>
POST: <?php echo htmlspecialchars(print_r($_POST, true)); ?>
</pre>
            </div>
        <?php endif; ?>
        <p>Already have an account? <a class="btn-link" href="login.php">Login here</a></p>
        <p><a class="btn-link" href="index.php">← Back to homepage</a></p>
    </section>
</main>
<?php require_once 'footer.php';
