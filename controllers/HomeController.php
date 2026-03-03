<?php
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/BannerModel.php';

class HomeController {
    public function index() {
        $postModel = new PostModel();
        $catModel = new CategoryModel();
        $bannerModel = new BannerModel();

        // 1. Lấy dữ liệu chung
        $categories = $catModel->getAllCategories();
        $featured = $postModel->getFeaturedPosts(4);
        $mostViewed = $postModel->getMostViewed(5);

        // Banners
        $bannerTop = $bannerModel->getBannerByPos('home_top', 1); 
        $bannerSidebar = $bannerModel->getBannerByPos('sidebar_right', 20);

        // 2. Lấy tin theo từng chuyên mục (Tăng từ 4 lên 5 hoặc 6 bài cho đỡ trống nếu muốn)
        $businessNews = $postModel->getPostsByCategory('kinh-doanh', 5);
        $techNews = $postModel->getPostsByCategory('cong-nghe', 5);
        $sportNews = $postModel->getPostsByCategory('the-thao', 5);
        $worldNews = $postModel->getPostsByCategory('the-gioi', 5);

        // 3. Xử lý tin Hot
        $heroPost = !empty($featured) ? $featured[0] : null;
        $subFeatured = array_slice($featured, 1, 3);

        $latestPosts = $postModel->getAllPostsPaginated(20, 0); 
        require __DIR__ . '/../views/home.php';
    }

    // Hàm gọi ngầm qua AJAX để tải thêm bài viết
    public function loadMore() {
        $postModel = new PostModel();
        
        // Nhận mốc bắt đầu (offset) từ Javascript gửi lên, mặc định là 20 (vì trang chủ đã lấy 20 bài đầu)
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 20;
        $limit = 10; // Mỗi lần bấm xem thêm tải 10 bài
        
        $posts = $postModel->getAllPostsPaginated($limit, $offset);
        
        $html = '';
        if (!empty($posts)) {
            foreach($posts as $news) {
                $thumb = !empty($news['thumbnail']) ? $news['thumbnail'] : 'https://via.placeholder.com/240x150';
                $catName = htmlspecialchars($news['cat_name'] ?? 'Tin tức');
                $slug = htmlspecialchars($news['slug']);
                $title = htmlspecialchars($news['title']);
                $date = date('H:i - d/m/Y', strtotime($news['published_at']));
                $summary = htmlspecialchars($news['summary']);
                
                // Nối chuỗi HTML các bài báo mới
                $html .= '
                <article class="news-row">
                    <img src="'.$thumb.'" alt="">
                    <div class="desc">
                        <span style="background: #eee; padding: 3px 8px; font-size: 0.8rem; border-radius: 3px; color: #b71c1c; margin-bottom: 5px; display: inline-block; font-weight: bold;">'.$catName.'</span>
                        <h4><a href="index.php?controller=post&slug='.$slug.'">'.$title.'</a></h4>
                        <div class="meta"><i class="fas fa-clock"></i> '.$date.'</div>
                        <p>'.$summary.'</p>
                    </div>
                </article>';
            }
        }
        
        // Trả kết quả về dạng JSON
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'html' => $html]);
        exit;
    }
}
?>