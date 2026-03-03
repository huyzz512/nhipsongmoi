<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Viết Bài Mới - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <style>
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

        /* Form Styles */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem; }
        .btn-submit { padding: 12px 25px; background: #b71c1c; color: white; border: none; border-radius: 4px; font-size: 1.1rem; cursor: pointer; font-weight: bold; }
        .btn-submit:hover { background: #8f1515; }
        
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<div class="sidebar">
        <div class="logo">ADMIN <span>PANEL</span></div>
        <a href="index.php?controller=admin&action=dashboard"><i class="fas fa-tachometer-alt"></i> Tổng quan</a>
        <a href="index.php?controller=admin&action=create_post" class="active"><i class="fas fa-pen"></i> Viết bài mới</a>
        <a href="index.php?controller=admin&action=list_posts"><i class="fas fa-list"></i> Danh sách bài viết</a>
        <a href="index.php?controller=admin&action=users"><i class="fas fa-users"></i> Quản lý Người dùng</a>
        <a href="index.php?controller=admin&action=banners"><i class="fas fa-ad"></i>  Quản lý Banner Quảng cáo</a>

                <div style="margin-top: auto;">
            <a href="index.php" target="_blank"><i class="fas fa-external-link-alt"></i> Ra ngoài Trang chủ</a>
            <a href="index.php?controller=logout" style="color: #e74c3c;"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h2>Viết Bài Mới</h2>
            <div>Xin chào, <strong><?= htmlspecialchars($username) ?></strong></div>
        </div>
        
        <div class="content-body">
            <?php if(!empty($msg)): ?> <div class="alert alert-success"><?= $msg ?></div> <?php endif; ?>
            <?php if(!empty($error)): ?> <div class="alert alert-danger"><?= $error ?></div> <?php endif; ?>

            <form action="index.php?controller=admin&action=create_post" method="POST" enctype="multipart/form-data" style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                
                <div class="form-group">
                    <label>Tiêu đề bài viết (*)</label>
                    <input type="text" name="title" class="form-control" required placeholder="Nhập tiêu đề...">
                </div>

                <div class="form-group">
                    <label>Tóm tắt (Sapo) (*)</label>
                    <textarea name="summary" class="form-control" rows="3" required placeholder="Đoạn tóm tắt ngắn hiển thị ở trang chủ..."></textarea>
                </div>

                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Chuyên mục (*)</label>
                        <select name="category_id" class="form-control" required>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label>Ảnh đại diện (Thumbnail)</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*" required>
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label>Trạng thái</label>
                        <select name="status" class="form-control">
                            <?php if($role === 'admin'): ?>
                                <option value="published">Xuất bản ngay</option>
                                <option value="pending">Lưu nháp / Chờ duyệt</option>
                            <?php else: ?>
                                <option value="pending">Gửi chờ duyệt</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nội dung chi tiết (*)</label>
                    <textarea name="content" id="post_content" required></textarea>
                </div>

                <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Đăng Bài</button>
            </form>
        </div>
    </div>

   <script>
        CKEDITOR.replace('post_content', {
            height: 400, // Chiều cao khung soạn thảo
            versionCheck: false // Tắt popup cảnh báo phiên bản
        });
    </script>
</body>
</html>