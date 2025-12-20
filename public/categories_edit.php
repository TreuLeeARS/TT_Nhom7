<?php

// Tải cấu hình
require __DIR__ . '/../config/app.php';

use App\Controllers\CategoryController;

// Xử lý sửa chuyên mục
$controller = new CategoryController($pdo);

$id = $_GET['id'] ?? null;
$controller->edit($id, $_POST, $_FILES);
