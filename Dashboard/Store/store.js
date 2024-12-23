document.addEventListener('DOMContentLoaded', function() {
    // Navigation functionality
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

    // Product form handling
    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            
            fetch('store.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    // Redirect to refresh the page and show updated data
                    window.location.href = 'store.php';
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error updating the product. Please try again.');
            });
        });
    }

    // Product deletion handling
    const deleteButtons = document.querySelectorAll('.delete-product-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const confirmed = confirm("Are you sure you want to delete this product?");
            if (confirmed) {
                window.location.href = this.href;
            }
        });
    });

    // Product editing handling
    const editButtons = document.querySelectorAll('.edit-product-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
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

                // Add or update the edit_id input
                let editIdInput = document.getElementById('edit_id');
                if (!editIdInput) {
                    editIdInput = document.createElement('input');
                    editIdInput.type = 'hidden';
                    editIdInput.name = 'edit_id';
                    editIdInput.id = 'edit_id';
                    document.getElementById('productForm').appendChild(editIdInput);
                }
                editIdInput.value = productId;

                // Show the form section
                document.getElementById('form-brand').style.display = 'block';
                
                // Update form title to indicate editing mode
                const formTitle = document.querySelector('#form-brand h2');
                if (formTitle) {
                    formTitle.textContent = 'Edit Product';
                }
            }
        });
    });

    // Community post editing handling
    const communityEditButtons = document.querySelectorAll('.edit-button');
    communityEditButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const confirmed = confirm("Are you sure you want to edit this community post?");
            if (confirmed) {
                const name = this.getAttribute('data-name');
                const description = this.getAttribute('data-description');
                const existingPhoto = this.getAttribute('data-photo');
                
                // Populate the form with existing data
                document.getElementById('nama_produk').value = name;
                document.getElementById('deskripsi_produk').value = description;
                if (document.getElementById('existing_photo')) {
                    document.getElementById('existing_photo').value = existingPhoto;
                }
                
                // Change the form title to indicate editing
                document.getElementById('form-title').innerText = 'Edit Your Community Post';
                
                // Show the form
                document.getElementById('communityForm').style.display = 'block';
                if (document.getElementById('saveChangesBtn')) {
                    document.getElementById('saveChangesBtn').style.display = 'inline-block';
                }
            }
        });
    });

    // Community post deletion handling
    const communityDeleteButtons = document.querySelectorAll('.delete-community-btn');
    communityDeleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const confirmed = confirm("Are you sure you want to delete this community post?");
            if (confirmed) {
                window.location.href = this.href;
            }
        });
    });

    // Product grid initialization
    const productGrid = document.getElementById('productGrid');
    const emptyState = document.getElementById('emptyState');

    if (productGrid && productGrid.children.length > 0) {
        productGrid.style.display = 'block';
        if (emptyState) emptyState.style.display = 'none';
    } else if (productGrid) {
        productGrid.style.display = 'none';
        if (emptyState) emptyState.style.display = 'block';
    }
});

// Mobile menu functionality
function menuFunction() {
    const navList = document.getElementById('nav-container-list');
    navList.classList.toggle('active');
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    const navList = document.getElementById('nav-container-list');
    const menuBtn = document.querySelector('.nav-menu-btn');
    
    if (navList && menuBtn && !navList.contains(event.target) && !menuBtn.contains(event.target)) {
        navList.classList.remove('active');
    }
});