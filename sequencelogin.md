![1768805384368](image/sequencelogin/1768805384368.jpg)

```plantuml
@startuml
actor User
boundary "Login UI" as UI
control "Auth Controller" as Auth
database "Database" as DB

User -> UI : Nhập username & password
UI -> Auth : Gửi thông tin đăng nhập
Auth -> DB : Kiểm tra tài khoản
DB --> Auth : Kết quả xác thực

alt Đăng nhập hợp lệ
    Auth -> UI : Thông báo đăng nhập thành công
    UI -> User : Chuyển vào trang chính
else Đăng nhập không hợp lệ
    Auth -> UI : Thông báo lỗi
    UI -> User : Hiển thị sai thông tin
end

@enduml
```

| Thuộc tính       | Mô tả                                                                                                                                                                                                                                                                                                                                                                                |
| ------------------ | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Tên chức năng   | Đăng nhập hệ thống                                                                                                                                                                                                                                                                                                                                                                |
| Actor              | Người dùng( Lễ tân, khách hàng, quản lý)                                                                                                                                                                                                                                                                                                                                      |
| Mô tả            | Chức năng cho phép người dùng (quản lý, lễ tân, nhân viên) đăng nhập vào hệ thống quản lý khách sạn bằng tài khoản và mật khẩu đã được cấp, nhằm truy cập các chức năng phù hợp với quyền hạn.                                                                                                                                             |
| Tiền điều kiện | - Người dùng có tài khoản trong hệ thống<br />- Hệ thống đang hoạt động bình thường<br />- Người dùng đang ở màn hình đăng nhập                                                                                                                                                                                                                             |
| Hậu điều kiện  | - Nếu đăng nhập thành công:<br />  Người dùng được chuyển vào trang chính của hệ thống<br />- Nếu đăng nhập thất bại:<br />  Hệ thống báo lỗi và người dùng vẫn ở màn hình đăng nhập                                                                                                                                                          |
| Kịch bản chính  | 1. Người dùng nhập username và password<br />2. Hệ thống gửi thông tin đăng nhập đến Auth Controller<br />3. Auth Controller kiểm tra thông tin tài khoản trong cơ sở dữ liệu<br />4. Cơ sở dữ liệu trả về kết quả xác thực<br />5. Hệ thống thông báo đăng nhập thành công<br />6. Hệ thống chuyển người dùng vào trang chính |
| Kịch bản phụ    | Đăng nhập không hợp lệ:<br />1. Hệ thống thông báo lỗi đăng nhập<br />2. Hệ thống hiển thị thông báo sai thông tin đăng nhập                                                                                                                                                                                                                                 |
