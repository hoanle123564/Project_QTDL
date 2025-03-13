<h2 class="text-center">Quản lý phiếu mượn</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($thong_bao)): ?>
    <div class="alert alert-success"><?php echo $thong_bao; ?></div>
<?php endif; ?>

<!-- Tìm kiếm và thêm sách -->
<form method="POST" class="mt-4 d-flex">
    <div class="input-group mb-3 ">
        <a class="btn btn-primary me-4" href="?action=themPhieuMuon" name="them_sach"></i> Thêm phiếu mượn</a>
        <input type="text" class="form-control" name="tu_khoa" placeholder="Tìm kiếm sách">
        <button type="submit" name="tim_kiem" class="btn btn-outline-secondary">Tìm</button>
    </div>
</form>

<table class="table table-striped mt-4">
    <thead>
        <tr>
            <th>Mã Phiếu Mượn</th>
            <th>Tên Độc Giả</th>
            <th>Ngày Mượn</th>
            <th>Ngày Trả</th>
            <th>Trạng Thái</th>
            <th>Sách Mượn</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($phieuMuonList as $pm): ?>
            <tr>
                <td><?php echo $pm['MaPhieuMuon']; ?></td>
                <td><?php echo $pm['TenDocGia']; ?></td>
                <td><?php echo $pm['NgayMuon']; ?></td>
                <td><?php echo $pm['NgayTra']; ?></td>
                <td><?php echo $pm['TrangThai']; ?></td>
                <td><?php echo $pm['SachMuon']; ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="ma_phieu_muon" value="<?php echo $pm['MaPhieuMuon']; ?>">
                        <button type="submit" name="capNhatTrangThai" class="btn btn-warning btn-sm"
                            onclick="return confirm('Chuyển trạng thái phiếu mượn này sang Đã trả?')">
                            Cập nhật trạng thái
                        </button>
                    </form>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<form method="POST" action="?action=xuatExcelPhieuMuon" class="mt-3">
    <button type="submit" class="btn btn-success">Xuất Excel</button>
</form>