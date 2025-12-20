<?php

// Tải cấu hình
require __DIR__ . '/../config/app.php';

use App\Controllers\PostController;

// Kiểm tra nếu không có ID bài viết thì quay về trang danh sách
if (!isset($_GET['id'])) {
    header('Location: posts.php');
    exit;
}

// Khởi tạo Post Controller và xử lý sửa bài viết
$controller = new PostController($pdo);
$controller->edit((int) $_GET['id'], $_POST, $_FILES);
