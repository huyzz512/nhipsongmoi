<?php include __DIR__ . '/partials/header.php'; ?>

<main class="container main-body">
    <div class="content-left">
        <h1 class="page-title" style="border-bottom: 2px solid #b71c1c; margin-bottom: 20px; padding-bottom: 10px;">
            <?= htmlspecialchars($currentCategoryName) ?>
        </h1>

        <div class="list-news">
            <?php if (!empty($posts)):
                foreach ($posts as $news): ?>
                    <article class="news-row">
                        <img src="<?= $news['thumbnail'] ?>" alt="">
                        <div class="desc">
                            <h4><a
                                    href="index.php?controller=post&slug=<?= $news['slug'] ?>"><?= htmlspecialchars($news['title']) ?></a>
                            </h4>
                            <div class="meta"><?= date('d/m/Y', strtotime($news['published_at'])) ?></div>
                            <p><?= htmlspecialchars($news['summary']) ?></p>
                        </div>
                    </article>
                <?php endforeach; else:
                echo "<p>Chưa có bài viết.</p>"; endif; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination" style="margin-top: 30px; display: flex; gap: 10px;">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="index.php?controller=category&slug=<?= $slug ?>&page=<?= $i ?>"
                        style="padding: 8px 15px; border: 1px solid #ddd; <?= ($i == $page) ? 'background:#b71c1c; color:#fff;' : 'background:#fff;' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>