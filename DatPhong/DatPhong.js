let dsKhachHang = []
let dsPhong = []
let dsDatPhong = []

// Load data on page load
window.onload = function() {
    loadKhachHang()
    loadPhong()
    loadDatPhong()
}

async function loadKhachHang() {
    try {
        let response = await fetch('../api/khachhang.php')
        dsKhachHang = await response.json()
    } catch (error) {
        console.error('Error loading customers:', error)
    }
}

async function loadPhong() {
    try {
        let response = await fetch('../api/phong.php')
        dsPhong = await response.json()
    } catch (error) {
        console.error('Error loading rooms:', error)
    }
}

async function loadDatPhong() {
    try {
        let response = await fetch('../api/api.php')
        dsDatPhong = await response.json()
        hienThi()
    } catch (error) {
        console.error('Error loading bookings:', error)
    }
}


function timKhachHang(){

let makh = document.getElementById("makh").value

let kh = dsKhachHang.find(x=>x.makh==makh)

if(kh){

document.getElementById("tenkh").value = kh.ten
document.getElementById("sdt").value = kh.sdt

}

}



function timPhong(){

let mp = document.getElementById("maphong").value

let p = dsPhong.find(x=>x.maphong==mp)

if(p){

document.getElementById("loaiphong").value = p.loaiphong
document.getElementById("giaphong").value = p.gia

}

}



function tinhTien(){

let gia = document.getElementById("giaphong").value
let ngaynhan = document.getElementById("ngaynhan").value
let ngaytra = document.getElementById("ngaytra").value

if(!gia || !ngaynhan || !ngaytra) return

let n1 = new Date(ngaynhan)
let n2 = new Date(ngaytra)

let songay = (n2-n1)/(1000*60*60*24)

if(songay<=0) return

let tong = songay * gia

document.getElementById("tongtien").value = tong

}



async function datPhong(){
    let data = {
        makh: document.getElementById("makh").value,
        ten: document.getElementById("tenkh").value,
        sdt: document.getElementById("sdt").value,
        maphong: document.getElementById("maphong").value,
        loaiphong: document.getElementById("loaiphong").value,
        ngaynhan: document.getElementById("ngaynhan").value,
        ngaytra: document.getElementById("ngaytra").value,
        tong: document.getElementById("tongtien").value
    }

    try {
        let response = await fetch('../api/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data)
        })
        let result = await response.json()
        if (result.success) {
            alert('Đặt phòng thành công!')
            loadDatPhong() // Reload the list
        } else {
            alert('Lỗi: ' + result.error)
        }
    } catch (error) {
        console.error('Error booking:', error)
    }
}



function hienThi() {
    let table = document.getElementById("tableBody");
    table.innerHTML = "";

    dsDatPhong.forEach(p => {
        // Nếu tổng tiền > 0 thì dòng đó có màu xanh nhạt
        let rowStyle = p.tong > 0 ? "background-color: #e8f6f3;" : "";
        
        table.innerHTML += `
        <tr style="${rowStyle}">
            <td>${p.makh}</td>
            <td><strong>${p.ten}</strong></td>
            <td>${p.sdt}</td>
            <td><span style="color: #2980b9; font-weight: bold;">${p.maphong}</span></td>
            <td>${p.loaiphong}</td>
            <td>${p.ngaynhan}</td>
            <td>${p.ngaytra}</td>
            <td style="color: red; font-weight: bold;">${Number(p.tong).toLocaleString()} đ</td>
        </tr>
        `;
    });
}



function huyPhong(){

dsDatPhong.pop()

hienThi()

}



function timKiem(){

let key = document.getElementById("timkiem").value.toLowerCase()

let rows = document.querySelectorAll("#tableBody tr")

rows.forEach(r=>{

if(r.innerText.toLowerCase().includes(key))
r.style.display=""
else
r.style.display="none"

})

}