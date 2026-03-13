<?php
session_start();


$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'home';

if ($controllerName == 'home') {
    require_once __DIR__ . '/controllers/HomeController.php';
    $controller = new HomeController();

    // Bổ sung bắt action cho nút Xem thêm
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';
    if ($action == 'loadMore') {
        $controller->loadMore();
    } else {
        $controller->index();
    }
} elseif ($controllerName == 'category') {
    require_once __DIR__ . '/controllers/CategoryController.php';
    $controller = new CategoryController();
    $controller->index();
}
//detail bài viết
elseif ($controllerName == 'post') {
    require_once __DIR__ . '/controllers/PostController.php';
    $controller = new PostController();
    $controller->index();
} elseif ($controllerName == 'post') {
    require_once __DIR__ . '/controllers/PostController.php';
    $controller = new PostController();
    $controller->index();
}
//search
elseif ($controllerName == 'search') {
    require_once __DIR__ . '/controllers/SearchController.php';
    $controller = new SearchController();
    $controller->index();
}
// --- THÊM ROUTER CHO USER THAO TÁC PROFILE/HISTORY/SAVED ---
elseif ($controllerName == 'user') {
    require_once __DIR__ . '/controllers/UserController.php';
    $controller = new UserController();

    $action = isset($_GET['action']) ? $_GET['action'] : 'profile';
    if ($action == 'profile')
        $controller->profile();
    elseif ($action == 'history')
        $controller->history();
    elseif ($action == 'saved')
        $controller->saved();
    elseif ($action == 'toggle_save')
        $controller->toggleSave();
}
//log/reg
elseif ($controllerName == 'login' || $controllerName == 'register' || $controllerName == 'logout') {
    require_once __DIR__ . '/controllers/AuthController.php';
    $controller = new AuthController();

    if ($controllerName == 'login') {
        $controller->login();
    } elseif ($controllerName == 'register') {
        $controller->register();
    } elseif ($controllerName == 'logout') {
        $controller->logout();
    }
} elseif ($controllerName == 'admin') {
    require_once __DIR__ . '/controllers/AdminController.php';
    $controller = new AdminController();

    // Bắt action trong admin, mặc định là trang dashboard
    $action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        $controller->dashboard();
    }
}

// ROUTER CHO API CHATBOT AI
elseif ($controllerName == 'chat') {
    require_once __DIR__ . '/controllers/ChatController.php';
    $controller = new ChatController();
    $controller->process();
    exit;
}

?>