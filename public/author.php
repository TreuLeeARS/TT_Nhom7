<?php

// Tải cấu hình
require __DIR__ . '/../config/app.php';

use App\Controllers\PostController;

// Khởi tạo Post Controller
$controller = new PostController($pdo);

// Lấy ID tác giả từ URL và hiển thị bài viết của họ
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$controller->author($id, $_GET);
