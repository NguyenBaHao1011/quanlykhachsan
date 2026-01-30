let customers = JSON.parse(localStorage.getItem("customers")) || [];
let customerCount = customers.length + 1;

const table = document.getElementById("customerTable");
const form = document.getElementById("customerForm");
const searchInput = document.getElementById("searchInput");


function renderTable(data) {
    table.innerHTML = "";
    data.forEach((c, index) => {
        const row = table.insertRow();
        row.innerHTML = `
            <td>${c.id}</td>
            <td>${c.name}</td>
            <td>${c.phone}</td>
            <td>${c.email}</td>
            <td>${c.cccd}</td>
            <td>
                <button class="btn delete" onclick="deleteCustomer(${index})">
                    Xóa
                </button>
            </td>
        `;
    });
}


form.addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const email = document.getElementById("email").value.trim();
    const cccd = document.getElementById("cccd").value.trim();

    if (!name || !phone) {
        alert("Vui lòng nhập họ tên và số điện thoại");
        return;
    }

    if (!/^[0-9]+$/.test(phone)) {
        alert("Số điện thoại chỉ được chứa số");
        return;
    }

    const customer = {
        id: "KH" + String(customerCount).padStart(3, "0"),
        name,
        phone,
        email,
        cccd
    };

    customers.push(customer);
    customerCount++;

    localStorage.setItem("customers", JSON.stringify(customers));
    renderTable(customers);
    form.reset();
});


function deleteCustomer(index) {
    if (confirm("Bạn có chắc muốn xóa khách hàng này?")) {
        customers.splice(index, 1);
        localStorage.setItem("customers", JSON.stringify(customers));
        renderTable(customers);
    }
}


searchInput.addEventListener("keyup", function () {
    const keyword = this.value.toLowerCase();
    const filtered = customers.filter(c =>
        c.name.toLowerCase().includes(keyword)
    );
    renderTable(filtered);
});


renderTable(customers);
