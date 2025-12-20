<?php

// Tải file cấu hình chung của ứng dụng
require __DIR__ . '/../config/app.php';

use App\Controllers\PostController;

// Khởi tạo Controller bài viết và chạy hàm trang chủ
$controller = new PostController($pdo);
$controller->home($_GET, $_POST);
