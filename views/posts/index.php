<?php
use App\Support\Auth;

$posts = $data['posts'] ?? [];
$page = $data['page'] ?? 1;
$pages = $data['pages'] ?? 1;
$total = $data['total'] ?? 0;
$search = $data['keyword'] ?? $_GET['search'] ?? '';
?>

<!-- Breadcrumb -->
<div class="admin-breadcrumb">
    <h4 class="mb-0"><i class="fas fa-newspaper"></i> Quản lý bài viết</h4>
    <small class="text-muted">
        <?php if (!empty($search)): ?>
            Tìm thấy <?php echo $total; ?> kết quả cho "<?php echo htmlspecialchars($search); ?>"
        <?php else: ?>
            Tổng số: <?php echo $total; ?> bài viết
        <?php endif; ?>
    </small>
</div>

<!-- Actions Bar -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <form class="d-flex gap-2" method="get">
                    <input type="text" name="search" class="form-control"
                        placeholder="Tìm kiếm theo tiêu đề hoặc nội dung..."
                        value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit" style="white-space: nowrap;">
                        <i class="fas fa-search"></i> Tìm
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="posts.php" class="btn btn-outline-secondary" style="white-space: nowrap;">
                            <i class="fas fa-times"></i> Xóa
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <a href="posts_add.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm bài viết mới
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Posts Table -->
<div class="admin-table">
    <?php if (empty($posts)): ?>
        <div class="alert alert-info text-center" style="margin: 2rem;">
            <i class="fas fa-info-circle fa-2x mb-2"></i>
            <h5>
                <?php if (!empty($search)): ?>
                    Không tìm thấy bài viết nào với từ khóa "<?php echo htmlspecialchars($search); ?>"
                <?php else: ?>
                    Chưa có bài viết nào
                <?php endif; ?>
            </h5>
            <?php if (!empty($search)): ?>
                <a href="posts.php" class="btn btn-primary mt-2">
                    <i class="fas fa-arrow-left"></i> Xem tất cả bài viết
                </a>
            <?php else: ?>
                <a href="posts_add.php" class="btn btn-primary mt-2">
                    <i class="fas fa-plus"></i> Tạo bài viết đầu tiên
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="width: 80px;">Hình ảnh</th>
                    <th>Tiêu đề</th>
                    <th style="width: 150px;">Tác giả</th>
                    <th style="width: 120px;">Ngày đăng</th>
                    <th style="width: 100px;">Lượt xem</th>
                    <th style="width: 180px;" class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><strong>#<?php echo $post['id']; ?></strong></td>
                        <td>
                            <?php if (!empty($post['image'])): ?>
                                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image"
                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            <?php else: ?>
                                <div
                                    style="width: 60px; height: 60px; background: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($post['title']); ?></strong>
                            <br>
                            <small class="text-muted">
                                <?php echo htmlspecialchars(substr(strip_tags($post['content']), 0, 80)); ?>...
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($post['username'] ?? 'N/A'); ?>
                            </span>
                        </td>
                        <td>
                            <small>
                                <i class="far fa-calendar"></i>
                                <?php echo date('d/m/Y', strtotime($post['date'])); ?>
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                <i class="fas fa-eye"></i> <?php echo number_format($post['views'] ?? 0); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php
                            $currentUser = Auth::user();
                            $isOwner = $currentUser && $post['author'] == $currentUser['id'];
                            $isAdmin = Auth::isAdmin();

                            // ADMIN HOẶC TÁC GIẢ MỚI THẤY NÚT SỬA
                            if ($isAdmin || $isOwner):
                                ?>
                                <a href="posts_edit.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-warning btn-action"
                                    title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                            <?php endif; ?>

                            <a href="post_comments.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-info btn-action"
                                title="Xem & Comments">
                                <i class="fas fa-comments"></i>
                            </a>

                            <?php
                            // ADMIN HOẶC TÁC GIẢ THẤY NÚT XÓA
                            if ($isAdmin || $isOwner):
                                ?>
                                <a href="posts.php?delete=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger btn-action"
                                    onclick="return confirmDelete('Bạn có chắc muốn xóa bài viết này?')" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if ($pages > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <?php
                $pageUrl = 'posts.php?page=' . $i;
                if (!empty($search)) {
                    $pageUrl .= '&search=' . urlencode($search);
                }
                ?>
                <li class="page-item <?php if ($i == $page)
                    echo 'active'; ?>">
                    <a class="page-link" href="<?php echo $pageUrl; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>