<?php
namespace App\Controllers;

use App\Models\DocGia;
use App\Models\NguoiDung;
use Gregwar\Captcha\CaptchaBuilder;
use PDOException;

class AuthController {
    private $conn;
    private $docGia;
    private $nguoiDung;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->docGia = new DocGia($conn);
        $this->nguoiDung = new NguoiDung($conn);
    }

    public function dangKy() {
        $errors = [];
        $thong_bao = '';
        $ten_dang_nhap = '';
        $ho_ten = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ten_dang_nhap = trim($_POST['ten_dang_nhap'] ?? '');
            $mat_khau = $_POST['mat_khau'] ?? '';
            $ho_ten = trim($_POST['ho_ten'] ?? '');
            // $captcha_input = $_POST['captcha'] ?? '';
            // $captcha_session = $_SESSION['captcha'] ?? '';

            if (empty($ten_dang_nhap)) $errors[] = "Tên đăng nhập không được để trống!";
            if (empty($mat_khau)) $errors[] = "Mật khẩu không được để trống!";
            if (strlen($mat_khau) < 6) $errors[] = "Mật khẩu phải từ 6 ký tự trở lên!";
            if (empty($ho_ten)) $errors[] = "Họ tên không được để trống!";
            // if ($captcha_input !== $captcha_session) $errors[] = "Mã captcha không đúng!";

            if (empty($errors)) {
                $mat_khau_hash = password_hash($mat_khau, PASSWORD_DEFAULT);
                $sql = "INSERT INTO NguoiDung (TenDangNhap, MatKhau, HoTen) 
                        VALUES (:ten_dang_nhap, :mat_khau, :ho_ten)";
                $stmt = $this->conn->prepare($sql);
                try {
                    $stmt->execute([
                        ':ten_dang_nhap' => $ten_dang_nhap,
                        ':mat_khau' => $mat_khau_hash,
                        ':ho_ten' => $ho_ten
                    ]);
                    $thong_bao = "Đăng ký tài khoản thành công!";
                    $ten_dang_nhap = '';
                    $ho_ten = '';
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) { // Duplicate entry
                        $errors[] = "Tên đăng nhập đã tồn tại!";
                    } else {
                        $errors[] = "Lỗi khi đăng ký: " . $e->getMessage();
                    }
                }
            }
        }

        $data = [
            'action' => 'dangKy',
            'errors' => $errors,
            'thong_bao' => $thong_bao,
            'ten_dang_nhap' => $ten_dang_nhap,
            'ho_ten' => $ho_ten
        ];
        extract($data);
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function dangNhap() {
        $errors = [];
        $thong_bao = '';
        $ten_dang_nhap = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ten_dang_nhap = trim($_POST['ten_dang_nhap'] ?? '');
            $mat_khau = $_POST['mat_khau'] ?? '';
            // $captcha_input = $_POST['captcha'] ?? '';
            // $captcha_session = $_SESSION['captcha'] ?? '';

            if (empty($ten_dang_nhap)) $errors[] = "Tên đăng nhập không được để trống!";
            if (empty($mat_khau)) $errors[] = "Mật khẩu không được để trống!";
            // if ($captcha_input !== $captcha_session) $errors[] = "Mã captcha không đúng!";

            if (empty($errors)) {
                $user = $this->nguoiDung->kiemTraDangNhap($ten_dang_nhap, $mat_khau);
                if ($user) {
                    $_SESSION['user'] = $user;
                    header("Location: /public/?action=trangChu");
                    exit;
                } else {
                    $errors[] = "Tên đăng nhập hoặc mật khẩu không đúng!";
                }
            }
        }

        $data = [
            'action' => 'dangNhap',
            'errors' => $errors,
            'thong_bao' => $thong_bao,
            'ten_dang_nhap' => $ten_dang_nhap
        ];
        extract($data);
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function dangXuat() {
        session_destroy();
        header("Location: /public/?action=trangChu");
        exit;
    }

    public function captcha() {
        header('Content-Type: image/jpeg');
        $captcha = new CaptchaBuilder();
        $captcha->build();
        $_SESSION['captcha'] = $captcha->getPhrase();
        $captcha->output();
        exit;
    }
}