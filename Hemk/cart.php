<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../logres/noi.php';

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

if (!isset($_SESSION['CustomerID'])) {
    header('Location: ../logres/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    foreach ($_POST['quantities'] as $productID => $quantity) {
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['ProductID'] == $productID) {
                $quantity = (int)$quantity;
                if ($quantity <= 0) {
                    unset($_SESSION['cart'][$index]);
                } else {
                    $_SESSION['cart'][$index]['Quantity'] = $quantity;
                }
            }
        }
    }
}

if (!isset($_GET['delete'])) {
    $deleteID = $_GET['delete'];
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($deleteID) {
        return $item['ProductID'] != $deleteID;
    });
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
if (isset($_POST['checkout']) && !empty($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    $customerID = $_SESSION['CustomerID'];
    $total = 0;

    foreach ($cart as $item) {
        $total += $item['Price'] * $item['Quantity'];
    }

    $insertOrder = "{Call sp_AddOrder(?, ?, ?)}";
    $orderParams = [$customerID, $total, 'Pending'];
    $orderStmt = sqlsrv_query($conn, $insertOrder, $orderParams);

    if ($orderStmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $getOrderID = sqlsrv_query($conn, "SELECT MAX(OrderID) AS OrderID FROM Orders WHERE CustomerID = ?", [$customerID]);
    $orderIDRow = sqlsrv_fetch_array($getOrderID, SQLSRV_FETCH_ASSOC);
    $orderID = $orderIDRow["OrderID"];

    foreach ($cart as $item) {
        $addDetail = "{Call sp_AddOrderDetail(?, ?, ?, ?)}";
        $params = [$orderID, $item['ProductID'], $item['Quantity'], $item['Price']];
        $detailStmt = sqlsrv_query($conn, $addDetail, $params);

        if ($detailStmt === false || $params === false || $addDetail === false) {
            die("<script>alert('Khôg đủ {$item['ProcductName']}');</script>");
        }
    }

    unset($_SESSION['cart']);
    echo "<script>alert('Đặt hàng thành công!');</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
   body {
    font-family: 'Roboto', Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

.sidebar {
    width: 220px;
    height: 100vh;
    background-color: #2c3e50;
    color: #ecf0f1;
    position: fixed;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
}

.sidebar h1 {
    text-align: center;
    padding: 20px 0;
    font-size: 24px;
    border-bottom: 1px solid #34495e;
    margin: 0;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    padding: 10px 20px;
}

.sidebar ul li a {
    color: #ecf0f1;
    text-decoration: none;
    font-size: 16px;
    display: block;
    transition: background 0.3s ease, color 0.3s ease;
}

.sidebar ul li a:hover {
    background-color: #34495e;
    color: #1abc9c;
}

.content {
    margin-left: 240px;
    padding: 20px;
}

.header {
    text-align: center;
    margin-bottom: 20px;
}

.cart-container {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    max-width: 900px;
    margin: 0 auto;
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
    background-color: #f4f4f4;
    font-weight: bold;
}

td {
    font-size: 14px;
}

input[type="number"] {
    width: 60px;
    padding: 5px;
    font-size: 14px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.cart-actions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.cart-actions button {
    padding: 10px 20px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.cart-actions button:hover {
    background-color: #2980b9;
}

.cart-actions button:nth-child(2) {
    background-color: #2ecc71;
}

.cart-actions button:nth-child(2):hover {
    background-color: #27ae60;
}

a {
    color: #e74c3c;
    text-decoration: none;
}

a:hover {
    color: #c0392b;
    text-decoration: underline;
}

.total {
    font-size: 18px;
    font-weight: bold;
    text-align: right;
    margin-top: 20px;
    color: #2c3e50;
}

</head>
<body>
    <div class="sidebar">
        <h1>🏠</h1>
        <ul>
            <li><a href="../Hemk/Maincus.php">Trang chính</a></li>
            <li><a href="../Hemk/cart.php">Giỏ hàng</a></li>
            <li><a href="../Hemk/history.php">Lịch sử mua</a></li>
            <li><a href="../logres/login.php">Đăng xuất</a></li>
        </ul>
    </div>

    <div class="content">
        <header class="header">
            <h1>Giỏ hàng của bạn</h1>
        </header>

        <div class="cart-container">
            <?php if (empty($cart)): ?>
                <p>Giỏ hàng của bạn đang trống.</p>
            <?php else: ?>
                <form method="post" action="">
                    <table>
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tổng</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['ProductName']) ?></td>
                                    <td><?= number_format($item['Price'], 0, ',', '.') ?> VNĐ</td>
                                    <td><input type="number" name="quantities[<?= $item['ProductID'] ?>]" value="<?= $item['Quantity'] ?>" min="1"></td>
                                    <td><?= number_format($item['Price'] * $item['Quantity'], 0, ',', '.') ?> VNĐ</td>
                                    <td><a href="?delete=<?= $item['ProductID'] ?>">Xóa</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="cart-actions">
                        <button type="submit" name="update">Cập nhật giỏ hàng</button>
                        <button href="../Hemk/pay.php"type="submit" name="checkout">Thanh toán</button> // them link thanh toan
                    </div>

                    <?php if ($total > 0): ?>
                        <p class="total">Tổng cộng: <?= number_format($total, 0, ',', '.') ?> VNĐ</p>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </div>
</body>