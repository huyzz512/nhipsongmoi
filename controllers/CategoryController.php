<?php
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class CategoryController {
    public function index() {
        $postModel = new PostModel();
        $catModel = new CategoryModel();

        $slug = isset($_GET['slug']) ? $_GET['slug'] : 'the-gioi';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $categories = $catModel->getAllCategories();
        $posts = $postModel->getPostsByCategoryPaginated($slug, $limit, $offset);
        $totalPosts = $postModel->countPostsByCategory($slug);
        
        $totalPages = ceil($totalPosts / $limit);

        $currentCategoryName = "Tin tức"; 
        foreach($categories as $c) {
            if($c['slug'] == $slug) $currentCategoryName = $c['name'];
        }

        require __DIR__ . '/../views/category.php';
    }
}
?>