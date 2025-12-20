<?php

// Tải cấu hình
require __DIR__ . '/../config/app.php';

use App\Controllers\UserController;

// Kiểm tra ID người dùng
if (!isset($_GET['id'])) {
    header('Location: users.php');
    exit;
}

// Xử lý sửa thông tin người dùng
$controller = new UserController($pdo);
$controller->edit((int) $_GET['id'], $_POST);
