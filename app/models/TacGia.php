<?php
namespace App\Models;

use PDO;
use Exception;

class TacGia {
    private $conn;
    public $MaTacGia;
    public $TenTacGia;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // public function themTacGia($ten, $ngaySinh, $sdt) {
    //     $sql = "INSERT INTO TacGia (TenTacGia, NgaySinh, SoDienThoai) VALUES (:ten, :ngay_sinh, :sdt)";
    //     $stmt = $this->conn->prepare($sql);
    //     return $stmt->execute([
    //         ':ten' => $ten,
    //         ':ngay_sinh' => $ngaySinh,
    //         ':sdt' => $sdt
    //     ]);
    // }
    // public function xoaTacGia($maTacGia) {
    //     $sql = "DELETE FROM TacGia WHERE MaTacGia = :maTacGia";
    //     $stmt = $this->conn->prepare($sql);
    //     return $stmt->execute([':maTacGia' => $maTacGia]);
    // }
    public function timKiemTacGia($tuKhoa) {
        $sql = "SELECT * FROM TacGia WHERE TenTacGia LIKE :tuKhoa";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tuKhoa' => "%$tuKhoa%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // public function suaTacGia($maTacGia, $tenTacGia, $ngaySinh, $soDT) {
    //     try {
    //         $this->conn->beginTransaction();
    
    
    //         // Cập nhật thông tin sách
    //         $sql = "UPDATE TacGia SET TenTacGia = :tenTacGia, NgaySinh = :ngaySinh, SoDienThoai = :soDT
    //                 WHERE MaTacGia = :maTacGia";
    //         $stmt = $this->conn->prepare($sql);
    //         $stmt->execute([
    //             ':maTacGia' => $maTacGia,
    //             ':tenTacGia' => $tenTacGia,
    //             ':ngaySinh' => $ngaySinh,
    //             ':soDT' => $soDT,
    //         ]);
    
    //         $this->conn->commit();
    //         return "Sửa sách thành công!";
    //     } catch (PDOException $e) {
    //         $this->conn->rollBack();
    //         return "Lỗi khi sửa sách: " . $e->getMessage();
    //     }
    // }
    public function danhSachTacGia() {
        $sql = "SELECT * FROM TacGia";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function laySachCuaTacGia($maTG){
        $sql = "SELECT s.TenSach from TacGia tg JOIN sach s ON tg.MaTacGia = s.MaTacGia where tg.MaTacGia = :maTG";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['maTG' => $maTG]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layTacGia($maTG): TacGia
    {
        $sql = "SELECT * from TacGia where MaTacGia = :maTG";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['maTG' => $maTG]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$row) {
            throw new Exception("Không tìm thấy tác giả với mã: " . $maTG);
        }

        // Tạo một đối tượng TacGia từ dữ liệu lấy được
        $tacGia = new TacGia($this->conn);
        $tacGia->MaTacGia = $row['MaTacGia'];
        $tacGia->TenTacGia = $row['TenTacGia'];

        return $tacGia;
    }
    
    // public function layThongTinTacGia($maTacGia) {
    //     $sql = "SELECT * FROM TacGia WHERE MaTacGia = :maTacGia";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute([':maTacGia' => $maTacGia]);
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }
}