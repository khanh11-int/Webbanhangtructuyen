<?php
include "noi.php";

$registerError = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $address = trim($_POST['address']);

    // Kiểm tra mật khẩu và xác nhận mật khẩu
    if ($password !== $confirmPassword) {
        $registerError = "Mật khẩu và xác nhận mật khẩu không khớp.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
        // Nếu email không rỗng mà không hợp lệ
        $registerError = "Email không hợp lệ.";
    } else if (!preg_match('/^[0-9]{9,15}$/', $phone)) {
        // Kiểm tra số điện thoại chỉ gồm 9-15 chữ số (có thể chỉnh theo yêu cầu)
        $registerError = "Số điện thoại không hợp lệ.";
    } else {
        // Kiểm tra email hoặc số điện thoại tồn tại
        $sql = "SELECT * FROM Customers WHERE Email = ? OR PhoneNumber = ?";
        $params = array($email, $phone);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        if (sqlsrv_has_rows($stmt)) {
            $registerError = "Email hoặc Số điện thoại đã tồn tại";
        } else {
            // Mã hóa mật khẩu
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sqlInsert = "INSERT INTO Customers (FullName, Email, Password, PhoneNumber, Address, CreatedAT) VALUES (?, ?, ?, ?, ?, GETDATE())";
            $paramsInsert = array($fullName, $email, $hashedPassword, $phone, $address);
            $stmtInsert = sqlsrv_query($conn, $sqlInsert, $paramsInsert);

            if ($stmtInsert === false) {
                $registerError = "Đăng ký thất bại, vui lòng thử lại.";
                if (($errors = sqlsrv_errors()) != null) {
                    foreach ($errors as $error) {
                        $registerError .= " SQLSTATE: " . $error['SQLSTATE'] . " Code: " . $error['code'] . " Message: " . $error['message'] . " ";
                    }
                }
            } else {
                $successMessage = "Đăng ký thành công! <a href='login.php'>Đăng nhập ngay</a>";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Đăng ký Shopee</title>
  <link rel="stylesheet" href="../styles/log.css" />
  <link rel="stylesheet" href="../styles/footer.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    crossorigin="anonymous"
  />
</head>
<body>
  <div class="login-container">
    <div class="login-left">
      <div class="logo-box">
        <a href="khach.php">
          <img src="../image/logo.webp" alt="SNSN" class="logo-img" />
        </a>
      </div>
    </div>
    <div class="login-right">
      <h2>Đăng ký</h2>
      <?php if ($registerError): ?>
        <p class="error-message"><?php echo $registerError; ?></p>
      <?php endif; ?>
      <?php if ($successMessage): ?>
        <p class="success-message"><?php echo $successMessage; ?></p>
      <?php endif; ?>
      <form method="POST" action="">
        <input type="text" name="fullName" placeholder="Tên đăng nhập" required />
        <input type="email" name="email" placeholder="Email (tuỳ chọn)" />
        <input type="text" name="phone" placeholder="Số điện thoại" required />
        <input type="password" name="password" placeholder="Mật khẩu" required />
        <input type="password" name="confirmPassword" placeholder="Xác nhận mật khẩu" required />
        <input type="text" name="address" placeholder="Địa chỉ" />
        <button type="submit">ĐĂNG KÝ</button>
        <div class="login-options">
          <a href="login.php">Đăng nhập</a>
        </div>
        <div class="signup">
          <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
