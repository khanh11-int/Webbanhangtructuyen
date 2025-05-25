<?php
include '../logres/noi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Productname = $_POST['Productname'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_POST['ImageURL'] ;
    $description = $_POST['Description'];

    $sql = "INSERT INTO Products (ProductName, CategoryID, Price, Quantity, ImageURL, Description) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $params = array($Productname, $category, $price, $quantity, $image, $description);
    sqlsrv_query($conn, $sql, $params);
}

header('Location: proman.php');
exit();
?>
