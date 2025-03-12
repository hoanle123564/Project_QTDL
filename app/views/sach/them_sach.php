<h2 class="text-center">Thêm sách mới</h2>
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


<!-- Form Thêm sách -->
<form method="POST" class="mt-3 col-md-5 offset-md-3">
    <!-- ten_sach -->
    <div class=" mb-3 d-flex">
        <label for="ten_sach" class="col-md-3 col-form-label"><b>Tên sách</b></label>
        <input type="text" class="form-control" name="ten_sach" placeholder="Nhập tên sách" required>
    </div>
    <!-- ma_tac_gia -->
    <div class=" mb-3 d-flex">
        <label for="ten_tac_gia" class="col-md-3 col-form-label"><b>Tác giả</b></label>
        <input type="text" class="form-control" name="ten_tac_gia" placeholder="Tên tác giả" required>
        <!-- <select class="form-control" name="ma_tac_gia" required>
            <?php
                    $statment = $conn->query("SELECT * FROM TacGia");
                    $statment->execute();
                    $result = $statment->setFetchMode(PDO::FETCH_ASSOC);
                    $tacGiaList = $statment->fetchAll();
                    foreach ($tacGiaList as $row){
                    $id = $row['MaTacGia'] ;
                    $name = $row['TenTacGia'] ;
                    echo "<option value='$id'>$name</option>";
                }
                ?>
        </select> -->
    </div>
    <!-- ma_the_loai -->
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
    <div class=" mb-3 d-flex">
        <label for="nam_xuat_ban" class="col-md-3 col-form-label"><b>Năm xuất bản</b></label>
        <input type="text" class="form-control" name="nam_xuat_ban" placeholder="Năm xuất bản">
    </div>
    <!-- Nhà xuất bản -->
    <div class=" mb-3 d-flex">
        <label for="nha_xuat_ban" class="col-md-3 col-form-label"><b>Nhà xuất bản</b></label>
        <input type="text" class="form-control" name="nha_xuat_ban" placeholder="Nhà xuất bản">
    </div>
    <!-- Số lượng -->
    <div class=" mb-3 d-flex">
        <label for="so_luong" class="col-md-3 col-form-label"><b>Số lượng</b></label>
        <input type="text" class="form-control" name="so_luong" placeholder="Số lượng" required>
    </div>

    <div class="col-md-4 mb-4 ">
        <button type="submit" name="them" class="btn btn-primary ">Thêm sách</button>
    </div>
</form>