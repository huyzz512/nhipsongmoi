<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Người Dùng - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ... (Giữ nguyên phần CSS cũ của file users.php) ... */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { display: flex; background-color: #f4f6f9; color: #333; height: 100vh; overflow: hidden; }
        .sidebar { width: 260px; background-color: #343a40; color: #fff; height: 100vh; display: flex; flex-direction: column; }
        .sidebar .logo { padding: 20px; text-align: center; background: #2c3136; border-bottom: 1px solid #4b545c; font-size: 1.2rem; font-weight: bold; }
        .sidebar a { display: block; padding: 15px 20px; color: #c2c7d0; text-decoration: none; border-bottom: 1px solid #40464d; }
        .sidebar a:hover, .sidebar a.active { background-color: #494e53; color: #fff; }
        .sidebar a i { width: 25px; text-align: center; margin-right: 10px; }
        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar { background: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .content-body { padding: 30px; }
        .table-wrap { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #eee; text-align: left; vertical-align: middle; }
        th { background-color: #f8f9fa; color: #555; font-weight: bold; text-transform: uppercase; font-size: 0.85rem; }
        tr:hover { background-color: #f1f3f5; }
        .user-info-td { display: flex; align-items: center; gap: 10px; }
        .avatar-img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd; }
        .role-badge { padding: 6px 12px; border-radius: 20px; font-weight: bold; font-size: 0.85rem; }
        .role-admin { background: #ffebee; color: #c62828; }
        .role-editor { background: #e3f2fd; color: #1565c0; }
        .role-user { background: #f5f5f5; color: #616161; }
        .btn-action { padding: 6px 12px; border-radius: 4px; color: #fff; text-decoration: none; font-size: 0.85rem; margin-right: 5px; display: inline-block; }
        .btn-edit { background-color: #f39c12; } .btn-edit:hover { background-color: #e67e22; }
        .btn-delete { background-color: #e74c3c; } .btn-delete:hover { background-color: #c0392b; }
        .btn-add { background: #b71c1c; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; margin-bottom: 20px;}
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">ADMIN <span>PANEL</span></div>
        <a href="index.php?controller=admin&action=dashboard"><i class="fas fa-tachometer-alt"></i> Tổng quan</a>
        <a href="index.php?controller=admin&action=create_post"><i class="fas fa-pen"></i> Viết bài mới</a>
        <a href="index.php?controller=admin&action=list_posts"><i class="fas fa-list"></i> Danh sách bài viết</a>
        <a href="index.php?controller=admin&action=users" class="active"><i class="fas fa-users"></i> Quản lý Người dùng</a>
        <a href="index.php?controller=admin&action=banners"><i class="fas fa-ad"></i> Quản lý Banner Quảng cáo</a>

                <div style="margin-top: auto;">
            <a href="index.php" target="_blank"><i class="fas fa-external-link-alt"></i> Ra ngoài Trang chủ</a>
            <a href="index.php?controller=logout" style="color: #e74c3c;"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h2>Quản Lý Người Dùng</h2>
            <div>Xin chào, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></div>
        </div>
        
        <div class="content-body">
            <a href="index.php?controller=admin&action=create_user" class="btn-add"><i class="fas fa-plus"></i> Thêm Người Dùng Mới</a>
            
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="30%">Người dùng</th>
                            <th width="25%">Email</th>
                            <th width="15%">Vai trò</th>
                            <th width="15%">Ngày ĐK</th>
                            <th width="10%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($users)): foreach($users as $u): ?>
                        <tr>
                            <td>#<?= $u['id'] ?></td>
                            <td>
                                <div class="user-info-td">
                                    <img src="<?= !empty($u['avatar']) ? $u['avatar'] : 'https://ui-avatars.com/api/?name='.urlencode($u['username']).'&background=random' ?>" class="avatar-img">
                                    <strong><?= htmlspecialchars($u['username']) ?></strong>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <span class="role-badge <?= $u['role'] == 'admin' ? 'role-admin' : ($u['role'] == 'bien_tap_vien' ? 'role-editor' : 'role-user') ?>">
                                    <?= strtoupper($u['role']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                            <td>
                                <a href="index.php?controller=admin&action=edit_user&id=<?= $u['id'] ?>" class="btn-action btn-edit" title="Sửa"><i class="fas fa-edit"></i></a>
                                
                                <?php if($u['id'] !== $_SESSION['user_id']): ?>
                                    <a href="index.php?controller=admin&action=delete_user&id=<?= $u['id'] ?>" class="btn-action btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn XÓA VĨNH VIỄN người dùng này?');"><i class="fas fa-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="6" style="text-align: center; padding: 30px;">Chưa có người dùng nào!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>