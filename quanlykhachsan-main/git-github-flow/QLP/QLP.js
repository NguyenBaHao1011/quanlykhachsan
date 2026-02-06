let dsPhong = JSON.parse(localStorage.getItem("dsPhong")) || [];
let indexDangSua = -1;


const bangGiaPhong = {
    "Standard": 500000,
    "Superior": 1000000,
    "Deluxe": 3000000,
    "Premier": 5000000,
    "Junior Suite": 7000000,
    "Family Suite": 10000000,
    "Executive Suite": 12000000
};


function go(page) {
    window.location.href = page;
}


function taoMaPhong() {
    let max = 0;
    dsPhong.forEach(p => {
        const so = parseInt(p.ma.replace("PR", ""));
        if (so > max) max = so;
    });
    return "PR" + String(max + 1).padStart(3, "0");
}


function capNhatGia() {
    const loai = loaiPhong.value;
    if (bangGiaPhong[loai]) {
        giaPhong.value = bangGiaPhong[loai];
        giaPhong.readOnly = true; 
    } else {
        giaPhong.value = "";
        giaPhong.readOnly = false;
    }
}


function render(data = dsPhong) {
    bangPhong.innerHTML = "";
    data.forEach((p, i) => {
        bangPhong.innerHTML += `
            <tr>
                <td>${p.ma}</td>
                <td>${p.loai}</td>
                <td>${Number(p.gia).toLocaleString()} VND</td>
                <td>${p.tinhTrang}</td>
                <td>
                    <button class="btn edit" onclick="chonPhong(${i})">Sửa</button>
                    <button class="btn delete" onclick="xoaPhong(${i})">Xóa</button>
                </td>
            </tr>
        `;
    });
}


function luuPhong() {
    if (!loaiPhong.value) {
        alert("Vui lòng chọn loại phòng");
        return;
    }

    if (!giaPhong.value) {
        alert("Giá phòng không hợp lệ");
        return;
    }

    if (indexDangSua === -1) {
        dsPhong.push({
            ma: taoMaPhong(),
            loai: loaiPhong.value,
            gia: giaPhong.value,
            tinhTrang: tinhTrang.value
        });
    } else {
        dsPhong[indexDangSua] = {
            ma: maPhong.value,
            loai: loaiPhong.value,
            gia: giaPhong.value,
            tinhTrang: tinhTrang.value
        };
    }

    luuLocal();
}


function chonPhong(i) {
    indexDangSua = i;
    const p = dsPhong[i];
    maPhong.value = p.ma;
    loaiPhong.value = p.loai;
    giaPhong.value = p.gia;
    tinhTrang.value = p.tinhTrang;
    giaPhong.readOnly = false; // cho phép chỉnh giá khi sửa
}


function xoaPhong(i) {
    if (dsPhong[i].tinhTrang === "Đã đặt") {
        alert("Không thể xóa phòng đang ĐÃ ĐẶT");
        return;
    }
    if (!confirm("Bạn chắc chắn muốn xóa phòng này?")) return;
    dsPhong.splice(i, 1);
    luuLocal();
}

function timKiem() {
    const key = timPhong.value.toLowerCase();
    render(dsPhong.filter(p =>
        p.ma.toLowerCase().includes(key) ||
        p.loai.toLowerCase().includes(key)
    ));
}

function lamMoi() {
    maPhong.value = taoMaPhong();
    loaiPhong.value = "";
    giaPhong.value = "";
    tinhTrang.value = "Trống";
    giaPhong.readOnly = false;
    indexDangSua = -1;
}

function luuLocal() {
    localStorage.setItem("dsPhong", JSON.stringify(dsPhong));
    lamMoi();
    render();
}

lamMoi();
render();
