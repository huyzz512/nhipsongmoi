<?php
require_once __DIR__ . '/../config/db.php';

class UserModel
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // 1. Kiểm tra xem Email đã tồn tại chưa
    public function checkEmailExists($email)
    {
        $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    // 2. Thêm người dùng mới vào CSDL
    public function registerUser($username, $email, $password)
    {
        // Mã hóa mật khẩu (Rất quan trọng)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, 'user')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $hashed_password, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // 3. Lấy thông tin user bằng email (Dùng cho đăng nhập)
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật thông tin Profile
    public function updateProfile($id, $username, $email, $avatar)
    {
        $sql = "UPDATE users SET username = :username, email = :email, avatar = :avatar WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':avatar', $avatar, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    // Lấy thông tin user bằng ID
    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách toàn bộ người dùng
    public function getAllUsers()
    {
        $sql = "SELECT id, username, email, role, created_at, avatar FROM users ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật vai trò (Quyền) của người dùng
    public function updateRole($id, $role)
    {
        $sql = "UPDATE users SET role = :role WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'role' => $role,
            'id' => (int) $id
        ]);
    }

    // Thêm người dùng mới (Dành cho Admin)
    public function addUserAdmin($username, $email, $password, $role)
    {
        $sql = "INSERT INTO users (username, email, password, role, created_at) VALUES (:user, :email, :pass, :role, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'user' => $username,
            'email' => $email,
            'pass' => password_hash($password, PASSWORD_DEFAULT), // Mã hóa mật khẩu
            'role' => $role
        ]);
    }

    // Cập nhật thông tin người dùng (Có thể đổi cả mật khẩu)
    public function updateUserAdmin($id, $username, $email, $role, $password = '')
    {
        if (!empty($password)) {
            // Nếu Admin có nhập mật khẩu mới
            $sql = "UPDATE users SET username = :user, email = :email, role = :role, password = :pass WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['user' => $username, 'email' => $email, 'role' => $role, 'pass' => password_hash($password, PASSWORD_DEFAULT), 'id' => $id]);
        } else {
            // Nếu không nhập mật khẩu mới thì giữ nguyên mật khẩu cũ
            $sql = "UPDATE users SET username = :user, email = :email, role = :role WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['user' => $username, 'email' => $email, 'role' => $role, 'id' => $id]);
        }
    }

    // Xóa người dùng khỏi hệ thống
    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Đếm tổng số người dùng
    public function countTotalUsers()
    {
        $sql = "SELECT COUNT(*) as total FROM users";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>