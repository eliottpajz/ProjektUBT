<?php

class Auth
{
    private $userModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
    }

    public function login($username, $password)
    {
        $user = $this->userModel->verifyPassword($username, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
        return false;
    }

    public function register($username, $email, $password)
    {
        if ($this->userModel->findByUsername($username) || $this->userModel->findByEmail($email)) {
            return false;
        }
        return $this->userModel->create($username, $email, $password);
    }

    public function usernameExists($username)
    {
        return (bool) $this->userModel->findByUsername($username);
    }

    public function emailExists($email)
    {
        return (bool) $this->userModel->findByEmail($email);
    }

    public function logout()
    {
        session_destroy();
        unset($_SESSION['user_id'], $_SESSION['user_role'], $_SESSION['username']);
    }

    public function isLoggedIn()
    {
        return !empty($_SESSION['user_id']);
    }

    public function isAdmin()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public function requireAdmin()
    {
        if (!$this->isLoggedIn() || !$this->isAdmin()) {
            header('Location: login.php');
            exit;
        }
    }

    public function getUser()
    {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'role' => $_SESSION['user_role'],
                'username' => $_SESSION['username'],
            ];
        }
        return null;
    }
}
