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
if ($row === false || strtolower($row['FullName']) !== 'admin') {
    header('Location: ../Hemk/Maincus.php');
    exit();
}

if (isset($_GET['id'])) {
    $is = $_GET['id'];
    $query = "SELECT * FROM Customers WHERE CustomerID = ?";
    $stmt = sqlsrv_query($conn, $query, array($is));
    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
} else {
    echo "Không tìm thấy người dùng.";
    header('Location: cusman.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chi tiết người dùng</title>
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

    .content {
        margin-left: 270px;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        max-width: 800px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 28px;
        color: #2c3e50;
        border-bottom: 2px solid #1abc9c;
        padding-bottom: 10px;
    }

    .user-details {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 20px 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .user-details h3 {
        font-size: 22px;
        color: #34495e;
        margin-bottom: 15px;
    }

    .user-details p {
        font-size: 16px;
        margin-bottom: 10px;
        color: #555;
    }

    .user-details p strong {
        color: #2c3e50;
    }

    .ve {
        display: inline-block;
        margin-top: 20px;
        padding: 12px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        font-size: 16px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .ve:hover {
        background-color: #0056b3;
    }

    @media (max-width: 768px) {
        .content {
            margin-left: 20px;
            margin-right: 20px;
            padding: 15px;
        }

        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .sidebar h1 {
            font-size: 20px;
        }

        .sidebar a {
            text-align: center;
            padding: 10px 0;
        }
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
        
        <div class="content">
            <h2>Chi tiết người dùng</h2>
            
            <div class="user-details">
                <div class="detail">
                    <h3><?php echo htmlspecialchars($user['FullName']); ?></h3>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
                    <p><strong>Điện thoại:</strong> <?php echo htmlspecialchars($user['Phone']); ?></p>
                    <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($user['Address']); ?></p>
                </div>

                <a class="ve" href="useman.php">Quay lại</a>
            </div>
        </div>
    </body>
</html>