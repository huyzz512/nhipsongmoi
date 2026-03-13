<?php
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class SearchController
{
    public function index()
    {
        $postModel = new PostModel();
        $catModel = new CategoryModel();

        // 1. Lấy từ khóa (q) và số trang (page) từ URL
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10; // Hiển thị 10 kết quả 1 trang
        $offset = ($page - 1) * $limit;

        $categories = $catModel->getAllCategories(); // Để load menu

        if ($keyword !== '') {
            $posts = $postModel->searchPostsPaginated($keyword, $limit, $offset);
            $totalPosts = $postModel->countSearchResults($keyword);
        } else {
            $posts = [];
            $totalPosts = 0;
        }

        $totalPages = ceil($totalPosts / $limit);

        require __DIR__ . '/../views/search.php';
    }
}
?>