<?php
// chi_tiet_don_hang.php

include("menu.php"); 

// Kiểm tra trạng thái đăng nhập của admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Kiểm tra xem có mã đơn hàng được truyền vào hay không
if (isset($_GET['ma_don_hang'])) {
    $ma_don_hang = $_GET['ma_don_hang'];

    // Kết nối đến database
    $conn = new mysqli("localhost", "root", "", "doanchuyennganh");
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Lấy thông tin đơn hàng
    $sql_don_hang = "SELECT * FROM don_hang WHERE ma_don_hang = ?";
    $stmt_don_hang = $conn->prepare($sql_don_hang);
    $stmt_don_hang->bind_param("i", $ma_don_hang);
    $stmt_don_hang->execute();
    $result_don_hang = $stmt_don_hang->get_result();

    if ($result_don_hang->num_rows > 0) {
        $don_hang = $result_don_hang->fetch_assoc();
        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <title>Chi tiết đơn hàng</title>
            <link rel="stylesheet" href="admin_style.css">
        </head>
        <body>

        <h2>Chi tiết đơn hàng #<?php echo $don_hang['ma_don_hang']; ?></h2>

        <h3>Thông tin khách hàng</h3>
        <p>Họ tên: <?php echo $don_hang['ho_ten']; ?></p>
        <p>Email: <?php echo $don_hang['email']; ?></p>
        <p>Số điện thoại: <?php echo $don_hang['so_dien_thoai']; ?></p>
        <p>Số nhà tên đường: <?php echo $don_hang['dia_chi']; ?></p>
        
        <?php
        // Lấy tên tỉnh thành, quận huyện, phường xã từ database
        $matp = $don_hang['tinh_thanh'];
        $maqh = $don_hang['quan_huyen'];
        $xaid = $don_hang['phuong_xa'];

        $sql_tinhthanh = "SELECT ten_tinh FROM tinh_thanh WHERE matp = ?";
        $stmt_tinhthanh = $conn->prepare($sql_tinhthanh);
        $stmt_tinhthanh->bind_param("s", $matp);
        $stmt_tinhthanh->execute();
        $result_tinhthanh = $stmt_tinhthanh->get_result();
        $tinhthanh = $result_tinhthanh->fetch_assoc()['ten_tinh'];

        $sql_quanhuyen = "SELECT name FROM quan_huyen WHERE maqh = ?";
        $stmt_quanhuyen = $conn->prepare($sql_quanhuyen);
        $stmt_quanhuyen->bind_param("s", $maqh);
        $stmt_quanhuyen->execute();
        $result_quanhuyen = $stmt_quanhuyen->get_result();
        $quanhuyen = $result_quanhuyen->fetch_assoc()['name'];

        $sql_phuongxa = "SELECT name FROM phuong_xa WHERE xaid = ?";
        $stmt_phuongxa = $conn->prepare($sql_phuongxa);
        $stmt_phuongxa->bind_param("s", $xaid);
        $stmt_phuongxa->execute();
        $result_phuongxa = $stmt_phuongxa->get_result();
        $phuongxa = $result_phuongxa->fetch_assoc()['name'];

        // Hiển thị địa chỉ đầy đủ
        echo "<p>Địa chỉ đầy đủ: " . $don_hang['dia_chi'] . ", " . $phuongxa . ", " . $quanhuyen . ", " . $tinhthanh . "</p>";
        ?>

        <h3>Chi tiết đơn hàng</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã sách</th>
                    <th>Tên sách</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Lấy chi tiết đơn hàng
                $sql_chi_tiet = "SELECT ctdh.*, b.TenSach, b.Gia
                                FROM chi_tiet_don_hang ctdh
                                INNER JOIN books b ON ctdh.MaSach = b.MaSach
                                WHERE ctdh.ma_don_hang = ?";
                $stmt_chi_tiet = $conn->prepare($sql_chi_tiet);
                $stmt_chi_tiet->bind_param("i", $ma_don_hang);
                $stmt_chi_tiet->execute();
                $result_chi_tiet = $stmt_chi_tiet->get_result();

                if ($result_chi_tiet->num_rows > 0) {
                    while ($row = $result_chi_tiet->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['MaSach'] . "</td>";
                        echo "<td>" . $row['TenSach'] . "</td>";
                        echo "<td>" . $row['soluong'] . "</td>";
                        echo "<td>" . $row['Gia'] . "</td>";
                        echo "<td>" . ($row['Gia'] * $row['soluong']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Không có sản phẩm nào trong đơn hàng này.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <p>Tổng tiền: <?php echo $don_hang['tong_tien']; ?> VNĐ</p>
        <p>Phương thức thanh toán: <?php echo $don_hang['phuong_thuc_thanh_toan']; ?></p>
        <p>Trạng thái: <?php echo $don_hang['trang_thai']; ?></p>

        <?php
        // Nút hủy đơn hàng
        echo "<a href='huy_don_hang.php?ma_don_hang=" . $don_hang['ma_don_hang'] . "' onclick='return confirm(\"Bạn có chắc chắn muốn hủy đơn hàng này?\")' class='btn-add'>Hủy đơn hàng</a>";
        ?>

        </body>
        </html>

        <?php
    } else {
        echo "<p>Không tìm thấy đơn hàng.</p>";
    }

    $conn->close();
} else {
    echo "<p>Mã đơn hàng không hợp lệ.</p>";
}
?>