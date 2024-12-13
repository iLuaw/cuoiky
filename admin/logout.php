<?php
session_start(); // Bắt đầu session

// Hủy tất cả các biến session
session_destroy();

// Chuyển hướng người dùng đến trang đăng nhập
header("Location: login.php");
exit;
?>