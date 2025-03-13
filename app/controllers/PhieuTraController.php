<?php

namespace App\Controllers;

use App\Models\PhieuTra;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PDO;
use Exception;

class PhieuTraController
{
    private $conn;
    public $phieuTra;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->phieuTra = new PhieuTra($conn);
    }

    public function quanLyPhieuTra() {
        if (!isset($_SESSION['user'])) {
            header("Location: /public/?action=dangNhap");
            exit;
        }

        $errors = [];
        $thong_bao = '';
        $phieuTraList = $this->phieuTra->danhSachPhieuTra();
        
        $data = [
            'action' => 'quanLyPhieuTra',
            'errors' => $errors,
            'thong_bao' => $thong_bao,
            'phieuTraList' => $phieuTraList
        ];
        extract($data);
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function xuatExcelPhieuTra($phieuTraList) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Thiết lập tiêu đề các cột
        $sheet->setCellValue('A1', 'Mã Phiếu Mượn');
        $sheet->setCellValue('B1', 'Tên Độc Giả');
        $sheet->setCellValue('C1', 'Ngày Mượn');
        $sheet->setCellValue('D1', 'Ngày Trả');
        $sheet->setCellValue('E1', 'Tên Sách');
        $sheet->setCellValue('F1', 'Số Lượng');
        $sheet->setCellValue('G1', 'Số Tiền Nộp Muộn');
    
        // Ghi dữ liệu vào file Excel
        $row = 2;
        foreach ($phieuTraList as $pm) {
            $sheet->setCellValue("A$row", $pm['MaPhieuMuon'] ?? 'N/A');
            $sheet->setCellValue("B$row", $pm['TenDocGia'] ?? 'N/A');
            $sheet->setCellValue("C$row", $pm['NgayMuon'] ?? 'N/A');
            $sheet->setCellValue("D$row", $pm['NgayTra'] ?? 'N/A');
            $sheet->setCellValue("E$row", $pm['TenSach'] ?? 'N/A');
            $sheet->setCellValue("F$row", $pm['SoLuongSachMuon'] ?? '0');
            $sheet->setCellValue("G$row", $pm['SoTienMuon'] ? number_format($pm['SoTienMuon'], 0, ',', '.') . ' VND' : '0 VND');
            $row++;
        }
    
        // Thiết lập header cho file Excel khi tải về
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="danh_sach_phieu_tra_' . date('YmdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    

    // public function danhSachPhieuTra()
    // {
    //     $sql = "CALL DanhSachPhieuTraChuaTra()";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
    
}