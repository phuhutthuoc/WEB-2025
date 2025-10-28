window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    // Thêm class 'scrolled' vào header khi cuộn xuống hơn 50px
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// =======================================
// --- CODE XỬ LÝ MODAL (POP-UP) ---
// =======================================

// Chờ cho toàn bộ nội dung trang web được tải xong
document.addEventListener('DOMContentLoaded', function() {
    
    // Lấy các phần tử DOM cần thiết
    const modal = document.getElementById('product-modal');
    const closeBtn = document.querySelector('.close-btn');
    const viewDetailButtons = document.querySelectorAll('.btn-view-detail');

    // Lấy các phần tử bên trong modal để điền thông tin
    const modalImg = document.getElementById('modal-img');
    const modalTitle = document.getElementById('modal-title');
    const modalDesc = document.getElementById('modal-desc');
    const modalSpecs = document.getElementById('modal-specs');
    const modalPrice = document.getElementById('modal-price');

    // Hàm để mở modal
    function openModal(productCard) {
        // 1. Lấy thông tin từ .product-card được click
        const img = productCard.querySelector('img').src;
        const title = productCard.querySelector('h3').textContent;
        const desc = productCard.querySelector('p').textContent;
        
        // 2. Xử lý thông số kỹ thuật (specs) và giá (price)
        // Lấy .product-info và clone (sao chép) cái <ul> bên trong
        // (Chúng ta clone để không làm hỏng dữ liệu gốc)
        const specsList = productCard.querySelector('.product-info ul').cloneNode(true);
        
        let priceText = '';
        // Tìm <li> chứa "Giá:"
        const listItems = specsList.querySelectorAll('li');
        listItems.forEach(li => {
            if (li.textContent.includes('Giá:')) {
                // Lấy text giá và xóa chữ "Giá:"
                priceText = li.textContent.replace('Giá:', '').trim(); 
                // Xóa <li> giá khỏi danh sách specs
                li.remove(); 
            }
        });

        // 3. Điền thông tin vào modal
        modalImg.src = img;
        modalTitle.textContent = title;
        modalDesc.textContent = desc;
        
        modalSpecs.innerHTML = ''; // Xóa specs cũ (nếu có)
        modalSpecs.appendChild(specsList); // Thêm danh sách specs mới (đã xóa giá)
        
        modalPrice.textContent = priceText; // Hiển thị giá

        // 4. Hiển thị modal
        modal.style.display = 'block';
    }

    // Hàm để đóng modal
    function closeModal() {
        modal.style.display = 'none';
    }

    // 5. Thêm sự kiện click cho TẤT CẢ các nút "Xem chi tiết"
    viewDetailButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Ngăn hành vi mặc định của thẻ <a> (nhảy lên đầu trang)
            const productCard = this.closest('.product-card'); // Tìm .product-card cha gần nhất
            openModal(productCard);
        });
    });

    // 6. Thêm sự kiện click cho nút đóng (X)
    closeBtn.addEventListener('click', closeModal);

    // 7. Thêm sự kiện click vào nền mờ (bên ngoài modal) để đóng
    modal.addEventListener('click', function(e) {
        // Nếu nơi được click chính là cái nền mờ (modal), không phải .modal-content
        if (e.target === modal) {
            closeModal();
        }
    });

});