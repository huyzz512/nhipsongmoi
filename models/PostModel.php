<?php
require_once __DIR__ . '/../config/db.php';

class PostModel
{
    private $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getFeaturedPosts($limit = 4)
    {
        $sql = "SELECT p.*, c.name as cat_name FROM posts p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status='published' 
                ORDER BY p.priority DESC, p.published_at DESC 
                LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMostViewed($limit = 5)
    {
        $sql = "SELECT * FROM posts WHERE status='published' ORDER BY view_count DESC LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostsByCategory($category_slug, $limit = 4)
    {
        $sql = "SELECT p.*, c.name as cat_name FROM posts p 
                JOIN categories c ON p.category_id = c.id 
                WHERE p.status='published' AND c.slug = :slug 
                ORDER BY p.published_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':slug', $category_slug, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countPostsByCategory($category_slug)
    {
        $sql = "SELECT COUNT(*) as total FROM posts p 
                JOIN categories c ON p.category_id = c.id 
                WHERE p.status='published' AND c.slug = :slug";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $category_slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getPostsByCategoryPaginated($category_slug, $limit, $offset)
    {
        $sql = "SELECT p.*, c.name as cat_name FROM posts p 
                JOIN categories c ON p.category_id = c.id 
                WHERE p.status='published' AND c.slug = :slug 
                ORDER BY p.published_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':slug', $category_slug, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết bài viết theo Slug (Đã cấp quyền Xem Thử cho Admin)
    public function getPostBySlug($slug, $can_preview = false)
    {
        // 1. KHÔNG DÙNG SELECT * // Chỉ lấy đúng những cột cần hiển thị ra View để tiết kiệm RAM và Băng thông
        $sql = "SELECT 
                p.id, p.title, p.slug, p.summary, p.content, p.thumbnail, 
                p.view_count, p.published_at, p.created_at, p.status,
                u.username, u.avatar, 
                c.name AS category_name, c.slug AS cat_slug
            FROM posts p
            INNER JOIN users u ON p.user_id = u.id
            INNER JOIN categories c ON p.category_id = c.id
            WHERE p.slug = :slug";

        // 2. Chặn bài ẩn ngay từ Database (Nhanh hơn là lấy ra PHP rồi mới if/else)
        if (!$can_preview) {
            $sql .= " AND p.status = 'published'";
        }

        // Tùy chọn: Thêm LIMIT 1 để Database ngừng tìm kiếm ngay khi thấy kết quả đầu tiên
        $sql .= " LIMIT 1";

        // 3. Thực thi truy vấn an toàn bằng PDO (Chống SQL Injection)
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tăng lượt xem (view_count) lên 1 đơn vị
    public function incrementViewCount($id)
    {
        $sql = "UPDATE posts SET view_count = view_count + 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // 1. Đếm số lượng kết quả tìm kiếm
    public function countSearchResults($keyword)
    {
        $keyword = '%' . $keyword . '%';
        $sql = "SELECT COUNT(*) as total FROM posts 
                WHERE status='published' AND (title LIKE :kw OR summary LIKE :kw2)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['kw' => $keyword, 'kw2' => $keyword]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // 2. Lấy danh sách bài viết theo từ khóa (Có phân trang)
    public function searchPostsPaginated($keyword, $limit, $offset)
    {
        $keyword = '%' . $keyword . '%';
        $sql = "SELECT p.*, c.name as cat_name FROM posts p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status='published' AND (p.title LIKE :kw OR p.summary LIKE :kw2) 
                ORDER BY p.published_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':kw', $keyword, PDO::PARAM_STR);
        $stmt->bindValue(':kw2', $keyword, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- TÍNH NĂNG LỊCH SỬ ĐỌC ---
    public function logHistory($user_id, $post_id)
    {
        // Nếu đã đọc rồi thì cập nhật lại thời gian (ON DUPLICATE KEY UPDATE)
        $sql = "INSERT INTO reading_history (user_id, post_id, read_at) 
                VALUES (:uid, :pid, NOW()) 
                ON DUPLICATE KEY UPDATE read_at = NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['uid' => $user_id, 'pid' => $post_id]);
    }

    public function getReadingHistory($user_id, $limit = 20)
    {
        $sql = "SELECT p.*, h.read_at FROM posts p 
                JOIN reading_history h ON p.id = h.post_id 
                WHERE h.user_id = :uid ORDER BY h.read_at DESC LIMIT :limit";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':uid', (int) $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);

        $stmt->execute(); // Chạy execute rỗng

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- TÍNH NĂNG LƯU BÀI VIẾT ---
    public function checkSaved($user_id, $post_id)
    {
        $sql = "SELECT * FROM saved_posts WHERE user_id = :uid AND post_id = :pid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['uid' => $user_id, 'pid' => $post_id]);
        return $stmt->fetch() ? true : false;
    }

    public function toggleSavePost($user_id, $post_id)
    {
        if ($this->checkSaved($user_id, $post_id)) {
            // Đã lưu -> Bỏ lưu
            $sql = "DELETE FROM saved_posts WHERE user_id = :uid AND post_id = :pid";
        } else {
            // Chưa lưu -> Lưu
            $sql = "INSERT INTO saved_posts (user_id, post_id) VALUES (:uid, :pid)";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['uid' => $user_id, 'pid' => $post_id]);
    }

    public function getSavedPosts($user_id)
    {
        $sql = "SELECT p.*, s.saved_at FROM posts p 
                JOIN saved_posts s ON p.id = s.post_id 
                WHERE s.user_id = :uid ORDER BY s.saved_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['uid' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Lấy TẤT CẢ bài viết có giới hạn số lượng (Dùng cho phần Tin Mới Nhất)
    public function getAllPostsPaginated($limit, $offset)
    {
        $sql = "SELECT p.*, c.name as cat_name FROM posts p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status='published' 
                ORDER BY p.published_at DESC 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm bài viết mới vào Database
    public function addPost($title, $slug, $summary, $content, $thumbnail, $category_id, $user_id, $status)
    {
        $sql = "INSERT INTO posts (title, slug, summary, content, thumbnail, category_id, user_id, status, published_at) 
                VALUES (:title, :slug, :summary, :content, :thumbnail, :cat_id, :user_id, :status, NOW())";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'title' => $title,
            'slug' => $slug,
            'summary' => $summary,
            'content' => $content,
            'thumbnail' => $thumbnail,
            'cat_id' => $category_id,
            'user_id' => $user_id,
            'status' => $status
        ]);
    }

    //  Lấy danh sách bài viết cho Admin/Biên tập viên
    public function getAdminPosts($user_id, $role)
    {
        $sql = "SELECT p.*, c.name as cat_name, u.username as author_name 
                FROM posts p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN users u ON p.user_id = u.id ";

        // Nếu không phải admin thì chỉ lấy bài của chính người đó viết
        if ($role !== 'admin') {
            $sql .= " WHERE p.user_id = :uid ";
        }
        $sql .= " ORDER BY p.id DESC"; // Sắp xếp bài mới nhất lên đầu

        $stmt = $this->conn->prepare($sql);
        if ($role !== 'admin') {
            $stmt->bindValue(':uid', (int) $user_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Hàm Duyệt bài (Dành cho Admin)
    public function approvePost($post_id)
    {
        $sql = "UPDATE posts SET status = 'published', published_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', (int) $post_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // 3. Hàm Xóa bài (Dành cho Admin)
    public function deletePost($post_id)
    {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', (int) $post_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Cập nhật mức độ ưu tiên của bài viết
    public function updatePriority($post_id, $priority)
    {
        $sql = "UPDATE posts SET priority = :priority WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'priority' => (int) $priority,
            'id' => (int) $post_id
        ]);
    }

    // Đếm tổng số bài viết theo trạng thái
    public function countTotalPosts($status = null)
    {
        $sql = "SELECT COUNT(*) as total FROM posts";
        if ($status) {
            $sql .= " WHERE status = :status";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        } else {
            $stmt = $this->conn->prepare($sql);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>