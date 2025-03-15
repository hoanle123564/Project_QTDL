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

<form method="POST" class="mt-3 col-md-8 offset-md-2">
    <div class="mb-3">
        <label for="ma_doc_gia" class="form-label"><b>Độc giả</b></label>
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
        <label for="ma_sach" class="form-label"><b>Chọn sách</b></label>
        <select class="form-control" id="ma_sach">
            <?php
            $stmt = $conn->query("SELECT * FROM Sach WHERE SoLuong > 0");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['MaSach']}' data-ten='{$row['TenSach']}' data-soluong='{$row['SoLuong']}'>
                        {$row['TenSach']} (Còn: {$row['SoLuong']})
                      </option>";
            }
            ?>
        </select>
        <input type="number" id="so_luong" class="form-control mt-2" placeholder="Số lượng mượn" min="1">
        <button type="button" class="btn btn-success mt-2" onclick="themVaoGio()">Thêm vào danh sách</button>
    </div>
    
    <h4>Danh sách mượn</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sách</th>
                <th>Số lượng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody id="danh_sach_sach"></tbody>
    </table>
    
    <input type="hidden" name="danh_sach_muon" id="danh_sach_muon">
    <button type="submit" class="btn btn-primary" name="them_phieu" onclick="truyenDuLieu()">Thêm phiếu mượn</button>

</form>

<script>
    let danhSachSach = [];
    
    function themVaoGio() {
        let select = document.getElementById('ma_sach');
        let soLuong = document.getElementById('so_luong').value;
        let selectedOption = select.options[select.selectedIndex];
        let maSach = selectedOption.value;
        let tenSach = selectedOption.getAttribute('data-ten');
        let soLuongCon = parseInt(selectedOption.getAttribute('data-soluong'));
        
        if (!maSach || soLuong <= 0 || soLuong > soLuongCon) {
            alert("Vui lòng chọn sách hợp lệ và nhập số lượng phù hợp!");
            return;
        }
        
        let exists = danhSachSach.find(s => s.maSach === maSach);
        if (exists) {
            exists.soLuong = parseInt(exists.soLuong) + parseInt(soLuong);
        } else {
            danhSachSach.push({ maSach, tenSach, soLuong });
        }
        capNhatDanhSach();
    }
    
    function xoaKhoiGio(index) {
        danhSachSach.splice(index, 1);
        capNhatDanhSach();
    }
    
    function capNhatDanhSach() {
        let tbody = document.getElementById('danh_sach_sach');
        tbody.innerHTML = "";
        danhSachSach.forEach((sach, index) => {
            tbody.innerHTML += `<tr>
                <td>${sach.tenSach}</td>
                <td>${sach.soLuong}</td>
                <td><button type='button' class='btn btn-danger' onclick='xoaKhoiGio(${index})'>Xóa</button></td>
            </tr>`;
        });
        document.getElementById('danh_sach_muon').value = JSON.stringify(danhSachSach);
    }
</script>

