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
            contentSections.forEach(section => section.style.display = 'none');
            
            // Show the selected content section
            const sectionId = this.getAttribute('data-section');
            document.getElementById(sectionId).style.display = 'block';
        });
    });

    // Untuk Update Your Store
    const productForm = document.getElementById('productForm');
    productForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah pengiriman default

        const formData = new FormData(productForm);
        
        fetch('store.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Tampilkan produk baru di grid
            document.getElementById('productsContainer').innerHTML += data; // Tambahkan produk baru
            productForm.reset(); // Reset formulir

            // Cek apakah ada produk
            const emptyState = document.getElementById('emptyState');
            if (emptyState) {
                emptyState.style.display = 'none'; // Sembunyikan pesan "Nothing Here"
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Konfirmasi penghapusan produk
    const deleteButtons = document.querySelectorAll('.delete-product-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah default link
            const confirmed = confirm("Are you sure you want to delete this product?");
            if (confirmed) {
                window.location.href = this.href; // Redirect ke URL penghapusan
            }
        });
    });

    
    

    // Konfirmasi pengeditan produk
    const editButtons = document.querySelectorAll('.edit-product-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah default link
            const confirmed = confirm("Are you sure you want to edit this product?");
            if (confirmed) {
                const productId = this.getAttribute('data-id');
                const brand = this.getAttribute('data-brand');
                const name = this.getAttribute('data-name');
                const price = this.getAttribute('data-price');
                const stock = this.getAttribute('data-stock');
                const category = this.getAttribute('data-category');
                const size = this.getAttribute('data-size');
                const description = this.getAttribute('data-description');

                // Populate the form with existing data
                document.getElementById('brandProduct').value = brand;
                document.getElementById('productName').value = name;
                document.getElementById('productPrice').value = price;
                document.getElementById('productStock').value = stock;
                document.getElementById('productSize').value = category;
                document.getElementById('sizeChart').value = size;
                document.getElementById('productDescription').value = description;

                // Set the edit ID in the form
                const productForm = document.getElementById('productForm');
                const editIdInput = document.createElement('input');
                editIdInput.type = 'hidden';
                editIdInput.name = 'edit_id';
                editIdInput.value = productId;
                productForm.appendChild(editIdInput);

                // Show the form
                document.getElementById('form-brand').style.display = 'block';
            }
        });
    });

    // Initialize view
    if (products.length > 0) {
        showProductGrid();
        renderProducts();
    } else {
        emptyState.style.display = 'block';
    }
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


// Add Product
document.addEventListener('DOMContentLoaded', function () {
    const productGrid = document.getElementById('productGrid');
    const emptyState = document.getElementById('emptyState');

    if (productGrid && productGrid.children.length > 0) {
        productGrid.style.display = 'block';
        emptyState.style.display = 'none';
    } else {
        productGrid.style.display = 'none';
        emptyState.style.display = 'block';
    }
});


// Konfirmasi pengeditan produk
const editButtons = document.querySelectorAll('.edit-button');
editButtons.forEach(button => {
   button.addEventListener('click', function(e) {
       e.preventDefault(); // Mencegah default link
       const confirmed = confirm("Are you sure you want to edit this community post?");
       if (confirmed) {
           const name = this.getAttribute('data-name');
           const description = this.getAttribute('data-description');
           const existingPhoto = this.getAttribute('data-photo'); // Assuming you have a data attribute for the photo
           
           // Populate the form with existing data
           document.getElementById('nama_produk').value = name;
           document.getElementById('deskripsi_produk').value = description;
           document.getElementById('existing_photo').value = existingPhoto; // Set existing photo
           
           // Change the form title to indicate editing
           document.getElementById('form-title').innerText = 'Edit Your Community Post';
           
           // Show the form
           document.getElementById('communityForm').style.display = 'block';
           document.getElementById('saveChangesBtn').style.display = 'inline-block'; // Tampilkan tombol Save Changes
       }
   });
});

// Konfirmasi penghapusan komunitas
const deleteButtons = document.querySelectorAll('.delete-community-btn');
deleteButtons.forEach(button => {
   button.addEventListener('click', function(e) {
       e.preventDefault(); // Mencegah default link
       const confirmed = confirm("Are you sure you want to delete this community post?");
       if (confirmed) {
           window.location.href = this.href; // Redirect ke URL penghapusan
       }
   });
});


