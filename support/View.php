<?php

namespace App\Support;

// Lớp hỗ trợ hiển thị giao diện (View)
class View
{
    // Hiển thị giao diện trang Public (có Header/Footer chung)
    public static function render($template, $data = [])
    {
        $viewPath = __DIR__ . '/../views/' . $template . '.php';

        if (!file_exists($viewPath)) {
            echo 'View not found: ' . $template;
            return;
        }

        extract($data);

        require __DIR__ . '/../views/layout/header.php';
        require $viewPath;
        require __DIR__ . '/../views/layout/footer.php';
    }

    // Hiển thị giao diện trang Admin (có Header/Footer quản trị)
    public static function renderAdmin($template, $data = [])
    {
        $viewPath = __DIR__ . '/../views/' . $template . '.php';

        if (!file_exists($viewPath)) {
            echo 'View not found: ' . $template;
            return;
        }

        extract($data);

        require __DIR__ . '/../views/layout/admin_header.php';
        require $viewPath;
        require __DIR__ . '/../views/layout/admin_footer.php';
    }
}

