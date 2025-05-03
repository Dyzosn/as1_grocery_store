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
        header('Location: index.php?added=1');
        exit;
    }
}

include 'includes/header.php';

// Display success message if item added to cart
if (isset($_GET['added']) && $_GET['added'] == 1) {
    echo '<div class="alert alert-success">Item added to cart successfully!</div>';
}

// Get products from each category
$categories = getCategories($conn);

// Featured products (get 8 random products)
$sql = "SELECT * FROM products ORDER BY RAND() LIMIT 8";
$result = mysqli_query($conn, $sql);
$featuredProducts = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $featuredProducts[] = $row;
    }
}
?>

<h1>Welcome to Online Grocery Store</h1>

<div class="section">
    <h2>Featured Products</h2>
    <div class="products-grid">
        <?php foreach ($featuredProducts as $product): ?>
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
</div>

<?php foreach ($categories as $category => $subcategories): ?>
<div class="section">
    <h2><?php echo $category; ?> Products</h2>
    <div class="products-grid">
        <?php 
        $categoryProducts = getProductsByCategory($conn, $category);
        $categoryProducts = array_slice($categoryProducts, 0, 4); // Limit to 4 products (displays four items per category)
        
        foreach ($categoryProducts as $product): 
        ?>
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
    <div class="view-more">
        <a href="category.php?category=<?php echo urlencode($category); ?>">View More <?php echo $category; ?> Products</a>
    </div>
</div>
<?php endforeach; ?>

<?php
include 'includes/footer.php';
?>