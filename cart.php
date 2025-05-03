<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Process cart actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action == 'remove' && isset($_GET['product_id'])) {
        // Remove item from cart
        removeFromCart($_GET['product_id']);
        header('Location: cart.php?removed=1');
        exit;
    } elseif ($action == 'update' && isset($_GET['product_id']) && isset($_GET['quantity'])) {
        // Update item quantity
        updateCartItemQuantity($_GET['product_id'], $_GET['quantity']);
        header('Location: cart.php?updated=1');
        exit;
    } elseif ($action == 'clear') {
        // Clear cart
        clearCart();
        header('Location: cart.php?cleared=1');
        exit;
    }
}

include 'includes/header.php';

// Display messages
if (isset($_GET['removed']) && $_GET['removed'] == 1) {
    echo '<div class="alert alert-success">Item removed from cart successfully!</div>';
} elseif (isset($_GET['updated']) && $_GET['updated'] == 1) {
    echo '<div class="alert alert-success">Cart updated successfully!</div>';
} elseif (isset($_GET['cleared']) && $_GET['cleared'] == 1) {
    echo '<div class="alert alert-success">Cart cleared successfully!</div>';
}

// Calculate cart total
$cartTotal = getCartTotal($conn);
$cartEmpty = true;

// Check if cart is empty
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        if ($quantity > 0) {
            $cartEmpty = false;
            break;
        }
    }
}
?>

<h1>Your Shopping Cart</h1>

<?php if ($cartEmpty): ?>
<div class="empty-cart-message">
    <p>Your shopping cart is empty.</p>
    <p>Start shopping now to add items to your cart!</p>
    <a href="index.php" class="back-to-shop">Browse Products</a>
</div>
<?php else: ?>
<div class="cart-page">
    <table class="cart-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $product_id => $quantity): ?>
                <?php 
                $product = getProductById($conn, $product_id);
                if ($product): 
                    $itemTotal = $product['unit_price'] * $quantity;
                ?>
                <tr>
                    <td>
                        <div class="cart-product">
                            <div class="cart-product-image">
                                <img src="images/product_<?php echo $product['product_id']; ?>.png" alt="<?php echo $product['product_name']; ?>" onerror="this.src='images/placeholder.png'">
                            </div>
                            <div class="cart-product-details">
                                <div class="cart-product-name"><?php echo $product['product_name']; ?></div>
                                <div class="cart-product-unit"><?php echo $product['unit_quantity']; ?></div>
                            </div>
                        </div>
                    </td>
                    <td>$<?php echo number_format($product['unit_price'], 2); ?></td>
                    <td>
                        <div class="quantity-control">
                            <button class="quantity-btn decrease" data-product-id="<?php echo $product['product_id']; ?>">-</button>
                            <input type="text" class="quantity-input" data-product-id="<?php echo $product['product_id']; ?>" value="<?php echo $quantity; ?>" min="1" max="<?php echo $product['in_stock']; ?>" readonly>
                            <button class="quantity-btn increase" data-product-id="<?php echo $product['product_id']; ?>">+</button>
                        </div>
                    </td>
                    <td>$<?php echo number_format($itemTotal, 2); ?></td>
                    <td>
                        <a href="cart.php?action=remove&product_id=<?php echo $product['product_id']; ?>" class="remove-btn" onclick="return confirm('Are you sure you want to remove this item?');">
                            <i class="fas fa-trash"></i> Remove
                        </a>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                <td>$<?php echo number_format($cartTotal, 2); ?></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3" class="text-right"><strong>Shipping:</strong></td>
                <td>$0.00</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                <td><strong>$<?php echo number_format($cartTotal, 2); ?></strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    
    <div class="cart-actions">
        <a href="index.php" class="continue-shopping">Continue Shopping</a>
        <a href="cart.php?action=clear" class="clear-cart-btn" onclick="return confirm('Are you sure you want to clear your cart?');">Clear Cart</a>
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    </div>
</div>
<?php endif; ?>

<?php
include 'includes/footer.php';
?>