// Fitur Tmbah kurang Quantity control functionality
document.querySelectorAll(".quantity-btn").forEach(button => {
    button.addEventListener("click", function() {
        const productId = this.dataset.productId;
        const action = this.classList.contains("add") ? "add" : "subtract";
        const quantityElement = this.parentElement.querySelector(".quantity");
        const currentQuantity = parseInt(quantityElement.textContent);
        
        // Don't proceed if button is disabled
        if (this.disabled) {
            return;
        }

        fetch("cart.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `action=${action}&product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                // Update quantity display
                quantityElement.textContent = data.newQuantity;
                
                // Update total price
                const priceElement = this.closest('.cart-item').querySelector('.price');
                const price = parseFloat(priceElement.textContent.replace('Rp ', '').replace(/\./g, ''));
                const totalElement = price * data.newQuantity;totalElement.textContent = `Rp ${(price * data.newQuantity).toLocaleString('id-ID')}`;
                
                // Update button states
                const addBtn = this.parentElement.querySelector('.add');
                const subtractBtn = this.parentElement.querySelector('.subtract');
                
                // Disable subtract if at minimum
                subtractBtn.disabled = data.newQuantity <= 1;
                
                // Disable add if at stock limit
                addBtn.disabled = data.newQuantity >= data.stock;
                
                // Update cart totals
                updateCartTotals();

                // Add page refresh after updates are complete
                window.location.reload();
            } else {
                alert(data.message || "Error updating quantity");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred while updating the quantity");
        });
    });
});


// Fitur Hapus
document.addEventListener("DOMContentLoaded", () => {
    const deleteButtons = document.querySelectorAll(".delete-list");

    deleteButtons.forEach(button => {
        button.addEventListener("click", async function() {
            const productId = this.getAttribute("data-product-id");
            const cartItem = this.closest('.cart-item');
            
            if (confirm("Are you sure you want to remove this item from your cart?")) {
                try {
                    const response = await fetch("cart.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: `action=delete&product_id=${productId}`
                    });

                    const data = await response.json();
                    
                    if (data.status === "success") {
                        // Remove the item from DOM
                        cartItem.remove();
                        
                        // Update cart count
                        const cartCount = document.querySelector('.cart-header span');
                        const currentCount = parseInt(cartCount.textContent);
                        cartCount.textContent = `${currentCount - 1} Items`;
                        
                        // Recalculate totals if needed
                        updateCartTotals();
                    } else {
                        alert("Failed to remove item from cart");
                    }
                } catch (error) {
                    console.error("Error:", error);
                    alert("An error occurred while removing the item");
                }
            }
        });
    });
});

// Add this helper function to update totals
function updateCartTotals() {
    const cartItems = document.querySelectorAll('.cart-item');
    let total = 0;
    
    cartItems.forEach(item => {
        const priceText = item.querySelector('.total').textContent;
        const price = parseInt(priceText.replace('Rp ', ''));
        total += price;
    });
    
    // Update all total displays with the simple format
    const totalElements = document.querySelectorAll('.total-amount .summary-row span:last-child, .summary-row:last-child span:last-child');
    totalElements.forEach(element => {
        element.textContent = `Rp ${total}`;
    });
    
    // Update subtotal with the simple format
    const subtotalElement = document.querySelector('.summary-row h3');
    if (subtotalElement) {
        subtotalElement.textContent = `Subtotal: Rp ${total}`;
    }
}

