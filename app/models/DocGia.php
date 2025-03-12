<?php
namespace App\Models;

use PDO;
use PDOException;

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
    public function xoaDocGia($maDocGia) {
        $sql = "DELETE FROM DocGia WHERE MaDocGia = :maDocGia";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':maDocGia' => $maDocGia]);
    }
    public function timKiemDocGia($tuKhoa) {
        $sql = "SELECT * FROM DocGia WHERE TenDocGia LIKE :tuKhoa";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tuKhoa' => "%$tuKhoa%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function suaDocGia($maDocGia, $tenDocGia, $ngaySinh, $soDT) {
        try {
            $this->conn->beginTransaction();
    
    
            // Cập nhật thông tin sách
            $sql = "UPDATE DocGia SET TenDocGia = :tenDocGia, NgaySinh = :ngaySinh, SoDienThoai = :soDT
                    WHERE MaDocGia = :maDocGia";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':maDocGia' => $maDocGia,
                ':tenDocGia' => $tenDocGia,
                ':ngaySinh' => $ngaySinh,
                ':soDT' => $soDT,
            ]);
    
            $this->conn->commit();
            return "Sửa sách thành công!";
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return "Lỗi khi sửa sách: " . $e->getMessage();
        }
    }
    public function danhSachDocGia() {
        $sql = "SELECT * FROM DocGia";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function kiemTraSdtTonTai($sdt) {
        $sql = "SELECT COUNT(*) FROM DocGia WHERE SoDienThoai = :sdt";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':sdt' => $sdt]);
        return $stmt->fetchColumn() > 0;
    }
    public function layThongTinDocGia($maDocGia) {
        $sql = "SELECT * FROM DocGia WHERE MaDocGia = :maDocGia";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maDocGia' => $maDocGia]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}