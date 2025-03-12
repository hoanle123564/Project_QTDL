<?php
namespace App\Models;

use PDO;
use PDOException;

class Sach {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function danhSachSach() {
        $sql = "SELECT s.*, tg.TenTacGia, tl.TenTheLoai 
                FROM Sach s 
                JOIN TacGia tg ON s.MaTacGia = tg.MaTacGia 
                JOIN TheLoai tl ON s.MaTheLoai = tl.MaTheLoai";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // public function themSach($tenSach, $maTacGia, $maTheLoai, $namXuatBan, $nhaXuatBan, $soLuong) {
    //     $sql = "INSERT INTO Sach (TenSach, MaTacGia, MaTheLoai, NamXuatBan, NhaXuatBan, SoLuong) 
    //             VALUES (:tenSach, :maTacGia, :maTheLoai, :namXuatBan, :nhaXuatBan, :soLuong)";
    //     $stmt = $this->conn->prepare($sql);
    //     return $stmt->execute([
    //         ':tenSach' => $tenSach,
    //         ':maTacGia' => $maTacGia,
    //         ':maTheLoai' => $maTheLoai,
    //         ':namXuatBan' => $namXuatBan,
    //         ':nhaXuatBan' => $nhaXuatBan,
    //         ':soLuong' => $soLuong
    //     ]);
    // }

    public function themSach($tenSach, $tenTacGia, $maTheLoai, $namXuatBan, $nhaXuatBan, $soLuong) {
        try {
            // Bắt đầu giao dịch để đảm bảo tính toàn vẹn dữ liệu
            $this->conn->beginTransaction();
    
            // Kiểm tra xem tác giả đã tồn tại chưa
            $sqlCheckTacGia = "SELECT MaTacGia FROM TacGia WHERE TenTacGia = :tenTacGia";
            $stmtCheck = $this->conn->prepare($sqlCheckTacGia);
            $stmtCheck->execute([':tenTacGia' => $tenTacGia]);
            $maTacGia = $stmtCheck->fetchColumn();
    
            // Nếu tác giả chưa tồn tại, thêm mới vào bảng TacGia
            if (!$maTacGia) {
                $sqlInsertTacGia = "INSERT INTO TacGia (TenTacGia) VALUES (:tenTacGia)";
                $stmtInsertTacGia = $this->conn->prepare($sqlInsertTacGia);
                $stmtInsertTacGia->execute([':tenTacGia' => $tenTacGia]);
                $maTacGia = $this->conn->lastInsertId(); // Lấy ID mới của tác giả vừa thêm
            }
    
            // Thêm sách vào bảng Sach
            $sql = "INSERT INTO Sach (TenSach, MaTacGia, MaTheLoai, NamXuatBan, NhaXuatBan, SoLuong) 
                    VALUES (:tenSach, :maTacGia, :maTheLoai, :namXuatBan, :nhaXuatBan, :soLuong)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':tenSach' => $tenSach,
                ':maTacGia' => $maTacGia,
                ':maTheLoai' => $maTheLoai,
                ':namXuatBan' => $namXuatBan,
                ':nhaXuatBan' => $nhaXuatBan,
                ':soLuong' => $soLuong
            ]);
    
            // Commit giao dịch nếu mọi thứ thành công
            $this->conn->commit();
            return "Thêm sách thành công!";
        } catch (PDOException $e) {
            // Rollback nếu có lỗi xảy ra
            $this->conn->rollBack();
            return "Lỗi khi thêm sách: " . $e->getMessage();
        }
    }

    public function suaSach($maSach, $tenSach, $tenTacGia, $maTheLoai, $namXuatBan, $nhaXuatBan, $soLuong) {
        try {
            $this->conn->beginTransaction();
    
            // Kiểm tra xem tác giả đã tồn tại hay chưa
            $sqlCheckTacGia = "SELECT MaTacGia FROM TacGia WHERE TenTacGia = :tenTacGia";
            $stmtCheck = $this->conn->prepare($sqlCheckTacGia);
            $stmtCheck->execute([':tenTacGia' => $tenTacGia]);
            $maTacGia = $stmtCheck->fetchColumn();
    
            // Nếu tác giả chưa tồn tại, thêm mới vào bảng TacGia
            if (!$maTacGia) {
                $sqlInsertTacGia = "INSERT INTO TacGia (TenTacGia) VALUES (:tenTacGia)";
                $stmtInsertTacGia = $this->conn->prepare($sqlInsertTacGia);
                $stmtInsertTacGia->execute([':tenTacGia' => $tenTacGia]);
                $maTacGia = $this->conn->lastInsertId(); // Lấy ID mới của tác giả vừa thêm
            }
    
            // Cập nhật thông tin sách
            $sql = "UPDATE Sach SET TenSach = :tenSach, MaTacGia = :maTacGia, MaTheLoai = :maTheLoai, 
                    NamXuatBan = :namXuatBan, NhaXuatBan = :nhaXuatBan, SoLuong = :soLuong 
                    WHERE MaSach = :maSach";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':maSach' => $maSach,
                ':tenSach' => $tenSach,
                ':maTacGia' => $maTacGia,
                ':maTheLoai' => $maTheLoai,
                ':namXuatBan' => $namXuatBan,
                ':nhaXuatBan' => $nhaXuatBan,
                ':soLuong' => $soLuong
            ]);
    
            $this->conn->commit();
            return "Sửa sách thành công!";
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return "Lỗi khi sửa sách: " . $e->getMessage();
        }
    }
    
    public function xoaSach($maSach) {
        $sql = "DELETE FROM Sach WHERE MaSach = :maSach";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':maSach' => $maSach]);
    }

    public function timKiemSach($tuKhoa) {
        $sql = "SELECT s.*, tg.TenTacGia, tl.TenTheLoai 
                FROM Sach s 
                JOIN TacGia tg ON s.MaTacGia = tg.MaTacGia 
                JOIN TheLoai tl ON s.MaTheLoai = tl.MaTheLoai 
                WHERE s.TenSach LIKE :tuKhoa OR tg.TenTacGia LIKE :tuKhoa";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tuKhoa' => "%$tuKhoa%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layThongTinSach($maSach) {
        $sql = "SELECT * FROM Sach a, TacGia b WHERE a.MaTacGia = b.MaTacGia and MaSach = :maSach";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':maSach' => $maSach]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}