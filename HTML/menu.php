<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Burger House</title>
    <link rel="icon" type="image" href="../Images/Icon.png">
    <link rel="stylesheet" href="../CSS/Menu.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php
session_start(); 

if (isset($_SESSION['role'])) {
    if ($_SESSION['user_role'] === 'admin') {
        include('AdminHeader.php');
    } elseif ($_SESSION['user_role'] === 'staff') {
        include('StaffHeader.php');
    } else {
        include('Header.php');
    }
} else {
    include('Header.php'); 
}
?>
<div class="categories-container">
    <div class="category-icon" onclick="filterCategory('Burger')">
        <img src="../Images/srilankan.png" alt="Burger">
        <p>Burger</p>
    </div>
    <div class="category-icon" onclick="filterCategory('Pasta')">
        <img src="../Images/chinese.png" alt="Pasta">
        <p>Pasta</p>
    </div>
    <div class="category-icon" onclick="filterCategory('Hot Dog')">
        <img src="../Images/italian.png" alt="Hot Dog">
        <p>Hot Dog</p>
    </div>
    <div class="category-icon" onclick="filterCategory('Submarine')">
        <img src="../Images/special.png" alt="Submarine">
        <p>Submarine</p>
    </div>
    <div class="category-icon" onclick="filterCategory('Beverages')">
        <img src="../Images/beverages.png" alt="Beverages">
        <p>Beverages</p>
    </div>
</div>
<div class="menu-container">
    <div class="menu-header">
        <select class="category-filter" onchange="filterCategory(this.value)">
            <option value="all">All Categories</option>
            <option value="Burger">Burger</option>
            <option value="Pasta">Pasta</option>
            <option value="Hot Dog">Hot Dog</option>
            <option value="Submarine">Submarine</option>
            <option value="Beverages">Beverages</option>
        </select>
        <input type="text" class="search-input" placeholder="Search Food..." onkeyup="searchFood(this.value)">
    </div>
    <div id="menu-items" class="menu-items">
        <!-- Items will be dynamically populated here -->
    </div>
</div>

<div class="cart-button" onclick="toggleCartTab()">
    <span class="cart-count">0</span>
    <img src="../Images/cart.png" alt="Cart">
</div>

<div class="cartTab">
    <button class="close-btn" onclick="closeCart()">×</button>
    <div class="listCart">
        <!-- Cart items will be dynamically populated here -->
    </div>
    <div class="cart-total">
        Total Price: Rs. <span id="total-price">0.00</span>
    </div>
    <button class="checkout-btn" onclick="checkout()">Check Out</button>
</div>

<script>
        let cart = [];
let userName = ''; 

$(document).ready(function() {
    initializeMenu();
});

function initializeMenu() {
    $.ajax({
        url: 'fetch_menu_items.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            let menuItems = {};
            data.forEach(function(item) {
                if (!menuItems[item.category]) {
                    menuItems[item.category] = [];
                }
                menuItems[item.category].push(item);
            });

            let menuHtml = '';
            for (let category in menuItems) {
                menuHtml += `<div class="category" id="${category}">`;
                menuHtml += `<h2>${category}</h2>`; 
                menuItems[category].forEach(function(item) {
                    menuHtml += `<div class="category-item">
                        <img src="${item.image_url}" alt="${item.name}">
                        <div class="category-item-info">
                            <h3>${item.name}</h3>
                            <p class="category-item-description">${item.description}</p>
                            <p class="category-item-price">${item.price}</p>
                            <button class="add-to-cart-btn" onclick='addToCart(${JSON.stringify(item)})'>Add to Cart</button>
                        </div>
                    </div>`;
                });
                menuHtml += `</div>`;
            }
            $('#menu-items').html(menuHtml);
            filterCategory('all'); 
            updateCartCount(); 
        },
        error: function(xhr, status, error) {
            console.error('Error fetching menu items:', error);
        }
    });
}

function filterCategory(category) {
    var categories = document.getElementsByClassName('category');
    for (var i = 0; i < categories.length; i++) {
        categories[i].style.display = category === 'all' || categories[i].id === category ? 'block' : 'none';
    }

    var selectElement = document.querySelector('.category-filter');
    if (selectElement.value !== category) {
        selectElement.value = category;
    }
}

function searchFood(query) {
    var items = document.getElementsByClassName('category-item');
    for (var i = 0; i < items.length; i++) {
        var itemName = items[i].getElementsByTagName('h3')[0].innerText.toLowerCase();
        items[i].style.display = itemName.includes(query.toLowerCase()) ? 'flex' : 'none';
    }
}

