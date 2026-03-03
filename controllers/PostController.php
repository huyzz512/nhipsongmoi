<?php
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/BannerModel.php';

class PostController {
    public function index() {
        $postModel = new PostModel();
        $catModel = new CategoryModel();
        $bannerModel = new BannerModel();

        // 1. Lấy Slug từ URL
        $slug = isset($_GET['slug']) ? $_GET['slug'] : '';

        if (empty($slug)) {
            // Nếu không có slug, đẩy về trang chủ
            header("Location: index.php");
            exit;
        }

        $is_preview = false;
        if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'bien_tap_vien')) {
            $is_preview = true;
        }

        // 2. Lấy dữ liệu chi tiết bài viết
        $post = $postModel->getPostBySlug($slug, $is_preview);

        if (!$post) {
            // Nếu không tìm thấy bài viết (slug sai hoặc bài bị ẩn)
            echo "<h2 style='text-align:center; margin-top:50px;'>404 - Bài viết không tồn tại hoặc chưa được xuất bản!</h2>";
            exit;
        }

        // 3. Tăng lượt xem cho bài viết này
        $postModel->incrementViewCount($post['id']);
        $isSaved = false;
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            // Ghi nhận lịch sử đọc báo
            $postModel->logHistory($user_id, $post['id']);
            $isSaved = $postModel->checkSaved($user_id, $post['id']);
        }

        // 4. Lấy dữ liệu cho Sidebar & Header
        $categories = $catModel->getAllCategories();
        $mostViewed = $postModel->getMostViewed(5);
        $bannerSidebar = $bannerModel->getBannerByPos('sidebar_right', 10);

        // 5. Lấy các tin liên quan (Cùng chuyên mục với bài viết hiện tại)
        $relatedPosts = $postModel->getPostsByCategory($post['cat_slug'], 4); 

        // 6. Trả dữ liệu ra View
        require __DIR__ . '/../views/post.php';
    }
}
?>