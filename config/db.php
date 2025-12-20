<?php

$dsn = 'mysql:host=localhost;dbname=dawidadach_cmsdb;charset=utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        $dsn,
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $exception) {
    exit('Database connection failed: ' . $exception->getMessage());
}

