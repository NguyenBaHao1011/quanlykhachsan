let rooms = [];

const soPhong = document.getElementById("soPhong");
const trangThai = document.getElementById("trangThai");
const editIndex = document.getElementById("editIndex");
const table = document.getElementById("roomTable");


function loadRooms() {
    table.innerHTML = "";

    rooms.forEach((room, index) => {
        table.innerHTML += `
            <tr>
                <td>${room.so}</td>
                <td class="${getClass(room.trangthai)}">${room.trangthai}</td>
                <td>
                    <button class="btn edit" onclick="editRoom(${index})">Sửa</button>
                    <button class="btn delete" onclick="deleteRoom(${index})">Xóa</button>
                </td>
            </tr>
        `;
    });
}


function saveRoom() {
    if (soPhong.value.trim() === "") {
        alert("Nhập số phòng!");
        return;
    }

    if (editIndex.value === "") {
        // Thêm
        if (rooms.some(r => r.so === soPhong.value)) {
            alert("Phòng đã tồn tại!");
            return;
        }

        rooms.push({
            so: soPhong.value,
            trangthai: trangThai.value
        });
    } else {
        
        rooms[editIndex.value] = {
            so: soPhong.value,
            trangthai: trangThai.value
        };
        editIndex.value = "";
    }

    soPhong.value = "";
    trangThai.value = "Trống";
    loadRooms();
}


function editRoom(index) {
    soPhong.value = rooms[index].so;
    trangThai.value = rooms[index].trangthai;
    editIndex.value = index;
}


function deleteRoom(index) {
    if (confirm("Xóa phòng này?")) {
        rooms.splice(index, 1);
        loadRooms();
    }
}


function getClass(status) {
    if (status === "Trống") return "trong";
    if (status === "Đang thuê") return "dangthue";
    if (status === "Bảo trì") return "baotri";
    return "";
}
