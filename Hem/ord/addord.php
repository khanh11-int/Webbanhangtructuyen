<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../logres/noi.php';

$customerID = sqlsrv_query($conn,'SELECT CustomerID, Fullname FROM Cutomers');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customerID = $_POST['CustomerID'];
    $total = $_POST['total'];
    $status = $_POST['status'];
    
    $sql = "{Call sp_AddOrder(?, ?, ?)}";
    $params = array($customerID, $total, $status);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    header('Location: ordman.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Thêm đơn hàng</title>
        <style>
            /* Tổng quan và Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    /* Font chữ hiện đại, dễ đọc, sử dụng Google Fonts nếu có thể */
    font-family: 'Roboto', 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
}

body {
    background-color: #f0f2f5; /* Nền xám xanh nhẹ, hiện đại hơn */
    display: flex; /* Sử dụng flexbox để bố cục sidebar và nội dung */
    min-height: 100vh; /* Đảm bảo body chiếm toàn bộ chiều cao màn hình */
}

/* Sidebar - Thanh điều hướng bên trái */
.sidebar {
    width: 250px; /* Chiều rộng sidebar */
    background-color: #2c3e50; /* Màu xanh đậm hơn, chuyên nghiệp hơn */
    color: #ecf0f1; /* Màu chữ trắng ngà */
    padding: 20px 0;
    position: fixed; /* Giữ sidebar cố định khi cuộn */
    height: 100%; /* Chiếm toàn bộ chiều cao */
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1); /* Bóng đổ nhẹ */
    display: flex;
    flex-direction: column;
    z-index: 1000; /* Đảm bảo sidebar nằm trên các phần tử khác */
}

.sidebar h1 {
    text-align: center;
    padding: 20px 0;
    font-size: 28px; /* Kích thước tiêu đề lớn hơn */
    margin-bottom: 20px; /* Khoảng cách dưới tiêu đề */
    color: #f1c40f; /* Màu vàng nổi bật cho tiêu đề admin */
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase; /* Chữ hoa */
}

.sidebar a {
    display: block;
    color: #ecf0f1;
    padding: 15px 25px; /* Tăng padding để dễ click hơn */
    text-decoration: none;
    font-size: 16px; /* Kích thước font menu */
    transition: background-color 0.3s ease, color 0.3s ease, border-left-color 0.3s ease; /* Hiệu ứng mượt mà */
    border-left: 5px solid transparent; /* Viền trái ẩn */
}

.sidebar a:hover, .sidebar a.active { /* Thêm class .active cho link hiện tại */
    background-color: #34495e; /* Màu nền đậm hơn khi hover/active */
    color: #ffffff; /* Chữ trắng tinh khi hover/active */
    border-left-color: #f1c40f; /* Hiện viền vàng khi hover/active */
}

/* Nội dung chính */
.container {
    margin-left: 250px; /* Đẩy nội dung chính sang phải bằng chiều rộng sidebar */
    flex-grow: 1; /* Cho phép nội dung chính chiếm hết không gian còn lại */
    padding: 30px; /* Tăng padding tổng thể */
    background-color: #f0f2f5; /* Nền đồng bộ với body */
    display: flex; /* Sử dụng flexbox để căn giữa form */
    justify-content: center; /* Căn giữa ngang form */
    align-items: flex-start; /* Căn trên form (hoặc center nếu muốn form ở giữa dọc) */
}

/* Form */
form {
    max-width: 600px; /* Giới hạn chiều rộng của form */
    width: 100%; /* Đảm bảo form chiếm toàn bộ chiều rộng có thể */
    background-color: #ffffff; /* Nền trắng cho form */
    padding: 40px; /* Tăng padding cho form */
    border-radius: 12px; /* Bo góc mềm mại hơn */
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12); /* Bóng đổ nổi bật và mềm mại hơn */
    display: flex;
    flex-direction: column; /* Sắp xếp các phần tử theo cột */
    gap: 15px; /* Khoảng cách giữa các phần tử trong form */
}

form h2 {
    text-align: center;
    font-size: 28px; /* Kích thước tiêu đề form lớn hơn */
    margin-bottom: 30px; /* Khoảng cách dưới tiêu đề */
    color: #34495e; /* Màu chữ đậm */
    font-weight: 600;
}

label {
    display: block;
    margin-bottom: 5px; /* Khoảng cách nhỏ giữa label và input */
    font-weight: 500; /* Đậm vừa cho label */
    color: #333;
    font-size: 15px;
}

