<?php
session_start();
include '../logres/noi.php';

if (!isset($_SESSION['CustomerID'])) {
    header('Location: ../logres/login.php');
    exit();
}

$customerID = $_SESSION['CustomerID'];
$result = sqlsrv_query($conn, "SELECT FullName FROM Customers WHERE CustomerID = ?$customerID");
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
if (!$row || strtolower($row['FullName']) !== 'admin') {
    header('Location: ../Hemk/Maincus.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM Orders WHERE OrderID = ?";
    $stmt = sqlsrv_query($conn, $query, array($id));
    $order = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
} else {
    echo "Không tìm thấy đơn hàng.";
    header('Location: odrman.php');
    exit();
}

if ($_SERVER['REQUUEST_METHOD'] === 'POST') {
    $id = $_POST['OrderID'];
    $status = $_POST['Status'];

    $sql = "UPDATE Orders SET Status = ? WHERE OrderID = ?";
    $params = array($status, $id);
    sqlsrv_query($conn, $sql, $params);
    
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
        margin-left: 270px;
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

    .edit {
        max-width: 600px;
        margin: 20px auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .edit h3 {
        font-size: 20px;
        margin-bottom: 20px;
        color: #2c3e50;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 10px;
    }

    .edit-form {
        display: flex;
        flex-direction: column;
    }

    .edit-form input[type="text"] {
        font-size: 16px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 20px;
        width: 100%;
    }

    .edit-form input[type="text"]:focus {
        border-color: #1abc9c;
        outline: none;
        box-shadow: 0 0 5px rgba(26, 188, 156, 0.5);
    }

    .edit-form button {
        background-color: #1abc9c;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    .edit-form button:hover {
        background-color: #16a085;
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
            <h2>Sửa đơn hàng</h2>

            <div class="edit">
                <form class="edit-form" method="POST">
                    <h3>Cập nhật trạng thái</h3>
                    <input type="hidden" name="OrderID" value="<?php echo $order['OrderID']; ?>">
                    <input typr="text" name="Status" value="<?php echo $order['Status']; ?>" placeholder="Trạng thái đơn hàng" required>
                    <button type="submit">Cập nhật</button>
                </form>
            </div>
    </body>
</html>