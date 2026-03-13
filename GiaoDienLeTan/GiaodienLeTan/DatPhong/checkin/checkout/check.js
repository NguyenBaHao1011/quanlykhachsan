let customers = [];

function checkIn(){

let id = document.getElementById("customerId").value;
let name = document.getElementById("customerName").value;
let room = document.getElementById("roomId").value;

if(id=="" || name=="" || room==""){
alert("Vui lòng nhập đầy đủ thông tin");
return;
}

let time = new Date().toLocaleString();

customers.push({
id:id,
name:name,
room:room,
checkIn:time,
checkOut:"",
status:"Đang ở"
});

renderTable();
clearInput();

}

function checkOut(){

let room = document.getElementById("roomId").value;

for(let i=0;i<customers.length;i++){

if(customers[i].room===room && customers[i].status==="Đang ở"){

customers[i].checkOut = new Date().toLocaleString();
customers[i].status = "Đã trả phòng";

break;
}

}

renderTable();
clearInput();

}

function renderTable(){

let table = document.getElementById("tableBody");
table.innerHTML="";

customers.forEach(c=>{

let row=`
<tr>
<td>${c.id}</td>
<td>${c.name}</td>
<td>${c.room}</td>
<td>${c.checkIn}</td>
<td>${c.checkOut}</td>
<td>${c.status}</td>
</tr>
`;

table.innerHTML+=row;

});

}

function clearInput(){
document.getElementById("customerId").value="";
document.getElementById("customerName").value="";
document.getElementById("roomId").value="";
}