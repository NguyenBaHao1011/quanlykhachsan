const rooms = [
    {so: "101", trangthai: "Trống"},
    {so: "102", trangthai: "Đang thuê"},
    {so: "103", trangthai: "Bảo trì"},
    {so: "104", trangthai: "Trống"},
    {so: "201", trangthai: "Đang thuê"},
    {so: "202", trangthai: "Trống"}
];

const grid = document.getElementById("roomGrid");

function loadRooms(){
    grid.innerHTML = ""; 

    rooms.forEach((room, index) => {

        let div = document.createElement("div");
        div.classList.add("room");

        
        if(room.trangthai === "Trống") div.classList.add("trong");
        if(room.trangthai === "Đang thuê") div.classList.add("dangthue");
        if(room.trangthai === "Bảo trì") div.classList.add("baotri");

        div.innerHTML = `
            Phòng ${room.so} <br>
            ${room.trangthai}
        `;

       
        div.onclick = () => {
            editStatus(index);
        }

        grid.appendChild(div);
    });
}

loadRooms();


function editStatus(index){

    let newStatus = prompt(
        "Nhập trạng thái mới:\nTrống / Đang thuê / Bảo trì"
    );

    if(newStatus){
        rooms[index].trangthai = newStatus;
        loadRooms(); 
    }
}