input[type="number"],
select {
    width: 100%; /* Chiếm toàn bộ chiều rộng của form */
    padding: 12px 15px; /* Tăng padding input/select */
    margin-bottom: 15px; /* Khoảng cách dưới input/select */
    border-radius: 8px; /* Bo góc input/select mềm mại */
    border: 1px solid #dcdcdc; /* Viền nhẹ nhàng */
    font-size: 16px;
    color: #333;
    transition: border-color 0.3s ease, box-shadow 0.3s ease; /* Hiệu ứng chuyển động */
    -webkit-appearance: none; /* Loại bỏ style mặc định của trình duyệt cho select */
    -moz-appearance: none;
    appearance: none;
    background-color: #fff; /* Đảm bảo nền trắng */
    background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23000000%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-24.5%200l-118.2%20118.2-118.2-118.2a17.6%2017.6%200%200%200-24.5%2024.5l130.6%20130.6c7.7%207.7%2017.9%2011.6%2028.1%2011.6s20.4-3.9%2028.1-11.6L287%2093.9c17.6-17.5%2017.6-46.1%200-63.7z%22%2F%3E%3C%2Fsvg%3E'); /* Icon mũi tên cho select */
    background-repeat: no-repeat;
    background-position: right 15px top 50%, center;
    background-size: 12px auto;
    padding-right: 35px; /* Đảm bảo khoảng trống cho icon */
}

input[type="number"]:focus,
select:focus {
    border-color: #3498db; /* Màu viền xanh khi focus */
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2); /* Bóng đổ nhẹ khi focus */
    outline: none;
}

button[type="submit"] {
    width: 100%;
    padding: 14px 25px; /* Tăng padding button */
    background-color: #28a745; /* Màu xanh lá cây cho nút thêm (Create/Add) */
    color: white;
    border: none;
    border-radius: 8px; /* Bo góc button */
    cursor: pointer;
    font-size: 17px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #218838; /* Màu xanh đậm hơn khi hover */
    transform: translateY(-2px); /* Nâng nhẹ button lên */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1); /* Thêm bóng đổ khi hover */
}

button[type="submit"]:active {
    transform: translateY(0);
    box-shadow: none;
}

/* Responsive Design */
@media (max-width: 992px) {
    .sidebar {
        width: 200px;
    }
    .container {
        margin-left: 200px;
        padding: 25px;
    }
    form {
        padding: 30px;
    }
    form h2 {
        font-size: 24px;
        margin-bottom: 25px;
    }
    input[type="number"], select, button[type="submit"] {
        padding: 10px 12px;
        font-size: 15px;
    }
}

@media (max-width: 768px) {
    body {
        flex-direction: column;
    }
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        flex-direction: row;
        justify-content: space-around;
        padding: 10px 0;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .sidebar h1 {
        display: none;
    }
    .sidebar a {
        padding: 10px 15px;
        border-left: none;
        border-bottom: 3px solid transparent;
        flex-shrink: 0;
    }
    .sidebar a:hover, .sidebar a.active {
        border-left-color: transparent;
        border-bottom-color: #f1c40f;
    }
    .container {
        margin-left: 0;
        padding: 20px;
        align-items: center; /* Căn giữa form trên màn hình nhỏ */
    }
    form {
        max-width: 100%; /* Chiếm toàn bộ chiều rộng khả dụng */
        padding: 25px;
        gap: 10px;
    }
    form h2 {
        font-size: 22px;
        margin-bottom: 20px;
    }
    input[type="number"], select, button[type="submit"] {
        padding: 12px;
        font-size: 16px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 15px;
    }
    form {
        padding: 20px;
    }
    form h2 {
        font-size: 20px;
        margin-bottom: 15px;
    }
    input[type="number"], select, button[type="submit"] {
        padding: 10px;
        font-size: 15px;
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

        <div class="container">
            <form method="POST">
                <h2>Xem đơn hàng của khách</h2>
                <label>Khách hàng:</label>
                <select name="CustomerID" required>
                    <option value="">Chọn khách hàng</option>
                    <?php while ($row = sqlsrv_fetch_array($customerID, SQLSRV_FETCH_ASSOC)): ?>
                        <option value="<?=$customerID['CustomerID']?>">
                            <?= $customerID['Fullname'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label>Tổng tiền:</label>
                <input type="number" name="total" required>

                <label>Trạng thái:</label>
                <select name="status" required>
                    <option value="Pending">Đang chờ</option>
                    <option value="Processing">Đang xử lý</option>
                    <option value="Completed">Đã hoàn thành</option>
                    <option value="Cancelled">Đã hủy</option>
                </select>

                <button type="submit">Thêm đơn hàng</button>
            </form>
        </div>
    </body>
</html>
