<?php
namespace App\Models;
use PDO;

class PhieuTra {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // public function danhSachPhieuTra() {
    //     $sql = "SELECT pt.MaPhieuTra,
    //                 dg.TenDocGia, 
    //                 pt.NgayTraSach,
    //                  pm.NgayMuon, 
    //                 s.TenSach, 
    //                 ctp.SoLuongMuon AS SoLuongSachMuon, 
    //                 pt.TienPhat AS SoTienMuon
    //             FROM PhieuMuon pm
    //             JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia
    //             JOIN ChiTietPhieuMuon ctp ON pm.MaPhieuMuon = ctp.MaPhieuMuon
    //             JOIN Sach s ON ctp.MaSach = s.MaSach
    //             LEFT JOIN PhieuTra pt ON ctp.MaChiTietPM = pt.MaChiTietPM
    //             WHERE pm.TrangThai = 'Đã trả'";
    //     // $sql = "SELECT * FROM PhieuTra";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    public function danhSachPhieuTra() {
        // $sql = "SELECT pt.MaPhieuTra,
        //             dg.TenDocGia, 
        //             pt.NgayTraSach,
        //             pm.NgayMuon, 
        //             s.TenSach, 
        //             ctp.SoLuongMuon AS SoLuongSachMuon, 
        //             pt.TienPhat AS SoTienMuon
        //         FROM PhieuMuon pm
        //         JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia
        //         JOIN ChiTietPhieuMuon ctp ON pm.MaPhieuMuon = ctp.MaPhieuMuon
        //         JOIN Sach s ON ctp.MaSach = s.MaSach
        //         LEFT JOIN PhieuTra pt ON ctp.MaChiTietPM = pt.MaChiTietPM
        //         WHERE pm.TrangThai = 'Đã trả'";
        $sql = "SELECT pt.MaPhieuTra, 
                   pm.MaPhieuMuon,   -- Bổ sung MaPhieuMuon
                   dg.TenDocGia, 
                   pt.NgayTraSach,
                   pm.NgayMuon, 
                   s.TenSach, 
                   ctp.SoLuongMuon AS SoLuongSachMuon, 
                   COALESCE(pt.TienPhat, 0) AS SoTienMuon  -- Dùng COALESCE để tránh NULL
            FROM PhieuMuon pm
            JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia
            JOIN ChiTietPhieuMuon ctp ON pm.MaPhieuMuon = ctp.MaPhieuMuon
            JOIN Sach s ON ctp.MaSach = s.MaSach
            LEFT JOIN PhieuTra pt ON ctp.MaChiTietPM = pt.MaChiTietPM
            WHERE pm.TrangThai = 'Đã trả'";
        
        $sql = "SELECT pt.MaPhieuTra, 
                   pm.MaPhieuMuon,   -- Bổ sung MaPhieuMuon
                   dg.TenDocGia, 
                   pt.NgayTraSach,
                   pm.NgayMuon, 
                   COALESCE(pt.TienPhat, 0) AS SoTienMuon  -- Dùng COALESCE để tránh NULL
            FROM PhieuMuon pm
            JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia
            JOIN PhieuTra pt ON pt.
            WHERE pm.TrangThai = 'Đã trả'";
        
        $sql = "SELECT COALESCE(pt.MaPhieuTra, 'Chưa có') AS MaPhieuTra, 
                   pm.MaPhieuMuon, 
                   dg.TenDocGia, 
                   pm.NgayMuon, 
                   COALESCE(pt.NgayTraSach, 'Chưa trả') AS NgayTraSach, 
                   s.TenSach, 
                   ctp.SoLuongMuon AS SoLuongSachMuon, 
                   COALESCE(pt.TienPhat, 0) AS SoTienMuon  
            FROM PhieuMuon pm
            JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia
            JOIN ChiTietPhieuMuon ctp ON pm.MaPhieuMuon = ctp.MaPhieuMuon
            JOIN Sach s ON ctp.MaSach = s.MaSach
            LEFT JOIN PhieuTra pt ON ctp.MaChiTietPM = pt.MaChiTietPM
            WHERE pm.TrangThai = 'Đã trả'";

        $sql = "SELECT 
        pt.MaPhieuTra,
        dg.TenDocGia, 
        pm.NgayMuon,
        pm.NgayTra,
        pt.NgayTraSach, 
        ctp.SoLuongMuon AS SoLuongSachMuon, 
        COALESCE(pt.TienPhat, 0) AS SoTienMuon
        FROM PhieuTra pt
        JOIN ChiTietPhieuMuon ctp ON pt.MaChiTietPM = ctp.MaChiTietPM
        JOIN PhieuMuon pm ON ctp.MaPhieuMuon = pm.MaPhieuMuon
        JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia
        ORDER BY pt.NgayTraSach DESC";

        $sql = "SELECT 
        pt.MaPhieuTra,
        dg.TenDocGia, 
        pm.NgayMuon,
        pm.NgayTra,
        pt.NgayTraSach,  
        COALESCE(pt.TienPhat, 0) AS SoTienMuon
        FROM PhieuTra pt
        JOIN PhieuMuon pm ON pt.MaChiTietPM = pm.MaPhieuMuon
        JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia
        ORDER BY pt.NgayTraSach DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function timKiemPhieuTra($tuKhoa) {
        $sql = "SELECT pt.MaPhieuTra,
                    dg.TenDocGia, 
                    pt.NgayTraSach,
                     pm.NgayMuon, 
                    s.TenSach, 
                    ctp.SoLuongMuon AS SoLuongSachMuon, 
                    pt.TienPhat AS SoTienMuon
                FROM PhieuMuon pm
                JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia
                JOIN ChiTietPhieuMuon ctp ON pm.MaPhieuMuon = ctp.MaPhieuMuon
                JOIN Sach s ON ctp.MaSach = s.MaSach
                JOIN PhieuTra pt ON ctp.MaChiTietPM = pt.MaChiTietPM
                WHERE pm.TrangThai = 'Đã trả' AND (s.TenSach LIKE :tuKhoa OR dg.TenDocGia LIKE :tuKhoa) ";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tuKhoa' => "%$tuKhoa%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}