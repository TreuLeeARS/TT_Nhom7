<?php

// Tải cấu hình
require __DIR__ . '/../config/app.php';

use App\Controllers\PostController;

// Kiểm tra ID bài viết
if (!isset($_GET['id'])) {
    header('Location: posts.php');
    exit;
}

// Xử lý hiển thị và thêm bình luận cho bài viết
$controller = new PostController($pdo);
$controller->comments((int) $_GET['id'], $_POST);
