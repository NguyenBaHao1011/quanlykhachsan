/* ================== DATA ================== */
let customers = JSON.parse(localStorage.getItem("customers")) || [];
let customerCounter = Number(localStorage.getItem("customerCounter")) || 1;

/* ================== ELEMENTS ================== */
const table = document.getElementById("customerTable");
const form = document.getElementById("customerForm");
const searchInput = document.getElementById("searchInput");

const nameInput = document.getElementById("name");
const phoneInput = document.getElementById("phone");
const emailInput = document.getElementById("email");
const cccdInput = document.getElementById("cccd");
const editIdInput = document.getElementById("editId");

const formTitle = document.getElementById("formTitle");
const submitBtn = document.getElementById("submitBtn");
const cancelBtn = document.getElementById("cancelBtn");

/* ================== RENDER ================== */
function renderTable(data) {
    table.innerHTML = "";
    data.forEach(c => {
        const row = table.insertRow();
        row.innerHTML = `
            <td>${c.id}</td>
            <td>${c.name}</td>
            <td>${c.phone}</td>
            <td>${c.email}</td>
            <td>${c.cccd}</td>
            <td>
                <button class="btn edit" onclick="editCustomer('${c.id}')">
                    Sửa
                </button>
                <button class="btn delete" onclick="deleteCustomer('${c.id}')">
                    Xóa
                </button>
            </td>
        `;
    });
}

/* ================== ADD / UPDATE ================== */
form.addEventListener("submit", e => {
    e.preventDefault();

    const id = editIdInput.value;
    const name = nameInput.value.trim();
    const phone = phoneInput.value.trim();
    const email = emailInput.value.trim();
    const cccd = cccdInput.value.trim();

    if (!name || !phone) {
        alert("Vui lòng nhập họ tên và số điện thoại");
        return;
    }

    if (!/^[0-9]+$/.test(phone)) {
        alert("Số điện thoại chỉ được chứa số");
        return;
    }

    if (cccd && !/^[0-9]{12}$/.test(cccd)) {
        alert("CCCD phải gồm đúng 12 chữ số");
        return;
    }

    if (id) {
        // UPDATE
        const c = customers.find(c => c.id === id);
        c.name = name;
        c.phone = phone;
        c.email = email;
        c.cccd = cccd;
    } else {
        // ADD NEW
        const newId = "KH" + String(customerCounter).padStart(3, "0");

        customers.push({
            id: newId,
            name,
            phone,
            email,
            cccd
        });

        customerCounter++;
        localStorage.setItem("customerCounter", customerCounter);
    }

    localStorage.setItem("customers", JSON.stringify(customers));
    resetForm();
    renderTable(customers);
});

/* ================== EDIT ================== */
function editCustomer(id) {
    const c = customers.find(c => c.id === id);
    editIdInput.value = c.id;
    nameInput.value = c.name;
    phoneInput.value = c.phone;
    emailInput.value = c.email;
    cccdInput.value = c.cccd;

    formTitle.innerText = "Cập nhật khách hàng";
    submitBtn.innerText = "Cập nhật";
    cancelBtn.hidden = false;
}

/* ================== DELETE ================== */
function deleteCustomer(id) {
    if (confirm("Bạn có chắc muốn xóa khách hàng này?")) {
        customers = customers.filter(c => c.id !== id);
        localStorage.setItem("customers", JSON.stringify(customers));
        renderTable(customers);
    }
}

/* ================== SEARCH ================== */
searchInput.addEventListener("keyup", function () {
    const keyword = this.value.toLowerCase();
    renderTable(customers.filter(c =>
        c.name.toLowerCase().includes(keyword)
    ));
});

/* ================== ENTER SUBMIT ================== */
form.addEventListener("keydown", e => {
    if (e.key === "Enter") {
        form.requestSubmit();
    }
});

/* ================== RESET ================== */
cancelBtn.addEventListener("click", resetForm);

function resetForm() {
    form.reset();
    editIdInput.value = "";
    formTitle.innerText = "Thêm khách hàng";
    submitBtn.innerText = "Thêm khách hàng";
    cancelBtn.hidden = true;
    nameInput.focus();
}

/* ================== INIT ================== */
renderTable(customers);
nameInput.focus();
