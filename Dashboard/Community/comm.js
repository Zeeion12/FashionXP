// Burger Button
function menuFunction() {
    const menuBtn = document.getElementById("nav-container-list");

    if (menuBtn.classList.contains("responsive")) {
        menuBtn.classList.remove("responsive");
    } else {
        menuBtn.classList.add("responsive");
    }
}


// Dropdown Menu
document.addEventListener('DOMContentLoaded', function () {
    const profileBtn = document.querySelector('.profile-btn');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    profileBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
    });

    document.addEventListener('click', function (e) {
        if (!dropdownMenu.contains(e.target) && !profileBtn.contains(e.target)) {
            dropdownMenu.classList.remove('show');
        }
    });
});

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
setInterval(nextSlide, 8000);

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
            imageList.scrollBy({ left: scrollAmount, behavior: "smooth" })
        })
    })

    const handleSlideButtons = () => {
        slideButtons[0].style.display = imageList.scrollLeft <= 0 ? "none" : "block";
        slideButtons[1].style.display = imageList.scrollLeft >= maxScrollLeft ? "none" : "block";
    }

    imageList.addEventListener("scroll", () => {
        handleSlideButtons();
    })
}

window.addEventListener("load", initSlider);