<?php include __DIR__ . '/partials/header.php'; ?>

<main class="container main-body">
    <div class="content-left">
        <h1 class="page-title" style="border-bottom: 2px solid #b71c1c; margin-bottom: 20px; padding-bottom: 10px; font-size: 1.5rem;">
            Kết quả tìm kiếm cho: <span style="color: #b71c1c;">"<?= htmlspecialchars($keyword) ?>"</span>
        </h1>
        
        <p style="margin-bottom: 20px; color: #666;">Tìm thấy <strong><?= $totalPosts ?></strong> bài viết phù hợp.</p>

        <div class="list-news">
            <?php if(!empty($posts)): foreach($posts as $news): ?>
            <article class="news-row">
                <img src="<?= !empty($news['thumbnail']) ? $news['thumbnail'] : 'https://via.placeholder.com/240x150' ?>" alt="">
                <div class="desc">
                    <span style="background: #eee; padding: 3px 8px; font-size: 0.8rem; border-radius: 3px; color: #555; margin-bottom: 5px; display: inline-block;">
                        <?= htmlspecialchars($news['cat_name']) ?>
                    </span>
                    <h4><a href="index.php?controller=post&slug=<?= $news['slug'] ?>"><?= htmlspecialchars($news['title']) ?></a></h4>
                    <div class="meta"><?= date('H:i - d/m/Y', strtotime($news['published_at'])) ?></div>
                    <p><?= htmlspecialchars($news['summary']) ?></p>
                </div>
            </article>
            <?php endforeach; else: ?>
                <div style="text-align: center; padding: 50px 0; color: #999;">
                    <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 15px;"></i>
                    <p>Rất tiếc, không tìm thấy bài viết nào chứa từ khóa này.</p>
                    <p>Hãy thử tìm bằng một từ khóa khác ngắn gọn hơn.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if($totalPages > 1): ?>
        <div class="pagination" style="margin-top: 30px; display: flex; gap: 10px;">
            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                <a href="index.php?controller=search&q=<?= urlencode($keyword) ?>&page=<?= $i ?>" 
                   style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 3px; <?= ($i == $page) ? 'background:#b71c1c; color:#fff;' : 'background:#fff; color:#333;' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>

    <aside class="sidebar-right">
        <div class="widget ads">
            <img src="https://images.unsplash.com/photo-1626243280056-9a2c1e63d395?w=300&h=400&fit=crop" alt="VDPR Ads" style="width: 100%; border-radius: 5px;">
        </div>
    </aside>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>