<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Initialise cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Get cart count
$cartCount = 0;
foreach ($_SESSION['cart'] as $quantity) {
    $cartCount += $quantity;
}

// Check if we're on cart, checkout, or confirmation page
$current_page = basename($_SERVER['PHP_SELF']);
$hide_search_and_categories = in_array($current_page, array('cart.php', 'checkout.php', 'order_confirmation.php'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Grocery Store</title>
    <!-- Extenal CSS references -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container header-container">
            <div class="logo">
                <a href="index.php">
                    <img src="images/logo.png" alt="Online Grocery Store Logo">
                </a>
            </div>
            
            <?php if (!$hide_search_and_categories): ?>
            <div class="search-container">
                <form action="search.php" method="GET">
                    <input type="text" name="keyword" placeholder="Search products" required>
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <?php endif; ?>
            
            <div class="cart-icon" id="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <?php if ($cartCount > 0): ?>
                <span class="cart-count"><?php echo $cartCount; ?></span>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <?php if (!$hide_search_and_categories): ?>
    <!-- Categories Navigation -->
    <div class="categories">
        <div class="container">
            <ul>
                <?php
                $categories = getCategories();
                foreach ($categories as $headerCategory => $headerSubcategories):
                ?>
                <li>
                    <a href="category.php?category=<?php echo urlencode($headerCategory); ?>"><?php echo $headerCategory; ?></a>
                    <ul class="subcategories">
                        <?php foreach ($headerSubcategories as $headerSubcategory): ?>
                        <li>
                            <a href="category.php?category=<?php echo urlencode($headerCategory); ?>&subcategory=<?php echo urlencode($headerSubcategory); ?>">
                                <?php echo $headerSubcategory; ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Shopping Cart Sidebar -->
    <div class="cart-container" id="cart-container">
        <div class="cart-header">
            <h2>Your Shopping Cart</h2>
            <button class="close-cart" id="close-cart"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="cart-items">
            <?php
            $cartTotal = 0;
            $cartEmpty = true;
            
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    $product = getProductById($conn, $product_id);
                    if ($product) {
                        $cartEmpty = false;
                        $itemTotal = $product['unit_price'] * $quantity;
                        $cartTotal += $itemTotal;
                        ?>
                        <div class="cart-item">
                            <div class="cart-item-image">
                                <img src="images/product_<?php echo $product['product_id']; ?>.png" alt="<?php echo $product['product_name']; ?>" onerror="this.src='images/placeholder.png'">
                            </div>
                            <div class="cart-item-details">
                                <div class="cart-item-name"><?php echo $product['product_name']; ?></div>
                                <div class="cart-item-price">$<?php echo number_format($product['unit_price'], 2); ?> - <?php echo $product['unit_quantity']; ?></div>
                                <div class="cart-item-quantity">
                                    <button class="quantity-btn decrease" data-product-id="<?php echo $product['product_id']; ?>">-</button>
                                    <input type="text" class="quantity-input" data-product-id="<?php echo $product['product_id']; ?>" value="<?php echo $quantity; ?>" min="1" max="<?php echo $product['in_stock']; ?>" readonly>
                                    <button class="quantity-btn increase" data-product-id="<?php echo $product['product_id']; ?>">+</button>
                                    <button class="remove-item" data-product-id="<?php echo $product['product_id']; ?>"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            
            if ($cartEmpty) {
                echo '<p>Your cart is empty. Start shopping now!</p>';
            }
            ?>
        </div>
        
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total:</span>
                <span>$<?php echo number_format($cartTotal, 2); ?></span>
            </div>
            <a href="cart.php" class="checkout-btn <?php if ($cartEmpty) echo 'checkout-btn-disabled'; ?>">
                View Cart
            </a>
            <button class="clear-cart <?php if ($cartEmpty) echo 'clear-cart-disabled'; ?>" <?php if ($cartEmpty) echo 'disabled'; ?>>
                Clear Cart
            </button>
        </div>
    </div>
    
    <main class="container">