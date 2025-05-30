    use master;
    DROP DATABASE IF EXISTS rb;

    CREATE DATABASE rb;
    GO

    USE rb;
    GO

    CREATE TABLE Customers (
        CustomerID INT PRIMARY KEY IDENTITY,
        FullName NVARCHAR(255) NOT NULL,
        Email NVARCHAR(255) UNIQUE,
        Password NVARCHAR(255) NOT NULL,
        PhoneNumber NVARCHAR(20) UNIQUE,
        Address NVARCHAR(500) NULL,
        CreatedAt DATETIME DEFAULT GETDATE()
    );
    GO

    CREATE TABLE Products (
        ProductID INT PRIMARY KEY IDENTITY,
        ProductName NVARCHAR(255) NOT NULL,
        Category NVARCHAR(100) NOT NULL,
        Price DECIMAL(18,2) NOT NULL,
        StockQuantity INT NOT NULL,
        ImageURL NVARCHAR(255) NULL,
        Description NVARCHAR(MAX) NULL
    );
    GO

    CREATE TABLE Orders (
        OrderID INT PRIMARY KEY IDENTITY,
        CustomerID INT,
        OrderDate DATETIME DEFAULT GETDATE(),
        TotalAmount DECIMAL(18,2) NOT NULL,
        Status NVARCHAR(50) DEFAULT 'Pending',
        CONSTRAINT FK_Orders_Customers FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID) ON DELETE SET NULL
    );
    GO

    CREATE TABLE OrderDetails (
        OrderDetailID INT PRIMARY KEY IDENTITY,
        OrderID INT,
        ProductID INT,
        Quantity INT NOT NULL,
        UnitPrice DECIMAL(18,2) NOT NULL,
        CONSTRAINT FK_OrderDetails_Orders FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) ON DELETE SET NULL,
        CONSTRAINT FK_OrderDetails_Products FOREIGN KEY (ProductID) REFERENCES Products(ProductID) ON DELETE SET NULL
    );
    GO

    INSERT INTO Customers (FullName, Email, Password, PhoneNumber, Address, CreatedAt) VALUES
