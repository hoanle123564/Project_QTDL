<!DOCTYPE html>
<html lang="vi">

<head>
    <title><?php
            switch ($action) {
                case 'trangChu':
                    echo 'Trang chủ';
                    break;
                case 'dangKy':
                    echo 'Đăng ký độc giả';
                    break;
                case 'dangNhap':
                    echo 'Đăng nhập';
                    break;
                case 'quanLySach':
                    echo 'Quản lý sách';
                    break;
                case 'quanLyDocGia':
                    echo 'Quản lý độc giả';
                    break;
                case 'themPhieuMuon':
                    echo 'Thêm phiếu mượn';
                    break;
                case 'thongKe':
                    echo 'Thống kê';
                    break;
                default:
                    echo 'Trang chủ';
            }
            ?></title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php require_once '../db_connect.php'?>

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="?action=trangChu">Thư viện</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="?action=trangChu">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=dangKy">Đăng ký</a>
                    </li>
                    <?php if (!isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=dangNhap">Đăng nhập</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=quanLyDocGia">Quản lý độc giả</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=quanLySach">Quản lý sách</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=themPhieuMuon">Thêm phiếu mượn</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=quanLyPhieuMuon">Quản lý phiếu mượn</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=traSach">Trả sách</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=thongKe">Thống kê</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=dangXuat">Đăng xuất</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Nội dung chính -->
    <div class="container mt-5">
        <?php
        if ($action === 'trangChu') {
            require_once __DIR__ . '/../trang_chu.php';
        } else {
            echo '<a href="?action=trangChu" class="btn btn-secondary mb-3">Quay lại Trang chủ</a>';
            if ($action === 'dangKy') {
                require_once __DIR__ . '/../auth/dang_ky.php';
            } elseif ($action === 'dangNhap') {
                require_once __DIR__ . '/../auth/dang_nhap.php';
            } elseif ($action === 'quanLySach') {
                require_once __DIR__ . '/../sach/quan_ly_sach.php';
            } elseif ($action === 'quanLyDocGia') {
                require_once __DIR__ . '/../doc_gia/quan_ly_doc_gia.php';
            } elseif ($action === 'dangKyDocGia') {
                require_once __DIR__ . '/../doc_gia/dang_ky.php';
            } elseif ($action === 'themPhieuMuon') {
                require_once __DIR__ . '/../phieu_muon/them_phieu_muon.php';
            } elseif ($action === 'thongKe') {
                require_once __DIR__ . '/../sach/thong_ke.php';
            } elseif ($action === 'quanLyPhieuMuon') {
                require_once __DIR__ . '/../phieu_muon/quan_ly_phieu_muon.php';
            } elseif ($action === 'traSach') {
                require_once __DIR__ . '/../phieu_muon/tra_sach.php';
            } elseif ($action === 'themSach') {
                require_once __DIR__ . '/../sach/them_sach.php';
            } elseif ($action === 'SuaSach') {
                require_once __DIR__ . '/../sach/sua sach.php';
            } elseif ($action === 'SuaDocGia') {
                require_once __DIR__ . '/../doc_gia/sua_doc_gia.php';
            }
            
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>