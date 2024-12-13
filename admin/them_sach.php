<?php
// them_sach.php
?>

<!DOCTYPE html>
<html>
<head>
  <title>Thêm sách</title>
  <link rel="stylesheet" href="admin_style.css">
</head>
<body>

<?php include('menu.php'); ?> 

<?php
// Kiểm tra trạng thái đăng nhập của admin
if (!isset($_SESSION['admin'])) {
  header('Location: login.php');
  exit();
}
?>

<h1>Thêm sách mới</h1>

<div class="container">
  <form action="xuly_them_sach.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label for="TenSach">Tên sách:</label>
      <input type="text" id="TenSach" name="TenSach" required>
    </div>
    <div class="form-group">
      <label for="TacGia">Tác giả:</label>
      <input type="text" id="TacGia" name="TacGia" required>
    </div>
    <div class="form-group">
      <label for="Gia">Giá:</label>
      <input type="number" id="Gia" name="Gia" required>
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
            echo "<option value='" . $row["matheloai"] . "'>" . $row["tentheloai"] . "</option>";
          }
        }
        $conn->close();
        ?>
      </select>
    </div>
    <div class="form-group">
      <label for="HinhAnh">Hình ảnh:</label>
      <input type="file" id="HinhAnh" name="HinhAnh">
    </div>
    <button type="submit">Thêm sách</button>
  </form>
</div>

<script>
  const form = document.querySelector('form');

  form.addEventListener('submit', function(event) {
    event.preventDefault(); // Ngăn chặn form submit mặc định

    // Lấy dữ liệu từ form
    const formData = new FormData(form);

    // Gửi AJAX request
    fetch('xuly_them_sach.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert(data.message); // Hiển thị thông báo thành công
        form.reset(); // Reset form
      } else {
        alert(data.message); // Hiển thị thông báo lỗi
      }
    })
    .catch(error => {
      console.error('Lỗi:', error);
    });
  });
</script>

</body>
</html>