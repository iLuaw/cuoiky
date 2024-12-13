<!DOCTYPE html>
<html>
<head>
  <title>Đăng nhập Admin</title>
  <link rel="stylesheet" href="admin_style.css">  </head>
<body>

<div class="login-container">
  <h2>Đăng nhập</h2>
  <form action="xuly_dangnhap.php" method="post"> 
    <div class="form-group">
      <label for="username">Tên đăng nhập:</label>
      <input type="text" id="username" name="username" required>
    </div>
    <div class="form-group">
      <label for="password">Mật khẩu:</label>
      <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Đăng nhập</button>
  </form>
</div>

</body>
</html>