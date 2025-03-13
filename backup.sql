-- Tạo database
CREATE DATABASE IF NOT EXISTS quan_ly_thu_vien;
USE quan_ly_thu_vien;

-- Bảng Tác giả
CREATE TABLE TacGia (
    MaTacGia INT AUTO_INCREMENT PRIMARY KEY,
    TenTacGia VARCHAR(100) NOT NULL
);

-- Bảng Thể loại
CREATE TABLE TheLoai (
    MaTheLoai INT AUTO_INCREMENT PRIMARY KEY,
    TenTheLoai VARCHAR(50) NOT NULL
);

-- Bảng Sách
CREATE TABLE Sach (
    MaSach INT AUTO_INCREMENT PRIMARY KEY,
    MaTacGia INT,
    MaTheLoai INT,
    TenSach VARCHAR(100) NOT NULL,
    NamXuatBan INT,
    NhaXuatBan VARCHAR(100),
    SoLuong INT NOT NULL,
    FOREIGN KEY (MaTacGia) REFERENCES TacGia(MaTacGia),
    FOREIGN KEY (MaTheLoai) REFERENCES TheLoai(MaTheLoai)
);

-- Bảng Độc giả
CREATE TABLE DocGia (
    MaDocGia INT AUTO_INCREMENT PRIMARY KEY,
    TenDocGia VARCHAR(100) NOT NULL,
    NgaySinh DATE,
    SoDienThoai VARCHAR(15)
);

-- Bảng Phiếu mượn
CREATE TABLE PhieuMuon (
    MaPhieuMuon INT AUTO_INCREMENT PRIMARY KEY,
    MaDocGia INT,
    NgayMuon DATE NOT NULL,
    NgayTra DATE,
    TrangThai ENUM('Đang mượn', 'Đã trả') DEFAULT 'Đang mượn',
    FOREIGN KEY (MaDocGia) REFERENCES DocGia(MaDocGia)
);

-- Bảng Chi tiết phiếu mượn
CREATE TABLE ChiTietPhieuMuon (
    MaChiTietPM INT AUTO_INCREMENT PRIMARY KEY,
    MaPhieuMuon INT,
    MaSach INT,
    SoLuongMuon INT NOT NULL,
    FOREIGN KEY (MaPhieuMuon) REFERENCES PhieuMuon(MaPhieuMuon),
    FOREIGN KEY (MaSach) REFERENCES Sach(MaSach)
);

-- Bảng Phiếu trả
CREATE TABLE PhieuTra (
    MaPhieuTra INT AUTO_INCREMENT PRIMARY KEY,
    MaChiTietPM INT,
    NgayTraSach DATE NOT NULL,
    TienPhat DECIMAL(10, 2) DEFAULT 0,
    FOREIGN KEY (MaChiTietPM) REFERENCES ChiTietPhieuMuon(MaChiTietPM)
);

CREATE TABLE NguoiDung (
    MaNguoiDung INT AUTO_INCREMENT PRIMARY KEY,
    TenDangNhap VARCHAR(50) NOT NULL UNIQUE,
    MatKhau VARCHAR(255) NOT NULL, -- Mật khẩu mã hóa
    HoTen VARCHAR(100),
    VaiTro ENUM('admin', 'nhanvien') DEFAULT 'nhanvien'
);

-- Thêm tài khoản mẫu (mật khẩu: 123456, mã hóa bằng password_hash)
INSERT INTO NguoiDung (TenDangNhap, MatKhau, HoTen, VaiTro) 
VALUES ('admin', '$2y$10$3qKNePOGfai8mlVdddyHPOOUpry7ZUF4CDOmJRYpKnuvMWwI8q55G', 'Quản trị viên', 'admin');

-- Thêm dữ liệu mẫu
INSERT INTO TacGia (TenTacGia) VALUES
('Nguyễn Nhật Ánh'),
('Nam Cao'),
('Tô Hoài'),
('Mario Puzo'),
('J.K. Rowling'),
('Arthur Conan Doyle'),
('Dale Carnegie'),
('Vũ Trọng Phụng'),
('Edmondo De Amicis'),
('Paulo Coelho'),
('Hector Malot'),
('Robert C. Martin'),
('Andrew Hunt'),
('Gustave Le Bon'),
('Stephen Hawking'),
('Karl Marx'),
('John C. Maxwell');

