<?php
require_once __DIR__ . '/../config/db.php';

class CategoryModel {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function getAllCategories() {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>