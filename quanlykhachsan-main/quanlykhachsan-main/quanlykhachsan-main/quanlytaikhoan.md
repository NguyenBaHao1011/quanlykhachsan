* Thuộc tính
- Tên chức năng: Quản lý tài khoản
- Tác nhân: Quản trị viên
- Mô tả: Chức năng cho phép quản trị viên thao tác thêm, xóa, cập nhật và xem thông tin người dùng
- Tiền điều kiện: Quản trị viên đăng nhập vào hệ thống
- Hậu điều kiệu:
+ Thông tin người dùng được lưu(nếu thêm mới)
+ Thông tin người dùng được cập nhật
+ Thông tin người dùng được xóa khỏi hệ thống
+ Hiển thị danh sách thông tin tài khoản
- Kịch bản chính:
1. Quản trị viên chọn chức năng Quản lý tài khoản
2. Hệ thống hiển thị giao diện danh sách tài khoản
3. Quản trị viên có thể thực hiện các thao tác:
+ Thêm mới tài khoản
+ Sửa thông tin tài khoản
+ Xóa tài khoản
+ Xem danh sách tài khoản
- Kịch bản phụ:
+ Nhập thông tin không hợp lệ khi thêm/sửa -> Hệ thống báo lỗi, yêu cầu nhập lại
+ Tên đăng nhập/email đã tồn tại -> không thêm mới được.
+ Xóa tài khoản -> Hệ thống yêu cầu xác nhận trước khi xóa
![QuanLyTaiKhoan](image/quanlytaikhoan/1768546115133.png)