<?php

include("menu.php");
// Kiểm tra trạng thái đăng nhập của admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "doanchuyennganh"; 

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

    // Xử lý tìm kiếm
    if (isset($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
        $sql = "SELECT * FROM usernguoidung WHERE username LIKE :keyword OR hovaten LIKE :keyword OR email LIKE :keyword";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':keyword', '%' . $keyword . '%');
        $stmt->execute();
    } else {
        // Lấy danh sách người dùng từ database
        $sql = "SELECT * FROM usernguoidung";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}

$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

<h1>Quản lý người dùng</h1>

<div class="container">
    <div class="search-form"> 
        <form action="quan_ly_nguoidung.php" method="get">
            <input type="text" name="keyword" placeholder="Nhập tên người dùng, họ tên hoặc email">
            <button type="submit">Tìm kiếm</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Họ và tên</th>
                <th>Email</th>
                <th>Địa chỉ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['hovaten']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['address']; ?></td>
                    <td>
                        <a href="xoa_nguoi_dung.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="message">
            <?php echo $_SESSION['message']; ?>
            <?php unset($_SESSION['message']); ?> 
        </div>
    <?php endif; ?>
</div>

</body>
</html>