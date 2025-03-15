<?php
namespace App\Controllers;

use App\Models\TacGia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PDO;
use PDOException;

class TacGiaController {
    private $conn;
    public $TacGia;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->TacGia = new TacGia($conn);
    }

    public function quanLyTacGia() {
        if (!isset($_SESSION['user'])) {
            header("Location: /public/?action=dangNhap");
            die();
        }

        $errors = [];
        $thong_bao = '';
        $tacGiaList = $this->TacGia->danhSachTacGia();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['tim_kiem'])) {
                $tuKhoa = trim($_POST['tu_khoa'] ?? '');
                $tacGiaList = $this->TacGia->timKiemTacGia($tuKhoa);
            }
        }

        $data = [
            'action' => 'quanLyTacGia',
            'errors' => $errors,
            'thong_bao' => $thong_bao,
            'TacGiaList' => $tacGiaList,
        ];
        extract($data);
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function xuatExcelTacGia($sachList) {
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
            $sheet->setCellValue("A$row", $sach['MaTacGia']);
            $sheet->setCellValue("B$row", $sach['TenTacGia']);
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
}