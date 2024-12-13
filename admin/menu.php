<?php
// Kiểm tra trạng thái đăng nhập của admin
session_start();
if (!isset($_SESSION['admin'])) {
  header('Location: login.php');
  exit();
}
?>

<div class="menu-container">
  <button class="menu-toggle">&#9776;</button> 
  <ul class="menu">
    <li><a href="admin.php">Trang chủ Admin</a></li>
    <li><a href="them_sach.php">Thêm sách</a></li>
    <li><a href="them_theloai.php">Thêm thể loại</a></li>
    <li><a href="danh_sach_theloai.php">Danh sách thể loại</a></li>
    <li><a href="logout.php">Đăng xuất</a></li>
  </ul>
</div>

<script>
const menuToggle = document.querySelector('.menu-toggle');
const menu = document.querySelector('.menu');

menuToggle.addEventListener('click', () => {
  menu.classList.toggle('active');
});

// Lắng nghe sự kiện click trên toàn bộ document
document.addEventListener('click', (event) => {
  // Kiểm tra xem click có nằm trong menuToggle hoặc menu hay không
  if (!menuToggle.contains(event.target) && !menu.contains(event.target)) {
    menu.classList.remove('active');
  }
});
</script>