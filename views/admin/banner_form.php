<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?> - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/admin/banner_form.css">
</head>

<body>
    <div class="sidebar">
        <div class="logo">ADMIN <span>PANEL</span></div>
        <a href="index.php?controller=admin&action=dashboard"><i class="fas fa-tachometer-alt"></i> Tổng quan</a>
        <a href="index.php?controller=admin&action=create_post"><i class="fas fa-pen"></i> Viết bài mới</a>
        <a href="index.php?controller=admin&action=list_posts"><i class="fas fa-list"></i> Danh sách bài viết</a>
        <a href="index.php?controller=admin&action=users"><i class="fas fa-users"></i> Quản lý Người dùng</a>
        <a href="index.php?controller=admin&action=banners" class="active"><i class="fas fa-ad"></i> Quản lý Banner
            Quảng cáo</a>

        <div style="margin-top: auto;">
            <a href="index.php" target="_blank"><i class="fas fa-external-link-alt"></i> Ra ngoài Trang chủ</a>
            <a href="index.php?controller=logout" style="color: #e74c3c;"><i class="fas fa-sign-out-alt"></i> Đăng
                xuất</a>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h2>Quản Lý Banner Quảng Cáo</h2>
            <a href="index.php?controller=admin&action=banners" style="text-decoration: none; color: #555;"><i
                    class="fas fa-arrow-left"></i> Quay lại</a>
        </div>
        <div class="content-body">
            <div class="form-wrap">
                <h3 style="margin-bottom: 20px; border-bottom: 2px solid #b71c1c; padding-bottom: 10px;"><?= $title ?>
                </h3>
                <?php if (!empty($error)): ?>
                    <div class="alert"><?= $error ?></div> <?php endif; ?>

                <form action="index.php?controller=admin&action=<?= $action_form ?>" method="POST"
                    enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Vị trí đặt Banner (*)</label>
                        <select name="position" class="form-control" required>
                            <option value="home_top" <?= ($b['position'] ?? '') == 'home_top' ? 'selected' : '' ?>>Top
                                Trang Chủ (Ngang dài)</option>
                            <option value="sidebar_right" <?= ($b['position'] ?? '') == 'sidebar_right' ? 'selected' : '' ?>>Cột Bên Phải (Vuông/Chữ nhật đứng)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Đường link khi click vào (URL)</label>
                        <input type="text" name="target_url" class="form-control"
                            value="<?= htmlspecialchars($b['target_url']) ?>"
                            placeholder="Ví dụ: https://vdpr.vn/giai-dau">
                    </div>

                    <div class="form-group">
                        <label>Hình ảnh Banner
                            <?= $action_form == 'create_banner' ? '(*)' : '(Để trống nếu không muốn đổi)' ?></label>
                        <?php if (!empty($b['image_url'])): ?>
                            <div style="margin-bottom: 10px;">
                                <img src="<?= $b['image_url'] ?>"
                                    style="max-width: 200px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control" accept="image/*"
                            <?= $action_form == 'create_banner' ? 'required' : '' ?>>
                    </div>

                    <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Lưu Banner</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>