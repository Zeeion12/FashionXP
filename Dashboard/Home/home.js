// JavaScript for auto image slider
const sliders = document.querySelectorAll('.slider1, .slider2, .slider3, .slider4');
const radioButtons = document.querySelectorAll('input[type="radio"]');
let currentSlide = 0;

function showSlide(index) {
    sliders.forEach((slider, i) => {
        slider.classList.remove('active');
        radioButtons[i].checked = false;
    });
    sliders[index].classList.add('active');
    radioButtons[index].checked = true;
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % sliders.length;
    showSlide(currentSlide);
}

// Auto slide every 7 seconds
setInterval(nextSlide, 3000);

// Manual control with radio buttons
radioButtons.forEach((radio, index) => {
    radio.addEventListener('change', () => {
        currentSlide = index;
        showSlide(currentSlide);
    });
});

// Initialize the first slide
showSlide(currentSlide);


// Category Slider
const initSlider = () => {
    const imageList = document.querySelector(".categorys")
    const slideButtons = document.querySelectorAll(".button-slide");
    const maxScrollLeft = imageList.scrollWidth - imageList.clientWidth;

    // Slide Image tergantung tombol yang ditekan
    slideButtons.forEach(button => {
        button.addEventListener("click", () => {
            const direction = button.id === "prev-slide" ? -1 : 1;
            const scrollAmount = imageList.clientWidth * direction;
            imageList.scrollBy({ left: scrollAmount, behavior: "smooth"})
        })
    })  
    
    const handleSlideButtons = () => {
        slideButtons[0].style.display = imageList.scrollLeft <= 0 ? "none" : "block";
        slideButtons[1].style.display = imageList.scrollLeft >=  maxScrollLeft ? "none" : "block";
    }
    
    imageList.addEventListener("scroll", () => {
        handleSlideButtons();
    })
}

window.addEventListener("load", initSlider);

// Dropdown Menu
document.addEventListener('DOMContentLoaded', function() {
    // Existing dropdown toggle code
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

    const profileImage = document.querySelector('.profile-dropdown-img');
    if (profileImage) {
        profileImage.onerror = function() {
            this.src = '/FashionXP/Dashboard/Home/Home-img/Profile/User.png';
        };
    }
});

// Tambahkan fungsi untuk menu mobile
function menuFunction() {
    const navList = document.getElementById('nav-container-list');
    navList.classList.toggle('active');
}

// Optional: Tutup menu saat mengklik di luar menu
document.addEventListener('click', function(event) {
    const navList = document.getElementById('nav-container-list');
    const menuBtn = document.querySelector('.nav-menu-btn');
    
    if (!navList.contains(event.target) && !menuBtn.contains(event.target)) {
        navList.classList.remove('active');
    }
});

// Tutup menu saat link diklik
document.querySelectorAll('.nav-list a').forEach(link => {
    link.addEventListener('click', () => {
        document.getElementById('nav-container-list').classList.remove('active');
    });
});



// Fungsi Untuk Menambahkan barang ke keranjang
document.querySelectorAll(".cart-button").forEach(button => {
    button.addEventListener("click", function () {
        const productId = this.dataset.productId;

        fetch("home.php", {
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


