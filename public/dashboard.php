<?php

// Tải file cấu hình chung của ứng dụng
require __DIR__ . '/../config/app.php';

use App\Controllers\DashboardController;

// Khởi tạo Dashboard Controller và hiển thị bảng tin
$controller = new DashboardController($pdo);
$controller->index($_GET);
