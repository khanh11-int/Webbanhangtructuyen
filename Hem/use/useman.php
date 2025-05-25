<?php
session_start();
include '../logres/noi.php';

if (!isset($_SESSION['CustomerID'])) {
    header('Location: ../logres/login.php');
    exit();
}

$customerID = $_SESSION['CustomerID'];
$result = sqlsrv_query($conn, "SELECT FullName FROM Customers WHERE CustomerID = $customerID");
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
if (!$row || strtolower($row['FullName']) !== 'admin') {
    header('Location: ../Hemk/Maincus.php');
    exit();
}

$userQuery = "SELECT * FROM Customers";
$query = mysqli_query($conn, $userQuery);
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quản lý người dùng</title>
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
        background-color: #34495e;
        margin: 0;
        font-size: 24px;
        text-transform: uppercase;
    }

    .sidebar a {
        display: block;
        color: white;
        padding: 15px 20px;
        font-size: 16px;
        text-decoration: none;
        border-bottom: 1px solid #34495e;
        transition: background-color 0.3s ease;
    }

    .sidebar a:hover {
        background-color: #1abc9c;
    }

    .container {
        margin-left: 270px;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        max-width: 1000px;
    }

    h1 {
        font-size: 28px;
        color: #2c3e50;
        text-align: center;
        border-bottom: 2px solid #1abc9c;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .user {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        font-size: 16px;
        text-transform: uppercase;
        color: #2c3e50;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .action a {
        color: #007bff;
        text-decoration: none;
        font-size: 14px;
        margin-right: 10px;
        transition: color 0.3s ease;
    }

    .action a:hover {
        color: #0056b3;
    }

    .action a:last-child {
        color: #e74c3c;
    }

    .action a:last-child:hover {
        color: #c0392b;
    }

    .user table tr:last-child td {
        text-align: center;
    }

    .user table tr:last-child td a {
        display: inline-block;
        padding: 10px 15px;
        background-color: #1abc9c;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }

    .user table tr:last-child td a:hover {
        background-color: #16a085;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .container {
            margin-left: 0;
            padding: 15px;
        }

        table {
            font-size: 14px;
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
            <h1>Quản lý người dùng</h1>
            
            <div class="user">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Tên đầy đủ</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($user = mysqli_fetch_array($user, SQLSRV_FETCH_ASSOC)) { ?>
                        <tr>
                            <td><?php echo $user['CustomerID']; ?></td>
                            <td><?php echo $user['FullName']; ?></td>
                            <td><?php echo $user['Email']; ?></td>
                            <td><?php echo $user['PhoneNumber']; ?></td>
                            <td><?php echo $user['Address']; ?></td>
                            <td class="action">
                                <a href="../Hem/use/detuse.php?id=<?php echo $user['CustomerID']; ?>">Chi tiết</a>
                                <a href="../Hem/use/ediuse.php?id=<?php echo $user['CustomerID']; ?>">Chỉnh sửa</a>
                                <a href="../Hem/use/deluse.php?id=<?php echo $user['CustomerID']; ?>" onclick="return confirm('Xoá người dùng này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">
                            <a href="../Hem/use/adduse.php">Thêm người dùng mới</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>