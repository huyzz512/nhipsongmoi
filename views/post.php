<?php include __DIR__ . '/partials/header.php'; ?>

<?php if ($is_preview): ?>
    <div id="preview-notification-bar" style="position: fixed; top: 0; left: 0; width: 100%; height: 40px; background-color: #fdf5e0; color: #856404; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; border-bottom: 1px solid #ffebac; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1001; font-size: 0.95rem;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-exclamation-triangle" style="color: #ffc107; font-size: 1.2rem;"></i>
            <span><strong>CHẾ ĐỘ XEM THỬ:</strong> Bài viết này đang chờ duyệt và chưa hiển thị công khai. Bạn có thể xuất bản nếu thấy nội dung ổn.</span>
        </div>
        
        <a href="index.php?controller=admin&action=approve_post&id=<?= $post['id'] ?>" style="background: #28a745; color: white; padding: 4px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; transition: 0.2s; display: flex; align-items: center; gap: 6px; height: 28px; font-size: 0.9rem;">
            <i class="fas fa-check"></i> Xuất bản ngay
        </a>
    </div>

    <style>
        body { padding-top: 40px !important; /* Thêm padding-top để tránh che khuất bởi thanh thông báo */ }
        header, #top-bar, footer { display: none !important; /* Tắt header, top-bar và footer mặc định */ }
    
        /* Hiển thị một footer đơn giản hơn chỉ chứa copyright cho preview (tùy chọn) */
        #preview-footer { background-color: #f4f4f4; border-top: 1px solid #ddd; padding: 15px 0; text-align: center; color: #666; font-size: 0.9rem; margin-top: auto; }
        body { display: flex; flex-direction: column; min-height: 100vh; }
        main.container.main-body { flex: 1; margin-top: 20px !important; margin-bottom: 30px !important; }
    </style>
<?php endif; ?>
<main class="container main-body" style="margin-top: 40px; margin-bottom: 50px;">
    <div class="content-left">
        <h1 style="font-size: 3rem; margin-bottom: 20px; color: #333; line-height: 1.2; font-weight: 800;"><?= htmlspecialchars($post['title']) ?></h1>
        <div class="meta" style="color: #666; font-size: 0.95rem; margin-bottom: 15px; display: flex; gap: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            <span><i class="fas fa-user" style="margin-right: 5px;"></i> Admin</span>
            <span><i class="fas fa-calendar-alt" style="margin-right: 5px;"></i> 19:42 - 02/03/2026</span>
            <span><i class="fas fa-eye" style="margin-right: 5px;"></i> 0 lượt xem</span>
        </div>
        
        <button style="background: #eee; border: none; color: #333; padding: 10px 20px; border-radius: 20px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 8px; margin-bottom: 30px;">
            <i class="fas fa-bookmark"></i> Lưu bài viết này
        </button>
        
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="<?= $post['thumbnail'] ?>" alt="Preview" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <p style="color: #666; font-size: 0.95rem; margin-top: 10px;">Ảnh minh họa: <?= htmlspecialchars($post['title']) ?></p>
        </div>
        
        <div class="post-content" style="font-size: 1.15rem; line-height: 1.8; color: #333; margin-bottom: 50px;">
            <?= $post['content'] ?>
        </div>
    </div>

    <aside class="sidebar-right">
        <div class="widget" style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 30px;">
            <h3 style="color: #333; text-transform: uppercase; font-size: 1.3rem; margin-bottom: 25px; border-bottom: 2px solid #eee; padding-bottom: 12px; font-weight: 800;">ĐỌC NHIỀU NHẤT</h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="display: flex; gap: 10px; margin-bottom: 20px;">
                    <span style="font-size: 1.3rem; color: #b71c1c; font-weight: bold;">1</span>
                    <div>
                        <p style="margin: 0; font-weight: bold; line-height: 1.4; color: #333;">Giá vàng SJC tăng sốc 14 triệu đồng/lượng: Cú "quay xe" lịch sử</p>
                        <small style="color: #888;">0 lượt xem</small>
                    </div>
                </li>
                <li style="display: flex; gap: 10px; margin-bottom: 20px;">
                    <span style="font-size: 1.3rem; color: #b71c1c; font-weight: bold;">2</span>
                    <div>
                        <p style="margin: 0; font-weight: bold; line-height: 1.4; color: #333;">Lisa (BlackPink) lộ video đi vào khách sạn cùng con trai tỷ phú</p>
                        <small style="color: #888;">0 lượt xem</small>
                    </div>
                </li>
                <li style="display: flex; gap: 10px; margin-bottom: 20px;">
                    <span style="font-size: 1.3rem; color: #b71c1c; font-weight: bold;">3</span>
                    <div>
                        <p style="margin: 0; font-weight: bold; line-height: 1.4; color: #333;">Vụ án Shark Thủy: Thông báo mới nhất từ C03 Bộ Công an</p>
                        <small style="color: #888;">0 lượt xem</small>
                    </div>
                </li>
            </ul>
        </div>
    </aside>
</main>

<?php if ($is_preview): ?>
    <div id="preview-footer">
        <div class="container">
            <p style="margin: 0;">&copy; 2026 Newspaper. Bài xem trước. Mọi quyền được bảo lưu.</p>
        </div>
    </div>
    <?php endif; ?>

<?php include __DIR__ . '/partials/footer.php'; ?>