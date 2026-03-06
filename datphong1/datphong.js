document.addEventListener('DOMContentLoaded', function() {
    
    const form = document.getElementById('bookingForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = {
            room_id: document.getElementById('room_id').value,
            customer_name: document.getElementById('name').value, // Key phải là customer_name để khớp với file PHP
            check_in: document.getElementById('check_in').value,
            check_out: document.getElementById('check_out').value
        };

        if (formData.check_in >= formData.check_out) {
            showMessage("Ngày trả phòng phải sau ngày nhận phòng!", false);
            return;
        }

        fetch('create_booking.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            return response.json().then(data => ({status: response.status, body: data}));
        })
        .then(res => {
            if (res.status === 201) {
                showMessage("✅ " + res.body.message, true);
                form.reset();
            } else {
                showMessage("⚠️ " + res.body.message, false);
            }
        })
        .catch(err => {
            console.error("Lỗi Fetch:", err);
            showMessage("❌ Không thể kết nối tới Server. Hãy kiểm tra lại XAMPP!", false);
        });
    });
});

function showMessage(text, isSuccess) {
    const box = document.getElementById('statusMessage');
    box.style.display = 'block';
    box.innerText = text;
    
    if (isSuccess) {
        box.className = 'message-box success';
    } else {
        box.className = 'message-box error';
    }
}