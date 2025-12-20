<?php

// Tải cấu hình
require __DIR__ . '/../config/app.php';

use App\Controllers\CategoryController;

// Xử lý thêm chuyên mục mới
$controller = new CategoryController($pdo);
$controller->create($_POST, $_FILES);
