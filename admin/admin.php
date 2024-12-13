<!DOCTYPE html>
<html>
<head>
    <title>Trang Admin</title>
    <link rel="stylesheet" href="admin_style.css">
    <style>
        .clear {
            clear: both; 
        }
    </style>
</head>
<body>

<?php include('menu.php'); ?> 

<?php 

// Kiểm tra trạng thái đăng nhập của admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
?>

<h1>Trang Quản trị Admin</h1>

<div class="container">
    <div class="search-form"> 
        <form action="admin.php" method="get">
            <input type="text" name="keyword" placeholder="Nhập tên sách, tác giả hoặc thể loại">
            <button type="submit">Tìm kiếm</button>
        </form>
        <div id="search-results"></div> 
    </div>

    <a href="them_sach.php" class="btn-add">Thêm sách</a> 
    <a href="them_theloai.php" class="btn-add">Thêm thể loại</a> 
    <a href="danh_sach_theloai.php" class="btn-add">Danh sách thể loại</a> 
    <a href="quan_ly_nguoidung.php" class="btn-add">Quản lý người dùng</a> 
    <form action="quan_ly_don_hang.php" method="get">
    <button type="submit" class="btn-add">Quản lý đơn hàng</button>
</form>
<div class="clear"></div>
    <div class="clear"></div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sách</th>
                <th>Tác giả</th>
                <th>Giá</th>
                <th>Thể loại</th>
                <th>Hình ảnh</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Kết nối đến cơ sở dữ liệu
            $conn = new mysqli("localhost", "root", "", "doanchuyennganh");
            if ($conn->connect_error) {
                die("Kết nối thất bại: " . $conn->connect_error);
            }

            // Xử lý tìm kiếm
            if (isset($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
                $sql = "SELECT b.*, t.tentheloai 
                FROM books b
                INNER JOIN theloai t ON b.MaTheLoai = t.matheloai
                WHERE b.TenSach LIKE '%$keyword%' 
                OR b.TacGia LIKE '%$keyword%' 
                OR t.tentheloai LIKE '%$keyword%' 
                ORDER BY b.MaSach ASC";
            } else {
                $sql = "SELECT b.*, t.tentheloai 
                        FROM books b
                        INNER JOIN theloai t ON b.MaTheLoai = t.matheloai
                        ORDER BY b.MaSach ASC";
            }

            $result = $conn->query($sql);

            // Hiển thị thông báo kết quả
            if (isset($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
                if ($result->num_rows > 0) {
                    echo "<script>document.getElementById('search-results').innerText = 'Tất cả kết quả cho từ khóa \"$keyword\"';</script>";
                } else {
                    echo "<script>document.getElementById('search-results').innerText = 'Không tìm thấy kết quả cho từ khóa \"$keyword\"';</script>";
                }
            }

            if ($result->num_rows > 0) {
                // Hiển thị dữ liệu sách
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['MaSach'] . "</td>";
                    echo "<td>" . $row['TenSach'] . "</td>";
                    echo "<td>" . $row['TacGia'] . "</td>";
                    echo "<td>" . $row['Gia'] . " VNĐ</td>"; 
                    echo "<td>" . $row['tentheloai'] . "</td>"; 
                    echo "<td>" . (isset($row['HinhAnh']) ? "<img src='uploads/" . $row['HinhAnh'] . "' alt='Hinh anh' width='100'>" : "") . "</td>";
                    echo "<td>";
                    echo "<a href='sua_sach.php?id=" . $row['MaSach'] . "'>Sửa</a> | ";
                    echo "<a href='xoa_sach.php?id=" . $row['MaSach'] . "' onclick='return confirm(\"Bạn có chắc chắn muốn xóa sách này?\")'>Xóa</a>"; 
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Không có sách nào.</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>

</body>
</html>