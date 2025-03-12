<h2 class="text-center">Thêm phiếu mượn</h2>

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
        <label for="ma_doc_gia" class="form-label">Độc giả</label>
        <select class="form-control" id="ma_doc_gia" name="ma_doc_gia">
            <?php
            $stmt = $conn->query("SELECT * FROM DocGia");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['MaDocGia']}'>{$row['TenDocGia']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="ma_sach" class="form-label">Sách</label>
        <select class="form-control" id="ma_sach" name="ma_sach" required>
            <?php
            $stmt = $conn->query("SELECT * FROM Sach WHERE SoLuong > 0");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['MaSach']}'>{$row['TenSach']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="so_luong_muon" class="form-label">Số lượng mượn</label>
        <input type="number" class="form-control" id="so_luong_muon" name="so_luong_muon" min="1" required>
    </div>
    <button type="submit" class="btn btn-primary">Thêm phiếu mượn</button>
</form>