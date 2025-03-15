<?php
namespace App\Models;

use DateTime;
use PDO;
use Exception;

class PhieuMuon {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function danhSachPhieuMuon() {
        $sql = "SELECT pm.*, dg.TenDocGia, GROUP_CONCAT(s.TenSach) as SachMuon 
                FROM PhieuMuon pm 
                JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia 
                JOIN ChiTietPhieuMuon ctpm ON pm.MaPhieuMuon = ctpm.MaPhieuMuon 
                JOIN Sach s ON ctpm.MaSach = s.MaSach 
                GROUP BY pm.MaPhieuMuon";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function xoaPhieuMuon($maPhieuMuon) {
        $sql = "SELECT SoLuongMuon FROM ChiTietPhieuMuon WHERE MaPhieuMuon = :maPhieuMuon";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maPhieuMuon' => $maPhieuMuon]);
        $SL = $stmt->fetch(PDO::FETCH_ASSOC)['SoLuongMuon'];

        $sql = "UPDATE Sach s
        JOIN ChiTietPhieuMuon ctpm ON s.MaSach = ctpm.MaSach
        SET s.SoLuong = s.SoLuong + :SL 
        WHERE ctpm.MaPhieuMuon = :maPhieuMuon";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':SL' => $SL,
            ':maPhieuMuon' => $maPhieuMuon
        ]);

        $sql = "DELETE FROM ChiTietPhieuMuon WHERE MaPhieuMuon = :maPhieuMuon";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maPhieuMuon' => $maPhieuMuon]);

        $sql = "DELETE FROM PhieuMuon WHERE MaPhieuMuon = :maPhieuMuon";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':maPhieuMuon' => $maPhieuMuon]);
    }
    
    // public function capNhatTrangThaiPhieuMuon($maPhieuMuon) {
    //     $sql = "SELECT SoLuongMuon FROM ChiTietPhieuMuon WHERE MaPhieuMuon = :maPhieuMuon";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute([':maPhieuMuon' => $maPhieuMuon]);
    //     $SL = $stmt->fetch(PDO::FETCH_ASSOC)['SoLuongMuon'];

    //     // Chỉnh UPDATE
    //     $sql = "UPDATE Sach s
    //     JOIN ChiTietPhieuMuon ctpm ON s.MaSach = ctpm.MaSach
    //     SET s.SoLuong = s.SoLuong + :SL 
    //     WHERE ctpm.MaPhieuMuon = :maPhieuMuon";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute([
    //         ':SL' => $SL,
    //         ':maPhieuMuon' => $maPhieuMuon
    //     ]);

    //     $sql = "UPDATE PhieuMuon SET TrangThai = 'Đã trả' WHERE MaPhieuMuon = :maPhieuMuon";
    //     $stmt = $this->conn->prepare($sql);
    //     return $stmt->execute([':maPhieuMuon' => $maPhieuMuon]);
    // }

    public function capNhatTrangThaiPhieuMuon($maPhieuMuon) {
        try {
            $this->conn->beginTransaction(); // Bắt đầu transaction
    
            // Lấy danh sách sách và số lượng đã mượn
            $sql = "SELECT MaSach, SoLuongMuon FROM ChiTietPhieuMuon WHERE MaPhieuMuon = :maPhieuMuon";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':maPhieuMuon' => $maPhieuMuon]);
            $sachMuon = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Cập nhật số lượng sách trong bảng Sach
            foreach ($sachMuon as $sach) {
                $sql = "UPDATE Sach SET SoLuong = SoLuong + :soLuongMuon WHERE MaSach = :maSach";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ':soLuongMuon' => $sach['SoLuongMuon'],
                    ':maSach' => $sach['MaSach']
                ]);
            }
    
            // Cập nhật trạng thái phiếu mượn thành "Đã trả"
            $sql = "UPDATE PhieuMuon SET TrangThai = 'Đã trả' WHERE MaPhieuMuon = :maPhieuMuon";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':maPhieuMuon' => $maPhieuMuon]);
    
            $this->conn->commit(); // Xác nhận transaction
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack(); // Nếu có lỗi, quay lại trạng thái trước
            error_log("Lỗi cập nhật phiếu mượn: " . $e->getMessage());
            return false;
        }
    }
    
    public function taoPhieuTra($maPhieuMuon) {
        // Lấy thông tin phiếu mượn
        $sql = "SELECT NgayMuon, NgayTra FROM PhieuMuon WHERE MaPhieuMuon = :maPhieuMuon";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maPhieuMuon' => $maPhieuMuon]);
        $phieuMuon = $stmt->fetch(PDO::FETCH_ASSOC);
    
    
        $ngayMuon = new DateTime($phieuMuon['NgayMuon']);
        $ngayTra = new DateTime($phieuMuon['NgayTra']);
        $today = new DateTime(); // Ngày hiện tại
    

    
            // Chèn phiếu trả vào bảng `PhieuTra`
            $sql = "INSERT INTO PhieuTra (MaChiTietPM, NgayTraSach) 
                    VALUES (:maPhieuMuon, CURDATE())";
            $stmt = $this->conn->prepare($sql);
             $stmt->execute([
                ':maPhieuMuon' => $maPhieuMuon,
            ]);
    
    }
    public function timKiemPhieuMuon($tuKhoa) {
        $sql = "SELECT pm.*, dg.TenDocGia, GROUP_CONCAT(s.TenSach) as SachMuon 
                FROM PhieuMuon pm 
                JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia 
                JOIN ChiTietPhieuMuon ctpm ON pm.MaPhieuMuon = ctpm.MaPhieuMuon 
                JOIN Sach s ON ctpm.MaSach = s.MaSach 
                WHERE s.TenSach LIKE :tuKhoa OR dg.TenDocGia LIKE :tuKhoa
                GROUP BY pm.MaPhieuMuon";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tuKhoa' => "%$tuKhoa%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}