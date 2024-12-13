<?php
// xoa_don_hang.php

include("menu.php"); 

// Kiểm tra trạng thái đăng nhập của admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['ma_don_hang'])) {
    $ma_don_hang = $_GET['ma_don_hang'];

    // Kết nối đến database
    $conn = new mysqli("localhost", "root", "", "doanchuyennganh");
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Kiểm tra trạng thái đơn hàng
    $sql_kiem_tra_trang_thai = "SELECT trang_thai FROM don_hang WHERE ma_don_hang = ?";
    $stmt_kiem_tra_trang_thai = $conn->prepare($sql_kiem_tra_trang_thai);
    $stmt_kiem_tra_trang_thai->bind_param("i", $ma_don_hang);
    $stmt_kiem_tra_trang_thai->execute();
    $result_kiem_tra_trang_thai = $stmt_kiem_tra_trang_thai->get_result();

    if ($result_kiem_tra_trang_thai->num_rows > 0) {
        $row = $result_kiem_tra_trang_thai->fetch_assoc();
        $trang_thai = $row['trang_thai'];

        if ($trang_thai == 'Đã hủy') {
            // Xóa chi tiết đơn hàng trước
            $sql_xoa_chi_tiet = "DELETE FROM chi_tiet_don_hang WHERE ma_don_hang = ?";
            $stmt_xoa_chi_tiet = $conn->prepare($sql_xoa_chi_tiet);
            $stmt_xoa_chi_tiet->bind_param("i", $ma_don_hang);
            $stmt_xoa_chi_tiet->execute();

            // Xóa đơn hàng
            $sql_xoa_don_hang = "DELETE FROM don_hang WHERE ma_don_hang = ?";
            $stmt_xoa_don_hang = $conn->prepare($sql_xoa_don_hang);
            $stmt_xoa_don_hang->bind_param("i", $ma_don_hang);

            if ($stmt_xoa_don_hang->execute()) {
                // Hiển thị thông báo bằng JavaScript
                echo "<script>alert('Xóa đơn hàng thành công.'); window.location.href = 'quan_ly_don_hang.php';</script>"; 
                exit();
            } else {
                echo "Lỗi: " . $stmt_xoa_don_hang->error;
            }

            $stmt_xoa_don_hang->close();
        } else {
            // Hiển thị thông báo bằng JavaScript
            echo "<script>alert('Đơn hàng chưa xem xét.'); window.location.href = 'quan_ly_don_hang.php';</script>"; 
            exit();
        }
    } else {
        echo "<p>Không tìm thấy đơn hàng.</p>";
    }

    $conn->close();
} else {
    echo "<p>Mã đơn hàng không hợp lệ.</p>";
}
?>