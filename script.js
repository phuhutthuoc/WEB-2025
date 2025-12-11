<<<<<<< HEAD
// [PART 1: HEADER SCROLL - UNCHANGED]
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
=======
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    // Thêm class 'scrolled' vào header khi cuộn xuống hơn 50px
>>>>>>> 4787611c99a7712928bbe5b5f8582a012465954e
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// =======================================
<<<<<<< HEAD
// --- CODE XỬ LÝ MODAL & SEARCH ---
// =======================================
document.addEventListener('DOMContentLoaded', function() {

    const modal = document.getElementById('product-modal');
    if (!modal) {
        console.log("Không tìm thấy modal sản phẩm (Đây là trang news.php)");
        return; 
    }

    const closeBtn = modal.querySelector('.close-btn');
=======
// --- CODE XỬ LÝ MODAL (POP-UP) ---
// =======================================

// Chờ cho toàn bộ nội dung trang web được tải xong
document.addEventListener('DOMContentLoaded', function() {
    
    // Lấy các phần tử DOM cần thiết
    const modal = document.getElementById('product-modal');
    const closeBtn = document.querySelector('.close-btn');
    const viewDetailButtons = document.querySelectorAll('.btn-view-detail');

    // Lấy các phần tử bên trong modal để điền thông tin
>>>>>>> 4787611c99a7712928bbe5b5f8582a012465954e
    const modalImg = document.getElementById('modal-img');
    const modalTitle = document.getElementById('modal-title');
    const modalDesc = document.getElementById('modal-desc');
    const modalSpecs = document.getElementById('modal-specs');
    const modalPrice = document.getElementById('modal-price');
<<<<<<< HEAD
    const modalAddToCartBtn = document.getElementById('modal-add-to-cart');
    const modalQuantityInput = document.getElementById('modal-quantity');
    const modalCartMessage = document.getElementById('modal-cart-message');

    // --- Biến Mini Cart ---
    const miniCart = document.getElementById('mini-cart');
    const miniCartList = document.getElementById('mini-cart-list');
    const miniCartEmptyMsg = document.getElementById('mini-cart-empty');
    const miniCartTotal = document.getElementById('mini-cart-total');

    // --- Biến Live Search ---
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('product-search-form');
    const resultsDiv = document.getElementById('live-search-results'); 

    // ... (Toàn bộ code Live Search của bạn giữ nguyên) ...
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) { e.preventDefault(); });
    }
    function showSearchResults(products) {
        resultsDiv.innerHTML = '';
        if (products.length === 0) {
            resultsDiv.innerHTML = '<div class="live-search-no-result">Không tìm thấy sản phẩm</div>';
            resultsDiv.style.display = 'block';
            return;
        }
        products.forEach(product => {
            const item = document.createElement('a');
            item.href = '#';
            item.className = 'live-search-item';
            item.dataset.id = product.id;
            item.dataset.name = product.name;
            item.dataset.image = product.image_url;
            item.dataset.desc = product.description;
            item.dataset.specs = product.specs;
            item.dataset.price = product.price;
            item.innerHTML = `<img src="${product.image_url}" alt="${product.name}"><span class="item-name">${product.name}</span>`;
            resultsDiv.appendChild(item);
        });
        resultsDiv.style.display = 'block';
    }
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            if (searchTerm.length < 2) {
                resultsDiv.innerHTML = '';
                resultsDiv.style.display = 'none';
                return;
            }
            fetch('live.php?term=' + encodeURIComponent(searchTerm))
                .then(response => response.json())
                .then(data => { showSearchResults(data); })
                .catch(error => { console.error('Lỗi khi tìm kiếm:', error); resultsDiv.style.display = 'none'; });
        });
    }
    
    // =========================================================
    // ✅ SỬA LỖI (CHỖ 1): KHI BẤM VÀO KẾT QUẢ TÌM KIẾM
    // =========================================================
    if (resultsDiv) {
        resultsDiv.addEventListener('click', function(e) {
            const clickedItem = e.target.closest('.live-search-item');
            if (clickedItem) {
                e.preventDefault();
                const productId = clickedItem.dataset.id;
                const name = clickedItem.dataset.name;
                const desc = clickedItem.dataset.desc;
                const img = clickedItem.dataset.image;
                const specsHTML = clickedItem.dataset.specs;
                const price = clickedItem.dataset.price; // price = "từ 3.200.000đ..."
                
                modalImg.src = img;
                modalTitle.textContent = name;
                modalDesc.textContent = desc;
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = specsHTML;
                const specsList = tempDiv.querySelector('ul');
                modalSpecs.innerHTML = '';
                if(specsList) {
                    const listItems = specsList.querySelectorAll('li');
                    listItems.forEach(li => { if (li.textContent.includes('Giá:')) { li.remove(); } });
                    modalSpecs.appendChild(specsList);
                }
                
                // ✅ SỬA LỖI: Xóa "khoảng " VÀ "từ "
                let displayPrice = price.replace('khoảng ', '').replace('từ ', '');
                modalPrice.textContent = displayPrice; // Gán giá đã làm sạch

                modalAddToCartBtn.dataset.id = productId;
                modalQuantityInput.value = 1;
                modalCartMessage.style.display = 'none';
                modal.style.display = 'block';
                resultsDiv.style.display = 'none';
                searchInput.value = '';
            }
        });
    }
    document.addEventListener('click', function(e) {
        if (searchInput && resultsDiv) {
            if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
                resultsDiv.style.display = 'none';
            }
        }
    });

    
    // =========================================================
    // ✅ SỬA LỖI (CHỖ 2): KHI BẤM VÀO NÚT "XEM CHI TIẾT"
    // =========================================================
    const viewDetailButtons = document.querySelectorAll('.btn-view-detail');
    function openModal(productCard) {
        const productId = productCard.dataset.id; 
        const img = productCard.querySelector('img').src;
        const title = productCard.querySelector('h3').textContent;
        const desc = productCard.querySelector('p').textContent;
        const specsList = productCard.querySelector('.product-info ul').cloneNode(true);
        
        let priceText = '';
        const listItems = specsList.querySelectorAll('li');
        listItems.forEach(li => { 
            if (li.textContent.includes('Giá:')) { 
                priceText = li.textContent.replace('Giá:', '').trim(); // priceText = "từ 3.200.000đ..."
=======

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
>>>>>>> 4787611c99a7712928bbe5b5f8582a012465954e
                li.remove(); 
            }
        });

<<<<<<< HEAD
        modalImg.src = img;
        modalTitle.textContent = title;
        modalDesc.textContent = desc;
        modalSpecs.innerHTML = '';
        modalSpecs.appendChild(specsList);
        
        // ✅ SỬA LỖI: Xóa "khoảng " VÀ "từ "
        let displayPrice = priceText.replace('khoảng ', '').replace('từ ', '');
        modalPrice.textContent = displayPrice; // Gán giá đã làm sạch

        modalAddToCartBtn.dataset.id = productId;
        modalQuantityInput.value = 1;
        modalCartMessage.style.display = 'none';
        modal.style.display = 'block';
    }
    
    // (Phần code còn lại không đổi)
    function closeModal() { modal.style.display = 'none'; }
    viewDetailButtons.forEach(button => {
        button.addEventListener('click', function(e) { e.preventDefault(); const productCard = this.closest('.product-card'); openModal(productCard); });
    });
    if (closeBtn) { closeBtn.addEventListener('click', closeModal); }
    if (modal) { modal.addEventListener('click', function(e) { if (e.target === modal) { closeModal(); } }); }


    // =======================================
    // --- LOGIC THÊM GIỎ HÀNG (AJAX) ---
    // (Đã sửa ở lần trước, giữ nguyên)
    // =======================================
    
    if (modalAddToCartBtn) {
        
        modalAddToCartBtn.addEventListener('click', function() {
            
            console.log("--- BÀI TEST 1: NÚT ĐÃ ĐƯỢC BẤM! ---");
            // ✅ 1. LẤY TRẠNG THÁI ĐĂNG NHẬP TỪ THẺ <body>
            const isLoggedIn = document.body.dataset.loggedIn === 'true';
            //console.log("ID Sản phẩm đang lấy:", this.dataset.id);
            // Lấy thông tin sản phẩm (giữ nguyên)
            const productId = this.dataset.id;
            const quantity = parseInt(modalQuantityInput.value, 10);
            const productName = modalTitle.textContent;
            const productPrice = modalPrice.textContent;
            const productImg = modalImg.src;

            if (quantity <= 0) {
                alert('Số lượng phải lớn hơn 0');
                return;
            }

            // ✅ 2. KIỂM TRA TRẠNG THÁI TRƯỚC KHI GỌI FETCH
        // =============================================
        if (!isLoggedIn) {
            console.log("Người dùng chưa đăng nhập. Yêu cầu đăng nhập.");
            
            // Hiển thị thông báo và link đăng nhập
            modalCartMessage.innerHTML = 'Bạn phải <a href="user/user_login.php" style="color: yellow; text-decoration: underline;">Đăng nhập</a> để thêm vào giỏ!';
            modalCartMessage.style.color = 'orange'; // Dùng màu cam/vàng cho cảnh báo
            modalCartMessage.style.display = 'block';

            return; // Dừng lại, không gọi fetch
        }

        // =============================================
        // ✅ 3. NẾU ĐÃ ĐĂNG NHẬP, TIẾN HÀNH FETCH
        // =============================================
        console.log("Người dùng đã đăng nhập, tiến hành fetch...");

        fetch('cart_manage.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'add',
                product_id: productId,
                quantity: quantity
            })
        })
            .then(response => response.json())
            .then(data => {
                
                console.log("--- BÀI TEST 2: KẾT QUẢ TỪ SERVER ---");
                console.log(data); 

                // ✅ 4. XỬ LÝ PHẢN HỒI (Phòng trường hợp backend check)
            if (data.success === false && data.action_required === 'login') {
                modalCartMessage.innerHTML = 'Bạn phải <a href="user/user_login.php" style="color: yellow; text-decoration: underline;">Đăng nhập</a> để thêm vào giỏ!';
                modalCartMessage.style.color = 'orange';
                modalCartMessage.style.display = 'block';
                return;
            }

                if (data.success) {
                    const cartCountSpan = document.getElementById('cart-item-count');
                    if (cartCountSpan) {
                        cartCountSpan.innerText = data.cartCount;
                    }
                    
                    modalCartMessage.innerText = data.message;
                    modalCartMessage.style.color = 'lime';
                    modalCartMessage.style.display = 'block';

                    setTimeout(() => { 
                        modalCartMessage.style.display = 'none'; 
                    }, 2500);

                    // (Logic mini-cart)
                    if (miniCartEmptyMsg) {
                        miniCartEmptyMsg.style.display = 'none';
                    }
                    if (miniCartList) { 
                        let existingItem = miniCartList.querySelector(`.mini-cart-item[data-id="${productId}"]`);
                        if (existingItem) {
                            // (Đã bỏ reload)
                        } else {
                            const li = document.createElement('li');
                            li.className = 'mini-cart-item';
                            li.dataset.id = productId;
                            li.innerHTML = `
                                <img src="${productImg}" alt="">
                                <div class="mini-cart-info">
                                    <div class="name">${productName}</div>
                                    <div class="qty">Số lượng: ${quantity}</div>
                                    <div class="price">${productPrice}</div>
                                </div>
                            `;
                            miniCartList.prepend(li);
                        }
                    }
                    
                    if (miniCart) { 
                        miniCart.classList.add('show-temp');
                        setTimeout(() => {
                            miniCart.classList.remove('show-temp');
                        }, 3000); 
                    }

                } else {
                    modalCartMessage.innerText = 'Lỗi: ' + data.message;
                    modalCartMessage.style.color = 'red';
                    modalCartMessage.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Lỗi Fetch nghiêm trọng:', error); 
                modalCartMessage.innerText = 'Lỗi kết nối. Vui lòng thử lại.';
                modalCartMessage.style.color = 'red';
                modalCartMessage.style.display = 'block';
            });
        });

    } else {
        console.error("--- LỖI NGHIÊM TRỌNG ---");
        console.error("JavaScript không tìm thấy nút 'modal-add-to-cart' trên trang.");
        console.error("Hãy kiểm tra lại ID trong file index.php và script.js.");
    }

=======
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

>>>>>>> 4787611c99a7712928bbe5b5f8582a012465954e
});