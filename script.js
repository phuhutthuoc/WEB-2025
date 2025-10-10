window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    // Thêm class 'scrolled' vào header khi cuộn xuống hơn 50px
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});


