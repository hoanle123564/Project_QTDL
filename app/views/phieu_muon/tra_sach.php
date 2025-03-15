<h2 class="text-center">Danh sách phiếu trả</h2>

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
        <input type="text" class="form-control" name="tu_khoa" placeholder="Tìm kiếm sách">
        <button type="submit" name="tim_kiem" class="btn btn-outline-secondary">Tìm</button>
    </div>
</form>

<table class="table table-striped mt-4">
    <thead>
        <tr>
            <th>Mã Phiếu Trả</th>
            <th>Tên Độc Giả</th>
            <th>Ngày Mượn</th>
            <th>Ngày Trả</th>
            <!-- <th>Tên Sách</th> -->
            <!-- <th>Số Lượng</th> -->
            <th>Số Tiền Nộp Muộn</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($phieuTraList) && is_array($phieuTraList)): ?>
        <?php foreach ($phieuTraList as $pm): ?>
        <tr>
            <td><?php echo $pm['MaPhieuTra'] ?? 'N/A'; ?></td>
            <td><?php echo $pm['TenDocGia'] ?? 'N/A'; ?></td>
            <td><?php echo $pm['NgayMuon'] ?? 'N/A'; ?></td>
            <td><?php echo $pm['NgayTraSach'] ?? 'N/A'; ?></td>
            <!-- <td><?php echo $pm['TenSach'] ?? 'N/A'; ?></td> -->
            <!-- <td><?php echo $pm['SoLuongSachMuon'] ?? '0'; ?></td> -->
            <td><?php echo number_format($pm['SoTienMuon'], 0, ',', '.') . ' VND'; ?></td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="7" class="text-center">Không có dữ liệu phiếu trả.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>


<form method="POST" action="?action=xuatExcelPhieuTra" class="mt-3">
    <button type="submit" class="btn btn-success">Xuất Excel</button>
</form>