<h2 class="text-center">Quản lý tác giả</h2>
<?php

use App\Models\TacGia;

 if (!empty($errors)): ?>
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

<form method="POST" class="mt-4 d-flex">
    <div class="input-group mb-3 ">
        <input type="text" class="form-control" name="tu_khoa" placeholder="Tìm kiếm tác giả">
        <button type="submit" name="tim_kiem" class="btn btn-outline-secondary">Tìm</button>
    </div>
</form>

<!-- Danh sách sách -->
<table class="table table-striped mt-4">
    <thead>
        <tr>
            <th>Mã tác giả</th>
            <th>Tên tác giả</th>
            <th>Sách</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tacGiaList as $tacgia): ?>
        <tr>
            <td><?php echo $tacgia['MaTacGia']; ?></td>
            <td><?php echo $tacgia['TenTacGia']; ?></td>
            <td>
            <?php 
                $temp = new TacGia($this->conn);
                $sachList = $temp->layTacGia($tacgia['MaTacGia'])->laySachCuaTacGia($tacgia['MaTacGia']);
                $sachArr = array_map(fn($sach) => $sach['TenSach'], $sachList);
                echo implode(", ", $sachArr);
            ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- <form method="POST" action="?action=xuatExcelSach" class="mb-3">
    <button type="submit" class="btn btn-success mx-3">Xuất Excel</button>
</form> -->