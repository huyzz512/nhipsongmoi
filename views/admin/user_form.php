<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?> - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/admin/user_form.css">
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
        <div style="margin-top: auto;"><a href="index.php" target="_blank"><i class="fas fa-external-link-alt"></i> Ra
                ngoài Trang chủ</a></div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h2>Quản Lý Người Dùng</h2>
            <a href="index.php?controller=admin&action=users" style="text-decoration: none; color: #555;"><i
                    class="fas fa-arrow-left"></i> Quay lại danh sách</a>
        </div>

        <div class="content-body">
            <div class="form-wrap">
                <h3 style="margin-bottom: 20px; border-bottom: 2px solid #b71c1c; padding-bottom: 10px;"><?= $title ?>
                </h3>

                <?php if (!empty($error)): ?>
                    <div class="alert"><?= $error ?></div> <?php endif; ?>

                <form action="index.php?controller=admin&action=<?= $action_form ?>" method="POST">
                    <div class="form-group">
                        <label>Tên hiển thị (*)</label>
                        <input type="text" name="username" class="form-control"
                            value="<?= htmlspecialchars($u['username']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email (*)</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($u['email']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Mật khẩu
                            <?= $action_form == 'create_user' ? '(*)' : '(Để trống nếu không muốn đổi)' ?></label>
                        <input type="password" name="password" class="form-control" <?= $action_form == 'create_user' ? 'required' : '' ?>>
                    </div>

                    <div class="form-group">
                        <label>Phân quyền (*)</label>
                        <select name="role" class="form-control" required <?= (isset($_GET['id']) && $_GET['id'] == $_SESSION['user_id']) ? 'disabled' : '' ?>>
                            <option value="user" <?= $u['role'] == 'user' ? 'selected' : '' ?>>Độc giả (User)</option>
                            <option value="bien_tap_vien" <?= $u['role'] == 'bien_tap_vien' ? 'selected' : '' ?>>Biên tập
                                viên</option>
                            <option value="admin" <?= $u['role'] == 'admin' ? 'selected' : '' ?>>Quản trị viên (Admin)
                            </option>
                        </select>
                        <?php if (isset($_GET['id']) && $_GET['id'] == $_SESSION['user_id']): ?>
                            <input type="hidden" name="role" value="admin">
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Lưu Dữ Liệu</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>