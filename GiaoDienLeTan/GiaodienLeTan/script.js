function checkin(){

let customer = document.getElementById("customerId").value
let room = document.getElementById("roomId").value

let rows = document.querySelectorAll("#roomTable tr")

rows.forEach(row => {

let roomId = row.cells[0].innerText

if(roomId === room){

row.cells[1].innerText = "Đã thuê"
row.cells[1].className = "status-full"

row.cells[2].innerText = customer

}

})

}

function checkout(){

let room = document.getElementById("roomCheckout").value

let rows = document.querySelectorAll("#roomTable tr")

rows.forEach(row => {

let roomId = row.cells[0].innerText

if(roomId === room){

row.cells[1].innerText = "Trống"
row.cells[1].className = "status-empty"

row.cells[2].innerText = ""

}

})

}