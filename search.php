<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Process add to cart action
if (isset($_GET['action']) && $_GET['action'] == 'add_to_cart' && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    
    // Get product details
    $product = getProductById($conn, $product_id);
    
    if ($product && $product['in_stock'] > 0) {
        // Add to cart
        addToCart($product_id);
        
        // Redirect to prevent form resubmission
        header('Location: ' . $_SERVER['PHP_SELF'] . '?keyword=' . urlencode($_GET['keyword']) . '&added=1');
        exit;
    }
}

// Get search keyword
if (isset($_GET['keyword'])) {
    $keyword = trim($_GET['keyword']);
} else {
    $keyword = '';
}

// test
// Redirect to homepage if keyword is empty
if (empty($keyword)) {
    header('Location: index.php');
    exit;
}

include 'includes/header.php';

// Display success message if item added to cart
if (isset($_GET['added']) && $_GET['added'] == 1) {
    echo '<div class="alert alert-success">Item added to cart successfully!</div>';
}

// Search products
$products = searchProducts($conn, $keyword);
?>

<h1>Search Results for "<?php echo htmlspecialchars($keyword); ?>"</h1>

<?php if (empty($products)): ?>
<p>No products found matching your search criteria.</p>
<p>Try searching for another term or <a href="index.php">browse our categories</a>.</p>
<?php else: ?>
<p>Found <?php echo count($products); ?> product(s) matching your search.</p>

<div class="products-grid">
    <?php foreach ($products as $product): ?>
    <div class="product-card">
        <div class="product-image">
            <img src="images/product_<?php echo $product['product_id']; ?>.png" alt="<?php echo $product['product_name']; ?>" onerror="this.src='images/placeholder.png'">
        </div>
        <div class="product-details">
            <div class="product-name"><?php echo $product['product_name']; ?></div>
            <div class="product-unit"><?php echo $product['unit_quantity']; ?></div>
            <div class="product-price">$<?php echo number_format($product['unit_price'], 2); ?></div>
            <div class="product-stock 
                <?php 
                if ($product['in_stock'] > 0) {
                    echo 'in-stock';
                } else {
                    echo 'out-of-stock';
                } 
                ?>">
                <?php 
                if ($product['in_stock'] > 0) {
                    echo 'In Stock';
                } else {
                    echo 'Out of Stock';
                } 
                ?>
            </div>
            <button class="add-to-cart" data-product-id="<?php echo $product['product_id']; ?>" 
                <?php if ($product['in_stock'] <= 0) echo 'disabled'; ?>>
                Add to Cart
            </button>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php
include 'includes/footer.php';
?>