<?php

class PageContent
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPageBySlug($slug)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM pages WHERE slug = :slug');
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    }

    public function getPageExtras($slug)
    {
        $page = $this->getPageBySlug($slug);
        if (!$page || empty($page['extras'])) {
            return [];
        }
        $extras = json_decode($page['extras'], true);
        return is_array($extras) ? $extras : [];
    }

    public function getProducts()
    {
        $stmt = $this->pdo->query('SELECT * FROM products ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function addProduct($title, $description, $imageUrl = null)
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO products (title, description, image_url) VALUES (:title, :description, :image_url)'
        );
        return $stmt->execute([
            'title' => $title,
            'description' => $description,
            'image_url' => $imageUrl ?: null,
        ]);
    }

    public function deleteProduct($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function getNewsItems($limit = 5)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM news ORDER BY created_at DESC LIMIT :limit');
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
