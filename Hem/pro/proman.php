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

$productQuery = "SELECT * FROM Products";
$products = sqlsrv_query($conn, $productQuery);
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quản lý sản phẩm</title>
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
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    h2 {
        margin-top: 0;
        font-size: 28px;
        color: #2c3e50;
        border-bottom: 2px solid #1abc9c;
        padding-bottom: 10px;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
    }

    .product {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .product:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .product img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .product h3 {
        margin: 10px 0;
        font-size: 20px;
        color: #34495e;
    }

    .product p {
        font-size: 16px;
        color: #555;
        margin: 10px 0;
    }

    .actions a {
        display: inline-block;
        padding: 8px 12px;
        margin: 5px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        color: white;
        background-color: #007bff;
        transition: background-color 0.3s ease;
    }

    .actions a:hover {
        background-color: #0056b3;
    }

    .add-product {
        text-align: center;
        margin-top: 20px;
    }

    .add-product a {
        display: inline-block;
        padding: 12px 20px;
        background-color: #1abc9c;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .add-product a:hover {
        background-color: #16a085;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal form {
        background-color: #ffffff;
        margin: 10% auto;
        padding: 30px;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .modal form h4 {
        margin: 0 0 20px;
        font-size: 22px;
        color: #2c3e50;
    }

    .modal form input,
    .modal form textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .modal form button {
        padding: 10px 15px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    .modal form button:hover {
        background-color: #218838;
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
            <h2>Quản lý sản phẩm</h2>

            <div class="grid">
                <?php while ($product = sqlsrv_fetch_array($products, SQLSRV_FETCH_ASSOC)) { ?>
                    <div class="product">
                        <img src=""<?php echo $product['Image']; ?>" alt="<?php echo $product['ProductName']; ?>">
                        <h3><?php echo $product['ProductName']; ?></h3>
                        <p>Giá: <?php echo number_format($product['Price'], 0, ',', '.'); ?> VNĐ</p>
                        <div class="actions">
                            <a href="edipro.php?id=<?php echo $product['ProductID']; ?>">Sửa</a>
                            <a href="delpro.php?id=<?php echo $product['ProductID']; ?>" onclick="return confirm('xoá sản phẩm này?')">Xóa</a>
                            <a href="detpro.php?id=<?php echo $product['ProductID']; ?>">Chi tiết</a>
                        </div>
                    </div>
                <?php } ?>

                <div class="add-product" oncclick="document.getElementById('addProductForm').style.display='block';">
                    <a href="addpro.php">Thêm sản phẩm mới</a>
                </div>
            </div>
        </div>

        <div id="addProductForm" class="modal">
            <form action="addpro.php" method="POST">
                <h4>Thêm sản phẩm mới</h4>
                <input type="text" name="ProductName" placeholder="Tên sản phẩm" required>
                <input type="text" name=""Category" placeholder="Loại sản phẩm" required>
                <input type="number" name="Price" placeholder="Giá sản phẩm" required>
                <input type="number" name="Quantity" placeholder="Số lượng" required>
                <input type="text" name="Image" placeholder="URL hình ảnh" required>
                <textarea name="Description" placeholder="Mô tả sản phẩm" required></textarea>
                <button type="submit">Thêm sản phẩm</button>
                <button type="button" onclick="document.getElementById('addProductForm').style.display='none';">Đóng</button>
            </form>
        </div>
    </body>
</html>