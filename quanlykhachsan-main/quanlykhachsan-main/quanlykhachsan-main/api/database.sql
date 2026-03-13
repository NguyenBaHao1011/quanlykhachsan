-- Tạo cơ sở dữ liệu
CREATE DATABASE quanlykhachsan;

-- Sử dụng cơ sở dữ liệu
USE quanlykhachsan;

-- Tạo bảng khách hàng
CREATE TABLE khachhang(
    makh INT AUTO_INCREMENT PRIMARY KEY,
    tenkh VARCHAR(100),
    sdt VARCHAR(15),
    email VARCHAR(100),
    cccd VARCHAR(20)
);

-- Tạo bảng phòng
CREATE TABLE phong(
    maphong VARCHAR(10) PRIMARY KEY,
    loaiphong VARCHAR(50),
    giaphong INT,
    tinhtrang ENUM('Trống','Đã đặt')
);

-- Tạo bảng đặt phòng
CREATE TABLE datphong(
    madatphong INT AUTO_INCREMENT PRIMARY KEY,
    makh INT,
    maphong VARCHAR(10),
    ngaynhan DATE,
    ngaytra DATE,
    songay INT,
    tongtien INT,
    trangthai VARCHAR(50),

    FOREIGN KEY (makh) REFERENCES khachhang(makh),
    FOREIGN KEY (maphong) REFERENCES phong(maphong)
);

-- Chèn dữ liệu mẫu
INSERT INTO khachhang VALUES ('KH01', 'Nguyễn Văn A', '0901111111');
INSERT INTO khachhang VALUES ('KH02', 'Trần Thị B', '0902222222');
INSERT INTO khachhang VALUES ('KH03', 'Lê Văn C', '0903333333');

INSERT INTO phong VALUES ('P01', 'Phòng đơn', 300000);
INSERT INTO phong VALUES ('P02', 'Phòng đôi', 500000);
INSERT INTO phong VALUES ('P03', 'VIP', 900000);