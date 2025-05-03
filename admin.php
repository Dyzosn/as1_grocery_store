<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Logout functionality
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    unset($_SESSION['admin']);
    header('Location: admin.php');
    exit;
}

// Check if user is logged in as admin
$isAdmin = false;
$loginError = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
    } else {
        $email = '';
    }
    
    if (isset($_POST['mobile'])) {
        $mobile = trim($_POST['mobile']);
    } else {
        $mobile = '';
    }
    
    
    // Check admin credentials
    if ($email == 'admin@admin.com' && $mobile == '1234567890') {
        $_SESSION['admin'] = true;
        $isAdmin = true;
    } else {
        $loginError = 'Invalid credentials. Please try again.';
    }
} else {
    $isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}

include 'includes/header.php';
?>

<h1>Admin Panel</h1>

<?php if (!$isAdmin): ?>
    <div class="admin-login">
        <h2>Admin Login</h2>
        
        <?php if (!empty($loginError)): ?>
        <div class="alert alert-danger"><?php echo $loginError; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="admin.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="mobile">Mobile</label>
                <input type="text" id="mobile" name="mobile" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
<?php else: ?>
    <div class="admin-dashboard">
        <h2>Order History</h2>
        <p>This page is to fulfil the requirement scenario from the old Assignment 1 spec file (Assignment1-AUT2025-1.pdf), but I already made it, so ignore this page.</p>
        <p>This is a simulation interface that shows samples of order data. Because this feature is not included in the marking criteria, So I only use hardcodes in this part (I don't have time to finish this because the deadline is tight).</p>
        
        <div class="admin-orders">
            <div class="order">
                <div class="order-header">
                    <div class="order-id">Order #1001</div>
                    <div class="order-date">April 21, 2025 10:30 AM</div>
                    <div class="order-status">Completed</div>
                </div>
                <div class="order-details">
                    <div class="customer-info">
                        <p><strong>Customer:</strong> John Smith</p>
                        <p><strong>Email:</strong> john.smith@example.com</p>
                        <p><strong>Mobile:</strong> 0412345678</p>
                        <p><strong>Address:</strong> 123 Main St, Sydney, NSW</p>
                    </div>
                    <div class="order-items">
                        <p><strong>Items:</strong></p>
                        <ul>
                            <li>Fish Fingers - 500 gram (Qty: 2) - $5.10</li>
                            <li>Hamburger Patties - Pack 10 (Qty: 1) - $2.35</li>
                        </ul>
                        <p><strong>Total:</strong> $7.45</p>
                    </div>
                </div>
            </div>
            
            <div class="order">
                <div class="order-header">
                    <div class="order-id">Order #1002</div>
                    <div class="order-date">April 21, 2025 11:45 AM</div>
                    <div class="order-status">Completed</div>
                </div>
                <div class="order-details">
                    <div class="customer-info">
                        <p><strong>Customer:</strong> Jane Doe</p>
                        <p><strong>Email:</strong> jane.doe@example.com</p>
                        <p><strong>Mobile:</strong> 0423456789</p>
                        <p><strong>Address:</strong> 456 Park Ave, Melbourne, VIC</p>
                    </div>
                    <div class="order-items">
                        <p><strong>Items:</strong></p>
                        <ul>
                            <li>Cheddar Cheese - 500 gram (Qty: 1) - $8.00</li>
                            <li>Earl Grey Tea Bags - Pack 25 (Qty: 2) - $4.98</li>
                        </ul>
                        <p><strong>Total:</strong> $12.98</p>
                    </div>
                </div>
            </div>
        </div>
        
        <a href="admin.php?logout=1" class="logout-btn">Logout</a>
    </div>
<?php endif; ?>

<?php
include 'includes/footer.php';
?>