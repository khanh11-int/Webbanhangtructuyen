<?php
session_start();
include "noi.php";

$loginErrors = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $sql = "SELECT CustomerID, FullName, Password FROM Customers WHERE Email = ?";
    $params = array($email);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($stmt)) {
        $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (password_verify($password, $user["Password"])) {
            $_SESSION["CustomerID"] = $user["CustomerID"];
            $_SESSION["FullName"] = $user["FullName"];
            header("Location: ../home/Mainad.php");
            exit();
        } else {
            $loginErrors = "Sai email hoặc mật khẩu";
        }
    } else {
        $loginErrors = "Sai email hoặc mật khẩu";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập</title>
  <link rel="stylesheet" href="..\styles\log.css">
</head>
<body>
  <div class="login-container">
    <div class="login-left">
      <a href="khach.php"> 
         <img src="..\image\logo.webp" alt="SNSN"> //logo
     </a>
    </div>
    <div class="login-right">
      <h2>Đăng nhập</h2>
      <?php if (!empty($loginErrors)): ?>
        <p class="error"><?= htmlspecialchars($loginErrors) ?></p>
      <?php endif; ?>
      <form method="POST" action="">
        <input type="text" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">ĐĂNG NHẬP</button>
        <div class="login-options">
          <a href="#">Quên mật khẩu</a>
          <span>|</span>
          <a href="#">Đăng nhập với SMS</a>
        </div>
        <div class="login-socials">
          <p>Hoặc</p>
          <button class="fb">Facebook</button>
          <button class="gg">Google</button>
        </div>
        <div class="signup">
          <p>Bạn mới biết đến SnowMan? <a href="register.php">Đăng ký</a></p>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
