<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh Sách Bài Viết - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            display: flex;
            background-color: #f4f6f9;
            color: #333;
            height: 100vh;
            overflow: hidden;
        }

        /* Menu Trái (Giống các trang trước) */
        .sidebar {
            width: 260px;
            background-color: #343a40;
            color: #fff;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .sidebar .logo {
            padding: 20px;
            text-align: center;
            background: #2c3136;
            border-bottom: 1px solid #4b545c;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .sidebar a {
            display: block;
            padding: 15px 20px;
            color: #c2c7d0;
            text-decoration: none;
            border-bottom: 1px solid #40464d;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #494e53;
            color: #fff;
        }

        .sidebar a i {
            width: 25px;
            text-align: center;
            margin-right: 10px;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .topbar {
            background: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .content-body {
            padding: 30px;
        }

        /* Bảng Dữ Liệu */
        .table-wrap {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            color: #555;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        tr:hover {
            background-color: #f1f3f5;
        }

        /* Nút thao tác và Nhãn trạng thái */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status-published {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            font-size: 0.85rem;
            margin-right: 5px;
            display: inline-block;
            transition: 0.2s;
        }

        .btn-approve {
            background-color: #28a745;
        }

        .btn-approve:hover {
            background-color: #218838;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-view {
            background-color: #17a2b8;
        }

        .btn-view:hover {
            background-color: #138496;
        }
    </style>
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
            <h2>Quản Lý Bài Viết</h2>
            <div>Xin chào, <strong><?= htmlspecialchars($username) ?></strong></div>
        </div>

        <div class="content-body">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="35%">Tiêu đề</th>
                            <th width="15%">Chuyên mục</th>
                            <th width="15%">Tác giả</th>
                            <th width="15%">Trạng thái</th>
                            <th width="15%">Ưu tiên</th>
                            <th width="15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($posts)):
                            foreach ($posts as $post): ?>
                                <tr>
                                    <td>#<?= $post['id'] ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($post['title']) ?></strong><br>
                                        <small style="color:#888;">Lượt xem: <?= $post['view_count'] ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($post['cat_name']) ?></td>
                                    <td><?= htmlspecialchars($post['author_name']) ?></td>
                                    <td>
                                        <?php if ($post['status'] == 'published'): ?>
                                            <span class="status-badge status-published">Đã xuất bản</span>
                                        <?php else: ?>
                                            <span class="status-badge status-pending">Chờ duyệt</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <form action="index.php?controller=admin&action=update_priority" method="POST"
                                            style="margin: 0;">
                                            <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                            <select name="priority" onchange="this.form.submit()" <?= $role !== 'admin' ? 'disabled' : '' ?>
                                                style="padding: 6px; border-radius: 4px; border: 1px solid #ccc; font-weight: bold; color: <?= (isset($post['priority']) && $post['priority'] > 0) ? '#b71c1c' : '#555' ?>; outline: none; cursor: pointer;">
                                                <option value="0" <?= (isset($post['priority']) && $post['priority'] == 0) ? 'selected' : '' ?>>0 - Bình thường</option>
                                                <option value="1" <?= (isset($post['priority']) && $post['priority'] == 1) ? 'selected' : '' ?>>1 - Ưu tiên nhỏ</option>
                                                <option value="2" <?= (isset($post['priority']) && $post['priority'] == 2) ? 'selected' : '' ?>>2 - Sub-Hero</option>
                                                <option value="3" <?= (isset($post['priority']) && $post['priority'] == 3) ? 'selected' : '' ?>>3 - Hero (Lớn nhất)</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="index.php?controller=post&slug=<?= $post['slug'] ?>" target="_blank"
                                            class="btn-action btn-view" title="Xem bài viết"><i class="fas fa-eye"></i></a>

                                        <?php if ($role === 'admin'): ?>
                                            <?php if ($post['status'] == 'pending'): ?>
                                                <a href="index.php?controller=admin&action=approve_post&id=<?= $post['id'] ?>"
                                                    class="btn-action btn-approve" title="Duyệt xuất bản"><i
                                                        class="fas fa-check"></i></a>
                                            <?php endif; ?>
                                            <a href="index.php?controller=admin&action=delete_post&id=<?= $post['id'] ?>"
                                                class="btn-action btn-delete" title="Xóa bài viết"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');"><i
                                                    class="fas fa-trash"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 30px;">Chưa có bài viết nào!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>