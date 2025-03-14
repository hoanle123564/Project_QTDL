<?php

namespace App\Controllers;

use App\Models\Sach;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PDO;
use Exception;

class SachController
{
    private $conn;
    public $sach;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sach = new Sach($conn);
    }

    public function quanLySach()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /public/?action=dangNhap");
            die();
        }

        $errors = [];
        $thong_bao = '';
        $sachList = $this->sach->danhSachSach();
        $isAdmin = $_SESSION['user']['VaiTro'] === 'admin';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['xoa'])) {
                $maSach = $_POST['ma_sach'] ?? '';
                if ($this->sach->xoaSach($maSach)) {
                    $thong_bao = "Xóa sách thành công!";
                    $sachList = $this->sach->danhSachSach();
                } else {
                    $errors[] = "Lỗi khi xóa sách!";
                }
            }

            if (isset($_POST['tim_kiem'])) {
                $tuKhoa = trim($_POST['tu_khoa'] ?? '');
                $sachList = $this->sach->timKiemSach($tuKhoa);
            } elseif (isset($_POST['xuat_excel'])) {
                $this->xuatExcelSach($sachList);
                die();
            }

            if (isset($_POST['sua'])) {
                $suaSach = trim($_POST['tu_khoa'] ?? '');
                $sachList = $this->sach->timKiemSach($suaSach);
            }
        }

        $data = [
            'action' => 'quanLySach',
            'errors' => $errors,
            'thong_bao' => $thong_bao,
            'sachList' => $sachList,
            'isAdmin' => $isAdmin
        ];
        extract($data);
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function xuatExcelSach($sachList)
    {
        ob_start();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Mã Sách');
        $sheet->setCellValue('B1', 'Tên Sách');
        $sheet->setCellValue('C1', 'Tác Giả');
        $sheet->setCellValue('D1', 'Thể Loại');
        $sheet->setCellValue('E1', 'Năm Xuất Bản');
        $sheet->setCellValue('F1', 'Nhà Xuất Bản');
        $sheet->setCellValue('G1', 'Số Lượng');

        $row = 2;
        foreach ($sachList as $sach) {
            $sheet->setCellValue("A$row", $sach['MaSach']);
            $sheet->setCellValue("B$row", $sach['TenSach']);
            $sheet->setCellValue("C$row", $sach['TenTacGia']);
            $sheet->setCellValue("D$row", $sach['TenTheLoai']);
            $sheet->setCellValue("E$row", $sach['NamXuatBan']);
            $sheet->setCellValue("F$row", $sach['NhaXuatBan']);
            $sheet->setCellValue("G$row", $sach['SoLuong']);
            $row++;
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="danh_sach_sach_' . date('YmdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }


    // public function thongKe() {
    //     if (!isset($_SESSION['user'])) {
    //         header("Location: /public/?action=dangNhap");
    //         exit;
    //     }

    //     $sql = "SELECT COUNT(*) as TongSach FROM Sach";
    //     $stmt = $this->conn->query($sql);
    //     $tongSach = $stmt->fetch(PDO::FETCH_ASSOC)['TongSach'];

    //     $sql = "SELECT COUNT(*) as TongDocGia FROM DocGia";
    //     $stmt = $this->conn->query($sql);
    //     $tongDocGia = $stmt->fetch(PDO::FETCH_ASSOC)['TongDocGia'];

    //     $sql = "SELECT COUNT(*) as TongPhieuMuon FROM PhieuMuon WHERE TrangThai = 'Đang mượn'";
    //     $stmt = $this->conn->query($sql);
    //     $tongPhieuMuon = $stmt->fetch(PDO::FETCH_ASSOC)['TongPhieuMuon'];

    //     $data = [
    //         'action' => 'thongKe',
    //         'tongSach' => $tongSach,
    //         'tongDocGia' => $tongDocGia,
    //         'tongPhieuMuon' => $tongPhieuMuon
    //     ];
    //     extract($data);
    //     require_once __DIR__ . '/../views/layouts/main.php';
    // }

    public function thongKe()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /public/?action=dangNhap");
            exit;
        }

        // Tổng số sách
        $sql = "SELECT COUNT(*) as TongSach FROM Sach";
        $stmt = $this->conn->query($sql);
        $tongSach = $stmt->fetch(PDO::FETCH_ASSOC)['TongSach'];

        // Tổng số độc giả
        $sql = "SELECT COUNT(*) as TongDocGia FROM DocGia";
        $stmt = $this->conn->query($sql);
        $tongDocGia = $stmt->fetch(PDO::FETCH_ASSOC)['TongDocGia'];

        // Tổng số phiếu mượn đang hoạt động
        $sql = "SELECT COUNT(*) as TongPhieuMuon FROM PhieuMuon WHERE TrangThai = 'Đang mượn'";
        $stmt = $this->conn->query($sql);
        $tongPhieuMuon = $stmt->fetch(PDO::FETCH_ASSOC)['TongPhieuMuon'];

        // Số lượng sách đã mượn trong tháng hiện tại (dựa vào Ngày Mượn trong bảng PhieuMuon)
        $sql = "SELECT SUM(SoLuongMuon) as SachMuonThang 
                FROM ChiTietPhieuMuon ctpm
                JOIN PhieuMuon pm ON ctpm.MaPhieuMuon = pm.MaPhieuMuon
                WHERE MONTH(pm.NgayMuon) = MONTH(CURRENT_DATE()) 
                AND YEAR(pm.NgayMuon) = YEAR(CURRENT_DATE())";
        $stmt = $this->conn->query($sql);
        $sachMuonThang = $stmt->fetch(PDO::FETCH_ASSOC)['SachMuonThang'];

        // Số lượng độc giả đã mượn sách trong năm hiện tại
        $sql = "SELECT COUNT(DISTINCT pm.MaDocGia) as DocGiaMuonNam 
                FROM PhieuMuon pm
                WHERE YEAR(pm.NgayMuon) = YEAR(CURRENT_DATE())";
        $stmt = $this->conn->query($sql);
        $docGiaMuonNam = $stmt->fetch(PDO::FETCH_ASSOC)['DocGiaMuonNam'];

        // Thống kê số sách sắp hết hạn trong vòng 3 ngày
        $sql = "SELECT s.TenSach, pm.NgayTra, dg.TenDocGia 
            FROM PhieuMuon pm
            JOIN ChiTietPhieuMuon ctpm ON pm.MaPhieuMuon = ctpm.MaPhieuMuon
            JOIN Sach s ON ctpm.MaSach = s.MaSach
            JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia
            WHERE pm.TrangThai = 'Đang mượn' 
            AND pm.NgayTra BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)";

        $stmt = $this->conn->query($sql);
        $sachSapDenHan = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Gợi ý sách
        $sql = "SELECT s.TenSach, COUNT(ctpm.MaSach) as SoLanMuon 
                FROM ChiTietPhieuMuon ctpm
                JOIN Sach s ON ctpm.MaSach = s.MaSach
                GROUP BY ctpm.MaSach
                ORDER BY SoLanMuon DESC
                LIMIT 5";  // Lấy 5 sách được mượn nhiều nhất
    
        $stmt = $this->conn->query($sql);
        $sachGoiY = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //require_once __DIR__ . '/../views/sach/thong_ke.php';
        $data = [
            'action' => 'thongKe',
            'tongSach' => $tongSach,
            'tongDocGia' => $tongDocGia,
            'tongPhieuMuon' => $tongPhieuMuon,
            'sachMuonThang' => $sachMuonThang,
            'docGiaMuonNam' => $docGiaMuonNam
        ];
        extract($data);
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function themSach()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /public/?action=dangNhap");
            die();
        }

        $errors = [];
        $thong_bao = '';


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tenSach = trim($_POST['ten_sach'] ?? '');
            $tenTacGia = trim($_POST['ten_tac_gia'] ?? '');
            $maTheLoai = $_POST['ma_the_loai'] ?? '';
            $namXuatBan = $_POST['nam_xuat_ban'] ?? '';
            $nhaXuatBan = trim($_POST['nha_xuat_ban'] ?? '');
            $soLuong = $_POST['so_luong'] ?? '';

            if (empty($tenSach)) $errors[] = "Tên sách không được để trống!";
            if (empty($tenTacGia)) $errors[] = "Tác giả không được để trống";
            if (empty($maTheLoai)) $errors[] = "Thể loại không được để trống!";
            if (empty($soLuong) || !is_numeric($soLuong)) $errors[] = "Số lượng không hợp lệ!";

            if (empty($errors)) {
                if ($this->sach->themSach($tenSach, $tenTacGia, $maTheLoai, $namXuatBan, $nhaXuatBan, $soLuong)) {
                    header("Location: ?action=quanLySach");
                    exit();
                } else {
                    $errors[] = "Lỗi khi thêm sách!";
                }
            }
        }

        $data = [
            'action' => 'themSach',
            'errors' => $errors,
            'thong_bao' => $thong_bao
        ];
        extract($data);
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function SuaSach()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /public/?action=dangNhap");
            die();
        }


        $errors = [];
        $thong_bao = '';
        $isAdmin = $_SESSION['user']['VaiTro'] === 'admin';

        $maSach = $_GET['ma_sach'] ?? '';

        if (!empty($maSach)) {
            $sachChiTiet = $this->sach->layThongTinSach($maSach);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['sua'])) {
                $tenSach = trim($_POST['ten_sach'] ?? '');
                $tenTacGia = trim($_POST['ten_tac_gia'] ?? '');
                $maTheLoai = $_POST['ma_the_loai'] ?? '';
                $namXuatBan = $_POST['nam_xuat_ban'] ?? '';
                $nhaXuatBan = trim($_POST['nha_xuat_ban'] ?? '');
                $soLuong = $_POST['so_luong'] ?? '';

                if (empty($tenSach)) $errors[] = "Tên sách không được để trống!";
                if (empty($tenTacGia)) $errors[] = "Tác giả không được để trống";
                if (empty($maTheLoai)) $errors[] = "Thể loại không được để trống!";
                if (empty($soLuong) || !is_numeric($soLuong)) $errors[] = "Số lượng không hợp lệ!";

                if (empty($errors)) {
                    if ($this->sach->suaSach($maSach, $tenSach, $tenTacGia, $maTheLoai, $namXuatBan, $nhaXuatBan, $soLuong)) {
                        header("Location: ?action=quanLySach");
                        exit();
                    } else {
                        $errors[] = "Lỗi khi sửa sách!";
                    }
                }
            }
        }

        $data = [
            'action' => 'SuaSach',
            'errors' => $errors,
            'thong_bao' => $thong_bao,
            'sachChiTiet' => $sachChiTiet ?? null,
            'isAdmin' => $isAdmin
        ];
        extract($data);
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function sachQuaHan()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /public/?action=dangNhap");
            exit;
        }

        $sql = "SELECT pm.MaPhieuMuon, dg.TenDocGia, s.TenSach, pm.NgayTra 
                FROM PhieuMuon pm
                JOIN ChiTietPhieuMuon ctpm ON pm.MaPhieuMuon = ctpm.MaPhieuMuon
                JOIN Sach s ON ctpm.MaSach = s.MaSach
                JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia
                WHERE pm.TrangThai = 'Đang mượn' AND pm.NgayTra < CURDATE()";

        $stmt = $this->conn->query($sql);
        $sachQuaHan = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'action' => 'sachQuaHan',
            'sachQuaHan' => $sachQuaHan
        ];
        extract($data);
        require_once __DIR__ . '/../views/layouts/main.php';
    }  
}
