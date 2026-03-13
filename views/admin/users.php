<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản Lý Người Dùng - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/admin/users.css">
</head>

<body>
    <div class="sidebar">
        <div class="logo">ADMIN <span>PANEL</span></div>
        <a href="index.php?controller=admin&action=dashboard"><i class="fas fa-tachometer-alt"></i> Tổng quan</a>
        <a href="index.php?controller=admin&action=create_post"><i class="fas fa-pen"></i> Viết bài mới</a>
        <a href="index.php?controller=admin&action=list_posts"><i class="fas fa-list"></i> Danh sách bài viết</a>
        <a href="index.php?controller=admin&action=users" class="active"><i class="fas fa-users"></i> Quản lý Người
            dùng</a>
        <a href="index.php?controller=admin&action=banners"><i class="fas fa-ad"></i> Quản lý Banner Quảng cáo</a>

        <div style="margin-top: auto;">
            <a href="index.php" target="_blank"><i class="fas fa-external-link-alt"></i> Ra ngoài Trang chủ</a>
            <a href="index.php?controller=logout" style="color: #e74c3c;"><i class="fas fa-sign-out-alt"></i> Đăng
                xuất</a>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h2>Quản Lý Người Dùng</h2>
            <div>Xin chào, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></div>
        </div>

        <div class="content-body">
            <a href="index.php?controller=admin&action=create_user" class="btn-add"><i class="fas fa-plus"></i> Thêm
                Người Dùng Mới</a>

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
                        <?php if (!empty($users)):
                            foreach ($users as $u): ?>
                                <tr>
                                    <td>#<?= $u['id'] ?></td>
                                    <td>
                                        <div class="user-info-td">
                                            <img src="<?= !empty($u['avatar']) ? $u['avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode($u['username']) . '&background=random' ?>"
                                                class="avatar-img">
                                            <strong><?= htmlspecialchars($u['username']) ?></strong>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td>
                                        <span
                                            class="role-badge <?= $u['role'] == 'admin' ? 'role-admin' : ($u['role'] == 'bien_tap_vien' ? 'role-editor' : 'role-user') ?>">
                                            <?= strtoupper($u['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                                    <td>
                                        <a href="index.php?controller=admin&action=edit_user&id=<?= $u['id'] ?>"
                                            class="btn-action btn-edit" title="Sửa"><i class="fas fa-edit"></i></a>

                                        <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                                            <a href="index.php?controller=admin&action=delete_user&id=<?= $u['id'] ?>"
                                                class="btn-action btn-delete" title="Xóa"
                                                onclick="return confirm('Bạn có chắc chắn muốn XÓA VĨNH VIỄN người dùng này?');"><i
                                                    class="fas fa-trash"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px;">Chưa có người dùng nào!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>