<?php

class Contact
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function saveMessage($name, $email, $message)
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)'
        );
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'message' => $message,
        ]);
    }

    public function getMessages()
    {
        $stmt = $this->pdo->query('SELECT * FROM contacts ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }
}
