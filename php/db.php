<?php
// Read connection parameters from config.php to connect to phpMyAdmin (MySQL).
$cfg = [];
if (file_exists(__DIR__ . '/config.php')) {
    $cfg = require __DIR__ . '/config.php';
}

function getPDO() {
    global $cfg;
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $driver = $cfg['driver'] ?? 'mysql';

    if ($driver === 'mysql') {
        $m = $cfg['mysql'] ?? [];
        $host = $m['host'] ?? '127.0.0.1';
        $port = $m['port'] ?? 3306;
        $db   = $m['dbname'] ?? 'banka_db';
        $user = $m['user'] ?? 'root';
        $pass = $m['pass'] ?? '';
        $charset = 'utf8mb4';

        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
            $pdo = new PDO($dsn, $user, $pass, $options);
            return $pdo;
        } catch (Exception $e) {
            // try to create DB if possible (dev convenience)
            try {
                $dsnNoDb = "mysql:host={$host};port={$port};charset={$charset}";
                $tmp = new PDO($dsnNoDb, $user, $pass, $options);
                $tmp->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET {$charset} COLLATE {$charset}_general_ci;");
                $pdo = new PDO("mysql:host={$host};port={$port};dbname={$db};charset={$charset}", $user, $pass, $options);
                return $pdo;
            } catch (Exception $e2) {
                error_log('MySQL connection failed: ' . $e2->getMessage());
                // fall through to sqlite fallback
            }
        }
    }

    // SQLite fallback for local testing
    try {
        $dataDir = __DIR__ . '/data';
        if (!is_dir($dataDir)) mkdir($dataDir, 0755, true);
        $sqlitePath = $cfg['sqlite_path'] ?? ($dataDir . '/database.sqlite');
        $pdo = new PDO('sqlite:' . $sqlitePath, null, null, $options);
        $pdo->exec('PRAGMA foreign_keys = ON');
        return $pdo;
    } catch (Exception $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'DB connection failed', 'detail' => $e->getMessage()]);
        exit;
    }
}

?>
