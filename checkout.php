<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if cart is empty
$cartEmpty = true;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        if ($quantity > 0) {
            $cartEmpty = false;
            break;
        }
    }
}

// Redirect to cart page if cart is empty
if ($cartEmpty) {
    header('Location: cart.php');
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    if (isset($_POST['name'])) {
        $name = trim($_POST['name']);
    } else {
        $name = '';
    }
    
    if (isset($_POST['street'])) {
        $street = trim($_POST['street']);
    } else {
        $street = '';
    }
    
    if (isset($_POST['city'])) {
        $city = trim($_POST['city']);
    } else {
        $city = '';
    }
    
    if (isset($_POST['state'])) {
        $state = trim($_POST['state']);
    } else {
        $state = '';
    }
    
    if (isset($_POST['mobile'])) {
        $mobile = trim($_POST['mobile']);
    } else {
        $mobile = '';
    }
    
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
    } else {
        $email = '';
    }
    
    
    // Validate form
    $errors = validateDeliveryForm($name, $street, $city, $state, $mobile, $email);
    
    if (empty($errors)) {
        // Check if all items are in stock
        $outOfStockItems = checkCartItemsInStock($conn);
        
        if (empty($outOfStockItems)) {
            // Store order details in session for confirmation page
            $_SESSION['order'] = array(
                'name' => $name,
                'street' => $street,
                'city' => $city,
                'state' => $state,
                'mobile' => $mobile,
                'email' => $email,
                'cart' => $_SESSION['cart'],
                'total' => getCartTotal($conn),
                'date' => date('Y-m-d H:i:s')
            );
            
            // Update product stock in database
            updateProductStock($conn, $_SESSION['cart']);
            
            // Clear cart
            $cart = $_SESSION['cart'];
            clearCart();
            
            // Redirect to confirmation page
            header('Location: order_confirmation.php');
            exit;
        } else {
            // Display error message for out-of-stock items
            $outOfStockMessage = 'The following items are not available in the requested quantity:<ul>';
            foreach ($outOfStockItems as $item) {
                $outOfStockMessage .= '<li>' . $item['product']['product_name'] . ' - Requested: ' . $item['requested'] . ', Available: ' . $item['available'] . '</li>';
            }
            $outOfStockMessage .= '</ul>';
        }
    }
}

include 'includes/header.php';

// Calculate cart total
$cartTotal = getCartTotal($conn);
?>

<h1>Checkout</h1>

<?php if (isset($outOfStockMessage)): ?>
<div class="alert alert-danger">
    <?php echo $outOfStockMessage; ?>
    <p>Please <a href="cart.php">return to your cart</a> to adjust the quantities.</p>
</div>
<?php endif; ?>

<div class="checkout-container">
    <div class="checkout-title">
        <h2>Delivery Details</h2>
    </div>
    
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <form id="checkout-form" method="POST" action="checkout.php">
        <div class="form-group">
            <label for="name" class="required">Recipient's Name</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="street" class="required">Street Address</label>
            <input type="text" id="street" name="street" class="form-control" value="<?php echo isset($street) ? htmlspecialchars($street) : ''; ?>">
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="city" class="required">City/Suburb</label>
                <input type="text" id="city" name="city" class="form-control" value="<?php echo isset($city) ? htmlspecialchars($city) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="state" class="required">State/Territory</label>
                <select id="state" name="state" class="form-control">
                    <option value="select">Select State/Territory</option>
                    <option value="NSW" <?php if (isset($state) && $state == 'NSW') echo 'selected'; ?>>New South Wales</option>
                    <option value="VIC" <?php if (isset($state) && $state == 'VIC') echo 'selected'; ?>>Victoria</option>
                    <option value="QLD" <?php if (isset($state) && $state == 'QLD') echo 'selected'; ?>>Queensland</option>
                    <option value="WA" <?php if (isset($state) && $state == 'WA') echo 'selected'; ?>>Western Australia</option>
                    <option value="SA" <?php if (isset($state) && $state == 'SA') echo 'selected'; ?>>South Australia</option>
                    <option value="TAS" <?php if (isset($state) && $state == 'TAS') echo 'selected'; ?>>Tasmania</option>
                    <option value="ACT" <?php if (isset($state) && $state == 'ACT') echo 'selected'; ?>>Australian Capital Territory</option>
                    <option value="NT" <?php if (isset($state) && $state == 'NT') echo 'selected'; ?>>Northern Territory</option>
                    <option value="Other" <?php if (isset($state) && $state == 'Other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="mobile" class="required">Mobile Number (Australian)</label>
            <input type="text" id="mobile" name="mobile" class="form-control" placeholder="04XX XXX XXX" value="<?php echo isset($mobile) ? htmlspecialchars($mobile) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="email" class="required">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
        </div>
        
        <div class="order-summary">
            <h3>Order Summary</h3>
            <table class="order-summary-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
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
                                <?php echo $product['product_name']; ?> - <?php echo $product['unit_quantity']; ?>
                            </td>
                            <td><?php echo $quantity; ?></td>
                            <td>$<?php echo number_format($itemTotal, 2); ?></td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"><strong>Total:</strong></td>
                        <td><strong>$<?php echo number_format($cartTotal, 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <button type="submit" class="checkout-btn">Place Order</button>
    </form>
</div>

<?php
include 'includes/footer.php';
?>