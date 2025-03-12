<h2 class="text-center">Sửa sách</h2>
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

<form method="POST" class="mt-3 col-md-5 offset-md-3">
    <input type="hidden" name="ma_sach" value="<?php echo $sachChiTiet['MaSach'] ?? ''; ?>">

    <!-- Name -->
    <div class="mb-3 d-flex">
        <label for="ten_sach" class="col-md-3 col-form-label"><b>Tên sách</b> </label>
        <input type="text" class="form-control" name="ten_sach" required
            value="<?php echo htmlspecialchars($sachChiTiet['TenSach'] ?? ''); ?>">
    </div>

    <!-- Tác giả -->
    <div class="mb-3 d-flex">
        <label for="ten_tac_gia" class="col-md-3 col-form-label"><b>Tác giả</b></label>
        <input type="text" class="form-control" name="ten_tac_gia" required
            value="<?php echo htmlspecialchars($sachChiTiet['TenTacGia'] ?? ''); ?>">
    </div>

    <!-- Thể loại -->
    <div class=" mb-3 d-flex">
        <label for="ma_the_loai" class="col-md-3 col-form-label"><b>Thể loại</b></label>
        <select class="form-control" name="ma_the_loai" required>
            <?php
                    $stmt = $conn->query("SELECT * FROM TheLoai");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['MaTheLoai']}'>{$row['TenTheLoai']}</option>";
                    }
                    ?>
        </select>
    </div>

    <!-- Năm xuất bản -->
    <div class="mb-3 d-flex">
        <label for="nam_xuat_ban" class="col-md-3 col-form-label"><b>Năm xuất bản</b></label>
        <input type="text" class="form-control" name="nam_xuat_ban" required
            value="<?php echo htmlspecialchars($sachChiTiet['NamXuatBan'] ?? ''); ?>">
    </div>

    <!-- Nhà xuất bản -->
    <div class="mb-3 d-flex">
        <label for="nha_xuat_ban" class="col-md-3 col-form-label"><b>Nhà xuất bản</b></label>
        <input type="text" class="form-control" name="nha_xuat_ban" required
            value="<?php echo htmlspecialchars($sachChiTiet['NhaXuatBan'] ?? ''); ?>">
    </div>

    <!-- Số lượng -->
    <div class="mb-3 d-flex">
        <label for="so_luong" class="col-md-3 col-form-label"><b>Số lượng</b></label>
        <input type="text" class="form-control" name="so_luong" required
            value="<?php echo htmlspecialchars($sachChiTiet['SoLuong'] ?? ''); ?>">
    </div>

    <!-- Submit -->
    <button type=" submit" name="sua" class="btn btn-primary  mb-4">Cập nhật sách</button>
</form>