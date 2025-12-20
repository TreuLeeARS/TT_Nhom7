<?php
$isEdit = !empty($category['id']);
?>

<div class="admin-breadcrumb">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="categories.php">Chuyên mục</a></li>
            <li class="breadcrumb-item active"><?php echo $isEdit ? 'Sửa chuyên mục' : 'Thêm chuyên mục'; ?></li>
        </ol>
    </nav>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-<?php echo $isEdit ? 'edit' : 'plus'; ?>"></i>
                    <?php echo $isEdit ? 'Sửa chuyên mục' : 'Thêm chuyên mục mới'; ?>
                </h5>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="form-label">Tên chuyên mục *</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="<?php echo htmlspecialchars($category['title'] ?? ''); ?>" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description"
                            rows="4"><?php echo htmlspecialchars($category['description'] ?? ''); ?></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="categories.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $isEdit ? 'Cập nhật' : 'Tạo mới'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>