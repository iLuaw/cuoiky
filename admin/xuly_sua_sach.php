<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $MaSach = $_POST['MaSach'];
  $TenSach = $_POST['TenSach'];
  $TacGia = $_POST['TacGia'];
  $Gia = $_POST['Gia'];
  $MaTheLoai = $_POST['MaTheLoai']; // Thêm dòng này để lấy giá trị TheLoai
  $HinhAnh = $_POST['HinhAnhCu']; // Lấy tên hình ảnh cũ từ form

  // Xử lý upload hình ảnh (nếu có)
  if (isset($_FILES['HinhAnh']) && $_FILES['HinhAnh']['error'] == 0) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["HinhAnh"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Kiểm tra định dạng file ảnh
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
      echo "Xin lỗi, chỉ cho phép file JPG, JPEG, PNG & GIF.";
      exit();
    }

    if (move_uploaded_file($_FILES["HinhAnh"]["tmp_name"], $target_file)) {
      $HinhAnh = basename($_FILES["HinhAnh"]["name"]);
    } else {
      echo "Xin lỗi, đã có lỗi xảy ra khi upload file.";
      exit();
    }
  } 

  // Kết nối đến database
  $conn = new mysqli("localhost", "root", "", "doanchuyennganh");
  if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
  }

  // Cập nhật thông tin sách trong database
  $sql = "UPDATE books SET 
        TenSach='$TenSach', 
        TacGia='$TacGia', 
        Gia='$Gia', 
        MaTheLoai='$MaTheLoai', 
        HinhAnh='$HinhAnh' 
        WHERE MaSach=$MaSach";

  if ($conn->query($sql) === TRUE) {
    header('Location: admin.php'); 
    exit();
  } else {
    echo "Lỗi: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}
?>