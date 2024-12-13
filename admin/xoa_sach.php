<?php
session_start();

if (!isset($_SESSION['admin'])) {
  header('Location: login.php');
  exit();
}

// Lấy MaSach từ URL
if (isset($_GET['id'])) {
  $MaSach = $_GET['id'];

  // Kết nối đến database
  $conn = new mysqli("localhost", "root", "", "doanchuyennganh"); 
  if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
  }

  // Xóa sách khỏi database
  $sql = "DELETE FROM books WHERE MaSach = $MaSach";

  if ($conn->query($sql) === TRUE) {
    // Xóa sách thành công
    header('Location: admin.php'); 
    exit();
  } else {
    echo "Lỗi: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
} else {
  echo "MaSach không hợp lệ.";
  exit();
}
?>