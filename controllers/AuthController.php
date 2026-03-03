<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/CategoryModel.php'; // Load menu header

class AuthController {
    
    public function login() {
        $catModel = new CategoryModel();
        $categories = $catModel->getAllCategories();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $userModel = new UserModel();
            $user = $userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Đăng nhập thành công -> Lưu vào Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['avatar'] = $user['avatar'];

                //logic chuyển hướng
                if ($user['role'] === 'admin' || $user['role'] === 'bien_tap_vien') {
                    // Chuyển thẳng vào trang Admin
                    header("Location: index.php?controller=admin");
                } else {
                    // Độc giả bình thường về trang chủ
                    header("Location: index.php");
                }
                exit;
            } else {
                $error = 'Email hoặc mật khẩu không chính xác!';
            }
        }
        
        require __DIR__ . '/../views/login.php';
    }

    public function register() {
        $catModel = new CategoryModel();
        $categories = $catModel->getAllCategories();
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $re_password = $_POST['re_password'];

            $userModel = new UserModel();

            if ($password !== $re_password) {
                $error = 'Mật khẩu nhập lại không khớp!';
            } elseif ($userModel->checkEmailExists($email)) {
                $error = 'Email này đã được sử dụng!';
            } else {
                if ($userModel->registerUser($username, $email, $password)) {
                    $success = 'Đăng ký thành công! Bạn có thể đăng nhập ngay.';
                } else {
                    $error = 'Có lỗi xảy ra, vui lòng thử lại sau.';
                }
            }
        }
        
        require __DIR__ . '/../views/register.php';
    }

    public function logout() {
        // Xóa tất cả Session
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }
}
?>