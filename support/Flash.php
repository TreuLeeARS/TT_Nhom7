<?php

namespace App\Support;

// Lớp quản lý thông báo (Flash Message)
class Flash
{
    // Tạo thông báo mới vào session
    public static function set($message, $type = 'success')
    {
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type,
        ];
    }

    // Lấy thông báo ra và xoá ngay lập tức
    public static function consume()
    {
        if (empty($_SESSION['flash'])) {
            return null;
        }

        $message = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $message;
    }
}

