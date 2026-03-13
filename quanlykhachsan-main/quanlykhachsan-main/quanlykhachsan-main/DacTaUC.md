# Đặc tả USECASE hệ thống quản lý khách sạn

## **I. Actor: Khách hàng (User)**

Use Case 1: Quản lý tài khoản

* Mô tả: Khách hàng đăng ký, đăng nhập và cập nhật thông tin cá nhân.
* Hậu điều kiện: Khách hàng sử dụng được các chức năng hệ thống.

Use Case 2: Xem thông tin và đặt phòng

* Mô tả: Khách hàng xem thông tin phòng, kiểm tra phòng trống theo thời gian và thực
  hiện đặt phòng online.
* Hậu điều kiện: Đơn đặt phòng được tạo và lưu trong hệ thống.

Use Case 3: Quản lý đặt phòng cá nhân

* Mô tả: Khách hàng xem lịch sử đặt phòng, gửi yêu cầu hủy hoặc chỉnh sửa đặt phòng
  và theo dõi trạng thái thanh toán.
* Hậu điều kiện: Thông tin đặt phòng được cập nhật.

Use Case 4: Thanh toán

* Mô tả: Khách hàng thực hiện thanh toán chi phí lưu trú.
* Hậu điều kiện: Trạng thái thanh toán được ghi nhận.

## **II. Actor: Lễ tân**

Use Case 5: Quản lý lưu trú

* Mô tả: Lễ tân quản lý toàn bộ quá trình lưu trú của khách bao gồm xác nhận đặt
  phòng, check-in, check-out và gia hạn ngày ở.
* Hậu điều kiện: Trạng thái lưu trú và phòng được cập nhật chính xác.

Use Case 6: Quản lý khách hàng và dịch vụ

* Mô tả: Lễ tân quản lý thông tin khách hàng, ghi nhận các dịch vụ khách sử dụng và
  lập hóa đơn thanh toán.
* Hậu điều kiện: Chi phí dịch vụ được tổng hợp đầy đủ.

Use Case 7: Quản lý tình trạng phòng

* Mô tả: Lễ tân theo dõi và cập nhật tình trạng phòng theo thời gian thực.
* Hậu điều kiện: Hệ thống phản ánh đúng tình trạng phòng.

## **III. Actor: Kế toán**

Use Case 8: Quản lý tài chính

* Mô tả: Kế toán quản lý hóa đơn, xác nhận thanh toán, thống kê doanh thu và xuất
  báo cáo tài chính.
* Hậu điều kiện: Dữ liệu tài chính chính xác, phục vụ quản lý.

## **IV. Actor: Admin**

Use Case 9: Quản lý hệ thống

* Mô tả: Admin quản lý tài khoản người dùng, phân quyền, quản lý phòng, loại phòng,
  giá phòng và cấu hình hệ thống.

- Hậu điều kiện: Hệ thống hoạt động ổn định và đúng phân quyền.
