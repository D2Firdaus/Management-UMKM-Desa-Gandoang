// hamburger
const toggleBtn = document.querySelector('.hamburger');

const sidebar = document.querySelector('.sidebar');

toggleBtn.addEventListener('click', () => {

    sidebar.classList.toggle('close');

    toggleBtn.classList.toggle('active');

});

// alert sukses tambah
setTimeout(function () {
    let alertBox = document.querySelector('.alert_sukses_menambah');
    if (alertBox) {
        alertBox.style.display = 'none';
    }
}, 3000);