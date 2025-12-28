<?php
// Dashboard for Regular Users
?>
<!-- Breadcrumb -->
<div class="admin-breadcrumb">
    <h4 class="mb-0"><i class="fas fa-user-edit"></i> Dashboard tác giả</h4>
    <small class="text-muted">Quản lý bài viết và nội dung của bạn</small>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card blue">
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <h3><?php echo number_format($stats['my_posts_count']); ?></h3>
            <p>Bài viết của tôi</p>

        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card green">
            <div class="icon">
                <i class="fas fa-eye"></i>
            </div>
            <h3><?php echo number_format($stats['my_total_views']); ?></h3>
            <p>Tổng lượt đọc</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card orange">
            <div class="icon">
                <i class="fas fa-comments"></i>
            </div>
            <h3><?php echo number_format($stats['my_comments_count']); ?></h3>
            <p>Bình luận</p>
        </div>
    </div>
</div>

<!-- Actions & Recent Posts -->
<div class="row g-3">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary"><i class="fas fa-clock"></i> Bài viết gần đây</h5>
                <a href="posts_add.php" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Viết bài mới
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tiêu đề</th>
                                <th>Trạng thái</th>
                                <th>Ngày đăng</th>
                                <th>Lượt xem</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['posts'])): ?>
                                <?php foreach ($data['posts'] as $post): ?>
                                    <tr>
                                        <td style="max-width: 300px;">
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($post['image'])): ?>
                                                    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt=""
                                                        class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="rounded me-2 bg-light d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <a href="posts_edit.php?id=<?php echo $post['id']; ?>"
                                                        class="fw-bold text-dark text-decoration-none">
                                                        <?php echo htmlspecialchars(substr($post['title'], 0, 50)); ?>...
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (($post['status'] ?? 'publish') === 'publish'): ?>
                                                <span class="badge bg-success">Công khai</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Riêng tư</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small
                                                class="text-muted"><?php echo date('d/m/Y', strtotime($post['date'])); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-eye small text-secondary"></i>
                                                <?php echo number_format($post['views']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="posts_edit.php?id=<?php echo $post['id']; ?>"
                                                class="btn btn-sm btn-info text-white" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="post_comments.php?id=<?php echo $post['id']; ?>"
                                                class="btn btn-sm btn-light border" title="Xem bình luận">
                                                <i class="fas fa-comments text-primary"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png"
                                            alt="Empty" style="height: 150px; opacity: 0.5;">
                                        <p class="text-muted mt-3">Bạn chưa có bài viết nào.</p>
                                        <a href="posts_add.php" class="btn btn-primary">Bắt đầu viết ngay</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Pagination -->
        <?php if (isset($data['pages']) && $data['pages'] > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($data['page'] > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $data['page'] - 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $data['pages']; $i++): ?>
                        <li class="page-item <?php echo $i == $data['page'] ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($data['page'] < $data['pages']): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $data['page'] + 1; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>