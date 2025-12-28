<?php
use App\Support\Auth;

// Lay du lieu tu controller
$posts = $data['posts'] ?? [];
// $categories duoc extract truc tiep tu controller, khong can lay tu $data
// $categories = $categories ?? [];
$currentCategory = $data['currentCategory'] ?? null;
$page = $data['page'] ?? 1;
$pages = $data['pages'] ?? 1;

// Kiem tra co posts khong
$has_posts = !empty($posts);
?>


<!-- Hero Section -->
<section class="hero" <?php if (isset($author))
    echo 'style="background: linear-gradient(135deg, #2c3e50 0%, #4ca1af 100%);"'; ?>>
    <div class="hero-content">
        <?php if (isset($author)): ?>
            <h1><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($author['username']); ?></h1>
            <p>Danh sách các bài viết được chia sẻ bởi tác giả.</p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="index.php" class="btn-outline" style="border-color: white; color: white;">
                    <i class="fas fa-arrow-left"></i> Quay lại trang chủ
                </a>
            </div>
        <?php else: ?>
            <h1>Chào mừng đến với MDB Blog</h1>
            <p>Khám phá những bài viết hay, chia sẻ kiến thức và kết nối với cộng đồng đam mê công nghệ</p>
            <?php if (!Auth::check()): ?>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="#" onclick="openRegisterModal(); return false;" class="btn-gradient"
                        style="font-size: 1.1rem; padding: 0.8rem 2rem;">
                        <i class="fas fa-rocket"></i> Bắt đầu ngay
                    </a>
                    <a href="#posts" class="btn-outline"
                        style="font-size: 1.1rem; padding: 0.8rem 2rem; background: rgba(255,255,255,0.2); border-color: white; color: white;">
                        <i class="fas fa-book-open"></i> Khám phá
                    </a>
                </div>
            <?php else: ?>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="dashboard.php" class="btn-gradient" style="font-size: 1.1rem; padding: 0.8rem 2rem;">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="posts_add.php" class="btn-outline"
                        style="font-size: 1.1rem; padding: 0.8rem 2rem; background: rgba(255,255,255,0.2); border-color: white; color: white;">
                        <i class="fas fa-plus"></i> Tạo bài viết
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Main Content -->
<div class="content-area" id="posts">

    <!-- Category Filter -->
    <?php if (!empty($categories)): ?>
        <div class="category-nav mb-4 ps-1"
            style="overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch; padding-bottom: 5px;">
            <a href="index.php"
                class="btn <?php echo empty($currentCategory) ? 'btn-primary' : 'btn-outline-primary'; ?> rounded-pill me-2">
                Tất cả
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="index.php?category=<?php echo $cat['slug']; ?>#posts"
                    class="btn <?php echo $currentCategory === $cat['slug'] ? 'btn-primary' : 'btn-outline-primary'; ?> rounded-pill me-2">
                    <?php echo htmlspecialchars($cat['title']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <style>
            .category-nav::-webkit-scrollbar {
                height: 4px;
            }

            .category-nav::-webkit-scrollbar-thumb {
                background: #ccc;
                border-radius: 4px;
            }

            /* Helper for buttons if bootstrap not fully loaded or specific override needed */
            .btn-outline-primary {
                border-color: #1266f1;
                color: #1266f1;
            }

            .btn-outline-primary:hover {
                background-color: #1266f1;
                color: #fff;
            }

            .btn-primary {
                background-color: #1266f1;
                border-color: #1266f1;
                color: #fff;
            }
        </style>
    <?php endif; ?>

    <!-- Section Title -->
    <h2 class="section-title">
        <i class="fas fa-fire"></i> Bài viết mới nhất
    </h2>

    <!-- Posts Grid -->
    <?php if (!empty($posts)): ?>
        <div class="posts-grid">
            <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <!-- Post Image -->
                    <!-- Post Image -->
                    <?php if (!empty($post['image'])): ?>
                        <div style="position: relative;">
                            <img src="<?php echo htmlspecialchars($post['image']); ?>"
                                alt="<?php echo htmlspecialchars($post['title']); ?>" class="post-image" style="background: none;">
                            <?php if (!empty($post['category_name'])): ?>
                                <span class="badge bg-primary"
                                    style="position: absolute; top: 10px; right: 10px; font-size: 0.8rem; opacity: 0.9;">
                                    <?php echo htmlspecialchars($post['category_name']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="post-image" style="position: relative;">
                            <i class="fas fa-newspaper"></i>
                            <?php if (!empty($post['category_name'])): ?>
                                <span class="badge bg-primary"
                                    style="position: absolute; top: 10px; right: 10px; font-size: 0.8rem; opacity: 0.9;">
                                    <?php echo htmlspecialchars($post['category_name']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Post Content -->
                    <div class="post-content">
                        <!-- Meta Info -->
                        <div class="post-meta">
                            <span>
                                <i class="fas fa-user"></i>
                                <a href="author.php?id=<?php echo $post['author']; ?>"
                                    style="color: inherit; text-decoration: none; border-bottom: 1px dotted;">
                                    <?php echo htmlspecialchars($post['username'] ?? 'Admin'); ?>
                                </a>
                            </span>
                            <span>
                                <i class="far fa-calendar"></i>
                                <?php echo date('d/m/Y', strtotime($post['date'])); ?>
                            </span>
                            <?php if (isset($post['views'])): ?>
                                <span>
                                    <i class="fas fa-eye"></i>
                                    <?php echo number_format($post['views']); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Title -->
                        <h3 class="post-title">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </h3>

                        <!-- Excerpt -->
                        <p class="post-excerpt">
                            <?php
                            $content = strip_tags($post['content']);
                            echo htmlspecialchars(mb_substr($content, 0, 150)) . '...';
                            ?>
                        </p>

                        <!-- Read More -->
                        <a href="post_comments.php?id=<?php echo $post['id']; ?>" class="read-more">
                            Đọc tiếp <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($pages > 1): ?>
            <div class="pagination">
                <?php
                // Helper to build pagination URL
                $buildUrl = function ($pageNum) {
                    $params = $_GET;
                    $params['page'] = $pageNum;
                    return '?' . http_build_query($params);
                };
                ?>

                <?php if ($page > 1): ?>
                    <a href="<?php echo $buildUrl($page - 1); ?>" class="page-link">
                        <i class="fas fa-chevron-left"></i> Trước
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <a href="<?php echo $buildUrl($i); ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $pages): ?>
                    <a href="<?php echo $buildUrl($page + 1); ?>" class="page-link">
                        Sau <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Empty State -->
        <div
            style="text-align: center; padding: 5rem 2rem; background: white; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <i class="fas fa-inbox" style="font-size: 5rem; color: var(--text-light); margin-bottom: 2rem;"></i>
            <h3 style="color: var(--text-dark); margin-bottom: 1rem;">Chưa có bài viết nào</h3>
            <p style="color: var(--text-light); margin-bottom: 2rem;">
                Hãy quay lại sau hoặc đăng nhập để tạo bài viết đầu tiên!
            </p>
            <?php if (Auth::check()): ?>
                <a href="posts_add.php" class="btn-gradient">
                    <i class="fas fa-plus"></i> Tạo bài viết
                </a>
            <?php else: ?>
                <a href="#" onclick="openRegisterModal(); return false;" class="btn-gradient">
                    <i class="fas fa-user-plus"></i> Đăng ký ngay
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>


<script>
    function showLoginPrompt() {
        if (!confirm('Bạn cần đăng nhập để đọc bài viết. Đăng nhập ngay?')) {
            return false;
        }
    }

    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.post-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
</script>