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
    $query = "SELECT * FROM Products WHERE ProductID = ?";
    $stmt = sqlsrv_query($conn, $query, array($id));
    $product = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
} else {
    echo "Không tìm thấy sản phẩm.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chi tiết sản phẩm</title>
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

    .product-details {
        display: flex;
        align-items: flex-start;
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .product-details img {
        max-width: 250px;
        max-height: 250px;
        border-radius: 8px;
        margin-right: 20px;
        border: 1px solid #ddd;
    }

    .product-details .details {
        flex: 1;
    }

    .product-details h3 {
        margin-top: 0;
        font-size: 24px;
        color: #2c3e50;
    }

    .product-details p {
        font-size: 16px;
        color: #555;
        margin: 5px 0;
    }

    .ve {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #1abc9c;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .ve:hover {
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
            <h2>Chi tiết sản phẩm</h2>
            
            <div class="product-details">
                <img src="<?php echo $product['ImageURL']; ?>" alt="<?php echo $product['ProductName']; ?>">
                <div class="details">
                    <h3><?php echo $product['ProductName']; ?></h3>
                    <p>Danh mục: <?php echo $product['Category']; ?></p>
                    <p>Giá: <?php echo number_format($product['Price'], 0, ',', '.'); ?> VNĐ</p>
                    <p>Trạng thái: <?php echo $product['Status'] ? 'Còn hàng' : 'Hết hàng'; ?></p>
                    <p>Mô tả: <?php echo $product['Description']; ?></p>
                </div>

                <a class="ve" href="proman.php">Quay lại</a>
            </div>
        </div>
    </body>
</html>