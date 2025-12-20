<?php
use App\Support\Auth;

$users = $data['users'] ?? [];
$page = $data['page'] ?? 1;
$pages = $data['pages'] ?? 1;
$total = $data['total'] ?? 0;
$search = $data['keyword'] ?? $_GET['search'] ?? '';
?>

<!-- Breadcrumb -->
<div class="admin-breadcrumb">
    <h4 class="mb-0"><i class="fas fa-users"></i> Quản lý người dùng</h4>
    <small class="text-muted">
        <?php if (!empty($search)): ?>
            Tìm thấy <?php echo $total; ?> kết quả cho "<?php echo htmlspecialchars($search); ?>"
        <?php else: ?>
            Tổng số: <?php echo $total; ?> người dùng
        <?php endif; ?>
    </small>
</div>

<!-- Actions Bar -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <form class="d-flex gap-2" method="get">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Tìm kiếm theo tên hoặc email..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit" style="white-space: nowrap;">
                        <i class="fas fa-search"></i> Tìm
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="users.php" class="btn btn-outline-secondary" style="white-space: nowrap;">
                            <i class="fas fa-times"></i> Xóa
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <?php
                // CHỈ ADMIN MỚI THẤY NÚT THÊM USER
                if (Auth::isAdmin()): 
                ?>
                    <a href="users_add.php" class="btn btn-success">
                        <i class="fas fa-user-plus"></i> Thêm người dùng mới
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="admin-table">
    <?php if (empty($users)): ?>
        <div class="alert alert-info text-center" style="margin: 2rem;">
            <i class="fas fa-info-circle fa-2x mb-2"></i>
            <h5>
                <?php if (!empty($search)): ?>
                    Không tìm thấy người dùng nào với từ khóa "<?php echo htmlspecialchars($search); ?>"
                <?php else: ?>
                    Chưa có người dùng nào
                <?php endif; ?>
            </h5>
            <?php if (!empty($search)): ?>
                <a href="users.php" class="btn btn-primary mt-2">
                    <i class="fas fa-arrow-left"></i> Xem tất cả người dùng
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Tên người dùng</th>
                    <th>Email</th>
                    <th style="width: 120px;">Vai trò</th>
                    <th style="width: 120px;">Trạng thái</th>
                    <th style="width: 150px;" class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><strong>#<?php echo $user['id']; ?></strong></td>
                        <td>
                            <i class="fas fa-user-circle text-primary"></i>
                            <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                        </td>
                        <td>
                            <i class="far fa-envelope"></i>
                            <?php echo htmlspecialchars($user['email']); ?>
                        </td>
                        <td>
                            <?php if (($user['role'] ?? 'user') === 'admin'): ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-crown"></i> Admin
                                </span>
                            <?php else: ?>
                                <span class="badge bg-info">
                                    <i class="fas fa-user"></i> User
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['active']): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Active
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-ban"></i> Inactive
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php
                            // CHỈ ADMIN MỚI THẤY NÚT SỬA VÀ XÓA
                            if (Auth::isAdmin()): 
                            ?>
                                <a href="users_edit.php?id=<?php echo $user['id']; ?>" 
                                   class="btn btn-sm btn-warning btn-action" 
                                   title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="users.php?delete=<?php echo $user['id']; ?>" 
                                   class="btn btn-sm btn-danger btn-action" 
                                   onclick="return confirmDelete('Bạn có chắc muốn xóa người dùng này?')"
                                   title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-muted small">
                                    <i class="fas fa-lock"></i> Không có quyền
                                </span>
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
                $pageUrl = 'users.php?page=' . $i;
                if (!empty($search)) {
                    $pageUrl .= '&search=' . urlencode($search);
                }
                ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="<?php echo $pageUrl; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>
