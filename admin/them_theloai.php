<?php
// (Không có session_start() ở đây)
?>

<!DOCTYPE html>
<html>
<head>
  <title>Thêm Thể Loại</title>
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

<h1>Thêm Thể Loại Mới</h1>

<div class="container">
  <form action="xuly_them_theloai.php" method="post">
    <div class="form-group">
      <label for="TenTheLoai">Tên thể loại:</label>
      <input type="text" id="TenTheLoai" name="TenTheLoai" required>
    </div>
    <button type="submit">Thêm thể loại</button>
  </form>
</div>

</body>
</html>