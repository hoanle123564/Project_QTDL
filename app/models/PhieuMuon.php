<?php
namespace App\Models;

use DateTime;
use PDO;

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
    
    public function capNhatTrangThaiPhieuMuon($maPhieuMuon) {
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

        $sql = "UPDATE PhieuMuon SET TrangThai = 'Đã trả' WHERE MaPhieuMuon = :maPhieuMuon";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':maPhieuMuon' => $maPhieuMuon]);
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
    
        // Tính số ngày trả muộn (nếu có)
        $soNgayTre = max(0, $today->diff($ngayTra)->days);
    
        // Giả sử phí nộp muộn là 5000 VND/ngày
        $tienPhat = "TinhTienPhat()";
    
            // Chèn phiếu trả vào bảng `PhieuTra`
            $sql = "INSERT INTO PhieuTra (MaChiTietPM, NgayTraSach, TienPhat) 
                    VALUES (:maPhieuMuon, :ngayTraSach, :tienPhat)";
            $stmt = $this->conn->prepare($sql);
             $stmt->execute([
                ':maPhieuMuon' => $maPhieuMuon,
                ':ngayTraSach' => $today->format('Y-m-d'),
                ':tienPhat' => $tienPhat
            ]);
    
    }
    
    
}