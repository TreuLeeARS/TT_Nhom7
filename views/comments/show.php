<?php
use App\Support\Auth;
?>
<!-- Breadcrumb -->
<div class="admin-breadcrumb">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a
                    href="<?php echo Auth::check() ? 'dashboard.php' : 'index.php'; ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a
                    href="<?php echo Auth::check() ? 'posts.php' : 'index.php'; ?>"><?php echo Auth::check() ? 'Bài viết' : 'Trang chủ'; ?></a>
            </li>
            <li class="breadcrumb-item active">Chi tiết & Bình luận</li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Post Detail -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <?php if (!empty($post['image'])): ?>
                <img src="<?php echo htmlspecialchars($post['image']); ?>" class="card-img-top" alt="Post Image"
                    style="max-height: 400px; object-fit: cover;">
            <?php endif; ?>

            <div class="card-body">
                <h2 class="card-title mb-3"><?php echo htmlspecialchars($post['title']); ?></h2>

                <div class="d-flex align-items-center mb-4 text-muted">
                    <span class="me-3">
                        <i class="fas fa-user"></i>
                        <?php echo htmlspecialchars($post['username'] ?? 'N/A'); ?>
                    </span>
                    <span class="me-3">
                        <i class="far fa-calendar"></i>
                        <?php echo date('d/m/Y H:i', strtotime($post['date'])); ?>
                    </span>
                    <span>
                        <i class="fas fa-eye"></i>
                        <?php echo number_format($post['views'] ?? 0); ?> lượt xem
                    </span>
                </div>

                <div class="post-content">
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                </div>
            </div>

            <div class="card-footer">
                <?php if (Auth::check() && (Auth::isAdmin() || (isset(Auth::user()['id']) && Auth::user()['id'] == ($post['author'] ?? 0)))): ?>
                    <a href="posts_edit.php?id=<?php echo $post['id']; ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Sửa bài viết
                    </a>
                <?php endif; ?>
                <a href="<?php echo Auth::check() ? 'posts.php' : 'index.php'; ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="col-lg-4">
        <!-- Comments List -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-comments"></i>
                    Bình luận (<?php echo count($comments); ?>)
                </h5>
            </div>
            <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                <?php if (!empty($comments)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($comments as $comment): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between mb-2">
                                    <h6 class="mb-0">
                                        <i class="fas fa-user-circle text-primary"></i>
                                        <?php echo htmlspecialchars($comment['username']); ?>
                                    </h6>
                                    <small class="text-muted">
                                        <?php echo date('d/m H:i', strtotime($comment['created_at'])); ?>
                                    </small>
                                </div>
                                <p class="mb-0 small">
                                    <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="far fa-comments fa-3x mb-3"></i>
                        <p>Chưa có bình luận nào.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Add Comment Form -->
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="fas fa-plus-circle"></i> Thêm bình luận
                </h6>
            </div>
            <div class="card-body">
                <?php if (Auth::check()): ?>
                    <form method="post">
                        <div class="mb-3">
                            <textarea name="comment" class="form-control" rows="4" required maxlength="1000"
                                placeholder="Nhập nội dung bình luận..."></textarea>
                            <small class="text-muted">Tối đa 1000 ký tự</small>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-paper-plane"></i> Gửi bình luận
                        </button>
                    </form>
                <?php else: ?>
                    <div class="text-center py-4">
                        <p class="mb-3 text-muted">Vui lòng đăng nhập để bình luận</p>
                        <a href="index.php#login" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .post-content {
        line-height: 1.8;
        font-size: 1.05rem;
        color: #333;
    }

    .list-group-item {
        border-left: 3px solid transparent;
        transition: all 0.3s;
    }

    .list-group-item:hover {
        background: #f8f9fa;
        border-left-color: #0dcaf0;
    }
</style>