(N'Nguyễn Hồng Tài', 'hongtai@gmail.com', 'c67a64a7ffbe35d52630241dc1d9e7868fac051bbf656a24ebfacc21c52848a9', '0192857462', N'147 Nguyễn Bỉnh Khiêm, Đa Kao, Quận 1', GETDATE()),
(N'Lê Minh Châu', 'chau.minh@gmail.com', '79cc1628945d51a31fa48dc9c491b2cff10ab91b592ed11dee12b5fcb66a585b', '0912345678', N'23 Lê Lợi, P. Bến Nghé, Quận 1', GETDATE()),
(N'Phạm Thị Hằng', 'hangpham@gmail.com', '2bba76f12818a1688bb295b01bc3267ab04b095fb3a16d959d2ef0d99c7728ad', '0934567890', N'56 Phan Xích Long, P.2, Phú Nhuận', GETDATE()),
(N'Trần Quốc Vinh', 'vinh.tran@gmail.com', '8a37f3bb8c6c26d2e97d19a9484b9537adda0d8287af0ad3bd6620a4ec391078', '0987654321', N'178 Cách Mạng Tháng 8, Quận 10', GETDATE()),
(N'Đỗ Thị Mai', 'maido@gmail.com', '3dbe0c24e30891456bf3a70e11bf3c23c0c4c79c95fd4db7c45f905351d3462c', '0909090909', N'88 Nguyễn Trãi, Quận 5', GETDATE()),
(N'Ngô Bảo Anh', 'anh.ngo@gmail.com', '38e1cb6983563274b94a1969c93b260c2f1a17eb14db1117a3ee4a63364a9507', '0888888888', N'12 Hoàng Sa, Quận 3', GETDATE()),
(N'Bùi Thanh Sơn', 'son.bui@gmail.com', '9e39856ef1c87292f409fb49d8fe1a2e72ef3a8aedab550e2fef05d62c9d6cdc', '0977777777', N'9A Trường Sa, Quận Bình Thạnh', GETDATE()),
(N'Vũ Thị Kim', 'kimvu@gmail.com', '993676af029d586c97602452480abc659ff7e98ae48309ccdae01467f10ea7b7', '0922222222', N'103 Võ Văn Tần, Quận 3', GETDATE()),
(N'Lý Hữu Phúc', 'phucly@gmail.com', '79f44d5a81d5a6ba612c56435ff580be56c0bc4bd87a4f6b023582eddd7abd41', '0966666666', N'15 Nguyễn Văn Cừ, Quận 5', GETDATE()),
(N'Tạ Quang Huy', 'huyta@gmail.com', '22f3da83634f3308c09aec58de5ff52f9abbb6bdee2b928b58a2eeee852d05b6', '0944444444', N'20 Nguyễn Thị Minh Khai, Quận 1', GETDATE()),
(N'Trịnh Gia Huy', 'gia.huy@gmail.com', '0ff2d35f9405eb2a2eec47702c6be383a7d24dce64059d20e9b8c095c7c8d39a', '0933001100', N'45 Nguyễn Thị Diệu, Quận 3', GETDATE()),
(N'Phan Nhật Linh', 'linhphan@gmail.com', '22cc05caf76b9cb716b459a17a7bd32b62a042ba1fae22b22b6cdf966c8c61b1', '0908123456', N'66 Đinh Tiên Hoàng, Quận 1', GETDATE()),
(N'Nguyễn Mỹ Duyên', 'duyen.my@gmail.com', '998536720f615986006c951092cc791411de23364271826f650df4a7375916a6', '0912333444', N'21 Nguyễn Văn Thủ, Quận 1', GETDATE()),
(N'Đặng Văn Cường', 'cuong.dang@gmail.com', 'a8887cd57d0f46e3c1393fab24bcef5098fc7efd3bd3edd0c6c000bff9a0471b', '0978123123', N'102 Bùi Viện, Quận 1', GETDATE()),
(N'Tống Thị Yến', 'yen.tong@gmail.com', 'bf84b4267de65af26e5a8ebcc34c0dd20f1267d1fde0ba14bb9c1c4f5638e4b9', '0944455566', N'72 Nguyễn Khoái, Quận 4', GETDATE()),
(N'Hoàng Ngọc Bích', 'bichhoang@gmail.com', '23e078260c2d8f46b7bbab6fa060fe471b25240228bd9e2859839136309603a0', '0922233445', N'9 Pasteur, Quận 1', GETDATE()),
(N'Võ Minh Tâm', 'tam.vo@gmail.com', '774ea7d16ccc9ff92cebf3fc528a72bc3e5689aa25dd81b0e4decd65f59b55f5', '0988111222', N'18A Nguyễn Huệ, Quận 1', GETDATE()),
(N'Lê Phước Long', 'long.le@gmail.com', 'fc55b2b99b2fb7d07608584ced908c367a98d3df64930287ae19a32242b4927a', '0966778899', N'33 Nguyễn Thái Học, Quận 1', GETDATE()),
(N'Bành Thanh Thảo', 'thao.banh@gmail.com', 'f813e270b526d6e08995545482e64e7aa8291ef6c0458ad479b12fad0b99a35a', '0911445566', N'19 Lý Tự Trọng, Quận 1', GETDATE()),
(N'Từ Quốc Dũng', 'dungquoc@gmail.com', 'ebf36e97738672826fee66dc24084221f6af0f27f980687d4262ddceada813ab', '0933667788', N'27 Nguyễn Đình Chiểu, Quận 3', GETDATE()),
(N'Ngô Thị Phượng', 'phuong.ngo@gmail.com', 'ee3910d6e126b2441368f8f602257ed2debef1f90825bb8d3bed68df8c00a3bd', '0909456123', N'88 Võ Thị Sáu, Quận 3', GETDATE()),
(N'Châu Minh Tuấn', 'tuan.chau@gmail.com', '601422fba074b521d683f821814c3996f6107591d961f7cd612f7157b43aa3a8', '0977889900', N'45 Điện Biên Phủ, Quận Bình Thạnh', GETDATE()),
(N'Mai Thanh Lan', 'lan.mai@gmail.com', 'b5abfc1711b87fba0fc7fc35c929dea60753c474d2843b499986e86190abfe61', '0955332211', N'60 Nguyễn Thái Bình, Quận 1', GETDATE()),
(N'Trần Văn An', 'an.tran@gmail.com', 'd3e525dc1ccc3fb70c496d615fd2191f4aa4c61e6ba909c70f9b6090336e4fd4', '0988111333', N'12B Nguyễn Trãi, Quận 5', GETDATE()),
(N'Đinh Quang Hải', 'hai.dinh@gmail.com', '8c4dc7ba8ae27651ddc9360984c7aae1ee8c2e09529b851c23e1815ff26afc1d', '0909988776', N'77 Trần Hưng Đạo, Quận 1', GETDATE());

    
    INSERT INTO Products (ProductName, Category, Price, StockQuantity, ImageURL, Description) VALUES
