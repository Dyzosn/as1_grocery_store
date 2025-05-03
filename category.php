<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Get category and subcategory
if (isset($_GET['category'])) {
    $category = $_GET['category'];
} else {
    $category = '';
}

if (isset($_GET['subcategory'])) {
    $subcategory = $_GET['subcategory'];
} else {
    $subcategory = '';
}

// Validate category
$validCategories = array_keys(getCategories($conn));
if (!in_array($category, $validCategories)) {
    // Redirect to homepage if category is invalid
    header('Location: index.php');
    exit;
}

include 'includes/header.php';

// Display success message if item added to cart
if (isset($_GET['added']) && $_GET['added'] == 1) {
    echo '<div class="alert alert-success">Item added to cart successfully!</div>';
}

// Get products by category and subcategory
$products = getProductsByCategory($conn, $category, $subcategory);
?>

<h1><?php echo $subcategory ? $subcategory : $category; ?> Products</h1>

<?php if (empty($products)): ?>
<p>No products found in this category.</p>
<?php else: ?>
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