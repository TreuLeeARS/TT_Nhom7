<?php

// Tải cấu hình chung
require __DIR__ . '/../config/app.php';

use App\Controllers\PostController;

// Khởi tạo Post Controller và hiển thị danh sách bài viết (Admin)
$controller = new PostController($pdo);
$controller->index($_GET);
