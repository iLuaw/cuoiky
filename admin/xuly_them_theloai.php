<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $TenTheLoai = $_POST['TenTheLoai'];

  // Kết nối đến database
  $conn = new mysqli("localhost", "root", "", "doanchuyennganh");
  if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
  }

  // Thêm thể loại vào database
  $sql = "INSERT INTO theloai (TenTheLoai) 
          VALUES ('$TenTheLoai')";

  if ($conn->query($sql) === TRUE) {
    // Thêm thể loại thành công
    header('Location: admin.php'); // Chuyển hướng về trang admin
    exit();
  } else {
    echo "Lỗi: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}
?>