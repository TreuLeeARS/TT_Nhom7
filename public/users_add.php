<?php

// Tải cấu hình
require __DIR__ . '/../config/app.php';

use App\Controllers\UserController;

// Xử lý thêm người dùng mới
$controller = new UserController($pdo);
$controller->create($_POST);
