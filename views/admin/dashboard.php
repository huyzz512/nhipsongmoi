<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tổng Quan - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset cơ bản và Layout */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { display: flex; background-color: #f4f6f9; color: #333; height: 100vh; overflow: hidden; }
        
        /* Sidebar Menu */
        .sidebar { width: 260px; background-color: #343a40; color: #fff; height: 100vh; display: flex; flex-direction: column; }
        .sidebar .logo { padding: 20px; text-align: center; background: #2c3136; border-bottom: 1px solid #4b545c; font-size: 1.2rem; font-weight: bold; }
        .sidebar .logo span { color: #b71c1c; }
        .sidebar a { display: block; padding: 15px 20px; color: #c2c7d0; text-decoration: none; border-bottom: 1px solid #40464d; transition: 0.2s; }
        .sidebar a:hover, .sidebar a.active { background-color: #494e53; color: #fff; }
        .sidebar a i { width: 25px; text-align: center; margin-right: 10px; }
        
        /* Main Content */
        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar { background: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .content-body { padding: 30px; }
        
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; color: #fff; }
        .badge.admin { background-color: #e74c3c; }
        .badge.editor { background-color: #3498db; }

        /* ================= CSS CHO DASHBOARD STATS ================= */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: space-between; border-left: 5px solid #ccc; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        
        .stat-info h3 { font-size: 1.8rem; margin-bottom: 5px; color: #333; }
        .stat-info p { color: #888; font-weight: bold; text-transform: uppercase; font-size: 0.85rem; }
        .stat-icon { font-size: 2.5rem; opacity: 0.3; }

        /* Màu sắc cho từng thẻ */
        .card-blue { border-left-color: #3498db; } .card-blue .stat-icon { color: #3498db; }
        .card-yellow { border-left-color: #f1c40f; } .card-yellow .stat-icon { color: #f1c40f; }
        .card-green { border-left-color: #2ecc71; } .card-green .stat-icon { color: #2ecc71; }

        /* Bảng bài viết gần đây */
        .recent-table-wrap { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .recent-table-wrap h4 { margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #eee; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        th { color: #666; font-size: 0.9rem; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
        .status-published { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo">ADMIN <span>PANEL</span></div>
        <a href="index.php?controller=admin&action=dashboard" class="active"><i class="fas fa-tachometer-alt"></i> Tổng quan (Dashboard)</a>
        <a href="index.php?controller=admin&action=create_post"><i class="fas fa-pen"></i> Viết bài mới</a>
        <a href="index.php?controller=admin&action=list_posts"><i class="fas fa-list"></i> Danh sách bài viết</a>
        <?php if($role === 'admin'): ?>
            <a href="index.php?controller=admin&action=users"><i class="fas fa-users"></i> Quản lý Người dùng</a>
            <a href="index.php?controller=admin&action=banners"><i class="fas fa-ad"></i> Quản lý Banner Quảng cáo</a>
        <?php endif; ?>
        <div style="margin-top: auto;">
            <a href="index.php" target="_blank"><i class="fas fa-external-link-alt"></i> Ra ngoài Trang chủ</a>
            <a href="index.php?controller=logout" style="color: #e74c3c;"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
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
                        <?php if(!empty($recentPosts)): foreach($recentPosts as $post): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($post['title']) ?></strong></td>
                            <td><?= htmlspecialchars($post['cat_name']) ?></td>
                            <td>
                                <?php if($post['status'] == 'published'): ?>
                                    <span class="status-badge status-published">Xuất bản</span>
                                <?php else: ?>
                                    <span class="status-badge status-pending">Chờ duyệt</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?controller=post&slug=<?= $post['slug'] ?>" target="_blank" style="color: #3498db; text-decoration: none; font-weight: bold;"><i class="fas fa-eye"></i> Xem</a>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="4" style="text-align: center; color: #888;">Chưa có bài viết nào!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <div style="text-align: right; margin-top: 15px;">
                    <a href="index.php?controller=admin&action=list_posts" style="color: #b71c1c; text-decoration: none; font-weight: bold;">Xem tất cả bài viết <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

        </div>
    </div>

</body>
</html>