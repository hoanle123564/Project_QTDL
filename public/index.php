<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';
use App\Controllers\AuthController;
use App\Controllers\SachController;
use App\Controllers\PhieuMuonController;
$config = require_once __DIR__ . '/../config/database.php';
try {
    $conn = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
        $config['username'],
        $config['password']
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : 'trangChu');

switch ($action) {
    case 'trangChu':
        $data = ['action' => 'trangChu'];
        extract($data);
        require_once __DIR__ . '/../app/views/layouts/main.php';
        break;
    case 'dangKy':
        $controller = new AuthController($conn);
        $controller->dangKy();
        break;
    case 'dangNhap':
        $controller = new AuthController($conn);
        $controller->dangNhap();
        break;
    case 'dangXuat':
        $controller = new AuthController($conn);
        $controller->dangXuat();
        break;
    case 'captcha':
        $controller = new AuthController($conn);
        $controller->captcha();
        break;
    case 'quanLySach':
        $controller = new SachController($conn);
        $controller->quanLySach();
        break;
    case 'themPhieuMuon':
        $controller = new SachController($conn);
        $controller->themPhieuMuon();
        break;
    case 'thongKe':
        $controller = new SachController($conn);
        $controller->thongKe();
        break;
    case 'quanLyPhieuMuon':
        $controller = new PhieuMuonController($conn);
        $controller->quanLyPhieuMuon();
        break;
    case 'traSach':
        $controller = new PhieuMuonController($conn);
        $controller->traSach();
        break;
    case 'xuatExcelSach':
        $controller = new SachController($conn);
        $controller->xuatExcelSach($controller->sach->danhSachSach());
        die();
        break;
    case 'xuatExcelPhieuMuon':
        $controller = new PhieuMuonController($conn);
        $controller->xuatExcelPhieuMuon($controller->phieuMuon->danhSachPhieuMuon());
        die();
        break;
    case 'themSach':
            $controller = new SachController($conn);
            $controller->themSach();
            break;
    case 'SuaSach':
        $controller = new SachController($conn);
        $controller->SuaSach();
        break;
    default:
        $data = ['action' => 'trangChu'];
        extract($data);
        require_once __DIR__ . '/../app/views/layouts/main.php';
}