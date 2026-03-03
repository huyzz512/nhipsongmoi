<?php include __DIR__ . '/partials/header.php'; ?>

<main class="container main-body" style="justify-content: center; margin-top: 40px; margin-bottom: 50px;">
    <div style="width: 100%; max-width: 800px; display: flex; flex-wrap: wrap; gap: 30px;">
        
        <div style="flex: 1; min-width: 300px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;">
            <h2 style="border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px; color: #333;">Hồ Sơ Của Bạn</h2>
            
            <img src="<?= !empty($user['avatar']) ? $user['avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode($user['username']) . '&background=random' ?>" 
                 alt="Avatar" 
                 style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #b71c1c; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            
            <h3 style="font-size: 1.5rem; color: #b71c1c; margin-bottom: 5px;"><?= htmlspecialchars($user['username']) ?></h3>
            <p style="color: #666; margin-bottom: 15px;">
                <span style="background: #eee; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; text-transform: uppercase;">
                    <?= $user['role'] === 'admin' ? 'Quản trị viên' : 'Độc giả' ?>
                </span>
            </p>
            
            <div style="text-align: left; background: #f9f9f9; padding: 15px; border-radius: 8px;">
                <p style="margin-bottom: 10px;"><strong><i class="fas fa-envelope"></i> Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong><i class="fas fa-calendar-alt"></i> Ngày tham gia:</strong> <?= date('d/m/Y', strtotime($user['created_at'] ?? 'now')) ?></p>
            </div>
        </div>

        <div style="flex: 1.5; min-width: 300px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <h3 style="border-bottom: 2px solid #b71c1c; padding-bottom: 10px; margin-bottom: 20px;">Cập Nhật Thông Tin</h3>
            
            <?php if(!empty($error)): ?>
                <div style="background: #ffebee; color: #c62828; padding: 12px; border-radius: 4px; margin-bottom: 20px; border-left: 4px solid #c62828;">
                    <?= $error ?>
                </div>
            <?php endif; ?>
            <?php if(!empty($msg)): ?>
                <div style="background: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 4px; margin-bottom: 20px; border-left: 4px solid #2e7d32;">
                    <?= $msg ?>
                </div>
            <?php endif; ?>

            <form action="index.php?controller=user&action=profile" method="POST" enctype="multipart/form-data">
                
                <div style="margin-bottom: 20px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 8px;">Tên hiển thị mới</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; outline: none;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 8px;">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; outline: none;">
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 8px;">Tải lên ảnh đại diện (Tùy chọn)</label>
                    <div style="border: 1px dashed #b71c1c; padding: 15px; border-radius: 4px; text-align: center; background: #fdfafb;">
                        <input type="file" name="avatar_upload" accept="image/*" style="width: 100%; cursor: pointer;">
                        <p style="font-size: 0.85rem; color: #666; margin-top: 10px;">Chấp nhận file: JPG, PNG, GIF. Dung lượng tối đa 2MB.</p>
                    </div>
                </div>

                <button type="submit" style="width: 100%; padding: 14px; background: #b71c1c; color: #fff; border: none; border-radius: 4px; font-weight: bold; font-size: 1.1rem; cursor: pointer; transition: 0.3s;">
                    Lưu Thay Đổi
                </button>
            </form>
        </div>

    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>