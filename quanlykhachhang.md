```plantuml
@startuml
left to right direction

actor "Nhân viên lễ tân" as LeTan
actor "Quản lý khách sạn" as QuanLy
actor "Khách hàng" as KhachHang

rectangle "HỆ THỐNG QUẢN LÝ KHÁCH SẠN\n(Quản lý khách hàng)" {

  usecase "Thêm khách hàng" as UC1
  usecase "Cập nhật thông tin\nkhách hàng" as UC2
  usecase "Xóa khách hàng" as UC3
  usecase "Tìm kiếm khách hàng" as UC4
  usecase "Xem chi tiết\nkhách hàng" as UC5
  usecase "Xem lịch sử lưu trú" as UC6
  usecase "Quản lý giấy tờ\n(CCCD / Hộ chiếu)" as UC7
  usecase "Thống kê khách hàng" as UC8
}

LeTan --> UC1
LeTan --> UC2
LeTan --> UC3
LeTan --> UC4
LeTan --> UC5
LeTan --> UC6
LeTan --> UC7

QuanLy --> UC4
QuanLy --> UC5
QuanLy --> UC6
QuanLy --> UC8

KhachHang --> UC1 : "Cung cấp\nthông tin"

UC1 --> UC7 : <<include>>
UC2 --> UC7 : <<include>>
UC5 --> UC6 : <<extend>>
@enduml
```


| Thuộc tính               | Mô tả                                                                                                                                                                                                                          |
| -------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Tên chức năng           | Quản lý khác hàng                                                                                                                                                                                                            |
| Actor chính               | Nhân viên lễ tân                                                                                                                                                                                                             |
| Actor phụ                 | Quản lý khách sạn, Khách hàng                                                                                                                                                                                              |
| Mô tả                    | Quản lý thông tin cá nhân và lịch sử lưu trú của khách hàng để phục vụ đặt phòng, thanh toán và báo cáo                                                                                                  |
| Tiền điều kiện         | Nhân viên lễ tân hoặc quản lý đã đăng nhập hệ thống; hệ thống và cơ sở dữ liệu hoạt động bình thường                                                                                                  |
| Hậu điều kiện          | Thông tin khách hàng được lưu trữ trong CSDL; dữ liệu sẵn sàng dùng cho đặt phòng, thanh toán và báo cáo                                                                                                     |
| Kịch bản chính          | 1. Chọn chức năng quản lý khách hàng<br />2. Hệ thống hiển thị danh sách khách hàng<br />3. Thêm / Cập nhật / Xóa / Tìm kiếm khách hàng<br />4. Hệ thống xử lý yêu cầu<br />5. Thông báo kết quả |
| Kịch bản phụ            | A1 - Khách hàng đã tồn tại<br />- Hệ thống phát hiện trùng CCCD/Hộ chiếu<br />- Thông báo khách hàng đã tồn tại                                                                                           |
| Luồng sự kiện thay thế | A1 - Khách hàng đã tồn tại<br />- Hệ thống phát hiện trùng CCCD/Hộ chiếu<br />- Thông báo khách hàng đã tồn tại<br />- Cho phép cập nhật thông ti                                                       |
