<h2 class="text-center">Trả sách</h2>

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

<form method="POST" class="mt-4">
    <div class="mb-3">
        <label for="ma_chi_tiet_pm" class="form-label">Chi tiết phiếu mượn</label>
        <select class="form-control" id="ma_chi_tiet_pm" name="ma_chi_tiet_pm" required>
            <?php
            $sql = "SELECT ctpm.MaChiTietPM, pm.MaPhieuMuon, dg.TenDocGia, s.TenSach 
                    FROM ChiTietPhieuMuon ctpm 
                    JOIN PhieuMuon pm ON ctpm.MaPhieuMuon = pm.MaPhieuMuon 
                    JOIN DocGia dg ON pm.MaDocGia = dg.MaDocGia 
                    JOIN Sach s ON ctpm.MaSach = s.MaSach 
                    LEFT JOIN PhieuTra pt ON ctpm.MaChiTietPM = pt.MaChiTietPM 
                    WHERE pm.TrangThai = 'Đang mượn' AND pt.MaChiTietPM IS NULL";
            $stmt = $conn->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['MaChiTietPM']}'>{$row['MaPhieuMuon']} - {$row['TenDocGia']} - {$row['TenSach']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="ngay_tra_sach" class="form-label">Ngày trả sách</label>
        <input type="date" class="form-control" id="ngay_tra_sach" name="ngay_tra_sach" required>
    </div>
    <button type="submit" class="btn btn-primary">Trả sách</button>
</form>