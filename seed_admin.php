<?php
require_once 'config.php';

$username = 'admin';
$email = 'admin@example.com';
$password = 'Admin123!';
$role = 'admin';

$userModel = new User($pdo);
if ($userModel->findByUsername($username) || $userModel->findByEmail($email)) {
    echo 'Admin user already exists.';
    exit;
}

$stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)');
$stmt->execute([
    'username' => $username,
    'email' => $email,
    'password' => password_hash($password, PASSWORD_DEFAULT),
    'role' => $role,
]);

echo 'Admin user created successfully. Username: admin, Password: Admin123!';
