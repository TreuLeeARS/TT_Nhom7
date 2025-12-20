<?php

namespace App\Models;

use PDO;

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function paginate($page = 1, $limit = 10)
    {
        $offset = max(0, ($page - 1) * $limit);
        $countStmt = $this->pdo->query('SELECT COUNT(*) AS total FROM users');
        $total = (int) ($countStmt->fetch()['total'] ?? 0);

        $stmt = $this->pdo->prepare(
            'SELECT * FROM users ORDER BY id DESC LIMIT :limit OFFSET :offset'
        );

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'users' => $stmt->fetchAll(),
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
            'SELECT COUNT(*) AS total FROM users 
             WHERE username LIKE :search OR email LIKE :search'
        );
        $countStmt->execute([':search' => $searchTerm]);
        $total = (int) ($countStmt->fetch()['total'] ?? 0);

        // Get results
        $stmt = $this->pdo->prepare(
            'SELECT * FROM users 
             WHERE username LIKE :search OR email LIKE :search 
             ORDER BY id DESC LIMIT :limit OFFSET :offset'
        );

        $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'users' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'pages' => max(1, (int) ceil($total / $limit)),
            'keyword' => $keyword,
        ];
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findByUsername($username)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute([':username' => $username]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (username, email, password, active, role) VALUES (:username, :email, :password, :active, :role)'
        );

        return $stmt->execute([
            ':username' => $data['username'] ?? '',
            ':email' => $data['email'] ?? '',
            ':password' => $data['password'] ?? '',
            ':active' => $data['active'] ?? 1,
            ':role' => $data['role'] ?? 'admin',
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare(
            'UPDATE users SET username = :username, email = :email, password = :password, active = :active, role = :role WHERE id = :id'
        );

        return $stmt->execute([
            ':username' => $data['username'] ?? '',
            ':email' => $data['email'] ?? '',
            ':password' => $data['password'] ?? '',
            ':active' => $data['active'] ?? 1,
            ':role' => $data['role'] ?? 'user',
            ':id' => $id,
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');

        return $stmt->execute([':id' => $id]);
    }

    public function attempt($email, $password)
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM users WHERE email = :email AND password = :password AND active = 1 LIMIT 1'
        );

        $stmt->execute([
            ':email' => $email,
            ':password' => sha1($password),
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }
}

