document.getElementById("bookingForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const soPhong = document.getElementById("soPhong").value.trim();
    const tenKhach = document.getElementById("tenKhach").value.trim();
    const soDienThoai = document.getElementById("soDienThoai").value.trim();
    const ngayNhan = document.getElementById("ngayNhan").value;
    const ngayTra = document.getElementById("ngayTra").value;

    if (!soPhong || !tenKhach || !soDienThoai || !ngayNhan || !ngayTra) {
        alert("Vui lòng điền đầy đủ thông tin!");
        return;
    }

    const bookingData = {
        so_phong: soPhong,
        ten_khach: tenKhach,
        so_dien_thoai: soDienThoai,
        ngay_nhan: ngayNhan,
        ngay_tra: ngayTra
    };

    fetch("../backend/booking_add.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(bookingData)
    })
    .then(response => {
        if (response.ok) {
            alert("Đặt phòng thành công!");
            
            document.getElementById("bookingForm").reset();
        } else {
            alert("Đặt phòng thất bại, vui lòng thử lại!");
        }
    })
    .catch(error => {
        console.error("Lỗi:", error);
        alert("Đã xảy ra lỗi. Vui lòng thử lại sau.");
    });
});
