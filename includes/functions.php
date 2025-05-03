<?php
// Get all product categories
function getCategories() {
    $categories = array(
        'Frozen' => array('Fish Fingers', 'Hamburger Patties', 'Ice Cream', 'Pizza', 'Vegetables'),
        'Fresh' => array('Cheese', 'Meat', 'Fruits', 'Dairy'),
        'Beverages' => array('Tea', 'Coffee', 'Chocolate', 'Juice', 'Water'),
        'Home' => array('Cleaning', 'Laundry', 'Garbage Bags'),
        'Medicine' => array('Pain Relief', 'Cold & Flu', 'First Aid', 'Vitamins'),
        'Pet-food' => array('Dog Food', 'Cat Food', 'Bird Food', 'Fish Food', 'Pet Treats')
    );
    
    return $categories;
}

// Get products by category or subcategory
function getProductsByCategory($conn, $category, $subcategory = '') {
    $sql = "SELECT * FROM products WHERE 1=1";
    
    if ($category == 'Frozen') {
        if ($subcategory == 'Fish Fingers') {
            $sql .= " AND (product_id = 1000 OR product_id = 1001)";
        } elseif ($subcategory == 'Hamburger Patties') {
            $sql .= " AND product_id = 1002";
        } elseif ($subcategory == 'Ice Cream') {
            $sql .= " AND (product_id = 1004 OR product_id = 1005)";
        } elseif ($subcategory == 'Pizza') {
            $sql .= " AND product_id = 1006";
        } elseif ($subcategory == 'Vegetables') {
            $sql .= " AND product_id = 1007";
        } else {
            $sql .= " AND product_id BETWEEN 1000 AND 1999";
        }
    } elseif ($category == 'Fresh') {
        if ($subcategory == 'Cheese') {
            $sql .= " AND (product_id = 3000 OR product_id = 3001)";
        } elseif ($subcategory == 'Meat') {
            $sql .= " AND product_id = 3002";
        } elseif ($subcategory == 'Fruits') {
            $sql .= " AND product_id BETWEEN 3003 AND 3008";
        } elseif ($subcategory == 'Dairy') {
            $sql .= " AND product_id = 3009";
        } else {
            $sql .= " AND product_id BETWEEN 3000 AND 3999";
        }
    } elseif ($category == 'Beverages') {
        if ($subcategory == 'Tea') {
            $sql .= " AND (product_id = 4000 OR product_id = 4001 OR product_id = 4002)";
        } elseif ($subcategory == 'Coffee') {
            $sql .= " AND (product_id = 4003 OR product_id = 4004)";
        } elseif ($subcategory == 'Chocolate') {
            $sql .= " AND product_id = 4005";
        } elseif ($subcategory == 'Juice') {
            $sql .= " AND product_id = 4006";
        } elseif ($subcategory == 'Water') {
            $sql .= " AND product_id = 4007";
        } else {
            $sql .= " AND product_id BETWEEN 4000 AND 4999";
        }
    } elseif ($category == 'Home') {
        if ($subcategory == 'Cleaning') {
            $sql .= " AND (product_id = 2002 OR product_id = 2007 OR product_id = 2008 OR product_id = 2009)";
        } elseif ($subcategory == 'Laundry') {
            $sql .= " AND (product_id = 2005 OR product_id = 2006)";
        } elseif ($subcategory == 'Garbage Bags') {
            $sql .= " AND (product_id = 2003 OR product_id = 2004)";
        } else {
            $sql .= " AND product_id BETWEEN 2002 AND 2009";
        }
    } elseif ($category == 'Medicine') {
        if ($subcategory == 'Pain Relief') {
            $sql .= " AND (product_id = 6000 OR product_id = 6001 OR product_id = 6002 OR product_id = 6003)";
        } elseif ($subcategory == 'Cold & Flu') {
            $sql .= " AND product_id = 6004";
        } elseif ($subcategory == 'First Aid') {
            $sql .= " AND (product_id = 6005 OR product_id = 6006)";
        } elseif ($subcategory == 'Vitamins') {
            $sql .= " AND product_id = 6007";
        } else {
            $sql .= " AND product_id BETWEEN 6000 AND 6999";
        }
    } elseif ($category == 'Pet-food') {
        if ($subcategory == 'Dog Food') {
            $sql .= " AND (product_id = 5000 OR product_id = 5001)";
        } elseif ($subcategory == 'Cat Food') {
            $sql .= " AND (product_id = 5003 OR product_id = 5005)";
        } elseif ($subcategory == 'Bird Food') {
            $sql .= " AND product_id = 5002";
        } elseif ($subcategory == 'Fish Food') {
            $sql .= " AND product_id = 5004";
        } elseif ($subcategory == 'Pet Treats') {
            $sql .= " AND product_id = 5006";
        } else {
            $sql .= " AND product_id BETWEEN 5000 AND 5999";
        }
    }
    
    $result = mysqli_query($conn, $sql);
    $products = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }
    
    return $products;
}

