<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../logres/noi.php';

if (!isset($_SESSION['CustomerID'])) {
    header('Location: ../logres/login.php');
    exit();
}

$cart = $_SESSION['cart'];
$customerID = $_SESSION['CustomerID'];
$total = 0;

foreach ($cart as $item) {
    $total += $item['Price'] * $item['Quantity'];
}

$insertOrder = "{Call sp_AddOrder(?, ?, ?)}";
$orderParams = [$customerID, $total, 'Pending'];
$orderStmt = sqlsrv_query($conn, $insertOrder, $orderParams);

if (!$orderStmt) {
    $_SESSION['error'] = "Không đủ " . $item['ProductName'] . " trong kho.";
    header("Location: ../Hemk/cart.php");
    exit();
}

$getOrderID = sqlsrv_query($conn, "SELECT MAX(OrderID) AS OrderID FROM Orders WHERE CustomerID = ?", [$customerID]);
$orderIDRow = sqlsrv_fetch_array($getOrderID, SQLSRV_FETCH_ASSOC);
$orderID = $orderIDRow["OrderID"];

foreach ($cart as $item) {
    $addDetail = "{CALL sp_AddOrderDetail(?, ?, ?, ?)}";
    $params = [$orderID, $item['ProductID'], $item['Quantity'], $item['Price']];
    $detailStmt = sqlsrv_query($conn, $addDetail, $params);

    if (!$detailStmt) {
        $_SESSION['error'] = "Không đủ " . $item['ProductName'] . " trong kho.";
        header("Location: ../Hemk/cart.php");
        exit();
    }
}

unset($_SESSION['cart']);
$_SESSION['success'] = "Đặt hàng thành công! Man hàng của bạn là: " . $orderID;
header("Location: ../Hemk/cart.php");
exit();
?>