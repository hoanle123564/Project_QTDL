<?php
namespace App\Models;

class DocGia {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function themDocGia($ten, $ngaySinh, $sdt) {
        $sql = "INSERT INTO DocGia (TenDocGia, NgaySinh, SoDienThoai) VALUES (:ten, :ngay_sinh, :sdt)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ten' => $ten,
            ':ngay_sinh' => $ngaySinh,
            ':sdt' => $sdt
        ]);
    }

    public function kiemTraSdtTonTai($sdt) {
        $sql = "SELECT COUNT(*) FROM DocGia WHERE SoDienThoai = :sdt";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':sdt' => $sdt]);
        return $stmt->fetchColumn() > 0;
    }
}