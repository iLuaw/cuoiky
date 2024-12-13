<?php
// (Không có session_start() ở đây)
?>

<!DOCTYPE html>
<html>
<head>
  <title>Danh sách Thể Loại</title>
  <link rel="stylesheet" href="admin_style.css">
</head>
<body>

<?php include('menu.php'); ?>

<?php
// Kiểm tra trạng thái đăng nhập của admin (Di chuyển xuống dưới)
if (!isset($_SESSION['admin'])) {
  header('Location: login.php');
  exit();
}
?>

<h1>Danh sách Thể Loại</h1>

<div class="container">
  <table>
    <thead>
      <tr>
        <th>Mã thể loại</th>
        <th>Tên thể loại</th>
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

      // Truy vấn dữ liệu từ bảng theloai
      $sql = "SELECT * FROM theloai ORDER BY matheloai ASC";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        // Hiển thị dữ liệu thể loại
        while($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row['matheloai'] . "</td>";
          echo "<td>" . $row['tentheloai'] . "</td>";
          echo "<td>";
          echo "<a href='sua_theloai.php?id=" . $row['matheloai'] . "'>Sửa</a> | ";
          echo "<a href='xoa_theloai.php?id=" . $row['matheloai'] . "' onclick='return confirm(\"Bạn có chắc chắn muốn xóa thể loại này?\")'>Xóa</a>";
          echo "</td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='3'>Không có thể loại nào.</td></tr>";
      }

      $conn->close();
      ?>
    </tbody>
  </table>
</div