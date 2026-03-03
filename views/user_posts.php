<?php include __DIR__ . '/partials/header.php'; ?>

<main class="container main-body">
    <div class="content-left" style="width: 100%;"> <h1 style="border-bottom: 2px solid #b71c1c; padding-bottom: 10px; margin-bottom: 20px;"><?= $pageTitle ?></h1>

        <div class="list-news">
            <?php if(!empty($posts)): foreach($posts as $news): ?>
            <article class="news-row" style="display: flex; gap: 20px; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
                <img src="<?= !empty($news['thumbnail']) ? $news['thumbnail'] : 'https://via.placeholder.com/240x150' ?>" alt="" style="width: 240px; height: 150px; object-fit: cover; border-radius: 4px;">
                <div class="desc">
                    <h4><a href="index.php?controller=post&slug=<?= $news['slug'] ?>" style="font-size: 1.3rem; font-weight: bold;"><?= htmlspecialchars($news['title']) ?></a></h4>
                    <div class="meta" style="color: #888; margin-top: 5px;">
                        <i class="fas fa-clock"></i> <?= isset($news['read_at']) ? 'Đã xem lúc: ' . date('d/m/Y H:i', strtotime($news['read_at'])) : 'Đã lưu lúc: ' . date('d/m/Y H:i', strtotime($news['saved_at'])) ?>
                    </div>
                </div>
            </article>
            <?php endforeach; else: ?>
                <div style="text-align: center; padding: 40px; color: #999;">
                    <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 15px;"></i>
                    <p>Danh sách trống.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>