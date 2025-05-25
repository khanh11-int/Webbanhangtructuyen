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

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "SELECT o.*, c.FullName
                FROM Orders o
                JOIN Customers c ON o.CustomerID = c.CustomerID
                WHERE o.OrderID = ?";
    $stmt = sqlsrv_query($conn, $query, array($id));
    $order = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    $detailQuery = "SELECT od.*, p.ProductName
                    FROM OrderDetails od
                    JOIN Products p ON od.ProductID = p.ProductID
                    WHERE od.OrderID = ?";
    $details = sqlsrv_query($conn, $detailQuery, array($id));

    if ($details === false) {
        die(print_r(sqlsrv_errors(), true));
    }
}else {
    echo " không tìm thấy đơn hàng.";
    header('Location: odrman.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chi tiết đơn hàng</title>
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
        margin-left: 270px; /* Adjusted for sidebar width */
        padding: 30px;
        background-color: #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        border-radius: 8px;
    }

    h2 {
        margin-top: 0;
        font-size: 28px;
        color: #2c3e50;
        border-bottom: 2px solid #1abc9c;
        padding-bottom: 10px;
    }

    p {
        font-size: 16px;
        color: #7f8c8d;
        margin: 10px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #1abc9c;
        color: white;
        text-transform: uppercase;
        font-size: 14px;
    }

    td {
        font-size: 14px;
        color: #2c3e50;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e8f6f3;
    }

    a {
        color: #1abc9c;
        text-decoration: none;
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
    }

    .back-link {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 15px;
        background-color: #1abc9c;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
    }

    .back-link:hover {
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
            <h2>Chi tiết đơn hàng #<?php echo $order['OrderID']; ?></h2>
            <p><strong>Khách hàng:</strong> <?php echo $order['FullName']; ?></p>
            <p><strong>Ngày đặt:</strong> <?php echo $order['OrderDate']->format('d-m-Y H:i:s'); ?></p>
            <p><strong>Tổng tiền:</strong> <?php echo number_format($order['Total'], 0, ',', '.'); ?> VNĐ</p>
            <p><strong>Trạng thái:</strong> <?php echo $order['Status']; ?></p>

            <h3>Chi tiết sản phẩm</h3>
            <table>
                <tr>
                    <th>ID sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                </tr>
                <?php if ($details !== false) { ?>
                    <?php while ($detail = sqlsrv_fetch_array($details, SQLSRV_FETCH_ASSOC)) { ?>
                        <tr>
                            <td><?php echo $detail['ProductID']; ?></td>
                            <td><?php echo $detail['ProductName']; ?></td>
                            <td><?php echo $detail['Quantity']; ?></td>
                            <td><?php echo number_format($detail['UnitPrice'], 0, ',', '.'); ?> VNĐ</td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4">Không có sản phẩm nào trong đơn hàng này.</td>
                    </tr>
                <?php } ?>
            </table>

            <a href="odrman.php">Quay lại danh sách đơn hàng</a>
    </body>
</html>