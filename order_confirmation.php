<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if order exists in session
if (!isset($_SESSION['order'])) {
    // Redirect to homepage if no order
    header('Location: index.php');
    exit;
}

// Get order details
$order = $_SESSION['order'];

include 'includes/header.php';
?>

<div class="confirmation-container">
    <div class="confirmation-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    
    <div class="confirmation-message">
        <h1>Order Confirmed!</h1>
        <p>Thank you for your order, <?php echo htmlspecialchars($order['name']); ?>.</p>
        <p>A confirmation email has been sent to <?php echo htmlspecialchars($order['email']); ?>.</p>
    </div>
    
    <div class="order-summary">
        <h2>Order Summary</h2>
        
        <div class="order-details">
            <p><strong>Order Date:</strong> <?php echo date('F j, Y g:i A', strtotime($order['date'])); ?></p>
            <p><strong>Delivery Address:</strong><br>
                <?php echo htmlspecialchars($order['name']); ?><br>
                <?php echo htmlspecialchars($order['street']); ?><br>
                <?php echo htmlspecialchars($order['city']); ?>, <?php echo htmlspecialchars($order['state']); ?><br>
            </p>
            <p><strong>Contact:</strong><br>
                Mobile: <?php echo htmlspecialchars($order['mobile']); ?><br>
                Email: <?php echo htmlspecialchars($order['email']); ?>
            </p>
        </div>
        
        <div class="order-items">
            <h3>Ordered Items:</h3>
            
            <?php foreach ($order['cart'] as $product_id => $quantity): ?>
                <?php 
                $product = getProductById($conn, $product_id);
                if ($product): 
                    $itemTotal = $product['unit_price'] * $quantity;
                ?>
                <div class="order-item">
                    <span>
                        <?php echo $product['product_name']; ?> - <?php echo $product['unit_quantity']; ?> 
                        (Qty: <?php echo $quantity; ?>)
                    </span>
                    <span>$<?php echo number_format($itemTotal, 2); ?></span>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <div class="order-total">
                <span>Total:</span>
                <span>$<?php echo number_format($order['total'], 2); ?></span>
            </div>
        </div>
    </div>
    
    <a href="index.php" class="back-to-shop">Continue Shopping</a>
</div>

<?php
include 'includes/footer.php';

// Remove order from session
unset($_SESSION['order']);
?>