        <?php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        include '../logres/noi.php';

        if (!isset($_SESSION['CustomerID'])) {
            header( 'Location: ../logres/login.php' );
            exit();
        }

        $sql = "SELECT * FROM Products ORDER BY ProductName";
        $stmt = sqlsrv_query($conn, $sql);

        ?>

        <!DOCTYPE html>
        <html lang="vi">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Trang chính</title>
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
    padding-top: 20px;
}

.sidebar h1 {
    text-align: center;
    padding: 20px 0;
    background-color: #34495e;
    margin: 0 0 20px 0;
    font-size: 24px;
    text-transform: uppercase;
}

.sidebar ul {
    list-style: none;
    padding-left: 0;
}

.sidebar ul li {
    margin: 0;
}

.sidebar ul li a {
    display: block;
    color: white;
    padding: 15px 20px;
    font-size: 16px;
    text-decoration: none;
    border-bottom: 1px solid #34495e;
    transition: background-color 0.3s ease;
}

.sidebar ul li a:hover {
    background-color: #1abc9c;
}

.content {
    margin-left: 270px;
    padding: 30px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    max-width: calc(100% - 300px);
    min-height: 100vh;
}

.header h1 {
    color: #2c3e50;
    font-size: 28px;
    margin-bottom: 20px;
    text-align: center;
    border-bottom: 2px solid #1abc9c;
    padding-bottom: 10px;
}

.danh-sach-san-pham h2 {
    text-align: center;
    color: #2c3e50;
    font-size: 24px;
    margin-bottom: 20px;
}

.grid {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.san-pham {
    border: 1px solid #ddd;
    border-radius: 10px;
    width: 250px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    background-color: #fff;
    transition: transform 0.2s;
}

.san-pham:hover {
    transform: translateY(-5px);
}

.san-pham img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 4px;
}

.san-pham h3 {
    font-size: 18px;
    margin: 10px 0 5px;
    color: #2c3e50;
}

.san-pham p {
    font-size: 14px;
    margin: 5px 0;
    color: #666;
}

.san-pham form {
    margin-top: 10px;
    display: flex;
    gap: 10px;
    align-items: center;
    justify-content: space-between;
}

.san-pham input[type="number"] {
    width: 60px;
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.san-pham button {
    background-color: #1abc9c;
    color: white;
    padding: 6px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.san-pham button:hover {
    background-color: #16a085;
}

@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .content {
        margin-left: 0;
        padding: 15px;
        max-width: 100%;
    }

    .grid {
        justify-content: flex-start;
    }
}

                </style>

            </head>
            <body>
                <div class="sidebar">
                    <h1>🏠</h1>
                    <ul>
                        <li><a href="../Hemk/Maincus.php">Trang chính</a></li>
                        <li><a href="../Hemk/cart.php">Giỏ hàng</a></li>
                        <li><a href="../Hemk/history.php">Lịch sử mua</a></li>
                        <li><a href="../logres/login.php">Đăng xuất</a></li>
                    </ul>
                </div>
                <div class="content">
                    <header class="header">
                        <h1>Chào mừng quý khách</h1>
                    </header>

                    <div class="danh-sach-san-pham">
                        <h2>Danh sách sản phẩm</h2>
                        <div class="grid">
                            <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)): ?>
                                <div class="san-pham" style="border: 1px solid #ccc; padding: 10px; margin: 10px; width: 250px;">
                                    <img src="<?= htmlspecialchars($row['ImageURL']) ?>" alt="<?= htmlspecialchars($row['ProductName']) ?>" style="width: 100%; height: auto;">
                                    <h3><?= htmlspecialchars($row['ProductName']) ?></h3>
                                    <p>Giá: <?= number_format($row['Price'], 0, ',', '.') ?> VNĐ</p>
                                    <p>Còn lại: <?= $row['StockQuantity'] ?> sản phẩm</p>
                                    <form method="post" action="add_to_cart.php">
                                        <input type="hidden" name="product_id" value="<?= $row['ProductID'] ?>">
                                        <input type="number" name="quantity" value="1" min="1" max="<?= $row['StockQuantity'] ?>" style="width: 60px;">
                                        <button type="submit">Thêm vào giỏ hàng</button>
                                    </form>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                </div>

            </body> 