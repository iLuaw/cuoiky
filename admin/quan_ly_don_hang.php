<?php
// quan_ly_don_hang.php

include("menu.php"); // menu.php đã có session_start()

// Kiểm tra trạng thái đăng nhập của admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Kết nối đến database
$conn = new mysqli("localhost", "root", "", "doanchuyennganh");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách đơn hàng từ database
$sql = "SELECT dh.*, usernguoidung.username 
        FROM don_hang dh
        INNER JOIN usernguoidung ON dh.id = usernguoidung.id
        ORDER BY dh.ngay_dat_hang DESC"; // Sắp xếp theo ngày đặt hàng giảm dần
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="admin_style.css">  
</head>
<body>

<h2>Danh sách đơn hàng</h2>

<table class="admin-table">
    <thead>
        <tr>
            <th>Mã đơn hàng</th>
            <th>Khách hàng</th>
            <th>Ngày đặt hàng</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Hành động</th> 
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['ma_don_hang'] . "</td>";
                echo "<td>" . $row['username'] . "</td>"; 
                echo "<td>" . date("d/m/Y H:i:s", strtotime($row['ngay_dat_hang'])) . "</td>"; 
                echo "<td>" . $row['tong_tien'] . "</td>";
                echo "<td>" . $row['trang_thai'] . "</td>";
                echo "<td>";
                echo "<a href='chi_tiet_don_hang.php?ma_don_hang=" . $row['ma_don_hang'] . "'>Xem chi tiết</a> | "; // Thêm nút "Xem chi tiết"
                echo "<a href='xoa_don_hang.php?ma_don_hang=" . $row['ma_don_hang'] . "' onclick='return confirm(\"Bạn có chắc chắn muốn xóa đơn hàng này?\")'>Xóa</a>"; // Thêm nút "Xóa"
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Không có đơn hàng nào.</td></tr>";
        }
        ?>
    </tbody>
</table>


<?php
$conn->close();
?>

</body>
</html>