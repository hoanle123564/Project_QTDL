<?php
namespace App\Models;
use PDO;

class PhieuTra {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function danhSachPhieuTra() {
        $sql = "SELECT pt.MaPhieuTra,
                    dg.TenDocGia, 
                    pm.NgayTra,
                     pm.NgayMuon, 
                    s.TenSach, 
                    ctp.SoLuongMuon AS SoLuongSachMuon, 
                    pt.TienPhat AS SoTienMuon
                FROM PhieuMuon pm
                JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia
                JOIN ChiTietPhieuMuon ctp ON pm.MaPhieuMuon = ctp.MaPhieuMuon
                JOIN Sach s ON ctp.MaSach = s.MaSach
                JOIN PhieuTra pt ON ctp.MaChiTietPM = pt.MaChiTietPM
                WHERE pm.TrangThai = 'Đã trả'";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}