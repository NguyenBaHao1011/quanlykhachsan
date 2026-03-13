const cards = document.querySelectorAll('.card');

cards.forEach(card => {
    card.addEventListener('click', () => {
        alert(`Bạn chọn: ${card.innerText}`);
    });
});
