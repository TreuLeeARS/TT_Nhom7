<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">Quản lý chuyên mục</h1>
    <a href="categories_add.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm chuyên mục
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered admin-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên chuyên mục</th>
                        <th>Slug</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="5" class="text-center">Chưa có chuyên mục nào</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><?php echo $cat['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($cat['title']); ?></strong></td>
                                <td><code><?php echo htmlspecialchars($cat['slug']); ?></code></td>
                                <td><?php echo htmlspecialchars($cat['description']); ?></td>
                                <td>
                                    <a href="categories_edit.php?id=<?php echo $cat['id']; ?>"
                                        class="btn btn-primary btn-action">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="categories.php?delete=<?php echo $cat['id']; ?>" class="btn btn-danger btn-action"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa chuyên mục này? Các bài viết thuộc chuyên mục này sẽ bị mất danh mục.');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>