<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../logres/noi.php';

$orders = sqlsrv_query($conn, "SELECT OrderID FROM Orders");
$products = sqlsrv_query($conn, "SELECT ProductID, ProductName FROM Products");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderID = $_POST['OrderID'];
    $productID = $_POST['ProductID'];
    $quantity = $_POST['Quantity'];
    $unitPrice = $_POST['UnitPrice'];
    
    $sql = "{Call sp_AddOrderDetail(?, ?, ?, ?)}";
    $params = array($orderID, $productID, $quantity, $unitPrice);
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    header('Location: proman.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Thêm sản phẩm vào đơn hàng</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }
            .sidebar {
                width: 200px;
                height: 100vh;
                background-color: #333;
                color: white;
                position: fixed;
            }
            .sidebar h1 {
                text-align: center;
                padding: 20px 0;
            }
            .sidebar a {
                display: block;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
            }
            .sidebar a:hover {
                background-color: #575757;
            }
            .container {
                margin-left: 220px; /* Adjusted for sidebar width */
                padding: 20px;
            }
            form {
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            label {
                display: block;
                margin-bottom: 10px;
            }
            input, select, button {
                width: calc(100% - 20px);
                padding: 10px;
                margin-bottom: 15px;
            }
            button {
                background-color: #28a745;
                color: white;
                border: none;
                cursor: pointer;
            }
            button:hover {
                background-color: #218838;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            th {
                background-color: #f4f4f4;
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
            <h2>Thêm sản phẩm vào đơn hàng</h2>
            <div class="container">
                <form method="POST">
                    <h3>Chi tiết</h3>

                    <label> Đơn Hàng</label>
                    <select name="OrderID" required>
                        <option value="">Chọn đơn hàng</option>
                        <?php while ($row = sqlsrv_fetch_array($orders, SQLSRV_FETCH_ASSOC)): ?>
                            <option value="<?php echo $row['OrderID']; ?>"><?php echo $row['OrderID']; ?></option>
                        <?php endwhile; ?>
                    </select>

                    <label>Sản phẩm</label>
                    <select name="ProductID" required>
                        <option value="">Chọn sản phẩm</option>
                        <?php while ($row = sqlsrv_fetch_array($products, SQLSRV_FETCH_ASSOC)): ?>
                            <option value="<?php echo $row['ProductID']; ?>"><?php echo $row['ProductName']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    
                    <label>Số lượng</label>
                    <input type="number" name="Quantity" min="1" required>

                    <label>Giá đơn vị</label>
                    <input type="number" name="UnitPrice" step="0.01" required>

                    <button type="submit">Thêm vào đơn hàng</button>
                </form>
            </div>
        </div>
</html>