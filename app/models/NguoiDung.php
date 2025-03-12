<?php
namespace App\Models;

use PDO;

class NguoiDung {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function kiemTraDangNhap($tenDangNhap, $matKhau) {
        $sql = "SELECT * FROM NguoiDung WHERE TenDangNhap = :tenDangNhap";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tenDangNhap' => $tenDangNhap]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($matKhau, $user['MatKhau'])) {
            return $user;
        }
        return false;
    }
}