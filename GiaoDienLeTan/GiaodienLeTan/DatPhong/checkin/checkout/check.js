
const bookingId = 1;

// URL backend PHP
const BASE_URL = "D:\Chuyên đề\quanlykhachsan\GiaoDienLeTan\GiaodienLeTan";

function checkIn() {
  const bookingId = document.getElementById("bookingId").value;
  if (!bookingId) {
    alert("Vui lòng nhập booking ID");
    return;
  }

  fetch(`${BASE_URL}/checkin.php?bookingId=${bookingId}`, {
    method: "POST"
  })
    .then(res => res.json())
    .then(data => {
      document.getElementById("message").innerText = data.message;

      if (data.message.includes("thành công")) {
        const status = document.getElementById("status");
        status.innerText = "Occupied";
        status.className = "occupied";
      }
    });
}

function checkOut() {
  const bookingId = document.getElementById("bookingId").value;
  if (!bookingId) {
    alert("Vui lòng nhập booking ID");
    return;
  }

  fetch(`${BASE_URL}/checkout.php?bookingId=${bookingId}`, {
    method: "POST"
  })
    .then(res => res.json())
    .then(data => {
      if (data.total) {
        document.getElementById("message").innerText =
          `Check-out thành công. Tổng tiền: ${data.total} VNĐ`;

        const status = document.getElementById("status");
        status.innerText = "Available";
        status.className = "available";
      } else {
        document.getElementById("message").innerText = data.message;
      }
    });
}
