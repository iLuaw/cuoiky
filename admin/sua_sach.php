<?php
// (Không có session_start() ở đây)

// Lấy MaSach từ URL
if (isset($_GET['id'])) {
  $MaSach = $_GET['id'];

  // Kết nối đến database
  $conn = new mysqli("localhost", "root", "", "doanchuyennganh");
  if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
  }

  // Truy vấn database để lấy thông tin sách
  $sql = "SELECT * FROM books WHERE MaSach = $MaSach";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
  } else {
    echo "Không tìm thấy sách.";
    exit();
  }

  $conn->close();
} else {
  echo "MaSach không hợp lệ.";
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Sửa sách</title>
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

<h1>Sửa thông tin sách</h1>

<div class="container">
  <form action="xuly_sua_sach.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="MaSach" value="<?php echo $book['MaSach']; ?>"> 
    <div class="form-group">
      <label for="TenSach">Tên sách:</label>
      <input type="text" id="TenSach" name="TenSach" value="<?php echo $book['TenSach']; ?>" required>
    </div>
    <div class="form-group">
      <label for="TacGia">Tác giả:</label>
      <input type="text" id="TacGia" name="TacGia" value="<?php echo $book['TacGia']; ?>" required>
    </div>
    <div class="form-group">
      <label for="Gia">Giá:</label>
      <input type="number" id="Gia" name="Gia" value="<?php echo $book['Gia']; ?>" required>
    </div>
    <div class="form-group">
      <label for="MaTheLoai">Thể loại:</label> 
      <select id="MaTheLoai" name="MaTheLoai">
        <?php
        // Kết nối đến database
        $conn = new mysqli("localhost", "root", "", "doanchuyennganh");
        // Kiểm tra kết nối
        if ($conn->connect_error) {
          die("Kết nối thất bại: " . $conn->connect_error);
        }
        // Truy vấn dữ liệu từ bảng theloai
        $sql = "SELECT * FROM theloai";
        $result = $conn->query($sql);
        // Hiển thị danh sách thể loại
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            // Kiểm tra xem thể loại hiện tại có phải là thể loại của sách không
            $selected = ($row['matheloai'] == $book['MaTheLoai']) ? "selected" : ""; 
            echo "<option value='" . $row["matheloai"] . "' $selected>" . $row["tentheloai"] . "</option>";
          }
        }
        $conn->close();
        ?>
      </select>
    </div>
    <div class="form-group">
      <label for="HinhAnh">Hình ảnh:</label>
      <input type="file" id="HinhAnh" name="HinhAnh">

      <?php if (isset($book['HinhAnh'])) : ?>
        <img src="uploads/<?php echo $book['HinhAnh']; ?>" alt="Hình ảnh hiện tại" width="100">
      <?php endif; ?>

      <input type="hidden" name="HinhAnhCu" value="<?php echo $book['HinhAnh']; ?>"> 
    </div>
    <button type="submit">Lưu thay đổi</button>
  </form>
</div>

</body>
</html>