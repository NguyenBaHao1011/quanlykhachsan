// Hiệu ứng click cho Menu
const menuItems = document.querySelectorAll('.sidebar li');
menuItems.forEach(item => {
    item.addEventListener('click', function() {
        menuItems.forEach(i => i.classList.remove('active'));
        this.classList.add('active');
    });
});

// Xử lý nút Logout (giả lập)
document.querySelector('.btn-logout').addEventListener('click', () => {
    if(confirm("Bạn có chắc chắn muốn đăng xuất?")) {
        window.location.reload();
    }
});