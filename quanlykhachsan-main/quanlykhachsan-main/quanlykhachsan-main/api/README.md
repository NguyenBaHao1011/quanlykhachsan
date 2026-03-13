# API cho Quản Lý Khách Sạn

## Cài đặt

1. Cài đặt XAMPP từ https://www.apachefriends.org/
2. Khởi động Apache và MySQL trong XAMPP Control Panel.
3. Mở PHPMyAdmin (http://localhost/phpmyadmin)
4. Tạo database mới tên `quanlykhachsan`
5. Import file `database.sql` vào database.

## Sử dụng

- `config.php`: Kết nối database (cập nhật username/password nếu cần)
- `api.php`: API cho đặt phòng (POST để thêm, GET để lấy danh sách)
- `khachhang.php`: Lấy danh sách khách hàng
- `phong.php`: Lấy danh sách phòng

## Chạy ứng dụng

Đặt thư mục `git-github-flow` vào `htdocs` của XAMPP (thường là C:\xampp\htdocs\)

Truy cập: http://localhost/git-github-flow/DatPhong/DatPhong.html

## Sử dụng cho các trang khác

Trong JS của các trang khác, fetch từ '../api/...' tương tự.