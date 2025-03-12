<h2 class="text-center">Sửa độc giả</h2>
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
    <input type="hidden" name="ma_doc_gia" value="<?php echo $docGiaChiTiet['MaDocGia'] ?? ''; ?>">

    <!-- Têm độc giả -->
    <div class="mb-3 d-flex">
        <label for="ten_doc_gia" class="col-md-3 col-form-label"><b>Tên độc giả</b> </label>
        <input type="text" class="form-control" name="ten_doc_gia" required
            value="<?= htmlspecialchars($docGiaChiTiet['TenDocGia'] ?? ''); ?>">
    </div>

    <!-- Ngày sinh -->
    <div class="mb-3 d-flex">
        <label for="ngay_sinh" class="col-md-3 col-form-label"><b>Ngày sinh</b></label>
        <input type="date" class="form-control" name="ngay_sinh" required
            value="<?php echo htmlspecialchars($docGiaChiTiet['NgaySinh'] ?? ''); ?>">
    </div>


    <!-- Năm xuất bản -->
    <div class="mb-3 d-flex">
        <label for="so_dt" class="col-md-3 col-form-label"><b>Số điện thoại</b></label>
        <input type="text" class="form-control" name="so_dt" required
            value="<?php echo htmlspecialchars($docGiaChiTiet['SoDienThoai'] ?? ''); ?>">
    </div>


    <!-- Submit -->
    <button type="submit" name="sua" class="btn btn-primary mb-4">Cập nhật</button>
</form>