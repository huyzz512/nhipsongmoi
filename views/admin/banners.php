<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản Lý Banner - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/admin/banners.css">
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
        </div>
        <div class="content-body">
            <a href="index.php?controller=admin&action=create_banner" class="btn-add"><i class="fas fa-plus"></i> Thêm
                Banner Mới</a>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="35%">Hình ảnh hiển thị</th>
                            <th width="20%">Vị trí đặt</th>
                            <th width="25%">Đường link (URL)</th>
                            <th width="15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($banners)):
                            foreach ($banners as $b): ?>
                                <tr>
                                    <td>#<?= $b['id'] ?></td>
                                    <td><img src="<?= $b['image_url'] ?>" class="banner-preview" alt="Banner"></td>
                                    <td><span
                                            class="pos-badge"><?= ($b['position'] ?? '') == 'home_top' ? 'Top Trang Chủ' : 'Cột Phải (Sidebar)' ?></span>
                                    </td>
                                    <td><a href="<?= htmlspecialchars($b['target_url']) ?>" target="_blank"
                                            style="color: #3498db;"><?= htmlspecialchars($b['target_url']) ?></a></td>
                                    <td>
                                        <a href="index.php?controller=admin&action=edit_banner&id=<?= $b['id'] ?>"
                                            class="btn-action btn-edit" title="Sửa"><i class="fas fa-edit"></i></a>
                                        <a href="index.php?controller=admin&action=delete_banner&id=<?= $b['id'] ?>"
                                            class="btn-action btn-delete" title="Xóa"
                                            onclick="return confirm('Xóa banner này?');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 30px;">Chưa có Banner nào!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>