INSERT INTO TheLoai (TenTheLoai) VALUES 
('Văn học'),
('Truyện ngắn'),
('Tiểu thuyết'),
('Lịch sử'),
('Khoa học viễn tưởng'),
('Tâm lý học'),
('Triết học'),
('Kinh tế học'),
('Kỹ năng sống'),
('Công nghệ thông tin'),
('Tôn giáo'),
('Chính trị'),
('Giáo dục'),
('Y học'),
('Thiếu nhi'),
('Trinh thám'),
('Khoa học tự nhiên'),
('Khoa học xã hội'),
('Nghệ thuật'),
('Du lịch');

INSERT INTO Sach (MaTacGia, MaTheLoai, TenSach, NamXuatBan, NhaXuatBan, SoLuong)  
VALUES 
    (9, 15, 'Những tấm lòng cao cả', 1886, 'NXB Kim Đồng', 11),
    (10, 5, 'Nhà giả kim', 1988, 'NXB Văn học', 14),
    (11, 3, 'Không gia đình', 1878, 'NXB Trẻ', 8),
    (12, 4, 'Tôi thấy hoa vàng trên cỏ xanh', 2010, 'NXB Trẻ', 12),
    (13, 10, 'Clean Code', 2008, 'NXB Công nghệ', 18),
    (14, 10, 'The Pragmatic Programmer', 1999, 'NXB Công nghệ', 9);


INSERT INTO DocGia (TenDocGia, NgaySinh, SoDienThoai) 
VALUES ('Nguyễn Văn A', '2000-05-15', '0909123456');

-- Stored Procedure: Danh sách phiếu mượn chưa trả
DELIMITER //
CREATE PROCEDURE DanhSachPhieuMuonChuaTra()
BEGIN
    SELECT pm.*, dg.TenDocGia, GROUP_CONCAT(s.TenSach) as SachMuon 
    FROM PhieuMuon pm 
    JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia 
    JOIN ChiTietPhieuMuon ctpm ON pm.MaPhieuMuon = ctpm.MaPhieuMuon 
    JOIN Sach s ON ctpm.MaSach = s.MaSach 
    WHERE pm.TrangThai = 'Đang mượn'
    GROUP BY pm.MaPhieuMuon;
END //
DELIMITER ;

-- Function: Kiểm tra số lượng sách còn lại
DELIMITER //
DROP FUNCTION IF EXISTS KiemTraSoLuongSach //
CREATE FUNCTION KiemTraSoLuongSach(inp_maSach INT) RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE sl INT DEFAULT 0;
    SELECT COALESCE(MAX(SoLuong), 0) INTO sl FROM Sach WHERE MaSach = inp_maSach;
    RETURN sl;
END //
DELIMITER ;
SELECT KiemTraSoLuongSach(1) as SoLuongCon;
SELECT SoLuong FROM SACH WHERE MaSach = 1;


-- Trigger: Tính tiền phạt khi trả sách muộn
DELIMITER //
CREATE TRIGGER TinhTienPhat
BEFORE INSERT ON PhieuTra
FOR EACH ROW
BEGIN
    DECLARE ngayTraDuKien DATE;
    DECLARE soNgayTre INT;

    -- Lấy NgayTra từ PhieuMuon thông qua ChiTietPhieuMuon
    SELECT pm.NgayTra INTO ngayTraDuKien
    FROM PhieuMuon pm
    JOIN ChiTietPhieuMuon ctpm ON pm.MaPhieuMuon = ctpm.MaPhieuMuon
    WHERE ctpm.MaChiTietPM = NEW.MaChiTietPM;

    -- Tính số ngày trễ
    SET soNgayTre = DATEDIFF(CURDATE(), ngayTraDuKien);
    IF soNgayTre > 0 THEN
        SET NEW.TienPhat = soNgayTre * 1000; -- 1000 VNĐ/ngày
    ELSE
        SET NEW.TienPhat = 0;
    END IF;
END //
DELIMITER ;
--


