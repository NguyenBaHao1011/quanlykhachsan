
let hoaDon = JSON.parse(localStorage.getItem("hoaDon")) || {

    tenKhach: "Nguyễn Văn A",
    sdt: "0123456789",

    phong: "PR001",
    giaPhong: 500000,

    ngayNhan: "2026-02-01",
    ngayTra: "2026-02-03",

    dichVu: [
        {
            ten: "Nước suối",
            soluong: 2,
            gia: 10000
        },
        {
            ten: "Mì gói",
            soluong: 1,
            gia: 15000
        }
    ]
};



document.getElementById("tenKhach").innerText = hoaDon.tenKhach;
document.getElementById("sdt").innerText = hoaDon.sdt;




document.getElementById("phong").innerText = hoaDon.phong;
document.getElementById("ngayNhan").innerText = hoaDon.ngayNhan;
document.getElementById("ngayTra").innerText = hoaDon.ngayTra;




let ngayNhan = new Date(hoaDon.ngayNhan);
let ngayTra = new Date(hoaDon.ngayTra);
let soNgay = (ngayTra - ngayNhan) / (1000 * 60 * 60 * 24);
document.getElementById("soNgay").innerText = soNgay;




let tienPhong = soNgay * hoaDon.giaPhong;
document.getElementById("tienPhong").innerText = tienPhong.toLocaleString();




let ds = document.getElementById("dsDichVu");
let tongDV = 0;
hoaDon.dichVu.forEach(function(dv) {
    let thanhTien = dv.soluong * dv.gia;
    tongDV += thanhTien;
    ds.innerHTML += `
        <tr>
            <td>${dv.ten}</td>
            <td>${dv.soluong}</td>
            <td>${dv.gia.toLocaleString()}</td>
            <td>${thanhTien.toLocaleString()}</td>
        </tr>
    `;
});



document.getElementById("tienDV").innerText = tongDV.toLocaleString();



let tongTien = tienPhong + tongDV;
document.getElementById("tongTien").innerText = tongTien.toLocaleString();



function thanhToan() {
    alert(
        "Thanh toán thành công!\nTổng tiền: "
        + tongTien.toLocaleString()
        + " VNĐ"
    );

    localStorage.removeItem("hoaDon");
    window.location.href = "../DatPhong/DatPhong.html";
}




function go(url) {
    window.location.href = url;

}

