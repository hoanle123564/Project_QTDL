<h2 class="text-center">Đăng nhập</h2>

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
        <label for="ten_dang_nhap" class="form-label">Tên đăng nhập</label>
        <input type="text" class="form-control" id="ten_dang_nhap" name="ten_dang_nhap"
            value="<?php echo htmlspecialchars($ten_dang_nhap); ?>" required>
    </div>

    <div class="mb-3">
        <label for="mat_khau" class="form-label">Mật khẩu</label>
        <input type="password" class="form-control" id="mat_khau" name="mat_khau" required>
    </div>

    <!-- <div class="mb-3">
        <label class="form-label">Mã Captcha</label>
        <img src="?action=captcha" alt="Captcha" class="mb-2">
        <input type="text" class="form-control" name="captcha" required>
    </div> -->

    <button type="submit" class="btn btn-primary">Đăng nhập</button>
</form>