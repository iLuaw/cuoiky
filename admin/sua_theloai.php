<?php
// (Không có session_start() ở đây)

// Kết nối đến database
$conn = new mysqli("localhost", "root", "", "doanchuyennganh");
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
  $id = $_GET['id'];

  // Lấy thông tin thể loại từ database
  $sql = "SELECT * FROM theloai WHERE matheloai = $id";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $tentheloai = $row['tentheloai'];
  } else {
    die("Thể loại không tồn tại.");
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
  $id = $_POST['id'];
  $tentheloai = $_POST['TenTheLoai'];

  // Cập nhật thể loại trong database
  $sql = "UPDATE theloai SET tentheloai='$tentheloai' WHERE matheloai=$id";

  if ($conn->query($sql) === TRUE) {
    // Cập nhật thể loại thành công
    header('Location: danh_sach_theloai.php'); 
    exit();
  } else {
    echo "Lỗi: " . $sql . "<br>" . $conn->error;
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Sửa Thể Loại</title>
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

<h1>Sửa Thể Loại</h1>

<div class="container">
  <form action="sua_theloai.php" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div class="form-group">
      <label for="TenTheLoai">Tên thể loại:</label>
      <input type="text" id="TenTheLoai" name="TenTheLoai" value="<?php echo $tentheloai; ?>" required>
    </div>
    <button type="submit">Cập nhật</button>
  </form>
</div>

</body>
</html>