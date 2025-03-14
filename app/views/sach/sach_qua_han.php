<h2 class="text-center">Sách quá hạn</h2>
<table class="table table-striped mt-4">
    <thead>
        <tr>
            <th>Mã Phiếu Mượn</th>
            <th>Tên Độc Giả</th>
            <th>Tên Sách</th>
            <th>Ngày Trả (Quá hạn)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sachQuaHan as $sach): ?>
            <tr>
                <td><?php echo $sach['MaPhieuMuon']; ?></td>
                <td><?php echo $sach['TenDocGia']; ?></td>
                <td><?php echo $sach['TenSach']; ?></td>
                <td class="text-danger"><?php echo $sach['NgayTra']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
