<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Tổng Quan - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/admin/dashboard.css">
</head>

<body>

    <div class="sidebar">
        <div class="logo">ADMIN <span>PANEL</span></div>
        <a href="index.php?controller=admin&action=dashboard" class="active"><i class="fas fa-tachometer-alt"></i> Tổng
            quan (Dashboard)</a>
        <a href="index.php?controller=admin&action=create_post"><i class="fas fa-pen"></i> Viết bài mới</a>
        <a href="index.php?controller=admin&action=list_posts"><i class="fas fa-list"></i> Danh sách bài viết</a>
        <?php if ($role === 'admin'): ?>
            <a href="index.php?controller=admin&action=users"><i class="fas fa-users"></i> Quản lý Người dùng</a>
            <a href="index.php?controller=admin&action=banners"><i class="fas fa-ad"></i> Quản lý Banner Quảng cáo</a>
        <?php endif; ?>
        <div style="margin-top: auto;">
            <a href="index.php" target="_blank"><i class="fas fa-external-link-alt"></i> Ra ngoài Trang chủ</a>
            <a href="index.php?controller=logout" style="color: #e74c3c;"><i class="fas fa-sign-out-alt"></i> Đăng
                xuất</a>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h2>Bảng điều khiển</h2>
            <div>
                Xin chào, <strong><?= htmlspecialchars($username) ?></strong>
                <span class="badge <?= $role === 'admin' ? 'admin' : 'editor' ?>">
                    <?= $role === 'admin' ? 'Quản Trị Viên' : 'Biên Tập Viên' ?>
                </span>
            </div>
        </div>

        <div class="content-body">

            <div class="stat-grid">
                <div class="stat-card card-blue">
                    <div class="stat-info">
                        <h3><?= number_format($totalPublished) ?></h3>
                        <p>Bài Đã Xuất Bản</p>
                    </div>
                    <div class="stat-icon"><i class="fas fa-newspaper"></i></div>
                </div>

                <div class="stat-card card-yellow">
                    <div class="stat-info">
                        <h3><?= number_format($totalPending) ?></h3>
                        <p>Bài Đang Chờ Duyệt</p>
                    </div>
                    <div class="stat-icon"><i class="fas fa-file-signature"></i></div>
                </div>

                <div class="stat-card card-green">
                    <div class="stat-info">
                        <h3><?= number_format($totalUsers) ?></h3>
                        <p>Tổng Người Dùng</p>
                    </div>
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                </div>
            </div>

            <div class="recent-table-wrap">
                <h4><i class="fas fa-history"></i> Bài viết cập nhật gần đây</h4>
                <table>
                    <thead>
                        <tr>
                            <th width="50%">Tiêu đề bài viết</th>
                            <th width="20%">Chuyên mục</th>
                            <th width="15%">Trạng thái</th>
                            <th width="15%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentPosts)):
                            foreach ($recentPosts as $post): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($post['title']) ?></strong></td>
                                    <td><?= htmlspecialchars($post['cat_name']) ?></td>
                                    <td>
                                        <?php if ($post['status'] == 'published'): ?>
                                            <span class="status-badge status-published">Xuất bản</span>
                                        <?php else: ?>
                                            <span class="status-badge status-pending">Chờ duyệt</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="index.php?controller=post&slug=<?= $post['slug'] ?>" target="_blank"
                                            style="color: #3498db; text-decoration: none; font-weight: bold;"><i
                                                class="fas fa-eye"></i> Xem</a>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #888;">Chưa có bài viết nào!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div style="text-align: right; margin-top: 15px;">
                    <a href="index.php?controller=admin&action=list_posts"
                        style="color: #b71c1c; text-decoration: none; font-weight: bold;">Xem tất cả bài viết <i
                            class="fas fa-arrow-right"></i></a>
                </div>
            </div>

        </div>
    </div>

</body>

</html>