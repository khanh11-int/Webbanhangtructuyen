<?php
session_start();
include '../logres/noi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $productID = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productID])) {
        $_SESSION['cart'][$productID] += $quantity;
    } else {
        $_SESSION['cart'][$productID] = $quantity;
    }
    
    $SESSION['message'] = "Sản phẩm đã được thêm vào giỏ hàng thành công!";
    header('Location: ../Maincus.php');
    exit();
}
?>
