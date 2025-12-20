<?php

// Tải cấu hình
require __DIR__ . '/../config/app.php';

use App\Support\Auth;

// Gọi hàm đăng xuất từ lớp Auth
Auth::logout();

// Chuyển hướng về trang chủ
header('Location: index.php');
exit;