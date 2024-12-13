<?php
// xuly_them_sach.php
// session_start(); // Đã di chuyển vào menu.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $TenSach = $_POST['TenSach'];
    $TacGia = $_POST['TacGia'];
    $Gia = $_POST['Gia'];
    $MaTheLoai = $_POST['MaTheLoai']; // Lấy MaTheLoai

    // Xử lý upload hình ảnh (nếu có)
    if (isset($_FILES['HinhAnh']) && $_FILES['HinhAnh']['error'] == 0) {
        $target_dir = "uploads/"; // Thư mục lưu trữ hình ảnh
        $target_file = $target_dir . basename($_FILES["HinhAnh"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Kiểm tra định dạng file ảnh
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            // Trả về thông báo lỗi dưới dạng JSON
            $response = array('success' => false, 'message' => 'Xin lỗi, chỉ cho phép file JPG, JPEG, PNG & GIF.');
            echo json_encode($response);
            exit();
        }

        if (move_uploaded_file($_FILES["HinhAnh"]["tmp_name"], $target_file)) {
            // Lưu tên file ảnh vào database
            $HinhAnh = basename($_FILES["HinhAnh"]["name"]);
        } else {
            // Trả về thông báo lỗi dưới dạng JSON
            $response = array('success' => false, 'message' => 'Xin lỗi, đã có lỗi xảy ra khi upload file.');
            echo json_encode($response);
            exit();
        }
    } else {
        $HinhAnh = null; // Không có hình ảnh
    }

    // Kết nối đến database
    $conn = new mysqli("localhost", "root", "", "doanchuyennganh");
    if ($conn->connect_error) {
        // Trả về thông báo lỗi dưới dạng JSON
        $response = array('success' => false, 'message' => 'Kết nối thất bại: ' . $conn->connect_error);
        echo json_encode($response);
        exit();
    }

    // Thêm sách vào database
    $sql = "INSERT INTO books (TenSach, TacGia, Gia, MaTheLoai, HinhAnh) 
            VALUES ('$TenSach', '$TacGia', '$Gia', '$MaTheLoai', '$HinhAnh')"; 

    if ($conn->query($sql) === TRUE) {
        // Trả về thông báo thành công dưới dạng JSON
        $response = array('success' => true, 'message' => 'Thêm sách thành công!');
        echo json_encode($response);
    } else {
        // Trả về thông báo lỗi dưới dạng JSON
        $response = array('success' => false, 'message' => 'Lỗi: ' . $sql . '<br>' . $conn->error);
        echo json_encode($response);
    }

    $conn->close();
}
?>