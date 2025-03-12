<?php
namespace App\Models;
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
        $sql = "DELETE FROM ChiTietPhieuMuon WHERE MaPhieuMuon = :maPhieuMuon";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maPhieuMuon' => $maPhieuMuon]);

        $sql = "DELETE FROM PhieuMuon WHERE MaPhieuMuon = :maPhieuMuon";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':maPhieuMuon' => $maPhieuMuon]);
    }
}