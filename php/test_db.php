<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';

try {
    $pdo = getPDO();
    // simple test: list tables (works for both mysql and sqlite)
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    if ($driver === 'sqlite') {
        $res = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
    } else {
        $res = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    }
    echo json_encode(['success' => true, 'driver' => $driver, 'tables' => $res]);
} catch (Exception $e) {
    $msg = $e->getMessage();
    @file_put_contents(__DIR__ . '/debug.log', date('c') . " DB test error: " . $msg . PHP_EOL, FILE_APPEND);
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'DB connection failed', 'detail' => $msg]);
}
