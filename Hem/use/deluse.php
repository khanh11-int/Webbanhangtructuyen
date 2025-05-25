<?php
include '../logres/noi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM Customers WHERE CustomerID = ?";
    sqlsrv_query($conn, $sql, array($id));
}

header('Location: cusman.php');
exit();
?>