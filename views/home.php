<?php include __DIR__ . '/partials/header.php'; ?>

<?php if (!empty($bannerTop)): ?>
    <div class="container" style="margin-top: 20px; margin-bottom: 20px;">
        <a href="<?= $bannerTop[0]['target_url'] ?>" target="_blank">
            <img src="<?= $bannerTop[0]['image_url'] ?>" alt="Banner Top"
                style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 5px;">
        </a>
    </div>
<?php endif; ?>

<main class="container main-body" style="margin-top: 0;">
    <div class="content-left">

        <section class="hot-news-section">
            <?php if ($heroPost): ?>
                <div class="hero-post">
                    <div class="img-wrap">
                        <img src="<?= $heroPost['thumbnail'] ?? 'https://via.placeholder.com/800x450' ?>" alt="">
                        <span class="badge">Nổi bật</span>
                    </div>
                    <div class="hero-info">
                        <h2><a
                                href="index.php?controller=post&slug=<?= $heroPost['slug'] ?>"><?= htmlspecialchars($heroPost['title']) ?></a>
                        </h2>
                        <p><?= htmlspecialchars($heroPost['summary']) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="sub-hero-grid">
                <?php foreach ($subFeatured as $sub): ?>
                    <div class="sub-item">
                        <img src="<?= !empty($sub['thumbnail']) ? $sub['thumbnail'] : 'https://via.placeholder.com/300x200' ?>"
                            alt="">
                        <h3><a
                                href="index.php?controller=post&slug=<?= $sub['slug'] ?>"><?= htmlspecialchars($sub['title']) ?></a>
                        </h3>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="topic-block">
            <div class="block-title">
                <h3><a href="index.php?controller=category&slug=kinh-doanh">Kinh Doanh</a></h3>
            </div>
            <div class="list-news">
                <?php foreach ($businessNews as $news): ?>
                    <article class="news-row">
                        <img src="<?= $news['thumbnail'] ?>" alt="">
                        <div class="desc">
                            <h4><a
                                    href="index.php?controller=post&slug=<?= $news['slug'] ?>"><?= htmlspecialchars($news['title']) ?></a>
                            </h4>
                            <div class="meta"><?= date('H:i d/m/Y', strtotime($news['published_at'])) ?></div>
                            <p><?= htmlspecialchars($news['summary']) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="topic-block" style="margin-top:30px">
            <div class="block-title">
                <h3><a href="index.php?controller=category&slug=the-thao">Thể Thao</a></h3>
            </div>
            <div class="list-news">
                <?php foreach ($sportNews as $news): ?>
                    <article class="news-row">
                        <img src="<?= $news['thumbnail'] ?>" alt="">
                        <div class="desc">
                            <h4><a
                                    href="index.php?controller=post&slug=<?= $news['slug'] ?>"><?= htmlspecialchars($news['title']) ?></a>
                            </h4>
                            <div class="meta"><?= date('H:i d/m/Y', strtotime($news['published_at'])) ?></div>
                            <p><?= htmlspecialchars($news['summary']) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="topic-block" style="margin-top: 50px; border-top: 3px solid #b71c1c; padding-top: 20px;">
            <div class="block-title" style="border-bottom: 2px solid #b71c1c; margin-bottom: 20px;">
                <h3
                    style="background: #b71c1c; color: #fff; padding: 8px 15px; font-size: 1.2rem; display: inline-block; margin-bottom: -2px;">
                    Tin Mới Cập Nhật
                </h3>
            </div>

            <div class="list-news" id="latestNewsContainer">
                <?php foreach ($latestPosts as $news): ?>
                <?php endforeach; ?>
            </div>

            <div style="text-align: center; margin-top: 30px; margin-bottom: 20px;">
                <button id="loadMoreBtn"
                    style="padding: 12px 30px; font-size: 1.1rem; font-weight: bold; color: #b71c1c; background: #fff; border: 2px solid #b71c1c; border-radius: 25px; cursor: pointer; transition: 0.3s;">
                    Xem thêm tin tức <i class="fas fa-angle-down"></i>
                </button>
            </div>
        </section>

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
        </div>

        <div class="widget ads">
            <?php if (!empty($bannerSidebar)): ?>
                <?php foreach ($bannerSidebar as $ad): ?>
                    <div style="margin-bottom: 20px;">
                        <a href="<?= $ad['target_url'] ?>" target="_blank" title="<?= htmlspecialchars($ad['title']) ?>">
                            <img src="<?= $ad['image_url'] ?>" alt="Ads"
                                style="width: 100%; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: block;">
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <img src="https://via.placeholder.com/300x500.png?text=Quang+Cao" alt="Quảng cáo">
            <?php endif; ?>
        </div>
    </aside>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>