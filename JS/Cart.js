function addToCart(item) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let existingItem = cart.find(cartItem => cartItem.id === item.id);
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        item.quantity = 1;
        cart.push(item);
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
}

function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let itemCount = cart.reduce((count, item) => count + item.quantity, 0);
    document.querySelector('.cart-count').innerText = itemCount;
}

function openCart() {
    document.getElementById('cart-overlay').classList.add('open');
    loadCartItems();
}

function closeCart() {
    document.getElementById('cart-overlay').classList.remove('open');
}

function loadCartItems() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let cartItemsContainer = document.getElementById('cart-items');
    cartItemsContainer.innerHTML = '';

    let totalPrice = 0;

    cart.forEach(item => {
        let itemDiv = document.createElement('div');
        itemDiv.classList.add('cart-item');
        itemDiv.innerHTML = `
            <img src="${item.image_url}" alt="${item.name}">
            <div class="cart-item-info">
                <h4>${item.name}</h4>
                <p>Price: Rs. ${item.price}</p>
            </div>
            <div class="cart-item-controls">
                <button onclick="updateQuantity(${item.id}, -1)">-</button>
                <input type="text" value="${item.quantity}" readonly>
                <button onclick="updateQuantity(${item.id}, 1)">+</button>
            </div>
        `;
        cartItemsContainer.appendChild(itemDiv);
        totalPrice += parseFloat(item.price) * item.quantity;
    });

    document.getElementById('cart-total-price').innerText = totalPrice.toFixed(2);
}

function updateQuantity(itemId, change) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let item = cart.find(cartItem => cartItem.id === itemId);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            cart = cart.filter(cartItem => cartItem.id !== itemId);
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCartItems();
        updateCartCount();
    }
}

function checkout() {
    // Redirect to the checkout page or handle checkout process
    window.location.href = 'checkout_page.php';
}
