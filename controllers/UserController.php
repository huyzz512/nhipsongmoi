<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class UserController
{
    public function __construct()
    {
        // Bắt buộc đăng nhập mới được vào các trang này
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=login");
            exit;
        }
    }
    public function profile()
    {
        $userModel = new UserModel();
        $catModel = new CategoryModel();
        $categories = $catModel->getAllCategories();
        $msg = '';
        $error = '';

        $user = $userModel->getUserById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $avatar = $user['avatar'];

            if (isset($_FILES['avatar_upload']) && $_FILES['avatar_upload']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/avatars/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = basename($_FILES['avatar_upload']['name']);
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (in_array($fileExt, $allowedExts)) {
                    $newFileName = uniqid('avatar_') . '.' . $fileExt;
                    $targetFilePath = $uploadDir . $newFileName;

                    if (move_uploaded_file($_FILES['avatar_upload']['tmp_name'], $targetFilePath)) {
                        $avatar = 'public/uploads/avatars/' . $newFileName;
                    } else {
                        $error = "Lỗi khi lưu file ảnh vào hệ thống!";
                    }
                } else {
                    $error = "Chỉ chấp nhận file ảnh định dạng JPG, JPEG, PNG, GIF hoặc WEBP.";
                }
            }

            if (empty($error)) {
                if ($userModel->updateProfile($_SESSION['user_id'], $username, $email, $avatar)) {
                    $_SESSION['username'] = $username;
                    $_SESSION['avatar'] = $avatar;
                    $msg = "Cập nhật thông tin thành công!";

                    $user = $userModel->getUserById($_SESSION['user_id']);
                } else {
                    $error = "Có lỗi xảy ra khi lưu vào cơ sở dữ liệu!";
                }
            }
        }

        require __DIR__ . '/../views/user_profile.php';
    }

    // 2. Trang lịch sử đọc
    public function history()
    {
        $postModel = new PostModel();
        $catModel = new CategoryModel();
        $categories = $catModel->getAllCategories();

        $posts = $postModel->getReadingHistory($_SESSION['user_id']);
        $pageTitle = "Lịch sử đọc báo";
        require __DIR__ . '/../views/user_posts.php';
    }

    // 3. Trang tin đã lưu
    public function saved()
    {
        $postModel = new PostModel();
        $catModel = new CategoryModel();
        $categories = $catModel->getAllCategories();

        $posts = $postModel->getSavedPosts($_SESSION['user_id']);
        $pageTitle = "Tin tức đã lưu";
        require __DIR__ . '/../views/user_posts.php';
    }

    // 4. Action Lưu/Bỏ lưu bài viết (Không có View, xử lý ngầm và quay lại)
    public function toggleSave()
    {
        $post_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $slug = isset($_GET['slug']) ? $_GET['slug'] : '';

        if ($post_id > 0) {
            $postModel = new PostModel();
            $postModel->toggleSavePost($_SESSION['user_id'], $post_id);
        }
        header("Location: index.php?controller=post&slug=" . $slug);
        exit;
    }
}
?>