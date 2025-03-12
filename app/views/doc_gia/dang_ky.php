<h2 class="text-center">Đăng ký tài khoản cho độc giả</h2>

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
        <label for="ten_doc_gia" class="form-label">Tên độc giả</label>
        <input type="text" class="form-control" id="ten_doc_gia" name="ten_doc_gia"
            value="<?php echo htmlspecialchars($ten_doc_gia); ?>" required>
    </div>
    <div class="mb-3">
        <label for="ngay_sinh" class="form-label">Ngày sinh</label>
        <input type="date" class="form-control" id="ngay_sinh" name="ngay_sinh" required>
    </div>
    <div class="mb-3">
        <label for="so_dt" class="form-label">Số điện thoại</label>
        <input type="text" class="form-control" id="so_dt" name="so_dt"
            value="<?php echo htmlspecialchars($so_dt); ?>" required>
    </div>
    <!-- <div class="mb-3">
        <label class="form-label">Mã Captcha</label>
        <img src="?action=captcha" alt="Captcha" class="mb-2">
        <input type="text" class="form-control" name="captcha" required>
    </div> -->
    <button type="submit" class="btn btn-primary">Đăng ký</button>
</form>