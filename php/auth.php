<?php
// auth.php - call this at the top of pages to populate $_SESSION['user'] from session or remember cookie
session_start();
require_once __DIR__ . '/db.php';

if (!empty($_SESSION['user'])) {
    // already logged in
    return;
}

$cookie = $_COOKIE['remember'] ?? null;
if (!$cookie) {
    return;
}

// cookie format: selector:validator
list($selector, $validator) = array_pad(explode(':', $cookie, 2), 2, null);
if (!$selector || !$validator) {
    return;
}

try {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT user_id, token_hash, expires FROM auth_tokens WHERE selector = ? LIMIT 1');
    $stmt->execute([$selector]);
    $row = $stmt->fetch();
    if (!$row) {
        return;
    }

    if (new DateTime() > new DateTime($row['expires'])) {
        // expired: remove token
        $del = $pdo->prepare('DELETE FROM auth_tokens WHERE selector = ?');
        $del->execute([$selector]);
        setcookie('remember', '', time() - 3600, '/');
        return;
    }

    // verify validator
    $calcHash = hash('sha256', $validator);
    if (!hash_equals($row['token_hash'], $calcHash)) {
        // token mismatch: possible theft, remove all tokens for this selector
        $del = $pdo->prepare('DELETE FROM auth_tokens WHERE selector = ?');
        $del->execute([$selector]);
        setcookie('remember', '', time() - 3600, '/');
        return;
    }

    // load user and populate session
    $userStmt = $pdo->prepare('SELECT id, username, name FROM users WHERE id = ? LIMIT 1');
    $userStmt->execute([$row['user_id']]);
    $user = $userStmt->fetch();
    if ($user) {
        $_SESSION['user'] = ['id' => $user['id'], 'username' => $user['username'], 'name' => $user['name'] ?: $user['username']];
        // refresh token expiry and cookie (rotate validator)
        $newValidator = bin2hex(random_bytes(32));
        $newHash = hash('sha256', $newValidator);
        $expires = (new DateTime('+30 days'))->format('Y-m-d H:i:s');
        $update = $pdo->prepare('UPDATE auth_tokens SET token_hash = ?, expires = ? WHERE selector = ?');
        $update->execute([$newHash, $expires, $selector]);
        $cookieValue = $selector . ':' . $newValidator;
        setcookie('remember', $cookieValue, time() + 30*24*60*60, '/', '', false, true);
    }
} catch (Exception $e) {
    error_log('Auth cookie check error: ' . $e->getMessage());
}
?>