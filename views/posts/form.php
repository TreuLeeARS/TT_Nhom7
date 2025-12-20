<?php
$isEdit = !empty($post['id']);
?>

<!-- Breadcrumb -->
<div class="admin-breadcrumb">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="posts.php">Bài viết</a></li>
            <li class="breadcrumb-item active"><?php echo $title; ?></li>
        </ol>
    </nav>
</div>

<form method="post" enctype="multipart/form-data" id="postForm">
    <div class="row">
        <div class="col-lg-8">
            <!-- Main Form Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-<?php echo $isEdit ? 'edit' : 'plus'; ?>"></i>
                        <?php echo $title; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="form-label">
                            <i class="fas fa-heading"></i> Tiêu đề bài viết *
                        </label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title"
                            value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" required
                            placeholder="Nhập tiêu đề bài viết...">
                    </div>

                    <!-- Content -->
                    <div class="mb-4">
                        <label for="content" class="form-label">
                            <i class="fas fa-align-left"></i> Nội dung *
                        </label>
                        <textarea name="content" id="content" rows="15" class="form-control"
                            placeholder="Nhập nội dung bài viết..."><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>
                        <small class="text-muted">Hỗ trợ HTML và TinyMCE editor</small>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="posts.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> <?php echo $isEdit ? 'Cập nhật' : 'Tạo mới'; ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Category & Status Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-cog"></i> Thiết lập bài viết
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Category -->
                    <div class="mb-4">
                        <label for="category_id" class="form-label">Chuyên mục</label>
                        <select class="form-control" id="category_id" name="category_id">
                            <option value="">-- Chọn chuyên mục --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo (!empty($post['category_id']) && $post['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Status (Public/Private) -->
                    <div class="mb-4">
                        <label class="form-label">Chế độ hiển thị</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusPublic" value="publish" 
                                    <?php echo (empty($post['status']) || $post['status'] === 'publish') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="statusPublic">
                                    <i class="fas fa-globe text-primary"></i> Công khai
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusPrivate" value="private" 
                                    <?php echo (!empty($post['status']) && $post['status'] === 'private') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="statusPrivate">
                                    <i class="fas fa-lock text-danger"></i> Riêng tư
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Upload Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
        <div class="card-body">
            <!-- Image Preview Area -->
            <div id="imagePreviewContainer" class="text-center mb-3">
                <?php if (!empty($post['image'])): ?>
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Current Image" id="previewImg"
                        class="img-fluid rounded" style="max-height: 250px; object-fit: cover;">
                    <p class="text-muted small mt-2">Hình ảnh hiện tại</p>
                <?php else: ?>
                    <div id="placeholderImg" class="p-4" style="background: #f8f9fa; border-radius: 8px;">
                        <i class="fas fa-image fa-3x text-muted"></i>
                        <p class="text-muted small mt-2 mb-0">Chưa có hình ảnh</p>
                    </div>
                    <img id="previewImg" class="img-fluid rounded"
                        style="max-height: 250px; object-fit: cover; display: none;">
                <?php endif; ?>
            </div>

            <label for="image" class="form-label">
                <i class="fas fa-upload"></i> Upload hình mới
            </label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*"
                onchange="previewImage(this)">
            <small class="text-muted">Định dạng: JPG, PNG, GIF. Tối đa 5MB</small>
        </div>
    </div>

    <!-- Date Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning">
            <h6 class="mb-0">
                <i class="far fa-calendar"></i> Ngày đăng
            </h6>
        </div>
        <div class="card-body">
            <input type="date" class="form-control" id="date" name="date"
                value="<?php echo htmlspecialchars($post['date'] ?? date('Y-m-d')); ?>">
            <small class="text-muted">Mặc định là ngày hôm nay</small>
        </div>
    </div>
    </div>
    </div>
</form>

<!-- TinyMCE Script -->
<script src="js/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#content',
        height: 400,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        content_style: 'body { font-family:Roboto,Arial,sans-serif; font-size:14px }'
    });

    // Preview image before upload
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                // Ẩn placeholder nếu có
                const placeholder = document.getElementById('placeholderImg');
                if (placeholder) {
                    placeholder.style.display = 'none';
                }

                // Hiển thị preview image
                const previewImg = document.getElementById('previewImg');
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Form submission handler
    document.getElementById('postForm').addEventListener('submit', function (e) {
        // Sync TinyMCE content to textarea
        tinymce.triggerSave();

        // Validate
        const title = document.getElementById('title').value.trim();
        const content = tinymce.get('content').getContent();

        if (!title) {
            e.preventDefault();
            alert('Vui lòng nhập tiêu đề bài viết!');
            return false;
        }

        if (!content || content.trim() === '') {
            e.preventDefault();
            alert('Vui lòng nhập nội dung bài viết!');
            return false;
        }

        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';

        return true;
    });
</script>