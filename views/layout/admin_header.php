<?php

use App\Support\Auth;
use App\Support\Flash;

$flash = Flash::consume();
$currentUser = Auth::user();

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin Dashboard'; ?> - CMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="css/mdb.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 60px;
            --primary-color: #1266f1;
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #34495e;
        }

        body {
            background: #f4f6f9;
        }

        /* Admin Header */
        .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .admin-header .brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
            text-decoration: none;
            margin-right: auto;
        }

        .admin-header .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: var(--sidebar-bg);
            color: white;
            overflow-y: auto;
            z-index: 999;
            transition: transform 0.3s;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: var(--sidebar-hover);
            color: white;
            border-left-color: var(--primary-color);
        }

        .sidebar-menu li a i {
            width: 30px;
            font-size: 1.2rem;
        }

        /* Main Content */
        .admin-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 30px;
            min-height: calc(100vh - var(--header-height));
        }

        /* Breadcrumb */
        .admin-breadcrumb {
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .stat-card.blue .icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-card.green .icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .stat-card.orange .icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .stat-card.red .icon {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: bold;
            margin: 10px 0;
            color: #2c3e50;
        }

        .stat-card p {
            color: #7f8c8d;
            margin: 0;
            font-size: 0.9rem;
        }

        /* Tables */
        .admin-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .admin-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .admin-table th {
            font-weight: 500;
            padding: 15px;
        }

        .admin-table td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .admin-table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-content {
                margin-left: 0;
            }
        }

        /* Badges */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Action Buttons */
        .btn-action {
            padding: 6px 12px;
            font-size: 0.85rem;
            border-radius: 6px;
            margin: 0 2px;
        }
    </style>
</head>

<body>

    <!-- Admin Header -->
    <div class="admin-header">
        <a href="dashboard.php" class="brand">
            <i class="fas fa-cube"></i> MDB CMS
        </a>

        <div class="user-menu">
            <a href="author.php?id=<?php echo $currentUser['id']; ?>" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-home"></i> Xem trang của tôi
            </a>
            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="userDropdown"
                    data-mdb-toggle="dropdown">
                    <i class="fas fa-user-circle"></i>
                    <?php echo htmlspecialchars($currentUser['username'] ?? 'Admin'); ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="author.php?id=<?php echo $currentUser['id']; ?>"><i
                                class="fas fa-user"></i> Trang cá nhân</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="admin-sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="dashboard.php"
                    class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <?php if (!Auth::isAdmin()): ?>
                <li>
                    <a href="posts.php"
                        class="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['posts.php', 'posts_add.php', 'posts_edit.php']) ? 'active' : ''; ?>">
                        <i class="fas fa-newspaper"></i>
                        <span>Quản lý bài viết</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (Auth::isAdmin()): ?>
                <li>
                    <a href="categories.php"
                        class="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['categories.php', 'categories_add.php', 'categories_edit.php']) ? 'active' : ''; ?>">
                        <i class="fas fa-folder"></i>
                        <span>Quản lý chuyên mục</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php
            // CHỈ ADMIN MỚI THẤY MENU QUẢN LÝ NGƯỜI DÙNG
            if (Auth::isAdmin()):
                ?>
                <li>
                    <a href="users.php"
                        class="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['users.php', 'users_add.php', 'users_edit.php']) ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i>
                        <span>Quản lý người dùng</span>
                    </a>
                </li>
            <?php endif; ?>
            <li style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                <a href="index.php">
                    <i class="fas fa-home"></i>
                    <span>Trang chủ</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="admin-content">
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?> alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($flash['message']); ?>
                <button type="button" class="btn-close" data-mdb-dismiss="alert"></button>
            </div>
        <?php endif; ?>