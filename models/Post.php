<?php

namespace App\Models;

use PDO;

class Post
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function publicList($page = 1, $limit = 10, $filters = [])
    {
        $offset = max(0, ($page - 1) * $limit);

        // Build conditions
        $where = [];
        $params = [];

        if (!empty($filters['author_id'])) {
            $where[] = 'posts.author = :author_id';
            $params[':author_id'] = $filters['author_id'];
        }

        if (!empty($filters['keyword'])) {
            $where[] = '(posts.title LIKE :keyword OR posts.content LIKE :keyword)';
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }

        if (!empty($filters['category'])) {
            $where[] = 'categories.slug = :category';
            $params[':category'] = $filters['category'];
        }

        if (!empty($filters['status'])) {
            $where[] = 'posts.status = :status';
            $params[':status'] = $filters['status'];
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        // Count total
        $countSql = "SELECT COUNT(*) AS total FROM posts 
                     LEFT JOIN categories ON categories.id = posts.category_id 
                     $whereClause";
        if (!empty($params)) {
            $countStmt = $this->pdo->prepare($countSql);
            $countStmt->execute($params);
        } else {
            $countStmt = $this->pdo->query($countSql);
        }
        $total = (int) ($countStmt->fetch()['total'] ?? 0);

        // Get posts
        $sql = "SELECT posts.*, users.username, categories.title as category_name, categories.slug as category_slug 
            FROM posts 
            LEFT JOIN users ON users.id = posts.author 
            LEFT JOIN categories ON categories.id = posts.category_id 
            $whereClause
            ORDER BY posts.id DESC 
            LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return [
            'posts' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'pages' => max(1, (int) ceil($total / $limit)),
        ];
    }

    public function search($keyword, $page = 1, $limit = 10)
    {
        $offset = max(0, ($page - 1) * $limit);
        $searchTerm = '%' . $keyword . '%';

        // Count total results
        $countStmt = $this->pdo->prepare(
            'SELECT COUNT(*) AS total FROM posts 
             WHERE title LIKE :search OR content LIKE :search'
        );
        $countStmt->execute([':search' => $searchTerm]);
        $total = (int) ($countStmt->fetch()['total'] ?? 0);

        // Get results with author info
        $stmt = $this->pdo->prepare(
            'SELECT posts.*, users.username, categories.title as category_name 
            FROM posts 
            LEFT JOIN users ON users.id = posts.author 
            LEFT JOIN categories ON categories.id = posts.category_id 
            WHERE posts.title LIKE :search OR posts.content LIKE :search
            ORDER BY posts.id DESC 
            LIMIT :limit OFFSET :offset'
        );

        $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'posts' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'pages' => max(1, (int) ceil($total / $limit)),
            'keyword' => $keyword,
        ];
    }

    public function paginate($page = 1, $limit = 10)
    {
        $offset = max(0, ($page - 1) * $limit);

        $countStmt = $this->pdo->query('SELECT COUNT(*) AS total FROM posts');
        $total = (int) ($countStmt->fetch()['total'] ?? 0);

        $stmt = $this->pdo->prepare(
            'SELECT posts.*, categories.title as category_name FROM posts 
             LEFT JOIN categories ON categories.id = posts.category_id
             ORDER BY posts.id DESC LIMIT :limit OFFSET :offset'
        );

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'posts' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'pages' => max(1, (int) ceil($total / $limit)),
        ];
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare(
            'SELECT posts.*, users.username, categories.title as category_name, categories.id as category_id 
             FROM posts 
             LEFT JOIN users ON users.id = posts.author 
             LEFT JOIN categories ON categories.id = posts.category_id 
             WHERE posts.id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);

        $post = $stmt->fetch();
        return $post ?: null;
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO posts (title, image, content, author, date, category_id, status)
             VALUES (:title, :image, :content, :author, :date, :category_id, :status)'
        );

        if (
            $stmt->execute([
                ':title' => $data['title'] ?? '',
                ':image' => $data['image'] ?? null,
                ':content' => $data['content'] ?? '',
                ':author' => $data['author'] ?? null,
                ':date' => $data['date'] ?? date('Y-m-d'),
                ':category_id' => !empty($data['category_id']) ? $data['category_id'] : null,
                ':status' => $data['status'] ?? 'publish',
            ])
        ) {
            return $this->pdo->lastInsertId();
        }

        return false;
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare(
            'UPDATE posts 
             SET title = :title, image = :image, content = :content, author = :author, date = :date, category_id = :category_id, status = :status
             WHERE id = :id'
        );

        return $stmt->execute([
            ':title' => $data['title'] ?? '',
            ':image' => $data['image'] ?? null,
            ':content' => $data['content'] ?? '',
            ':author' => $data['author'] ?? null,
            ':date' => $data['date'] ?? date('Y-m-d'),
            ':category_id' => !empty($data['category_id']) ? $data['category_id'] : null,
            ':status' => $data['status'] ?? 'publish',
            ':id' => $id,
        ]);
    }


    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM posts WHERE id = :id');

        return $stmt->execute([':id' => $id]);
    }

    public function addComment($postId, $userId, $content)
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)'
        );

        return $stmt->execute([
            ':post_id' => $postId,
            ':user_id' => $userId,
            ':content' => $content,
        ]);
    }

    public function comments($postId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT comments.*, users.username 
             FROM comments 
             JOIN users ON users.id = comments.user_id 
             WHERE comments.post_id = :post_id 
             ORDER BY comments.created_at ASC'
        );

        $stmt->execute([':post_id' => $postId]);

        return $stmt->fetchAll() ?: [];
    }
}

