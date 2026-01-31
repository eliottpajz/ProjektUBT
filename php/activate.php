<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$name = trim($_POST['name'] ?? '');
$surname = trim($_POST['surname'] ?? '');
$personal = trim($_POST['personal'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($name === '' || $surname === '' || $personal === '' || $email === '' || $username === '' || $password === '') {
    echo json_encode(['success' => false, 'error' => 'All fields are required.']);
    exit;
}

try {
    $pdo = getPDO();

    // create table if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role VARCHAR(20) NOT NULL DEFAULT 'user',
        name VARCHAR(100) NOT NULL,
        surname VARCHAR(100) NOT NULL,
        personal VARCHAR(64) NOT NULL,
        phone VARCHAR(64),
        email VARCHAR(150) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // create auth_tokens table for "remember me" persistent logins
    $pdo->exec("CREATE TABLE IF NOT EXISTS auth_tokens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        selector CHAR(24) NOT NULL,
        token_hash CHAR(64) NOT NULL,
        expires DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (selector),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // ensure required columns exist (handle older schemas)
    try {
        $colsStmt = $pdo->prepare("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users'");
        $colsStmt->execute();
        $existing = $colsStmt->fetchAll(PDO::FETCH_COLUMN);

        $expected = [
            'username' => 'VARCHAR(100) NULL',
            'password_hash' => 'VARCHAR(255) NULL',
            'role' => "VARCHAR(20) NULL",
            'name' => 'VARCHAR(100) NULL',
            'surname' => 'VARCHAR(100) NULL',
            'personal' => 'VARCHAR(64) NULL',
            'phone' => 'VARCHAR(64) NULL',
            'email' => 'VARCHAR(150) NULL',
        ];

        foreach ($expected as $col => $def) {
            if (!in_array($col, $existing, true)) {
                try {
                    $pdo->exec("ALTER TABLE users ADD COLUMN `" . $col . "` " . $def . ";");
                    error_log("Added missing column $col to users table.");
                } catch (Exception $e) {
                    error_log('Failed to add column ' . $col . ': ' . $e->getMessage());
                }
            }
        }

        // refresh existing columns list after alterations
        $colsStmt->execute();
        $existing = $colsStmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (Exception $e) {
        error_log('Schema check failed: ' . $e->getMessage());
        $existing = [];
    }

    // check existing username
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Username already exists.']);
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Detect which password column exists and build INSERT dynamically
    $colsStmt = $pdo->prepare("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users'");
    $colsStmt->execute();
    $existing = $colsStmt->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('password_hash', $existing, true)) {
        if (in_array('password', $existing, true)) {
            // table uses 'password' column; we'll store our hash there
            $passwordColumn = 'password';
        } else {
            // try to add 'password_hash' column
            try {
                $pdo->exec("ALTER TABLE users ADD COLUMN password_hash VARCHAR(255) NOT NULL AFTER username;");
                $passwordColumn = 'password_hash';
            } catch (Exception $e) {
                error_log('Failed to add password_hash column: ' . $e->getMessage());
                // fallback to using a column name that exists (if any), otherwise abort
                $passwordColumn = null;
            }
        }
    } else {
        $passwordColumn = 'password_hash';
    }

    if ($passwordColumn === null) {
        throw new Exception('No suitable password column available in users table.');
    }

    // include role (default 'user') in insert
    $cols = ['username', $passwordColumn, 'role', 'name', 'surname', 'personal', 'phone', 'email'];
    $placeholders = implode(',', array_fill(0, count($cols), '?'));
    $insertSql = 'INSERT INTO users (' . implode(',', $cols) . ') VALUES (' . $placeholders . ')';
    $insert = $pdo->prepare($insertSql);
    $params = [$username, $password_hash, 'user', $name, $surname, $personal, $phone, $email];
    $insert->execute($params);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    error_log('Activate error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Server error.', 'detail' => $e->getMessage()]);
}

?>
