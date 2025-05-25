<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../logres/noi.php';

if (!isset($_SESSION['CustomerID'])) {
    header('Location: ../logres/login.php');
    exit();
}

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$customerID = $_SESSION['CustomerID'];
$sql = "SELECT * FROM vw_CustomerPurchaseHistory WHERE CustomerID = ?";
$result = sqlsrv_query($conn, $sql, [$customerID]);
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lịch sử mua hàng</title>
        <style>
            body {
    font-family: 'Roboto', Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

.sidebar {
    width: 240px;
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
    margin: 0;
    background-color: #34495e;
    border-bottom: 1px solid #34495e;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    padding: 15px 20px;
}

.sidebar ul li a {
    color: #ecf0f1;
    text-decoration: none;
    font-size: 16px;
    display: block;
    transition: background 0.3s ease, color 0.3s ease;
}

.sidebar ul li a:hover {
    background-color: #1abc9c;
    color: white;
}

.container {
    max-width: 900px;
    margin: 40px auto;
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
    font-size: 24px;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-size: 14px;
    line-height: 1.5;
}

.alert-success {
    background-color: #dff0d8;
    color: #3c763d;
}

.alert-danger {
    background-color: #f2dede;
    color: #a94442;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
    font-weight: bold;
    font-size: 14px;
}

td {
    font-size: 14px;
}

tbody tr:hover {
    background-color: #f9f9f9;
    transition: background 0.2s ease;
}

tfoot {
    font-weight: bold;
    background-color: #f2f2f2;
}

@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .container {
        margin: 20px;
        padding: 15px;
    }

    table {
        font-size: 12px;
    }

    th, td {
        padding: 8px;
    }
}

        </style>
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

        <div class="container">
            <h1>Lịch sử mua hàng</h1>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['OrderID']) ?></td>
                            <td><?= htmlspecialchars($row['OrderDate']->format('Y-m-d H:i:s')) ?></td>
                            <td><?= htmlspecialchars(number_format($row['Total'], 2)) ?> VND</td>
                            <td><?= htmlspecialchars($row['Status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
</html>