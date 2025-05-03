// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    initialiseCart();
    initialiseAddToCartButtons();
    initialiseFormValidation();
    initialiseCategoryHoverEffects();
    initialiseProductHoverEffects();
});

// Initialise cart functionality
function initialiseCart() {
    const cartIcon = document.querySelector('.cart-icon');
    const cartContainer = document.querySelector('.cart-container');
    const closeCartButton = document.querySelector('.close-cart');
    
    // Toggle cart visibility when cart icon is clicked
    if (cartIcon) {
        cartIcon.addEventListener('click', function() {
            cartContainer.classList.add('active');
        });
    }
    
    // Close cart when close button is clicked
    if (closeCartButton) {
        closeCartButton.addEventListener('click', function() {
            cartContainer.classList.remove('active');
        });
    }
    
    // Initialise quantity buttons
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    quantityBtns.forEach(btn => {
        btn.addEventListener('click', handleQuantityChange);
    });
    
    // Initialise remove buttons
    const removeButtons = document.querySelectorAll('.remove-item');
    removeButtons.forEach(btn => {
        btn.addEventListener('click', handleRemoveItem);
    });
    
    // Initialise clear cart button
    const clearCartButton = document.querySelector('.clear-cart');
    if (clearCartButton) {
        clearCartButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear your cart?')) {
                window.location.href = 'cart.php?action=clear';
            }
        });
    }
    
    // Initialise checkout button
    const checkoutButton = document.querySelector('.checkout-btn');
    if (checkoutButton) {
        checkoutButton.addEventListener('click', function() {
            window.location.href = 'checkout.php';
        });
    }
}

// Handle quantity changes in cart
function handleQuantityChange() {
    const input = this.parentElement.querySelector('.quantity-input');
    const productId = input.dataset.productId;
    
    if (this.classList.contains('decrease')) {
        if (input.value > 1) {
            input.value = parseInt(input.value) - 1;
        }
    } else if (this.classList.contains('increase')) {
        input.value = parseInt(input.value) + 1;
    }
    
    updateCartItem(productId, input.value);
}

// Update cart item quantity
function updateCartItem(productId, quantity) {
    window.location.href = `cart.php?action=update&product_id=${productId}&quantity=${quantity}`;
}

// Handle removing items from cart
function handleRemoveItem() {
    const productId = this.dataset.productId;
    
    if (confirm('Are you sure you want to remove this item?')) {
        window.location.href = `cart.php?action=remove&product_id=${productId}`;
    }
}

// Initialise add to cart buttons
function initialiseAddToCartButtons() {
    const addToCartButtons = document.getElementsByClassName('add-to-cart');
    
    for (let i = 0; i < addToCartButtons.length; i++) {
        addToCartButtons[i].addEventListener('click', function() {
            const productId = this.dataset.productId;
            window.location.href = `index.php?action=add_to_cart&product_id=${productId}`;
        });
    }
}

// Initialise form validation
function initialiseFormValidation() {
    const checkoutForm = document.getElementById('checkout-form');
    
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(event) {
            const name = document.getElementById('name').value;
            const street = document.getElementById('street').value;
            const city = document.getElementById('city').value;
            const state = document.getElementById('state').value;
            const mobile = document.getElementById('mobile').value;
            const email = document.getElementById('email').value;
            
            let isValid = true;
            clearErrorMessages();
            
            if (name.trim() === '') {
                displayErrorMessage('name', 'Name is required');
                isValid = false;
            }
            
            if (street.trim() === '') {
                displayErrorMessage('street', 'Street address is required');
                isValid = false;
            }
            
            if (city.trim() === '') {
                displayErrorMessage('city', 'City/Suburb is required');
                isValid = false;
            }
            
            if (state === 'select') {
                displayErrorMessage('state', 'Please select a state/territory');
                isValid = false;
            }
            
            if (mobile.trim() === '') {
                displayErrorMessage('mobile', 'Mobile number is required');
                isValid = false;
            } else if (!validateMobile(mobile)) {
                displayErrorMessage('mobile', 'Mobile number must be 10 digits starting with 04');
                isValid = false;
            }
            
            if (email.trim() === '') {
                displayErrorMessage('email', 'Email address is required');
                isValid = false;
            } else if (!validateEmail(email)) {
                displayErrorMessage('email', 'Invalid email format');
                isValid = false;
            }
            
            if (!isValid) {
                event.preventDefault();
            }
        });
    }
}

// Validate email format
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Validate Australian mobile number
function validateMobile(mobile) {
    const re = /^04\d{8}$/;
    return re.test(mobile);
}

// Display error message
function displayErrorMessage(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorElement = document.createElement('div');
    errorElement.className = 'error-message';
    errorElement.textContent = message;
    
    field.parentElement.appendChild(errorElement);
    field.classList.add('error');
}

// Clear all error messages
function clearErrorMessages() {
    const errorMessages = document.querySelectorAll('.error-message');
    const errorFields = document.querySelectorAll('.error');
    
    errorMessages.forEach(element => {
        element.remove();
    });
    
    errorFields.forEach(field => {
        field.classList.remove('error');
    });
}

// Initialise category hover effects
function initialiseCategoryHoverEffects() {
    const categoryLinks = document.querySelectorAll('.categories li > a');
    
    categoryLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.classList.add('hover');
        });
        
        link.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
        });
    });
}

// Initialise product hover effects
function initialiseProductHoverEffects() {
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('hover');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
        });
    });
}