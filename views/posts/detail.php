<?php
use App\Support\Auth;
?>

<!-- Minimal Breadcrumb for Public -->
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <?php if (Auth::check()): ?>
                <li class="breadcrumb-item"><a href="author.php?id=<?php echo Auth::user()['id']; ?>">Trang cá nhân</a></li>
            <?php else: ?>
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
            <?php endif; ?>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($post['title']); ?></li>
        </ol>
    </nav>
</div>

<div class="container mb-5">
    <div class="row">
        <!-- Post Detail -->
        <div class="col-12">
            <article class="blog-post">
                <!-- 1. Title -->
                <h1 class="mb-3 fw-bold"><?php echo htmlspecialchars($post['title']); ?></h1>

                <!-- 2. Meta Info -->
                <div class="mb-4 text-muted d-flex align-items-center">
                    <span>
                        <i class="fas fa-user-circle me-1"></i>
                        <strong><?php echo htmlspecialchars($post['username'] ?? 'Unknown'); ?></strong>
                    </span>
                    <span class="mx-3">|</span>
                    <span>
                        <i class="far fa-calendar-alt me-1"></i> <?php echo date('d/m/Y', strtotime($post['date'])); ?>
                    </span>
                </div>

                <!-- 3. Image (Resized) -->
                <?php if (!empty($post['image'])): ?>
                    <div class="text-center mb-4">
                        <img src="<?php echo htmlspecialchars($post['image']); ?>" class="img-fluid rounded shadow-sm"
                            alt="<?php echo htmlspecialchars($post['title']); ?>"
                            style="object-fit: cover; max-height: 400px; width: auto; max-width: 100%;">
                    </div>
                <?php endif; ?>

                <!-- 4. Content -->
                <div class="post-content" style="font-size: 1.1rem; line-height: 1.8; color: #2d3748;">
                    <?php echo $post['content']; ?>
                </div>
            </article>

            <!-- Actions (Edit for Admin/Owner) -->
            <?php if (Auth::check() && (Auth::isAdmin() || (isset(Auth::user()['id']) && Auth::user()['id'] == ($post['author'] ?? 0)))): ?>
                <div class="mt-4 p-3 bg-light rounded">
                    <a href="posts_edit.php?id=<?php echo $post['id']; ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Sửa bài viết
                    </a>
                </div>
            <?php endif; ?>

            <hr class="my-5">

            <!-- Comments Section -->
            <div class="comments-section" id="comments">
                <h3 class="mb-4"><i class="far fa-comments"></i> Bình luận (<?php echo count($comments ?? []); ?>)</h3>

                <!-- Comment List -->
                <?php if (!empty($comments)): ?>
                    <div class="comment-list mb-5">
                        <?php foreach ($comments as $comment): ?>
                            <div class="card mb-3 border-0 shadow-sm" style="background: #f8f9fa;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>
                                            <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                                            <span class="text-muted small ms-2">
                                                <?php echo date('d/m/Y H:i', strtotime($comment['created_at'])); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-secondary">
                                        <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-light text-center mb-5">
                        Chưa có bình luận nào. Hãy là người đầu tiên bình luận!
                    </div>
                <?php endif; ?>

                <!-- Comment Form -->
                <div class="comment-form-card card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">Viết bình luận của bạn</h5>
                        <?php if (Auth::check()): ?>
                            <form action="post_comments.php?id=<?php echo $post['id']; ?>" method="POST">
                                <div class="mb-3">
                                    <textarea name="comment" class="form-control" rows="3"
                                        placeholder="Chia sẻ suy nghĩ của bạn..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-paper-plane"></i> Gửi bình luận
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="text-center py-4 bg-light rounded">
                                <p class="mb-3">Vui lòng đăng nhập để bình luận bài viết này.</p>
                                <a href="#" onclick="openLoginModal(); return false;" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt"></i> Đăng nhập ngay
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <?php if (Auth::check()): ?>
                    <a href="author.php?id=<?php echo Auth::user()['id']; ?>" class="text-decoration-none text-muted">
                        <i class="fas fa-arrow-left"></i> Quay về trang cá nhân
                    </a>
                <?php else: ?>
                    <a href="index.php" class="text-decoration-none text-muted">
                        <i class="fas fa-arrow-left"></i> Quay về trang chủ
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>