function addToCart(item) {
    checkLoginStatus(function(loggedIn) {
        if (!loggedIn) {
            alert('Please log in to add items to the cart.');
            window.location.href = 'login.php';
            return;
        }

        const existingItem = cart.find(cartItem => cartItem.name === item.name);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            item.quantity = 1;
            cart.push(item);
        }

        updateCartCount();
        renderCartItems();

        $.ajax({
            url: 'add_to_cart.php',
            method: 'POST',
            data: {
                user_name: userName,
                item_name: item.name,
                quantity: item.quantity,
                image_url: item.image_url
            },
            success: function(response) {
                console.log('Cart updated successfully');
            },
            error: function(xhr, status, error) {
                console.error('Error updating cart:', error);
            }
        });
    });
}

function updateCartCount() {
    $('.cart-count').text(cart.reduce((total, item) => total + item.quantity, 0));
}

function closeCart() {
    document.querySelector('.cartTab').classList.remove('active');
}

function renderCartItems() {
    let cartHtml = '';
    let totalPrice = 0;
    cart.forEach(function(item) {
        cartHtml += `<div class="cart-item">
            <img src="${item.image_url}" alt="${item.name}">
            <div class="cart-item-info">
                <h3>${item.name}</h3>
                <p>Price: Rs. ${item.price}</p>
                <div class="cart-buttons">
                    <button onclick="changeQuantity('${item.name}', -1)">-</button>
                    <span class="quantity">${item.quantity}</span>
                    <button onclick="changeQuantity('${item.name}', 1)">+</button>
                    <button class="delete-btn" onclick="removeFromCart('${item.name}')">Delete</button>
                </div>
            </div>
        </div>`;
        totalPrice += item.price * item.quantity;
    });
    document.querySelector('.listCart').innerHTML = cartHtml;
    document.getElementById('total-price').textContent = totalPrice.toFixed(2);
}

function changeQuantity(name, delta) {
    const item = cart.find(cartItem => cartItem.name === name);
    if (item) {
        item.quantity += delta;
        if (item.quantity <= 0) {
            removeFromCart(name);
        } else {
            renderCartItems();
            updateCartCount();
        }
    }
}

function removeFromCart(name) {
    cart = cart.filter(cartItem => cartItem.name !== name);
    renderCartItems();
    updateCartCount();
}

function checkout() {
            if (cart.length === 0) {
                alert('Your cart is empty. Add some items before proceeding to checkout.');
                return;
            }

            const orderData = {
                user_name: userName,
                order_date: new Date().toISOString().split('T')[0],  // Date in YYYY-MM-DD format
                order_time: new Date().toLocaleTimeString(),          // Time in HH:MM:SS format
                cart: JSON.stringify(cart)
            };


            // Send the order data to process_checkout.php
            $.ajax({
                url: 'process_checkout.php',
                method: 'POST',
                data: orderData,
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        cart = []; // Clear cart after successful order
                        updateCartCount();
                        window.location.href = `bill.php?order_id=${data.order_id}`;
                    } else {
                        alert('Error placing order: ' + data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error during checkout:', error);
                }
            });
        }

function toggleCartTab() {
    checkLoginStatus(function(loggedIn) {
        if (!loggedIn) {
            alert('Please log in to view your cart.');
            window.location.href = 'UserProfile.php';
            return;
        }
        $('.cartTab').toggleClass('active');
        if ($('.cartTab').hasClass('active')) {
            fetchCartItems();
        }
    });
}

function fetchCartItems() {
    $.ajax({
        url: 'fetch_cart_items.php',
        method: 'POST',
        data: { user_name: userName },
        dataType: 'json',
        success: function(data) {
            let cartHtml = '';
            data.forEach(function(item) {
                cartHtml += `<div class="cart-item">
                    <img src="${item.image_url}" alt="${item.item_name}">
                    <div class="cart-item-info">
                        <h3>${item.item_name}</h3>
                        <p>Quantity: ${item.quantity}</p>
                    </div>
                </div>`;
            });
            $('.listCart').html(cartHtml);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching cart items:', error);
        }
    });
}

function checkLoginStatus(callback) {
    $.ajax({
        url: 'check_login.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            callback(response.loggedIn);
        },
        error: function(xhr, status, error) {
            console.error('Error checking login status:', error);
            callback(false);
        }
    });
}

    </script>
<script>


    </script>
    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>

<?php include('footer.php') ?>
</body>
</html>
