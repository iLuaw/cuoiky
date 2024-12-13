<?php
session_start(); // Bắt đầu session để kiểm tra đăng nhập

// Kiểm tra trạng thái đăng nhập của admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "doanchuyennganh";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

    // Kiểm tra xem có ID người dùng được gửi đến hay không
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Câu lệnh SQL để xóa người dùng dựa trên ID
        $sql = "DELETE FROM usernguoidung WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);

        // Thực thi câu lệnh SQL
        if ($stmt->execute()) {
            // Xóa thành công, lưu thông báo vào session
            $_SESSION['message'] = "Xóa người dùng thành công!"; 
            header('Location: quan_ly_nguoidung.php'); // Chuyển hướng về trang quản lý người dùng
            exit();
        } else {
            echo "Lỗi: Không thể xóa người dùng.";
        }
    } else {
        echo "Lỗi: ID người dùng không hợp lệ.";
    }
} catch(PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}

$conn = null;
?>