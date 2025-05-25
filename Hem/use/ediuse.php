<?php
session_start();
include '../logres/noi.php';

if (!isset($_SESSION['CustomerID'])) {
    header('Location: ../logres/login.php');
    exit();
}

$customerID = $_SESSION['CustomerID'];
$result = sqlsrv_query($conn, "SELECT FullName FROM Customers WHERE CustomerID = ($customerID");
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
if (!$row || strtolower($row['FullName']) !== 'admin') {
    header('Location: ../Hemk/Maincus.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM Customers WHERE CustomerID = ?";
    $stmt = sqlsrv_query($conn, $query, array($id));
    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
} else {
    echo "Không tìm thấy người dùng.";
    header('Location: cusman.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['CustomerID'];
    $name = $_POST['FullName'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone'];
    $address = $_POST['Address'];

    $sql = "UPDATE Customers SET FullName = ?, Email = ?, Phone = ?, Address = ? WHERE CustomerID = ?";
    $params = array($name, $email, $phone, $address, $id);
    sqlsrv_query($conn, $sql, $params);

    header('Location: cusman.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chỉnh sửa người dùng</title>
        <style>
            
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
            <h2>Chỉnh sửa người dùng</h2>
            
            <div class="edit-form">
                <form method="POST" action="">
                    <h3>Thông tin người dùng</h3>
                    <input type="hidden" name="CustomerID" value="<?php echo htmlspecialchars($user['CustomerID']); ?>">
                    <input type="text" name="FullName" value="<?php echo htmlspecialchars($user['FullName']); ?>" placeholder="Họ và tên" required>
                    <input type="email" name="Email" value="<?php echo htmlspecialchars($user['Email']); ?>" placeholder="Email" required>
                    <input type="text" name="Phone" value="<?php echo htmlspecialchars($user['Phone']); ?>" placeholder="Số điện thoại" required>
                    <input type="text" name="Address" value="<?php echo htmlspecialchars($user['Address']); ?>" placeholder="Địa chỉ" required>
                    <button type="submit">Cập nhật người dùng</button>
                </form>
            </div> 
        </div>
    </body>
</html>