(N'Áo sơ mi Uniqlo Smart Fit', N'Áo', 891122.5, 93, 'https://tse1.mm.bing.net/th?id=OIP.gDluGrKQ2gauU_ZQqR3AugHaJA&pid=Api&P=0&h=180', N'Sản phẩm Áo sơ mi Uniqlo Smart Fit thuộc danh mục Áo, thiết kế thanh lịch phù hợp môi trường công sở.'),
(N'Quần jean Levi''s 511 Slim Fit', N'Quần', 296505.08, 91, 'https://do2padres.com/cdn/shop/products/04-1622749646-617c5e769c191-wp_800x.webp?v=1675097437', N'Sản phẩm Quần jean Levi''s 511 Slim Fit thuộc danh mục Quần, mang phong cách trẻ trung hiện đại.'),
(N'Giày sneaker Nike Air Force 1', N'Giày', 440452.81, 84, 'https://tse2.mm.bing.net/th?id=OIP.tHmajzY_NRJF6j4NUyUC8wHaHa&pid=Api&P=0&h=180', N'Sản phẩm Giày sneaker Nike Air Force 1 thuộc danh mục Giày, phong cách thể thao cá tính.'),
(N'Túi xách Charles & Keith Classic', N'Túi xách', 744300.43, 31, 'https://www.charleskeith.vn/dw/image/v2/BCWJ_PRD/on/demandware.static/-/Sites-vn-products/default/dw4bb094f0/images/hi-res/2020-L2-CK2-50701030-01-1.jpg?sw=756&sh=1008', N'Sản phẩm Túi xách Charles & Keith Classic thuộc danh mục Túi xách, phù hợp cho cả đi làm và dạo phố.'),
(N'Mắt kính Ray-Ban Aviator', N'Mắt kính', 282356.62, 22, 'https://vn-test-11.slatic.net/p/6d2c4c672722abb24ce93e036033f002.jpg', N'Sản phẩm Mắt kính Ray-Ban Aviator thuộc danh mục Mắt kính, bảo vệ mắt và thời trang.'),
(N'Đồng hồ Casio G-Shock GA-2100', N'Đồng hồ', 674922.39, 62, 'https://tse1.mm.bing.net/th?id=OIP.TpG6ez0nfpJDvCAf8jn1PwHaHa&pid=Api', N'Sản phẩm Đồng hồ Casio G-Shock GA-2100 thuộc danh mục Đồng hồ, bền bỉ và phong cách.'),
(N'Áo khoác Zara Bomber', N'Áo', 615922.13, 74, 'https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lltdzizayaxr28', N'Sản phẩm Áo khoác Zara Bomber thuộc danh mục Áo, phong cách hiện đại năng động.'),
(N'Quần short H&M Denim', N'Quần', 961344.18, 50, 'https://tse2.mm.bing.net/th?id=OIP.zwJvA6kmHvmGV8bh7tQOpwHaLH&pid=Api&P=0&h=180', N'Sản phẩm Quần short H&M Denim thuộc danh mục Quần, thoải mái cho mùa hè.'),
(N'Mũ bucket MLB Korea', N'Phụ kiện', 337895.16, 47, 'https://lykorea.com/wp-content/uploads/2022/08/303776707_789200795682756_7191282257454697907_n.jpg', N'Sản phẩm Mũ bucket MLB Korea thuộc danh mục Phụ kiện, hợp thời trang đường phố.'),
(N'Áo thun Converse Classic Logo', N'Áo', 437124.6, 87, 'https://tse1.mm.bing.net/th?id=OIP.CGk7kJ9ghg6jk_ulxxq--gHaHa&pid=Api', N'Sản phẩm Áo thun Converse Classic Logo thuộc danh mục Áo, thiết kế basic phù hợp mọi phong cách.'),
(N'Giày boot Dr. Martens 1460', N'Giày', 302401.95, 56, 'https://product.hstatic.net/1000382882/product/giay-da-cao-cap-arrowshopvn__78__950d7a947d6d4d58bf605ca2c6a7a511_1024x1024.png', N'Sản phẩm Giày boot Dr. Martens 1460 thuộc danh mục Giày, chất da thật cổ điển.'),
(N'Túi đeo chéo Adidas Originals', N'Túi xách', 271911.7, 51, 'https://bizweb.dktcdn.net/100/427/145/products/tui-adidas-originals-for-all-waist-pack-ir1052-4.jpg?v=1710389938547', N'Sản phẩm Túi đeo chéo Adidas Originals thuộc danh mục Túi xách, tiện lợi và thời trang.'),
(N'Đồng hồ Daniel Wellington Iconic Link', N'Đồng hồ', 987058.66, 19, 'https://donghoxiteen.com/wp-content/uploads/2019/11/DW00100210-1.jpg', N'Sản phẩm Đồng hồ Daniel Wellington Iconic Link thuộc danh mục Đồng hồ, phong cách thanh lịch.'),
(N'Áo hoodie Champion Reverse Weave', N'Áo', 1012931.02, 94, 'https://tse4.mm.bing.net/th?id=OIP.LCAQMDM0Wo1egyPFVCM1LgHaHa&pid=Api&P=0&h=180', N'Sản phẩm Áo hoodie Champion Reverse Weave thuộc danh mục Áo, phù hợp phong cách streetwear.'),
(N'Giày lười Vans Slip-On Checkerboard', N'Giày', 749371.29, 45, 'https://tse3.mm.bing.net/th?id=OIP.mw0nlcj3oAo8XOcqDPc1WQHaHa&pid=Api&P=0&h=180', N'Sản phẩm Giày lười Vans Slip-On Checkerboard thuộc danh mục Giày, phong cách đường phố năng động.'),
(N'Quần tây Owen Slim Fit', N'Quần', 940861.91, 61, 'https://tse1.mm.bing.net/th?id=OIP.v4_IJswNfwM-4ZePE_8r7wHaHa&pid=Api', N'Sản phẩm Quần tây Owen Slim Fit thuộc danh mục Quần, thiết kế thanh lịch công sở.'),
(N'Balo Fjallraven Kanken Classic', N'Túi xách', 1042077.41, 91, 'https://travelgear.vn/image/cache/catalog/hanh-ly/balo-du-lich/balo-kanken-fjallraven/balo-fjallraven-kanken-classic-0642-2-1000x1000.jpg', N'Sản phẩm Balo Fjallraven Kanken Classic thuộc danh mục Túi xách, tiện dụng và nổi bật.'),
(N'Mắt kính Gentle Monster Her 01', N'Mắt kính', 390727.85, 85, 'https://authentic-shoes.com/wp-content/uploads/2023/04/kinh-mat-gentle-monster-dong-uni_3e4e239168974b12a091f4b8952f93a2.png', N'Sản phẩm Mắt kính Gentle Monster Her 01 thuộc danh mục Mắt kính, phong cách cá tính hiện đại.'),
(N'Tất cổ cao Nike Everyday', N'Phụ kiện', 742665.75, 95, 'https://down-vn.img.susercontent.com/file/vn-11134201-23030-89sq3b3v27nv86', N'Sản phẩm Tất cổ cao Nike Everyday thuộc danh mục Phụ kiện, phù hợp tập luyện thể thao.'),
(N'Áo blazer Mango Women Tailored', N'Áo', 477097.67, 57, 'https://tse1.mm.bing.net/th?id=OIP.SPX6F5Bs9pM_1SSdS_WG9wHaLI&pid=Api', N'Sản phẩm Áo blazer Mango Women Tailored thuộc danh mục Áo, phong cách công sở nữ tính.'),
(N'Giày thể thao Puma RS-X', N'Giày', 684423.18, 65, 'https://tse3.mm.bing.net/th?id=OIP.f2aMwAogVr_DX_KxJa38zAHaHa&pid=Api&P=0&h=180', N'Sản phẩm Giày thể thao Puma RS-X thuộc danh mục Giày, thiết kế nổi bật, đệm êm.'),
(N'Áo polo Lacoste Classic Fit', N'Áo', 1135923.11, 32, 'https://cdn.vuahanghieu.com/unsafe/0x900/left/top/smart/filters:quality(90)/https://admin.vuahanghieu.com/upload/product/2023/03/ao-polo-lacoste-classic-fit-l1212-51-z3t-mau-xanh-co-vit-size-s-64000cfe85b84-02032023094206.jpg', N'Sản phẩm Áo polo Lacoste Classic Fit thuộc danh mục Áo, biểu tượng phong cách thời trang nam.'),
(N'Áo khoác gió The North Face', N'Áo', 428532.97, 58, 'https://tse4.mm.bing.net/th?id=OIP.OF2KduyjrLLDJRfMSZYjYQHaHa&pid=Api&P=0&h=180', N'Sản phẩm Áo khoác gió The North Face thuộc danh mục Áo, thích hợp thời tiết lạnh.');


    INSERT INTO Orders (CustomerID, OrderDate, TotalAmount, Status) VALUES
    (5, '2025-04-27 15:25:23', 1489713.95, N'Pending'),
    (3, '2025-05-14 15:25:23', 1676013.39, N'Cancelled'),
    (5, '2025-05-03 15:25:23', 1038144.43, N'Pending'),
    (17, '2025-05-09 15:25:23', 1334239.82, N'Cancelled'),
    (7, '2025-05-16 15:25:23', 800362.85, N'Completed'),
    (20, '2025-04-28 15:25:23', 2765672.67, N'Cancelled'),
    (4, '2025-05-05 15:25:23', 2928463.94, N'Pending'),
    (15, '2025-05-11 15:25:23', 2716584.38, N'Pending'),
    (12, '2025-04-28 15:25:23', 2715711.81, N'Pending'),
    (22, '2025-05-07 15:25:23', 1348257.32, N'Pending'),
    (12, '2025-04-17 15:25:23', 944872.41, N'Completed'),
    (14, '2025-04-29 15:25:23', 1910888.72, N'Cancelled'),
    (2, '2025-04-20 15:25:23', 1734213.92, N'Cancelled'),
    (7, '2025-05-07 15:25:23', 1324633.76, N'Pending'),
    (5, '2025-04-30 15:25:23', 1280870.24, N'Completed'),
    (21, '2025-05-13 15:25:23', 1615505.17, N'Cancelled'),
    (3, '2025-05-06 15:25:23', 2258852.16, N'Completed'),
    (10, '2025-05-07 15:25:23', 918633.28, N'Pending'),
    (9, '2025-05-03 15:25:23', 2644519.11, N'Pending'),
    (2, '2025-04-24 15:25:23', 1178438.35, N'Completed'),
    (14, '2025-04-27 15:25:23', 2213948.71, N'Completed'),
    (11, '2025-05-08 15:25:23', 779143.14, N'Cancelled'),
    (21, '2025-04-17 15:25:23', 2894551.26, N'Pending'),
    (6, '2025-05-02 15:25:23', 1058962.85, N'Pending'),
    (9, '2025-04-28 15:25:23', 2894217.32, N'Completed'),
    (5, '2025-05-03 15:25:23', 2560066.11, N'Cancelled'),
    (22, '2025-04-30 15:25:23', 2573497.75, N'Cancelled'),
    (2, '2025-05-07 15:25:23', 2478158.41, N'Pending'),
    (23, '2025-04-26 15:25:23', 398904.28, N'Cancelled'),
    (24, '2025-05-14 15:25:23', 2724395.53, N'Pending');

    INSERT INTO OrderDetails (OrderID, ProductID, Quantity, UnitPrice) VALUES
    (9, 5, 5, 921019.52),
    (4, 17, 2, 839903.35),
    (21, 24, 3, 432122.76),
    (13, 1, 3, 265753.82),
    (22, 7, 1, 620019.27),
    (26, 11, 4, 532149.02),
    (24, 3, 1, 1141164.14),
    (11, 21, 1, 531244.47),
    (23, 18, 5, 336218.33),
    (16, 11, 1, 816078.97),
    (29, 13, 2, 674134.16),
    (18, 5, 3, 808503.48),
    (24, 17, 3, 549259.74),
    (9, 19, 2, 773695.02),
    (4, 16, 2, 504503.63),
    (2, 4, 2, 411948.23),
    (23, 16, 1, 195947.84),
    (16, 12, 3, 523173.3),
    (19, 10, 3, 407439.29),
    (15, 21, 2, 846659.38),
    (16, 19, 4, 163298.89),
    (14, 12, 2, 463035.24),
    (22, 16, 2, 239208.12),
    (18, 21, 3, 304305.24),
    (20, 20, 4, 927751.99),
    (14, 4, 3, 1183148.65),
    (20, 24, 3, 513761.46),
    (18, 10, 2, 912127.65);
    Go

    -- //////////////////////////////////PROCEDURE///////////////////////////////////////////

    -- Thêm khách hàng m?i
    CREATE PROCEDURE sp_AddCustomer
        @FullName NVARCHAR(255),
        @Email NVARCHAR(255),
        @Password NVARCHAR(255),
        @PhoneNumber NVARCHAR(20),
        @Address NVARCHAR(500)
    AS
    BEGIN
        INSERT INTO Customers (FullName, Email, Password, PhoneNumber, Address)
        VALUES (@FullName, @Email, @Password, @PhoneNumber, @Address);
    END
    GO

    -- Thêm ??n hàng m?i
    CREATE PROCEDURE sp_AddOrder
        @CustomerID INT,
        @TotalAmount DECIMAL(18,2),
        @Status NVARCHAR(50)
    AS
    BEGIN
        INSERT INTO Orders (CustomerID, TotalAmount, Status)
        VALUES (@CustomerID, @TotalAmount, @Status);
    END
    GO

    -- C?p nh?t tr?ng thái ??n hàng
    CREATE PROCEDURE sp_UpdateOrderStatus
        @OrderID INT,
        @NewStatus NVARCHAR(50)
    AS
    BEGIN
        UPDATE Orders
        SET Status = @NewStatus
        WHERE OrderID = @OrderID;
    END
    GO

    -- Thêm s?n ph?m vào ??n hàng
    CREATE PROCEDURE sp_AddProductToOrder
        @OrderID INT,
        @ProductID INT,
        @Quantity INT,
        @UnitPrice DECIMAL(18,2)
    AS
    BEGIN
        INSERT INTO OrderDetails (OrderID, ProductID, Quantity, UnitPrice)
        VALUES (@OrderID, @ProductID, @Quantity, @UnitPrice);
    END
    GO

    -- C?p nh?t s? l??ng s?n ph?m trong ??n hàng
    CREATE PROCEDURE sp_UpdateOrderDetailQuantity
        @OrderDetailID INT,
        @NewQuantity INT
    AS
    BEGIN
        UPDATE OrderDetails
        SET Quantity = @NewQuantity
        WHERE OrderDetailID = @OrderDetailID;
    END
    GO

    -- Xóa s?n ph?m kh?i ??n hàng
    CREATE PROCEDURE sp_RemoveProductFromOrder
        @OrderDetailID INT
    AS
    BEGIN
        DELETE FROM OrderDetails
        WHERE OrderDetailID = @OrderDetailID;
    END
    GO

    -- L?y danh sách ??n hàng c?a m?t khách hàng
    CREATE PROCEDURE sp_GetOrdersByCustomer
        @CustomerID INT
    AS
    BEGIN
        SELECT * FROM Orders
        WHERE CustomerID = @CustomerID
        ORDER BY OrderDate DESC;
    END
    GO

    -- Tìm ki?m s?n ph?m theo tên
    CREATE PROCEDURE sp_SearchProductsByName
        @Keyword NVARCHAR(255)
    AS
    BEGIN
        SELECT * FROM Products
        WHERE ProductName LIKE '%' + @Keyword + '%';
    END
    GO

    -- //////////////////////////////////TRIGGER///////////////////////////////////////////

    -- Gi?m s? l??ng hàng t?n khi thêm s?n ph?m
    CREATE TRIGGER trg_DecreaseStock_AfterInsertOrderDetails
    ON OrderDetails
    AFTER INSERT
    AS
    BEGIN
        UPDATE Products
        SET StockQuantity = StockQuantity - i.Quantity
        FROM Products p
        JOIN inserted i ON p.ProductID = i.ProductID;
    END
    GO

    -- T?ng l?i s? l??ng t?n kho khi ??n hàng b? h?y 
    CREATE TRIGGER trg_Restock_OnOrderCancelled
    ON Orders
    AFTER UPDATE
    AS
    BEGIN
        IF EXISTS (
            SELECT * FROM inserted i
            JOIN deleted d ON i.OrderID = d.OrderID
            WHERE i.Status = 'Cancelled' AND d.Status <> 'Cancelled'
        )
        BEGIN
            UPDATE Products
            SET StockQuantity = StockQuantity + od.Quantity
            FROM Products p
            JOIN OrderDetails od ON p.ProductID = od.ProductID
            JOIN inserted i ON od.OrderID = i.OrderID
            JOIN deleted d ON i.OrderID = d.OrderID
            WHERE i.Status = 'Cancelled' AND d.Status <> 'Cancelled';
        END
    END
    GO

    -- Ki?m tra hàng t?n kho tr??c khi thêm s?n ph?m vào
    CREATE TRIGGER trg_CheckStock_BeforeInsertOrderDetails
    ON OrderDetails
    INSTEAD OF INSERT
    AS
    BEGIN
        IF EXISTS (
            SELECT 1
            FROM inserted i
            JOIN Products p ON i.ProductID = p.ProductID
            WHERE i.Quantity > p.StockQuantity
        )
        BEGIN
            RAISERROR('Không ?? hàng trong kho ?? th?c hi?n ??n hàng.', 16, 1);
            ROLLBACK TRANSACTION;
        END
        ELSE
        BEGIN
            INSERT INTO OrderDetails (OrderID, ProductID, Quantity, UnitPrice)
            SELECT OrderID, ProductID, Quantity, UnitPrice
            FROM inserted;
        END
    END
    GO

    -- Th?ng kê doanh thu theo tháng
    CREATE VIEW vw_RevenueByMonth AS
    SELECT
        FORMAT(OrderDate, 'yyyy-MM') AS YearMonth,
        SUM(TotalAmount) AS TotalRevenue,
        COUNT(*) AS TotalOrders
    FROM Orders
    WHERE Status = 'Completed'
    GROUP BY FORMAT(OrderDate, 'yyyy-MM');
    GO

    -- Danh sách s?n ph?m còn hàng
    CREATE VIEW vw_ProductsInStock AS
    SELECT *
    FROM Products
    WHERE StockQuantity > 0;
    GO

    -- l?ch s? mua hàng c?a khách hàng
    CREATE VIEW vw_CustomerPurchaseHistory AS
    SELECT
        c.CustomerID,
        c.FullName,
        o.OrderID,
        o.OrderDate,
        o.Status,
        od.ProductID,
        p.ProductName,
        od.Quantity,
        od.UnitPrice,
        od.Quantity * od.UnitPrice AS Total
    FROM Customers c
    JOIN Orders o ON c.CustomerID = o.CustomerID
    JOIN OrderDetails od ON o.OrderID = od.OrderID
    JOIN Products p ON p.ProductID = od.ProductID;
    GO
