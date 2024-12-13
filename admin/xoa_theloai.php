<?php
session_start();
require_once 'db.php'; // Include file kết nối database

// Kiểm tra trạng thái đăng nhập của admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Kiểm tra xem thể loại có sách nào không
    $checkSql = "SELECT COUNT(*) as count FROM books WHERE MaTheLoai = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $checkRow = $checkResult->fetch_assoc();

    if ($checkRow['count'] > 0) {
        // Có sách thuộc thể loại này, không cho phép xóa
        echo "<script>alert('Không thể xóa thể loại này vì có sách thuộc thể loại này.'); window.location.href = 'danh_sach_theloai.php';</script>"; 
        exit();
    }

    // Xóa thể loại khỏi database
    $sql = "DELETE FROM theloai WHERE matheloai = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Xóa thể loại thành công
        header('Location: danh_sach_theloai.php'); 
        exit();
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Chuyển hướng về trang danh sách thể loại nếu không có id
    header('Location: danh_sach_theloai.php'); 
    exit();
}

$conn->close();
?>