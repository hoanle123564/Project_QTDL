<?php
namespace App\Controllers;

use App\Models\DocGia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PDO;
use PDOException;

class DocGiaController {
    private $conn;
    public $docgia;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->docgia = new DocGia($conn);
    }

    public function quanLyDocGia() {
        if (!isset($_SESSION['user'])) {
            header("Location: /public/?action=dangNhap");
            die();
        }

        $errors = [];
        $thong_bao = '';
        $docGiaList = $this->docgia->danhSachDocGia();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['xoa'])) {
                $maDocGia = $_POST['ma_doc_gia'] ?? '';
                
                if ($this->docgia->xoaDocGia($maDocGia)) {
                    $thong_bao = "Xóa độc giả thành công!";
                    $docGiaList = $this->docgia->danhSachDocGia();
                } else {
                    $errors[] = "Lỗi khi xóa độc giả!";
                }
            }

            if (isset($_POST['tim_kiem'])) {
                $tuKhoa = trim($_POST['tu_khoa'] ?? '');
                $docGiaList = $this->docgia->timKiemDocGia($tuKhoa);
            } elseif (isset($_POST['xuat_excel'])) {
                $this->xuatExcelSach($docGiaList);
                die();
            }
            
            if (isset($_POST['sua'])) {
                $maDocGia = trim($_POST['ma_doc_gia'] ?? '');
                $docGiaList = $this->docgia->layThongTinDocGia($maDocGia);
            }
        }

        $data = [
            'action' => 'quanLyDocGia',
            'errors' => $errors,
            'thong_bao' => $thong_bao,
            'docGiaList' => $docGiaList,
        ];
        extract($data);
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function xuatExcelSach($sachList) {
        ob_start();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Mã Độc Giả');
        $sheet->setCellValue('B1', 'Tên Độc Giả');
        $sheet->setCellValue('C1', 'Ngày Sinh');
        $sheet->setCellValue('D1', 'Số Điện Thoại');
        $sheet->setCellValue('E1', 'Năm Xuất Bản');
        $sheet->setCellValue('F1', 'Nhà Xuất Bản');
        $sheet->setCellValue('G1', 'Số Lượng');

        $row = 2;
        foreach ($sachList as $sach) {
            $sheet->setCellValue("A$row", $sach['MaDocGia']);
            $sheet->setCellValue("B$row", $sach['TenDocGia']);
            $sheet->setCellValue("C$row", $sach['NgaySinh']);
            $sheet->setCellValue("D$row", $sach['SoDienThoai']);
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


    public function thongKe() {
        if (!isset($_SESSION['user'])) {
            header("Location: /public/?action=dangNhap");
            exit;
        }
    
        $sql = "SELECT COUNT(*) as TongSach FROM Sach";
        $stmt = $this->conn->query($sql);
        $tongSach = $stmt->fetch(PDO::FETCH_ASSOC)['TongSach'];
    
        $sql = "SELECT COUNT(*) as TongDocGia FROM DocGia";
        $stmt = $this->conn->query($sql);
        $tongDocGia = $stmt->fetch(PDO::FETCH_ASSOC)['TongDocGia'];
    
        $sql = "SELECT COUNT(*) as TongPhieuMuon FROM PhieuMuon WHERE TrangThai = 'Đang mượn'";
        $stmt = $this->conn->query($sql);
        $tongPhieuMuon = $stmt->fetch(PDO::FETCH_ASSOC)['TongPhieuMuon'];
    
        $data = [
            'action' => 'thongKe',
            'tongSach' => $tongSach,
            'tongDocGia' => $tongDocGia,
            'tongPhieuMuon' => $tongPhieuMuon
        ];
        extract($data);
        require_once __DIR__ . '/../views/layouts/main.php';
    }
    public function dangKyDocGia() {
        $errors = [];
        $thong_bao = '';
        $ten_doc_gia = '';
        $ngay_sinh = '';
        $so_dt = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ten_doc_gia = trim($_POST['ten_doc_gia'] ?? '');
            $ngay_sinh = $_POST['ngay_sinh'] ?? '';
            $so_dt = trim($_POST['so_dt'] ?? '');
            // $captcha_input = $_POST['captcha'] ?? '';
            // $captcha_session = $_SESSION['captcha'] ?? '';

            if (empty($ten_doc_gia)) $errors[] = "Tên độc giả không được để trống!";
            if (empty($ngay_sinh)) $errors[] = "Ngày sinh không được để trống!";
            if (empty($so_dt)) $errors[] = "Số điện thoại không được để trống!";
            // if ($captcha_input !== $captcha_session) $errors[] = "Mã captcha không đúng!";

            if (empty($errors)) {
                $sql = "INSERT INTO DocGia (TenDocGia, NgaySinh, SoDienThoai) 
                        VALUES (:ten_doc_gia, :ngay_sinh, :so_dt)";
                $stmt = $this->conn->prepare($sql);
                try {
                    $stmt->execute([
                        ':ten_doc_gia' => $ten_doc_gia,
                        ':ngay_sinh' => $ngay_sinh,
                        ':so_dt' => $so_dt
                    ]);
                    $thong_bao = "Đăng ký tài khoản cho độc giả thành công!";
                    $ten_dang_nhap = '';
                    $ho_ten = '';
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) { // Duplicate entry
                        $errors[] = "Tên độc giả đã tồn tại!";
                    } else {
                        $errors[] = "Lỗi khi đăng ký: " . $e->getMessage();
                    }
                }
            }
        }

        $data = [
            'action' => 'dangKyDocGia',
            'errors' => $errors,
            'thong_bao' => $thong_bao,
            'ten_doc_gia' => $ten_doc_gia,
            'ngay_sinh' => $ngay_sinh,
            'so_dt' => $so_dt
        ];
        extract($data);
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function SuaDocGia() {
        if (!isset($_SESSION['user'])) {
            header("Location: /public/?action=dangNhap");
            die();
        }
       
        $errors = [];
        $thong_bao = '';
        
        $maDocGia = $_GET['ma_doc_gia'] ?? '';
        // echo !empty($maDocGia); exit();

        if (!empty($maDocGia)) {
            $docGiaChiTiet = $this->docgia->layThongTinDocGia($maDocGia);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['sua'])) {
                $tenDocGia = trim($_POST['ten_doc_gia'] ?? '');
                $ngaySinh = trim ($_POST['ngay_sinh'] ?? '');
                $soDT = trim($_POST['so_dt']) ?? '';

                if (empty($tenDocGia)) $errors[] = "Tên độc giả không được để trống!";
                if (empty($ngaySinh)) $errors[] = "Ngày sinh không được để trống";
                if (empty($soDT)) $errors[] = "Số điện thoại không được để trống!";

                if (empty($errors)) {
                    if ($this->docgia->suaDocGia($maDocGia, $tenDocGia, $ngaySinh, $soDT)) {
                        header("Location: ?action=quanLyDocGia"); 
                        exit();
                    } else {
                        $errors[] = "Lỗi khi sửa thông tin độc giả!";
                    }
                }
            } 
        }

        $data = [
            'action' => 'SuaDocGia',
            'errors' => $errors,
            'thong_bao' => $thong_bao,
            'docGiaChiTiet' => $docGiaChiTiet ?? null,
        ];
        extract($data);
        require_once __DIR__ . '/../views/layouts/main.php';
    }
    
}