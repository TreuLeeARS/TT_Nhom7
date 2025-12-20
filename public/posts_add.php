<?php

// Tải cấu hình
require __DIR__ . '/../config/app.php';

use App\Controllers\PostController;

// Khởi tạo Post Controller và xử lý thêm bài viết mới
$controller = new PostController($pdo);
$controller->create($_POST, $_FILES);
