let rooms = JSON.parse(localStorage.getItem("rooms")) || [];

function themPhong(){
    let so = document.getElementById("soPhong").value.trim();

    if(so === ""){
        alert("Vui lòng nhập số phòng");
        return;
    }

    if(rooms.some(r => r.so === so)){
        alert("Phòng đã tồn tại!");
        return;
    }

    rooms.push({
        so: so,
        trangthai: "Trống"
    });

    localStorage.setItem("rooms", JSON.stringify(rooms));
    document.getElementById("soPhong").value = "";
    hienThiDanhSach();
}

function hienThiDanhSach(){
    const tbody = document.getElementById("dsPhong");
    tbody.innerHTML = "";

    rooms.forEach(r => {
        tbody.innerHTML += `
            <tr>
                <td>${r.so}</td>
                <td class="trong">${r.trangthai}</td>
            </tr>
        `;
    });
}

hienThiDanhSach();
