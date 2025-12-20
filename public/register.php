<?php
// Tải cấu hình
require __DIR__ . '/../config/app.php';

use App\Controllers\UserController;

// Khởi tạo User Controller và xử lý đăng ký
$controller = new UserController($pdo);
$controller->register($_POST);
