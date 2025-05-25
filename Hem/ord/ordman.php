<?php
session_start();
include '../logres/noi.php';

if (!isset($_SESSION['CustomerID'])) {
    header('Location: ../logres/login.php');
    exit();
}

$customerID = $_SESSION['CustomerID'];
$result = sqlsrv_query($conn,'SELECT FullName FROM Customers WHERE CustomerID = $customerID');
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
if (!$row || strtolower($row['FullName']) !== 'admin') {
    header('Location: ../Hemk/Maincus.php');
    exit();
}

$orderQuery = "SELECT o.OrderID, o.CustomerID, o.FULLName, o.OrderDate, o.Status
               FROM Orders o
               JOIN Customers c ON o.CustomerID = c.CustomerID";
$orders = sqlsrv_query($conn, $orderQuery);
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quản lý đơn hàng</title>
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
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        border-radius: 8px;
    }

    h1 {
        margin-top: 0;
        font-size: 28px;
        color: #2c3e50;
        border-bottom: 2px solid #1abc9c;
        padding-bottom: 10px;
    }

    .order_list {
        max-width: 100%;
        margin-top: 20px;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        font-size: 16px;
    }

    td {
        font-size: 14px;
        color: #555;
    }

    .actions a {
        display: inline-block;
        padding: 5px 10px;
        margin-right: 10px;
        color: white;
        background-color: #007bff;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
    }

    .actions a:hover {
        background-color: #0056b3;
    }

    .actions a:last-child {
        background-color: #dc3545;
    }

    .actions a:last-child:hover {
        background-color: #c82333;
    }

    .add-order {
        text-align: center;
        margin-top: 20px;
    }

    .add-order a {
        display: inline-block;
        padding: 10px 20px;
        background-color: #1abc9c;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        margin: 10px;
    }

    .add-order a:hover {
        background-color: #16a085;
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
            <h1>Quản lý đơn hàng</h1>

            <div class="order_list">
            <table >
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Full Name</th>
                        <th>Order Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                    <?php while ($order = sqlsrv_fetch_array($orders, SQLSRV_FETCH_ASSOC)): { ?>
                        <tr>
                            <td><?php $order['OrderID']; ?></td>
                            <td><?php $order['FULLName']; ?></td>
                            <td><?php $order['OrderDate']->format('Y-m-d H:i:s'); ?></td>
                            <td><?php $order['Status']; ?></td>
                            <tp class="actions">
                                <a href="../Hemk/ord/edipro.php?OrderID=<?php echo $order['OrderID']; ?>">Sửa đơn hàng</a>
                                <a href="../Hemk/ord/delord.php?OrderID=<?php echo $order['OrderID']; ?>" onclick="return confirm('xoá đơn hàng này?')">Xoá đơn hàng</a>
                                <a href="../Hemk/ord/detord.php?OrderID=<?php echo $order['OrderID']; ?>">Chi tiết đơn hàng</a>
                            </tp>
                        </tr>
                        <?php } ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">
                                    <a href="../Hem/ord/addord.php">Thêm đơn hàng mới</a>
                                    <br>
                                    <br>
                                    <br>
                                    <a href="../Hem/ord/addprotoord.php">Thêm sản phẩm vào đơn hàng</a>
                                </td>
                            </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </body>
</html>