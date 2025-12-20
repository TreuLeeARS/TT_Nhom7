<?php
$isEdit = !empty($user['id']);
?>

<!-- Breadcrumb -->
<div class="admin-breadcrumb">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="users.php">Người dùng</a></li>
            <li class="breadcrumb-item active"><?php echo $title; ?></li>
        </ol>
    </nav>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Main Form Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-<?php echo $isEdit ? 'user-edit' : 'user-plus'; ?>"></i>
                    <?php echo $title; ?>
                </h5>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <!-- Username -->
                        <div class="col-md-6 mb-4">
                            <label for="username" class="form-label">
                                <i class="fas fa-user"></i> Tên người dùng *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="username" 
                                   name="username" 
                                   value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" 
                                   required
                                   placeholder="Nhập tên người dùng...">
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email *
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" 
                                   required
                                   placeholder="Nhập email...">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6 mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-key"></i> Mật khẩu <?php if (!$isEdit) echo '*'; ?>
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   <?php if (!$isEdit) echo 'required'; ?>
                                   placeholder="<?php echo $isEdit ? 'Để trống nếu không đổi' : 'Nhập mật khẩu'; ?>">
                            <?php if (!$isEdit): ?>
                                <small class="text-muted">Tối thiểu 6 ký tự</small>
                            <?php else: ?>
                                <small class="text-muted">Chỉ nhập nếu muốn đổi mật khẩu</small>
                            <?php endif; ?>
                        </div>

                        <!-- Role -->
                        <div class="col-md-6 mb-4">
                            <label for="role" class="form-label">
                                <i class="fas fa-user-tag"></i> Vai trò
                            </label>
                            <select class="form-select" id="role" name="role">
                                <option value="user" <?php if (($user['role'] ?? 'user') === 'user') echo 'selected'; ?>>
                                    <i class="fas fa-user"></i> User
                                </option>
                                <option value="admin" <?php if (($user['role'] ?? '') === 'admin') echo 'selected'; ?>>
                                    <i class="fas fa-crown"></i> Admin
                                </option>
                            </select>
                            <small class="text-muted">Admin có toàn quyền quản trị</small>
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="active" 
                                   name="active" 
                                   <?php if ($user['active'] ?? 1) echo 'checked'; ?>>
                            <label class="form-check-label" for="active">
                                <i class="fas fa-check-circle text-success"></i> Tài khoản hoạt động
                            </label>
                            <small class="text-muted d-block">Tắt để vô hiệu hóa tài khoản</small>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <?php if ($isEdit): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Lưu ý:</strong> Để trống trường mật khẩu nếu không muốn thay đổi.
                        </div>
                    <?php endif; ?>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="users.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i> <?php echo $isEdit ? 'Cập nhật' : 'Tạo mới'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Additional Info (if editing) -->
        <?php if ($isEdit): ?>
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin tài khoản</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <strong>ID:</strong> #<?php echo $user['id']; ?>
                        </li>
                        <li class="mb-2">
                            <strong>Ngày tạo:</strong> 
                            <?php echo isset($user['created_at']) ? date('d/m/Y H:i', strtotime($user['created_at'])) : 'N/A'; ?>
                        </li>
                        <li>
                            <strong>Trạng thái:</strong> 
                            <?php if ($user['active']): ?>
                                <span class="badge bg-success">Đang hoạt động</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Không hoạt động</span>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Validation before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    
    <?php if (!$isEdit): ?>
        if (password.length < 6) {
            e.preventDefault();
            alert('Mật khẩu phải có ít nhất 6 ký tự!');
            return false;
        }
    <?php endif; ?>
});
</script>
