<h2 class="text-center">Quản lý sách</h2>
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
        <a class="btn btn-primary me-4" href="?action=themSach" name="them_sach"></i> Thêm sách</a>
        <input type="text" class="form-control" name="tu_khoa" placeholder="Tìm kiếm sách">
        <button type="submit" name="tim_kiem" class="btn btn-outline-secondary">Tìm</button>
    </div>
</form>

<!-- Danh sách sách -->
<table class="table table-striped mt-4">
    <thead>
        <tr>
            <th>Mã Sách</th>
            <th>Tên Sách</th>
            <th>Tác Giả</th>
            <th>Thể Loại</th>
            <th>Năm Xuất Bản</th>
            <th>Nhà Xuất Bản</th>
            <th>Số Lượng</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sachList as $sach): ?>
        <tr>
            <td><?php echo $sach['MaSach']; ?></td>
            <td><?php echo $sach['TenSach']; ?></td>
            <td><?php echo $sach['TenTacGia']; ?></td>
            <td><?php echo $sach['TenTheLoai']; ?></td>
            <td><?php echo $sach['NamXuatBan']; ?></td>
            <td><?php echo $sach['NhaXuatBan']; ?></td>
            <td><?php echo $sach['SoLuong']; ?></td>
            <td>
                <!-- <form method="POST" style="display:inline;">
                    <input type="hidden" name="ma_sach" value="<?php echo $sach['MaSach']; ?>">
                    <input type="text" name="ten_sach" value="<?php echo htmlspecialchars($sach['TenSach']); ?>"
                        class="form-control d-inline-block w-auto" required>
                    <select name="ma_tac_gia" class="form-control d-inline-block w-auto" required>
                        <?php
                                $stmt = $conn->query("SELECT * FROM TacGia");
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = $row['MaTacGia'] == $sach['MaTacGia'] ? 'selected' : '';
                                    echo "<option value='{$row['MaTacGia']}' $selected>{$row['TenTacGia']}</option>";
                                }
                                ?>
                    </select>
                    <select name="ma_the_loai" class="form-control d-inline-block w-auto" required>
                        <?php
                                $stmt = $conn->query("SELECT * FROM TheLoai");
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = $row['MaTheLoai'] == $sach['MaTheLoai'] ? 'selected' : '';
                                    echo "<option value='{$row['MaTheLoai']}' $selected>{$row['TenTheLoai']}</option>";
                                }
                                ?>
                    </select>
                    <input type="number" name="nam_xuat_ban" value="<?php echo $sach['NamXuatBan']; ?>"
                        class="form-control d-inline-block w-auto">
                    <input type="text" name="nha_xuat_ban" value="<?php echo htmlspecialchars($sach['NhaXuatBan']); ?>"
                        class="form-control d-inline-block w-auto">
                    <input type="number" name="so_luong" value="<?php echo $sach['SoLuong']; ?>"
                        class="form-control d-inline-block w-auto" required>

                </form> -->
                <form method="POST" value="<?php echo $sach['MaSach']; ?>">
                    <!-- <input type="hidden" name="ma_sach" value="<?php echo $sach['MaSach']; ?>">
                    <input type="hidden" name="ten_sach" value="<?php echo htmlspecialchars($sach['TenSach']); ?>">
                    <input type="hidden" name="ma_tac_gia" value="<?php echo $sach['MaTacGia']; ?>">
                    <input type="hidden" name="ma_the_loai" value="<?php echo $sach['MaTheLoai']; ?>">
                    <input type="hidden" name="nam_xuat_ban" value="<?php echo $sach['NamXuatBan']; ?>">
                    <input type="hidden" name="so_luong" value="<?php echo $sach['SoLuong']; ?>"> -->
                    <!-- btn -->
                    <a href="?action=SuaSach&ma_sach=<?php echo $sach['MaSach']; ?>"
                        class="btn btn-warning btn-sm">Sửa</a>
                    <!-- <button type="submit" name="sua" class="btn btn-warning btn-sm">Sửa</button> -->
                    <button type="submit" name="xoa" class="btn btn-danger btn-sm"
                        onclick="return confirm('Xóa sách này?')">Xóa</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<form method="POST" action="?action=xuatExcelSach" class="mb-3">
    <button type="submit" class="btn btn-success mx-3">Xuất Excel</button>
</form>