<?php

// Tải cấu hình
require __DIR__ . '/../config/app.php';

use App\Controllers\CategoryController;

// Hiển thị danh sách chuyên mục
$controller = new CategoryController($pdo);
$controller->index();
