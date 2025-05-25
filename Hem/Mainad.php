<?php
session_start();
include '../logres/noi.php';
include 'Funt.php';

if (!isset($_SESSION["CustomerID"])) {
    header(header: 'Location: ../logres/login.php');
    exit();
}

$customerID = $_SESSION["CustomerID"];
$result = sqlsrv_query($conn, "SELECT FullName FROM Customers WHERE CustomerID = ?", array($customerID));
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
if ( !$row || strtolower($row['FullName']) !== 'admin') {
    header('Location: ../Hemk/Maincus.php');
    exit();
}

$searchResult = null;
if (isset($_GET['search'])) {
    $keyword = $_GET['search'];
    $searchResult = searchProductsByName($keyword);
}

$oderResult = null;
if (isset($_GET['customerID'])) {
    $customerID = $_GET['customerID'];
    $oderResult = getOrdersByCustomer($customerID);
}
$revenueResult = sqlsrv_query( $conn, sql: "SELECT * FROM vw_RevenueByMonth");

$hisResult = null;
if (isset($_GET['history_customerID'])) {
    $historyCustomerID = $_GET['history_customerID'];
    $sql = "SELECT * FROM vw_CustomerPurchaseHistory WHERE CustomerID = ?";
    $hisResult = sqlsrv_query($conn, $sql, [$historyCustomerID]);
}
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin</title>
     <style>
    body {
        font-family: 'Arial', sans-serif;
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
        background-color: #34495e;
        margin: 0;
        font-size: 24px;
        text-transform: uppercase;
    }

    .sidebar a {
        display: block;
        color: white;
        padding: 15px 20px;
        font-size: 16px;
        text-decoration: none;
        border-bottom: 1px solid #34495e;
        transition: background-color 0.3s ease;
    }

    .sidebar a:hover {
        background-color: #1abc9c;
    }

    .home {
        margin-left: 270px;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        max-width: calc(100% - 300px);
    }

    .head {
        font-size: 28px;
        color: #2c3e50;
        text-align: center;
        border-bottom: 2px solid #1abc9c;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .fun {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .fun h1 {
        font-size: 20px;
        color: #2c3e50;
        border-bottom: 2px solid #1abc9c;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }

    .fun form {
        margin-bottom: 20px;
    }

    .fun input[type="text"] {
        padding: 10px;
        width: calc(100% - 120px);
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-right: 10px;
    }

    .fun .nut {
        background-color: #1abc9c;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .fun .nut:hover {
        background-color: #16a085;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        font-size: 16px;
        text-transform: uppercase;
        color: #2c3e50;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .home {
            margin-left: 0;
            padding: 15px;
        }

        table {
            font-size: 14px;
        }
    }
</style>

        <script src="../js/Mainad.js"></script>
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

        <div class="home">
            <h class="head">Bảng chức năng</h>

            <div class="fun">
                <h1>Tìm Kiếm</h1>
                <form method="GET">
                    <input type="text" name="search" placeholder="Nhập tên sản phẩm..." required>
                    <button type="submit" class="nut">Tìm kiếm</button>
                </form>
                <?php if ($searchResult): ?>
                    <table>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Loại</th>
                        </tr>
                        <?php while ($row = sqlsrv_fetch_array($searchResult, SQLSRV_FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo $row['ProductName']; ?></td>
                                <td><?php echo number_format($row['Price'], 0, ',', '.'); ?> VNĐ</td>
                                <td><?php echo $row['Category']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php endif; ?>
            </div>

            <div class="fun">
                <h1>Quản lý đơn hàng</h1>
                <form method="GET">
                    <input type="text" name="customerID" placeholder="Nhập ID khách hàng..." required>
                    <button type="submit" class="nut">Xem đơn hàng</button>
                </form>
                <?php if ($oderResult): ?>
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Ngày tạo</th>
                            <th>Số lượng</th>
                            <th>Trạng thái</th>
                        </tr>
                        <?php while ($row = sqlsrv_fetch_array($oderResult, SQLSRV_FETCH_ASSOC)): ?>
                            <tr>
                                <td>#<?php echo $row['OrderID']; ?></td>
                                <td><?php echo $row['OrderDate']->format('d - m - y'); ?></td>
                                <td><? number_format( $o['TotalAmount'], 0, ',', ',') ?> đ</td>
                                <td><?php echo $row['Status']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php endif; ?>
            </div>

            <div class="fun">
                <h1>Doanh thu</h1>
                <table>
                    <tr>
                        <th>Tháng</th>
                        <th>Doanh thu</th>
                    </tr>
                    <?php while ($row = sqlsrv_fetch_array($revenueResult, SQLSRV_FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo $row['Month']; ?></td>
                            <td><?php echo number_format($row['TotalRevenue'], 0, ',', '.'); ?> VNĐ</td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>

            <div class="fun">
                <h1>Lịch sử mua hàng</h1>
                <form method="GET">
                    <input type="text" name="history_customerID" placeholder="Nhập ID khách hàng..." required>
                    <button type="submit" class="nut">Xem lịch sử</button>
                </form>
                <?php if ('historyCustomerID' && $hisResult): ?>
                    <table>
                        <tr>
                            <th>Tên khách hàng</th>
                            <th>Ngày mua</th>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Mã đơn</th>
                        </tr>
                        <?php while ($row = sqlsrv_fetch_array($hisResult, SQLSRV_FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo $row['CustomerName']; ?></td>
                                <td><?php echo $row['PurchaseDate']->format('d - m - y'); ?></td>
                                <td><?php echo $row['ProductName']; ?></td>
                                <td><?php echo $row['Quantity']; ?></td>
                                <td><?php echo number_format($row['Price'], 0, ',', '.'); ?> VNĐ</td>
                                <td>#<?php echo $row['OrderID']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php endif; ?>
            </div>

        </div>
    </body>
</html>