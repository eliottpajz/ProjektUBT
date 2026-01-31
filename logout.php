<?php
session_start();
// clear session and redirect to homepage
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
// remove remember token if present
$cookie = $_COOKIE['remember'] ?? null;
if ($cookie) {
    require_once __DIR__ . '/php/db.php';
    try {
        list($selector, $validator) = array_pad(explode(':', $cookie, 2), 2, null);
        if ($selector) {
            $pdo = getPDO();
            $del = $pdo->prepare('DELETE FROM auth_tokens WHERE selector = ?');
            $del->execute([$selector]);
        }
    } catch (Exception $e) {
        error_log('Failed to delete remember token on logout: ' . $e->getMessage());
    }
    setcookie('remember', '', time() - 3600, '/');
}

session_destroy();
header('Location: homepage.php');
exit;
?>