<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../logres/noi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
    $name = $_POST['Fullname'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $phone = $_POST['Phone'];
    $address = $_POST['Address'];

    $hashedPassword = hash('sha256', $password);

    $sql = "{Call sp_AddUser(?, ?, ?, ?, ?)}";
    $params = array($name, $email, $hashedPassword, $phone, $address);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    header('Location: useman.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Thêm người dùng</title>
      <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    .sidebar {
        width: 250px;
        height: 100vh;
        background-color: #2c3e50;
        color: white;
        position: fixed;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    .sidebar h1 {
        text-align: center;
        padding: 20px 0;
        font-size: 24px;
        background-color: #34495e;
        margin: 0;
        text-transform: uppercase;
    }

    .sidebar a {
        display: block;
        color: white;
        padding: 15px 20px;
        text-decoration: none;
        font-size: 16px;
        border-bottom: 1px solid #34495e;
    }

    .sidebar a:hover {
        background-color: #1abc9c;
    }

    .container {
        margin-left: 270px;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        max-width: 800px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 28px;
        color: #2c3e50;
        border-bottom: 2px solid #1abc9c;
        padding-bottom: 10px;
    }

    .form-container {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 20px 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin: auto;
    }

    .form-container input[type="text"],
    .form-container input[type="email"],
    .form-container input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .form-container button {
        width: 100%;
        padding: 12px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-container button:hover {
        background-color: #218838;
    }

    .form-container input::placeholder {
        color: #aaa;
    }

    .form-container input:focus {
        border-color: #1abc9c;
        outline: none;
        box-shadow: 0 0 5px rgba(26, 188, 156, 0.5);
    }

    @media (max-width: 768px) {
        .container {
            margin-left: 20px;
            margin-right: 20px;
            padding: 15px;
        }

        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .sidebar h1 {
            font-size: 20px;
        }

        .sidebar a {
            text-align: center;
            padding: 10px 0;
        }
    }
</style>

    </head>
    <body>
        <side class="sidebar">
            <h1>=Admin=</h1>
                <a href="../Hem/Mainad.php">Trang Chính</a>
                <a href="../Hem/pro/proman.php">Sản phẩm</a>
                <a href="../Hem/use/useman.php">Người dùng</a>
                <a href="../Hem/ord/ordman.php">Đơn hàng</a>
                <a href="../logres/login.php">Đăng xuất</a>
        </side>

        <div class="container">
            <h2>Thêm người dùng mới</h2>
            
            <div class="form-container">
                <form method="POST" action="">
                    <input type="text" name="Fullname" placeholder="Họ và tên" required>
                    <input type="email" name="Email" placeholder="Email" required>
                    <input type="password" name="Password" placeholder="Mật khẩu" required>
                    <input type="text" name="Phone" placeholder="Số điện thoại" required>
                    <input type="text" name="Address" placeholder="Địa chỉ" required>
                    <button type="submit">Thêm người dùng</button>
                </form>
            </div>
        </div>
    </body>
</html>