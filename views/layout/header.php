<?php

use App\Support\Auth;
use App\Support\Flash;

$flash = Flash::consume();

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'MDB Blog CMS'; ?> - Modern Blog Platform</title>
    <meta name="description" content="A modern blog platform built with PHP MVC">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="css/mdb.min.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --text-dark: #2d3748;
            --text-light: #718096;
            --bg-light: #f7fafc;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Modern Navbar */
        .modern-navbar {
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 1rem 0;
        }

        .modern-navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .brand {
            font-size: 1.75rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-menu a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-menu a:hover {
            color: var(--primary-color);
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-outline {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            background: transparent;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 5rem 2rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.5;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        /* Content Area */
        .content-area {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        /* Section Title */
        .section-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-title::after {
            content: '';
            flex: 1;
            height: 2px;
            background: linear-gradient(to right, var(--primary-color), transparent);
        }

        /* Post Cards */
        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .post-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
        }

        .post-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.2);
        }

        .post-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .post-content {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .post-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .post-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
            line-height: 1.3;
        }

        .post-excerpt {
            color: var(--text-light);
            margin-bottom: 1.5rem;
            flex: 1;
        }

        .read-more {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: gap 0.3s;
        }

        .read-more:hover {
            gap: 1rem;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin: 3rem 0;
        }

        .page-link {
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            background: white;
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 600;
            border: 2px solid var(--border-color);
            transition: all 0.3s;
        }

        .page-link:hover,
        .page-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-color: transparent;
        }

        /* Footer */
        .modern-footer {
            background: #1a202c;
            color: white;
            padding: 3rem 2rem 1.5rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .footer-section p,
        .footer-section a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
        }

        .footer-section a:hover {
            color: white;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .posts-grid {
                grid-template-columns: 1fr;
            }

            .nav-menu {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- Modern Navbar -->
    <nav class="modern-navbar">
        <div class="container">
            <?php if (Auth::check()): ?>
                <a href="author.php?id=<?php echo Auth::user()['id']; ?>" class="brand">
                    <i class="fas fa-blog"></i>
                    <span>MDB Blog</span>
                </a>
            <?php else: ?>
                <a href="index.php" class="brand">
                    <i class="fas fa-blog"></i>
                    <span>MDB Blog</span>
                </a>
            <?php endif; ?>

            <ul class="nav-menu">
                <?php if (Auth::check()): ?>
                    <li><a href="author.php?id=<?php echo Auth::user()['id']; ?>"><i class="fas fa-home"></i> Trang chủ</a>
                    </li>
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                <?php else: ?>
                    <li><a href="index.php"><i class="fas fa-home"></i> Trang chủ</a></li>
                    <li><a href="#" onclick="openRegisterModal(); return false;" class="btn-outline">Đăng ký</a></li>
                    <li><a href="#" onclick="openLoginModal(); return false;" class="btn-gradient">Đăng nhập</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <?php if ($flash): ?>
        <div class="container mt-3">
            <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        </div>
    <?php endif; ?>