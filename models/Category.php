<?php

namespace App\Models;

use PDO;

class Category
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function all()
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY title ASC");
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findBySlug($slug)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE slug = :slug");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO categories (title, slug, description, image)
             VALUES (:title, :slug, :description, :image)'
        );

        return $stmt->execute([
            ':title' => $data['title'],
            ':slug' => $data['slug'],
            ':description' => $data['description'] ?? '',
            ':image' => $data['image'] ?? null,
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare(
            'UPDATE categories 
             SET title = :title, slug = :slug, description = :description, image = :image
             WHERE id = :id'
        );

        return $stmt->execute([
            ':title' => $data['title'],
            ':slug' => $data['slug'],
            ':description' => $data['description'] ?? '',
            ':image' => $data['image'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM categories WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
