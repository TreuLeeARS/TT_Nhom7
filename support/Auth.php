<?php

namespace App\Support;

use App\Support\Flash;

class Auth
{
    public static function check()
    {
        return !empty($_SESSION['user']);
    }

    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }

    public static function login($user)
    {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'role' => $user['role'] ?? 'user', // LƯU ROLE VÀO SESSION
        ];
    }

    public static function logout()
    {
        unset($_SESSION['user']);
    }

    public static function requireLogin()
    {
        if (!self::check()) {
            Flash::set('Please login first', 'danger');
            header('Location: index.php');
            exit;
        }
    }

    /**
     * Kiểm tra xem user hiện tại có phải admin không
     */
    public static function isAdmin()
    {
        $user = self::user();
        return $user && ($user['role'] ?? 'user') === 'admin';
    }

    /**
     * Yêu cầu quyền admin, nếu không redirect về dashboard
     */
    public static function requireAdmin()
    {
        self::requireLogin();
        
        if (!self::isAdmin()) {
            Flash::set('Bạn không có quyền truy cập chức năng này!', 'danger');
            header('Location: dashboard.php');
            exit;
        }
    }
}

