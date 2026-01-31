<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';
session_start();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'error' => 'Username and password required.']);
    exit;
}

try {
    $pdo = getPDO();
    // select possible password columns and user info
    $stmt = $pdo->prepare('SELECT id, username, name, surname, role, password_hash, password FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $row = $stmt->fetch();

    if (!$row) {
        echo json_encode(['success' => false, 'error' => 'Invalid credentials.']);
        exit;
    }

    $valid = false;
    if (!empty($row['password_hash'])) {
        $valid = password_verify($password, $row['password_hash']);
    } elseif (!empty($row['password'])) {
        // fallback: plain or hashed password stored in 'password' column
        // try password_verify first, then plain-compare
        if (password_verify($password, $row['password'])) {
            $valid = true;
        } elseif ($password === $row['password']) {
            $valid = true;
        }
    }

    if ($valid) {
        // store minimal user info in session
        $_SESSION['user'] = [
            'id' => $row['id'],
            'username' => $row['username'],
            'name' => $row['name'] ?? $row['username'],
            'role' => $row['role'] ?? 'user'
        ];

        // handle "remember me" token (30 days)
        if (!empty($_POST['remember']) && $_POST['remember'] === '1') {
            try {
                $selector = bin2hex(random_bytes(12)); // 24 chars
                $validator = bin2hex(random_bytes(32));
                $tokenHash = hash('sha256', $validator);
                $expires = (new DateTime('+30 days'))->format('Y-m-d H:i:s');
                $ins = $pdo->prepare('INSERT INTO auth_tokens (user_id, selector, token_hash, expires) VALUES (?, ?, ?, ?)');
                $ins->execute([$row['id'], $selector, $tokenHash, $expires]);
                $cookieValue = $selector . ':' . $validator;
                setcookie('remember', $cookieValue, time() + 30*24*60*60, '/', '', false, true);
            } catch (Exception $e) {
                error_log('Failed to create remember token: ' . $e->getMessage());
            }
        }

        echo json_encode(['success' => true, 'user' => $_SESSION['user']]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid credentials.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    $msg = $e->getMessage();
    error_log('Login error: ' . $msg);
    @file_put_contents(__DIR__ . '/debug.log', date('c') . " Login error: " . $msg . PHP_EOL, FILE_APPEND);
    echo json_encode(['success' => false, 'error' => 'Server error.', 'detail' => $msg]);
}

?>
