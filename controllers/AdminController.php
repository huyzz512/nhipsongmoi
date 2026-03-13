<?php
class AdminController
{

    public function __construct()
    {
        // Nếu chưa đăng nhập HOẶC vai trò là 'user' bình thường -> Trang chủ
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'bien_tap_vien')) {
            header("Location: index.php?controller=login");
            exit;
        }
    }

    // Trang chủ Admin
    public function dashboard()
    {
        $role = $_SESSION['role'];
        $username = $_SESSION['username'];

        require_once __DIR__ . '/../models/PostModel.php';
        require_once __DIR__ . '/../models/UserModel.php';

        $postModel = new PostModel();
        $userModel = new UserModel();

        $totalPublished = $postModel->countTotalPosts('published');
        $totalPending = $postModel->countTotalPosts('pending');
        $totalUsers = $userModel->countTotalUsers();

        // Lấy 5 bài viết mới nhất
        $recentPosts = $postModel->getAdminPosts($_SESSION['user_id'], $role);
        $recentPosts = array_slice($recentPosts, 0, 5); // Cắt lấy 5 bài đầu tiên

        require __DIR__ . '/../views/admin/dashboard.php';
    }

    // Hàm gõ tiếng Việt
    private function createSlug($str)
    {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        );
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', "-", $str);
        return trim($str, '-');
    }

    // Trang hiển thị Form thêm bài và xử lý lưu
    public function create_post()
    {
        require_once __DIR__ . '/../models/CategoryModel.php';
        require_once __DIR__ . '/../models/PostModel.php';

        $catModel = new CategoryModel();
        $postModel = new PostModel();
        $categories = $catModel->getAllCategories(); // Lấy danh mục cho thẻ <select>

        $role = $_SESSION['role'];
        $username = $_SESSION['username'];
        $msg = '';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title']);
            $summary = trim($_POST['summary']);
            $content = $_POST['content'];
            $category_id = (int) $_POST['category_id'];

            // Xử lý quyền: Nếu là Biên tập viên thì bắt buộc trạng thái là Chờ duyệt (pending)
            $status = ($role === 'admin') ? $_POST['status'] : 'pending';

            // Tự động tạo slug (Vd: "Giải Pickleball" -> "giai-pickleball")
            $slug = $this->createSlug($title) . '-' . time(); // Thêm time() để đảm bảo không trùng lặp

            $thumbnail = '';
            // Xử lý Upload ảnh Thumbnail
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/posts/';
                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0777, true);

                $fileName = basename($_FILES['thumbnail']['name']);
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (in_array($fileExt, $allowedExts)) {
                    $newFileName = uniqid('thumb_') . '.' . $fileExt;
                    if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadDir . $newFileName)) {
                        $thumbnail = 'public/uploads/posts/' . $newFileName;
                    }
                } else {
                    $error = "Định dạng ảnh không hợp lệ!";
                }
            }

            if (empty($error)) {
                if ($postModel->addPost($title, $slug, $summary, $content, $thumbnail, $category_id, $_SESSION['user_id'], $status)) {
                    $msg = "Đăng bài viết thành công!";
                } else {
                    $error = "Có lỗi xảy ra khi lưu vào CSDL.";
                }
            }
        }

        require __DIR__ . '/../views/admin/create_post.php';
    }

    // --- HIỂN THỊ DANH SÁCH BÀI VIẾT ---
    public function list_posts()
    {
        require_once __DIR__ . '/../models/PostModel.php';
        $postModel = new PostModel();

        $role = $_SESSION['role'];
        $username = $_SESSION['username'];

        // Lấy danh sách bài viết theo quyền
        $posts = $postModel->getAdminPosts($_SESSION['user_id'], $role);

        require __DIR__ . '/../views/admin/list_posts.php';
    }

    // --- XỬ LÝ DUYỆT BÀI ---
    public function approve_post()
    {
        // Chặn Biên tập viên cố tình truy cập link này
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=list_posts");
            exit;
        }

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id > 0) {
            require_once __DIR__ . '/../models/PostModel.php';
            $postModel = new PostModel();
            $postModel->approvePost($id);
        }

        // Trở lại trang danh sách
        header("Location: index.php?controller=admin&action=list_posts");
        exit;
    }

    // --- XỬ LÝ XÓA BÀI ---
    public function delete_post()
    {
        // Chặn Biên tập viên
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=list_posts");
            exit;
        }

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id > 0) {
            require_once __DIR__ . '/../models/PostModel.php';
            $postModel = new PostModel();
            $postModel->deletePost($id);
        }

        header("Location: index.php?controller=admin&action=list_posts");
        exit;
    }

    // --- XỬ LÝ CẬP NHẬT ƯU TIÊN ---
    public function update_priority()
    {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=list_posts");
            exit;
        }

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $priority = isset($_POST['priority']) ? (int) $_POST['priority'] : 0;

        if ($id > 0) {
            require_once __DIR__ . '/../models/PostModel.php';
            $postModel = new PostModel();
            $postModel->updatePriority($id, $priority);
        }

        // Quay lại trang danh sách
        header("Location: index.php?controller=admin&action=list_posts");
        exit;
    }

    // --- HIỂN THỊ DANH SÁCH NGƯỜI DÙNG ---
    public function users()
    {
        // Chỉ Admin mới được quản lý User
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=dashboard");
            exit;
        }

        require_once __DIR__ . '/../models/UserModel.php';
        $userModel = new UserModel();
        $users = $userModel->getAllUsers();

        $role = $_SESSION['role'];
        $username = $_SESSION['username'];
        $current_user_id = $_SESSION['user_id']; // Dùng để khóa nút đổi quyền của chính mình

        require __DIR__ . '/../views/admin/users.php';
    }

    // --- XỬ LÝ ĐỔI QUYỀN (VAI TRÒ) ---
    public function update_role()
    {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=dashboard");
            exit;
        }

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $new_role = isset($_POST['role']) ? trim($_POST['role']) : '';

        // Chỉ lưu nếu Role hợp lệ và Admin không đang tự đổi quyền của chính mình
        if ($id > 0 && $id !== $_SESSION['user_id'] && in_array($new_role, ['user', 'bien_tap_vien', 'admin'])) {
            require_once __DIR__ . '/../models/UserModel.php';
            $userModel = new UserModel();
            $userModel->updateRole($id, $new_role);
        }

        // Quay lại trang danh sách người dùng
        header("Location: index.php?controller=admin&action=users");
        exit;
    }

    // --- THÊM NGƯỜI DÙNG MỚI ---
    public function create_user()
    {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=dashboard");
            exit;
        }

        $msg = '';
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $role = trim($_POST['role']);

            require_once __DIR__ . '/../models/UserModel.php';
            $userModel = new UserModel();

            // Kiểm tra trùng Email
            if ($userModel->getUserByEmail($email)) {
                $error = "Email này đã được sử dụng bởi người khác!";
            } else {
                if ($userModel->addUserAdmin($username, $email, $password, $role)) {
                    header("Location: index.php?controller=admin&action=users");
                    exit;
                } else {
                    $error = "Có lỗi xảy ra khi thêm dữ liệu!";
                }
            }
        }

        $action_form = "create_user";
        $title = "Thêm Người Dùng Mới";
        $u = ['username' => '', 'email' => '', 'role' => 'user']; // Dữ liệu rỗng cho form
        require __DIR__ . '/../views/admin/user_form.php';
    }

    // --- SỬA THÔNG TIN NGƯỜI DÙNG ---
    public function edit_user()
    {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=dashboard");
            exit;
        }

        require_once __DIR__ . '/../models/UserModel.php';
        $userModel = new UserModel();
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        $u = $userModel->getUserById($id);
        if (!$u) {
            header("Location: index.php?controller=admin&action=users");
            exit;
        }

        $msg = '';
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password']; // Có thể rỗng
            $role = trim($_POST['role']);

            // Chống Admin tự hạ quyền của chính mình
            if ($id == $_SESSION['user_id']) {
                $role = 'admin';
            }

            if ($userModel->updateUserAdmin($id, $username, $email, $role, $password)) {
                header("Location: index.php?controller=admin&action=users");
                exit;
            } else {
                $error = "Có lỗi xảy ra khi cập nhật!";
            }
        }

        $action_form = "edit_user&id=" . $id;
        $title = "Cập Nhật Người Dùng";
        require __DIR__ . '/../views/admin/user_form.php';
    }

    // --- XÓA NGƯỜI DÙNG ---
    public function delete_user()
    {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=dashboard");
            exit;
        }

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        // Không cho phép tự xóa chính mình
        if ($id > 0 && $id !== $_SESSION['user_id']) {
            require_once __DIR__ . '/../models/UserModel.php';
            $userModel = new UserModel();
            $userModel->deleteUser($id);
        }

        header("Location: index.php?controller=admin&action=users");
        exit;
    }

    // --- 1. DANH SÁCH BANNER ---
    public function banners()
    {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=dashboard");
            exit;
        }

        require_once __DIR__ . '/../models/BannerModel.php';
        $bannerModel = new BannerModel();
        $banners = $bannerModel->getAllBannersAdmin();

        require __DIR__ . '/../views/admin/banners.php';
    }

    // --- 2. THÊM BANNER ---
    public function create_banner()
    {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=dashboard");
            exit;
        }

        $msg = '';
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $target_url = trim($_POST['target_url']);
            $position = trim($_POST['position']);
            $image_url = '';

            // Xử lý upload ảnh Banner
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/banners/';
                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0777, true);

                $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $newFileName = uniqid('banner_') . '.' . $fileExt;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newFileName)) {
                        $image_url = 'public/uploads/banners/' . $newFileName;
                    }
                } else {
                    $error = "Định dạng ảnh không hợp lệ!";
                }
            }

            if (empty($error) && !empty($image_url)) {
                require_once __DIR__ . '/../models/BannerModel.php';
                $bannerModel = new BannerModel();
                if ($bannerModel->addBanner($image_url, $target_url, $position)) {
                    header("Location: index.php?controller=admin&action=banners");
                    exit;
                } else {
                    $error = "Lỗi lưu vào database!";
                }
            } elseif (empty($image_url) && empty($error)) {
                $error = "Vui lòng tải lên hình ảnh banner!";
            }
        }

        $action_form = "create_banner";
        $title = "Thêm Banner Mới";
        $b = ['target_url' => '#', 'position' => 'home_top'];
        require __DIR__ . '/../views/admin/banner_form.php';
    }

    // --- 3. SỬA BANNER ---
    public function edit_banner()
    {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=dashboard");
            exit;
        }

        require_once __DIR__ . '/../models/BannerModel.php';
        $bannerModel = new BannerModel();
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        $b = $bannerModel->getBannerById($id);
        if (!$b) {
            header("Location: index.php?controller=admin&action=banners");
            exit;
        }

        $msg = '';
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $target_url = trim($_POST['target_url']);
            $position = trim($_POST['position']);
            $image_url = '';

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/banners/';
                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0777, true);
                $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $newFileName = uniqid('banner_') . '.' . $fileExt;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newFileName)) {
                        $image_url = 'public/uploads/banners/' . $newFileName;
                    }
                }
            }

            if (empty($error)) {
                if ($bannerModel->updateBanner($id, $image_url, $target_url, $position)) {
                    header("Location: index.php?controller=admin&action=banners");
                    exit;
                }
            }
        }

        $action_form = "edit_banner&id=" . $id;
        $title = "Cập Nhật Banner";
        require __DIR__ . '/../views/admin/banner_form.php';
    }

    // --- 4. XÓA BANNER ---
    public function delete_banner()
    {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=dashboard");
            exit;
        }
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id > 0) {
            require_once __DIR__ . '/../models/BannerModel.php';
            $bannerModel = new BannerModel();
            $bannerModel->deleteBanner($id);
        }
        header("Location: index.php?controller=admin&action=banners");
        exit;
    }

    // Các chức năng khác (đăng bài, duyệt bài...) sẽ viết tiếp ở đây sau
}
?>