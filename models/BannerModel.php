<?php
require_once __DIR__ . '/../config/db.php';

class BannerModel
{
    private $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getBannerByPos($position, $limit = 1)
    {
        $sql = "SELECT * FROM banners 
                WHERE position_key = :pos AND is_active = 1 
                AND start_date <= NOW() AND end_date >= NOW()
                ORDER BY RAND() LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':pos', $position, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT); // Đã fix lỗi chuỗi
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả banner cho trang Quản trị
    public function getAllBannersAdmin()
    {
        $sql = "SELECT * FROM banners ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy banner theo ID để sửa
    public function getBannerById($id)
    {
        $sql = "SELECT * FROM banners WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => (int) $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm banner mới
    public function addBanner($image_url, $target_url, $position)
    {
        $sql = "INSERT INTO banners (image_url, target_url, position) VALUES (:img, :link, :pos)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['img' => $image_url, 'link' => $target_url, 'pos' => $position]);
    }

    // Cập nhật banner
    public function updateBanner($id, $image_url, $target_url, $position)
    {
        if (!empty($image_url)) {
            // Nếu có upload ảnh mới
            $sql = "UPDATE banners SET image_url = :img, target_url = :link, position = :pos WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['img' => $image_url, 'link' => $target_url, 'pos' => $position, 'id' => (int) $id]);
        } else {
            // Nếu chỉ đổi link/vị trí, giữ nguyên ảnh cũ
            $sql = "UPDATE banners SET target_url = :link, position = :pos WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['link' => $target_url, 'pos' => $position, 'id' => (int) $id]);
        }
    }

    // Xóa banner
    public function deleteBanner($id)
    {
        $sql = "DELETE FROM banners WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => (int) $id]);
    }
}
?>