// Search products by keyword
function searchProducts($conn, $keyword) {
    $keyword = mysqli_real_escape_string($conn, $keyword);
    
    $sql = "SELECT * FROM products WHERE 
            product_name LIKE '%$keyword%' OR
            unit_quantity LIKE '%$keyword%'";
    
    $result = mysqli_query($conn, $sql);
    $products = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }
    
    return $products;
}

// Get product by ID
function getProductById($conn, $product_id) {
    $product_id = mysqli_real_escape_string($conn, $product_id);
    
    $sql = "SELECT * FROM products WHERE product_id = '$product_id'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}

// Add item to cart
function addToCart($product_id, $quantity = 1) {
    // Initialise cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // If product already in cart, increase quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Otherwise add product to cart
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Remove item from cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update item quantity in cart
function updateCartItemQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        if ($quantity <= 0) {
            removeFromCart($product_id);
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
}

// Clear cart
function clearCart() {
    $_SESSION['cart'] = array();
}

// Get cart total
function getCartTotal($conn) {
    $total = 0;
    
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $product = getProductById($conn, $product_id);
            if ($product) {
                $total += $product['unit_price'] * $quantity;
            }
        }
    }
    
    return $total;
}

// Check if all items in cart are in stock
function checkCartItemsInStock($conn) {
    $outOfStockItems = array();
    
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $product = getProductById($conn, $product_id);
            if ($product) {
                if ($product['in_stock'] < $quantity) {
                    $outOfStockItems[] = array(
                        'product' => $product,
                        'requested' => $quantity,
                        'available' => $product['in_stock']
                    );
                }
            }
        }
    }
    
    return $outOfStockItems;
}

// Update product stock after order
function updateProductStock($conn, $cart) {
    foreach ($cart as $product_id => $quantity) {
        $sql = "UPDATE products SET in_stock = in_stock - $quantity 
                WHERE product_id = $product_id";
        mysqli_query($conn, $sql);
    }
}

// Validate email format
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate Australian mobile number (10 digits starting with 04)
function validateMobile($mobile) {
    return preg_match('/^04\d{8}$/', $mobile);
}

// Validate delivery form
function validateDeliveryForm($name, $street, $city, $state, $mobile, $email) {
    $errors = array();
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($street)) {
        $errors[] = "Street address is required";
    }
    
    if (empty($city)) {
        $errors[] = "City/Suburb is required";
    }
    
    if (empty($state) || $state == "select") {
        $errors[] = "State/Territory is required";
    }
    
    if (empty($mobile)) {
        $errors[] = "Mobile number is required";
    } elseif (!validateMobile($mobile)) {
        $errors[] = "Mobile number must be 10 digits starting with '04'";
    }
    
    if (empty($email)) {
        $errors[] = "Email address is required";
    } elseif (!validateEmail($email)) {
        $errors[] = "Invalid email format";
    }
    
    return $errors;
}
?>