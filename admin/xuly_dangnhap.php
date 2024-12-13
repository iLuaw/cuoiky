<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Kết nối đến database (thay đổi thông tin cho phù hợp)
  $conn = new mysqli("localhost", "root", "", "doanchuyennganh"); 
  if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
  }

  // Truy vấn database (lưu ý bảng là `user`)
  $sql = "SELECT * FROM user WHERE username = '$username'"; 
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // So sánh trực tiếp mật khẩu (vì không mã hóa)
    if ($password == $row['password']) { 
      // Đăng nhập thành công
      $_SESSION['admin'] = $row['id'];
      header('Location: admin.php'); 
      exit();
    } else {
      // Đăng nhập thất bại (mật khẩu không đúng)
      $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
    }
  } else {
    // Đăng nhập thất bại (không tìm thấy tên đăng nhập)
    $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
  }

  // Hiển thị thông báo lỗi (nếu có) trên trang login.php
  // Ví dụ: 
  if (isset($error)) {
    echo "<script>alert('$error'); window.location.href = 'login.php';</script>"; 
  }
}
?>