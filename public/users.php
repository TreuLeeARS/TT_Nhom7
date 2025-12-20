<?php

// Tải cấu hình
require __DIR__ . '/../config/app.php';

use App\Controllers\UserController;

// Hiển thị danh sách người dùng (Admin chỉ xem)
$controller = new UserController($pdo);
$controller->index($_GET);
