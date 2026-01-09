## I. Chức năng dành cho User (Khách hàng)

### 1. Đăng ký tài khoản

Cho phép người dùng tạo tài khoản để sử dụng các chức năng của hệ thống.

**Mô tả chi tiết:**

* Nhập thông tin cá nhân: họ tên, email/số điện thoại, mật khẩu
* Kiểm tra trùng tài khoản
* Lưu thông tin người dùng vào hệ thống
* Thông báo đăng ký thành công hoặc thất bại


### 2. Đăng nhập

Cho phép người dùng truy cập hệ thống bằng tài khoản đã đăng ký.

**Mô tả chi tiết:**

* Nhập tên đăng nhập và mật khẩu
* Xác thực thông tin người dùng
* Phân quyền truy cập theo vai trò User

### 3. Xem thông tin khách sạn và phòng

Cho phép người dùng tìm hiểu thông tin trước khi đặt phòng.

**Mô tả chi tiết:**

* Xem thông tin chung về khách sạn
* Xem danh sách các loại phòng
* Xem giá phòng, tiện nghi, hình ảnh phòng

### 4. Xem tình trạng phòng trống

Cho phép người dùng kiểm tra phòng còn trống theo thời gian mong muốn.

**Mô tả chi tiết:**

* Nhập ngày nhận phòng và ngày trả phòng
* Hiển thị danh sách phòng còn trống
* Không hiển thị phòng đã được đặt

### 5. Đặt phòng

Cho phép người dùng đặt phòng trực tuyến.

**Mô tả chi tiết:**

* Chọn phòng trống
* Nhập thông tin lưu trú
* Gửi yêu cầu đặt phòng
* Nhận thông báo trạng thái đặt phòng (chờ xác nhận)

### 6. Xem lại thông tin đặt phòng

Cho phép người dùng theo dõi các đơn đặt phòng của mình.

**Mô tả chi tiết:**

* Xem danh sách các đặt phòng đã thực hiện
* Xem trạng thái: chờ xác nhận, đã xác nhận, đã hủy
* Xem chi tiết thông tin đặt phòng

### 7. Cập nhật thông tin cá nhân

Cho phép người dùng chỉnh sửa thông tin cá nhân.

**Mô tả chi tiết:**

* Cập nhật họ tên, số điện thoại, mật khẩu
* Lưu thông tin mới vào hệ thống

## II. Chức năng dành cho Admin (Quản trị viên / Nhân viên)

### 1. Đăng nhập

Cho phép Admin truy cập vào hệ thống quản trị.

**Mô tả chi tiết:**

* Xác thực tài khoản Admin
* Phân quyền truy cập các chức năng quản lý

### 2. CRUD Phòng

Cho phép Admin quản lý thông tin phòng.

**Mô tả chi tiết:**

* Thêm phòng mới
* Cập nhật thông tin phòng
* Xóa phòng
* Xem danh sách phòng


### 3. CRUD Khách hàng

Cho phép Admin quản lý thông tin khách hàng.

**Mô tả chi tiết:**

* Thêm thông tin khách hàng
* Cập nhật thông tin khách
* Xóa khách hàng
* Tra cứu thông tin khách


### 4. Quản lý đặt phòng

Cho phép Admin xử lý các yêu cầu đặt phòng.

**Mô tả chi tiết:**

* Xác nhận đặt phòng
* Chỉnh sửa thông tin đặt phòng
* Hủy đặt phòng
* Cập nhật trạng thái đặt phòng


### 5. Check-in / Check-out

Cho phép Admin thực hiện quy trình nhận và trả phòng.

**Mô tả chi tiết:**

* Check-in cho khách đến
* Cập nhật trạng thái phòng đang sử dụng
* Check-out khi khách trả phòng
* Cập nhật trạng thái phòng trống


### 6. Quản lý dịch vụ

Cho phép Admin quản lý các dịch vụ phát sinh.

**Mô tả chi tiết:**

* Thêm dịch vụ cho khách
* Cập nhật số lượng, chi phí dịch vụ
* Xóa dịch vụ không sử dụng


### 7. Quản lý thanh toán và hóa đơn

Cho phép Admin xử lý thanh toán cho khách hàng.

**Mô tả chi tiết:**

* Lập hóa đơn
* Tính tổng tiền
* Cập nhật trạng thái thanh toán
* In/xuất hóa đơn
