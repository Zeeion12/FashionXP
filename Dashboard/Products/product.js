document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('.slider');
    const slides = document.querySelectorAll('.product-card');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');
    
    let currentIndex = 0;
    const slidesToShow = getSlidesToShow();
    const totalSlides = slides.length;
    
    // Clone slides for infinite effect
    for (let i = 0; i < slidesToShow; i++) {
        slider.appendChild(slides[i].cloneNode(true));
        slider.insertBefore(slides[totalSlides - 1 - i].cloneNode(true), slides[0]);
    }
    
    // Set initial position
    updateSliderPosition(false);
    
    function getSlidesToShow() {
        if (window.innerWidth <= 640) return 1;
        if (window.innerWidth <= 1024) return 2;
        return 3;
    }
    
    function updateSliderPosition(transition = true) {
        const slideWidth = slider.offsetWidth / slidesToShow;
        slider.style.transition = transition ? 'transform 0.5s ease-in-out' : 'none';
        slider.style.transform = `translateX(${-(currentIndex + slidesToShow) * slideWidth}px)`;
    }
    
    function moveSlider(direction) {
        if (direction === 'next') {
            currentIndex++;
            if (currentIndex >= totalSlides) {
                setTimeout(() => {
                    currentIndex = 0;
                    updateSliderPosition(false);
                }, 500);
            }
        } else {
            currentIndex--;
            if (currentIndex < 0) {
                setTimeout(() => {
                    currentIndex = totalSlides - 1;
                    updateSliderPosition(false);
                }, 500);
            }
        }
        updateSliderPosition();
    }
    
    // Event Listeners
    prevButton.addEventListener('click', () => moveSlider('prev'));
    nextButton.addEventListener('click', () => moveSlider('next'));
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            const newSlidesToShow = getSlidesToShow();
            if (newSlidesToShow !== slidesToShow) {
                location.reload(); // Refresh page on breakpoint change
            }
        }, 250);
    });
    
    // Touch events for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    slider.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    slider.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        if (touchStartX - touchEndX > 50) {
            moveSlider('next');
        } else if (touchEndX - touchStartX > 50) {
            moveSlider('prev');
        }
    });
});


// DropDown Menu
document.addEventListener('DOMContentLoaded', function() {
    const profileBtn = document.querySelector('.profile-btn');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    profileBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
    });

    document.addEventListener('click', function(e) {
        if (!dropdownMenu.contains(e.target) && !profileBtn.contains(e.target)) {
            dropdownMenu.classList.remove('show');
        }
    });
});

// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.querySelector('.nav-menu-btn');
    const navList = document.querySelector('.nav-list');

    menuBtn.addEventListener('click', function() {
        navList.classList.toggle('active');
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!navList.contains(e.target) && !menuBtn.contains(e.target)) {
            navList.classList.remove('active');
        }
    });
});


// Bagian Display Product
document.addEventListener('DOMContentLoaded', function() {
    // Handle size selection
    const sizeOptions = document.querySelectorAll('.size-option');
    sizeOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            sizeOptions.forEach(opt => opt.classList.remove('selected'));
            // Add selected class to clicked option
            this.classList.add('selected');
        });
    });
});


// Fungsi Untuk Menambahkan barang ke keranjang di productpage
document.querySelectorAll(".cart-button").forEach(button => {
    button.addEventListener("click", function () {
        const productId = this.dataset.productId;

        fetch("product.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert("Produk berhasil ditambahkan ke keranjang!");
            } else {
                alert(data.message || "Gagal menambahkan produk ke keranjang.");
            }
        })
        .catch(error => console.error("Error:", error));
    });
});


// Fungsi Untuk Menambahkan barang ke keranjang  Untuk Produk di display
function showAlert(event) {
    const form = event.target;
     // Tampilkan alert
    alert("Produk berhasil ditambahkan ke keranjang!");
}
 // Event listener untuk tombol "Add To Cart"
 document.querySelectorAll(".add_to_cart").forEach(button => {
    button.addEventListener("click", function () {
        const productId = this.dataset.productId;
         fetch("displayproduct.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                showAlert(); // Panggil fungsi alert
            } else {
                alert(data.message || "Gagal menambahkan produk ke keranjang.");
            }
        })
        .catch(error => console.error("Error:", error));
    });
 });


