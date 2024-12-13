<?php
// huy_don_hang.php

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

    // Cập nhật trạng thái đơn hàng thành "Đã hủy"
    $sql_huy_don_hang = "UPDATE don_hang SET trang_thai = 'Đã hủy' WHERE ma_don_hang = ?";
    $stmt_huy_don_hang = $conn->prepare($sql_huy_don_hang);
    $stmt_huy_don_hang->bind_param("i", $ma_don_hang);

    if ($stmt_huy_don_hang->execute()) {
        // Chuyển hướng về trang chi tiết đơn hàng và hiển thị thông báo
        header("Location: chi_tiet_don_hang.php?ma_don_hang=" . $ma_don_hang . "&message=Đơn hàng đã được hủy."); 
        exit();
    } else {
        echo "Lỗi: " . $stmt_huy_don_hang->error;
    }

    $stmt_huy_don_hang->close();
    $conn->close();
} else {
    echo "<p>Mã đơn hàng không hợp lệ.</p>";
}
?>