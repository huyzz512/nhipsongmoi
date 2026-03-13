<?php include __DIR__ . '/partials/header.php'; ?>

<?php if ($is_preview): ?>
    <div id="preview-notification-bar"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 40px; background-color: #fdf5e0; color: #856404; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; border-bottom: 1px solid #ffebac; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1001; font-size: 0.95rem;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-exclamation-triangle" style="color: #ffc107; font-size: 1.2rem;"></i>
            <span><strong>CHẾ ĐỘ XEM THỬ:</strong> Bài viết này đang chờ duyệt và chưa hiển thị công khai. Bạn có thể xuất
                bản nếu thấy nội dung ổn.</span>
        </div>

        <a href="index.php?controller=admin&action=approve_post&id=<?= $post['id'] ?>"
            style="background: #28a745; color: white; padding: 4px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; transition: 0.2s; display: flex; align-items: center; gap: 6px; height: 28px; font-size: 0.9rem;">
            <i class="fas fa-check"></i> Xuất bản ngay
        </a>
    </div>

    <style>
        body {
            padding-top: 40px !important;
            /* Thêm padding-top để tránh che khuất bởi thanh thông báo */
        }

        header,
        #top-bar,
        footer {
            display: none !important;
            /* Tắt header, top-bar và footer mặc định */
        }

        /* Hiển thị một footer đơn giản hơn chỉ chứa copyright cho preview (tùy chọn) */
        #preview-footer {
            background-color: #f4f4f4;
            border-top: 1px solid #ddd;
            padding: 15px 0;
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-top: auto;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main.container.main-body {
            flex: 1;
            margin-top: 20px !important;
            margin-bottom: 30px !important;
        }
    </style>
<?php endif; ?>
<main class="container main-body" style="margin-top: 40px; margin-bottom: 50px;">
    <div class="content-left">
        <h1 style="font-size: 3rem; margin-bottom: 20px; color: #333; line-height: 1.2; font-weight: 800;">
            <?= htmlspecialchars($post['title']) ?></h1>
        <div class="meta"
            style="color: #666; font-size: 0.95rem; margin-bottom: 15px; display: flex; gap: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            <span><i class="fas fa-user" style="margin-right: 5px;"></i>
                <?= htmlspecialchars($post['username'] ?? 'Admin') ?></span>

            <span>
                <i class="fas fa-calendar-alt" style="margin-right: 5px;"></i>
                <?= date('H:i - d/m/Y', strtotime($post['published_at'] ?? $post['created_at'])) ?>
            </span>

            <span>
                <i class="fas fa-eye" style="margin-right: 5px;"></i>
                <?= number_format($post['view_count'] + 1) ?> lượt xem
            </span>
        </div>

        <button
            style="background: #eee; border: none; color: #333; padding: 10px 20px; border-radius: 20px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 8px; margin-bottom: 30px;">
            <i class="fas fa-bookmark"></i> Lưu bài viết này
        </button>

        <div style="text-align: center; margin-bottom: 30px;">
            <img src="<?= $post['thumbnail'] ?>" alt="Preview"
                style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <p style="color: #666; font-size: 0.95rem; margin-top: 10px;">Ảnh minh họa:
                <?= htmlspecialchars($post['title']) ?></p>
        </div>

        <div class="post-content" style="font-size: 1.15rem; line-height: 1.8; color: #333; margin-bottom: 50px;">
            <?= $post['content'] ?>
        </div>
    </div>

    <aside class="sidebar-right">
        <div class="widget">
            <div class="widget-header">
                <h3>Đọc nhiều nhất</h3>
            </div>
            <ul class="ranking-list">
                <?php $i = 1;
                foreach ($mostViewed as $mv): ?>
                    <li>
                        <span class="rank-num top-<?= $i ?>"><?= $i ?></span>
                        <a
                            href="index.php?controller=post&slug=<?= $mv['slug'] ?>"><?= htmlspecialchars($mv['title']) ?></a>
                    </li>
                    <?php $i++; endforeach; ?>
            </ul>
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