<?php
session_start();
include '../logres/noi.php';

if (!isset($_SESSION['CustomerID'])) {
    header('Location: ../logres/login.php');
    exit();
}

$customerID = $_SESSION['CustomerID'];
$result = sqlsrv_query($conn, "SELECT FullName FROM Customers WHERE CustomerID = ?", array($customerID));
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
if (!$row || strtolower($row['FullName']) !== 'admin') {
    header('Location: ../Hemk/Maincus.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM Products WHERE ProductID = ?";
    $stmt = sqlsrv_query($conn, $query, array($id));
    $product = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
} else {
    echo "Không tìm thấy sản phẩm.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['ProductID'];
    $name = $_POST['ProductName'];
    $price = $_POST['Price'];
    $quantity = $_POST['Quantity'];
    $image = $_POST['ImageURL'];
    $description = $_POST['Description'];

    $sql = "UPDATE Products SET ProductName = ?, Price = ?, Quantity = ?, ImageURL = ?, Description = ? WHERE ProductID = ?";
    $params = array($name, $price, $quantity, $image, $description, $id);
    sqlsrv_query($conn, $sql, $params);

    header('Location: proman.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chỉnh sửa sản phẩm</title>
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
        margin: 0 auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .edit-form {
        display: flex;
        flex-direction: column;
    }

    .edit-form input[type="text"],
    .edit-form input[type="number"],
    .edit-form textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 16px;
        color: #555;
    }

    .edit-form textarea {
        height: 100px;
        resize: vertical;
    }

    .edit-form button {
        background-color: #1abc9c;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .edit-form button:hover {
        background-color: #16a085;
    }

    input[type="text"]:focus,
    input[type="number"]:focus,
    textarea:focus {
        border-color: #1abc9c;
        outline: none;
        box-shadow: 0 0 5px rgba(26, 188, 156, 0.5);
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
            <h2>Chỉnh sửa sản phẩm</h2>
            
            <div class="edit">
                <form class="edit-form" method="POST">
                    <input type="hidden" name="ProductID" value="<?php echo $product['ProductID']; ?>">
                    <input type="text" name="ProductName" value="<?php echo $product['ProductName']; ?>" placeholder="Tên sản phẩm" required>
                    <input type="text" name="Category" value="<?php echo $product['Category']; ?>" placeholder="Loại sản phẩm" required>
                    <input type="number" name="Price" value="<?php echo $product['Price']; ?>" placeholder="Giá sản phẩm" required>
                    <input type="number" name="Quantity" value="<?php echo $product['Quantity']; ?>" placeholder="Số lượng" required>
                    <input type="text" name="ImageURL" value="<?php echo $product['ImageURL']; ?>" placeholder="URL hình ảnh" required>
                    <textarea name="Description" placeholder="Mô tả sản phẩm" required><?php echo $product['Description']; ?></textarea>
                    <button type="submit">Cập nhật sản phẩm</button>
                </form>
            </div>
        </div>
    </body>
</html>