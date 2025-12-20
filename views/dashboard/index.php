<!-- Admin Dashboard: User Management Focus -->

<!-- Breadcrumb -->
<div class="admin-breadcrumb">
    <h4 class="mb-0"><i class="fas fa-users-cog"></i> Quản trị thành viên</h4>
    <small class="text-muted">Tổng quan về người dùng và hoạt động</small>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <!-- Total Users -->
    <div class="col-md-4">
        <div class="stat-card purple">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <h3><?php echo number_format($stats['total_users']); ?></h3>
            <p>Tổng thành viên</p>
        </div>
    </div>

    <!-- New Users -->
    <div class="col-md-4">
        <div class="stat-card green">
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h3><?php echo number_format($stats['new_users']); ?></h3>
            <p>Thành viên mới (Tháng này)</p>
        </div>
    </div>

    <!-- Active Users -->
    <div class="col-md-4">
        <div class="stat-card blue">
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
            <h3><?php echo number_format($stats['active_users']); ?></h3>
            <p>Đang hoạt động</p>
        </div>
    </div>
</div>

<!-- Charts & Tables -->
<div class="row g-3">
    <!-- Charts -->
    <div class="col-lg-5">
        <!-- Role Distribution -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-chart-pie"></i> Phân bố vai trò</h5>
            </div>
            <div class="card-body">
                <canvas id="roleChart" style="max-height: 250px;"></canvas>
            </div>
        </div>

        <!-- Account Status -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-success fw-bold"><i class="fas fa-chart-doughnut"></i> Trạng thái tài khoản</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Users Table -->
    <div class="col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-info fw-bold"><i class="fas fa-clock"></i> Thành viên mới nhất</h5>
                <a href="users.php" class="btn btn-sm btn-outline-primary rounded-pill">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Thành viên</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Ngày tham gia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($stats['recent_users'])): ?>
                                <?php foreach ($stats['recent_users'] as $user): ?>
                                    <tr>
                                        <td>#<?php echo $user['id']; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2"
                                                    style="width: 35px; height: 35px; font-size: 14px;">
                                                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <span
                                                        class="fw-bold d-block text-dark"><?php echo htmlspecialchars($user['username']); ?></span>
                                                    <small
                                                        class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($user['role'] === 'admin'): ?>
                                                <span class="badge bg-danger rounded-pill">Quản trị viên</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary rounded-pill">Thành viên</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (($user['active'] ?? 0) == 1): ?>
                                                <span class="badge bg-success rounded-pill">Hoạt động</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark rounded-pill">Khóa</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted small">
                                            <i class="far fa-calendar-alt"></i>
                                            <?php echo date('d/m/Y', strtotime($user['created_at'])); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Chưa có thành viên nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare Data
    const roles = <?php echo json_encode($stats['roles'] ?? []); ?>;
    const statuses = <?php echo json_encode($stats['statuses'] ?? []); ?>;

    // Role Chart
    const roleCtx = document.getElementById('roleChart').getContext('2d');
    new Chart(roleCtx, {
        type: 'pie',
        data: {
            labels: Object.keys(roles).map(r => r === 'admin' ? 'Quản trị viên' : 'Thành viên'),
            datasets: [{
                data: Object.values(roles),
                backgroundColor: ['#ff6384', '#36a2eb'],
                borderWidth: 1
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statuses).map(s => s == 1 ? 'Hoạt động' : 'Bị khóa'),
            datasets: [{
                data: Object.values(statuses),
                backgroundColor: ['#00b894', '#bdc3c7'],
                borderWidth: 1
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
</script>