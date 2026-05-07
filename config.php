<?php
session_start();

// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'bankaproject');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Auth.php';
require_once __DIR__ . '/classes/PageContent.php';
require_once __DIR__ . '/classes/Contact.php';

try {
    $database = Database::getInstance();
    $pdo = $database->getConnection();
} catch (PDOException $e) {
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}

$auth = new Auth($pdo);
$pageModel = new PageContent($pdo);
$contactModel = new Contact($pdo);
