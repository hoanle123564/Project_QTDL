<h2 class="text-center">Thống kê</h2>
<table class="table table-striped mt-4">
    <thead>
        <tr>
            <th>Loại thống kê</th>
            <th>Số lượng</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Tổng số sách</td>
            <td><?php echo $tongSach; ?></td>
        </tr>
        <tr>
            <td>Tổng số độc giả</td>
            <td><?php echo $tongDocGia; ?></td>
        </tr>
        <tr>
            <td>Tổng số phiếu mượn đang hoạt động</td>
            <td><?php echo $tongPhieuMuon; ?></td>
        </tr>
        <tr>
            <td>Số sách đã mượn trong tháng</td>
            <td><?php echo $sachMuonThang; ?></td>
        </tr>
        <tr>
            <td>Số độc giả đã mượn sách trong năm</td>
            <td><?php echo $docGiaMuonNam; ?></td>
        </tr>
    </tbody>
</table>

<h3 class="mt-5">📢 Sách sắp đến hạn trả</h3>
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>Tên sách</th>
            <th>Ngày trả</th>
            <th>Độc giả</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($sachSapDenHan)): ?>
            <?php foreach ($sachSapDenHan as $sach): ?>
                <tr>
                    <td><?php echo $sach['TenSach']; ?></td>
                    <td><?php echo $sach['NgayTra']; ?></td>
                    <td><?php echo $sach['TenDocGia']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="text-center">Không có sách nào sắp đến hạn trả</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<h3 class="mt-5">📚 Sách được mượn nhiều nhất</h3>
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>Tên sách</th>
            <th>Số lần mượn</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($sachGoiY)): ?>
            <?php foreach ($sachGoiY as $sach): ?>
                <tr>
                    <td><?php echo $sach['TenSach']; ?></td>
                    <td><?php echo $sach['SoLanMuon']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2" class="text-center">📖 Chưa có dữ liệu</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<a href="?action=sachQuaHan" class="btn btn-danger mt-3">Xem sách quá hạn</a>
<br><br>