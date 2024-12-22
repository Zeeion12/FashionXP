// Sidebar
document.addEventListener('DOMContentLoaded', function() {
    const navItems = document.querySelectorAll('.nav-item');
    const contentSections = document.querySelectorAll('.content-section');

    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all nav items
            navItems.forEach(nav => nav.classList.remove('active'));
            
            // Add active class to clicked nav item
            this.classList.add('active');
            
            // Hide all content sections
            contentSections.forEach(section => section.classList.remove('active'));
            
            // Show the selected content section
            const sectionId = this.getAttribute('data-section');
            document.getElementById(sectionId).classList.add('active');
        });
    });

    // Password visibility toggle
    const passwordToggles = document.querySelectorAll('.password-toggle');
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('bx-hide');
                this.classList.add('bx-show');
            } else {
                input.type = 'password';
                this.classList.remove('bx-show');
                this.classList.add('bx-hide');
            }
        });
    });
});

// Purchased Item
document.addEventListener('DOMContentLoaded', function() {
    // Simulate having purchased items (true = has items, false = empty)
    const hasPurchasedItems = false; // Change this to true to see purchased items

    const purchasedEmpty = document.getElementById('purchasedEmpty');
    const purchasedItems = document.getElementById('purchasedItems');

    // Toggle between empty state and purchased items
    if (hasPurchasedItems) {
        purchasedEmpty.style.display = 'none';
        purchasedItems.style.display = 'block';
    } else {
        purchasedEmpty.style.display = 'block';
        purchasedItems.style.display = 'none';
    }

    // Handle "Buy Again" button clicks
    const buyAgainButtons = document.querySelectorAll('.buy-again');
    buyAgainButtons.forEach(button => {
        button.addEventListener('click', function() {
            alert('Item added to cart!');
        });
    });
});


// Add this at the end of your existing JavaScript
function menuFunction() {
    const navList = document.getElementById('nav-container-list');
    navList.classList.toggle('active');
}

// Optional: Close menu when clicking outside
document.addEventListener('click', function(event) {
    const navList = document.getElementById('nav-container-list');
    const menuBtn = document.querySelector('.nav-menu-btn');
    
    if (!navList.contains(event.target) && !menuBtn.contains(event.target)) {
        navList.classList.remove('active');
    }
});


// Profile Pictures
function selectProfilePicture(url) {
    document.querySelector('.profile-picture img').src = url; 
    document.getElementById('profilePicturePopup').style.display = 'none';
    document.getElementById('profile_picture').value = url;
    updateNavbarProfilePicture(url); // Perbarui gambar di navbar
}
// Dashboard/Profile/profile.js
function openProfilePicturePopup() {
    document.getElementById('profilePicturePopup').style.display = 'block'; // Menampilkan popup
}
function updateNavbarProfilePicture(newImageUrl) {
    const navbarImage = document.querySelector('.profile-dropdown-img');
    if (navbarImage) {
        navbarImage.src = newImageUrl;
    }
}