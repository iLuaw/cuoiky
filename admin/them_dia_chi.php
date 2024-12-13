<?php
ini_set('max_execution_time', 99999999999); // Tăng thời gian thực thi

$conn = new mysqli("localhost", "root", "", "doanchuyennganh");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Bắt đầu transaction
$conn->begin_transaction();

try {
    include 'tinh_thanhpho.php'; 

    foreach ($tinh_thanhpho as $matp => $ten_tinh) {
        // Sử dụng key của mảng $tinh_thanhpho làm mã tỉnh
        $matp = strtoupper($matp);

        // Kiểm tra mã tỉnh đã tồn tại chưa
        $check_sql = "SELECT * FROM tinh_thanh WHERE matp = ?"; 
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $matp); 
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        if ($check_result->num_rows > 0) {
            // Nếu mã tỉnh đã tồn tại, thêm số đếm vào sau mã tỉnh
            $count = 1;
            do {
                $matp_new = $matp . $count; 
                $count++;
                $check_sql = "SELECT * FROM tinh_thanh WHERE matp = ?"; 
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->bind_param("s", $matp_new); 
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
            } while ($check_result->num_rows > 0);
            $matp = $matp_new; 
        }
        $check_stmt->close();

        $sql = "INSERT INTO tinh_thanh (matp, ten_tinh) VALUES (?, ?)"; 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $matp, $ten_tinh); 

        if (!$stmt->execute()) {
            throw new Exception("Lỗi: " . $sql . "<br>" . $conn->error);
        }

        $stmt->close();
    }

    include 'quan_huyen.php';

    foreach ($quan_huyen as $quan) {
        $maqh = $quan['maqh'];
        $name = $quan['name'];
        $matp = $quan['matp']; 

        $sql = "INSERT INTO quan_huyen (maqh, name, matp) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $maqh, $name, $matp); 

        if (!$stmt->execute()) {
            throw new Exception("Lỗi: " . $sql . "<br>" . $conn->error);
        }

        $stmt->close();
    }

    include 'phuong_xa.php';

    foreach ($xa_phuong_thitran as $xa) {
        $xaid = $xa['xaid'];
        $name = $xa['name'];
        $maqh = $xa['maqh'];

        $sql = "INSERT INTO phuong_xa (xaid, name, maqh) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $xaid, $name, $maqh);

        if (!$stmt->execute()) {
            throw new Exception("Lỗi: " . $sql . "<br>" . $conn->error);
        }

        $stmt->close();
    }

    // Commit transaction
    $conn->commit();
    echo "Thêm dữ liệu địa chỉ thành công!";

} catch (Exception $e) {
    // Rollback transaction nếu có lỗi
    $conn->rollback();
    echo "Lỗi: " . $e->getMessage();
}

// Đóng kết nối
$conn->close();
?>