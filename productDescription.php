<?php 
// Include database connection
include '../db_connect.php';

// Fetch product details based on product ID (passed via GET request)
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($productId <= 0) {
    echo "<p>Invalid Product ID!</p>";
    exit;
}

// Fetch product details
$productQuery = $conn->prepare("SELECT * FROM items WHERE id = ?");
$productQuery->bind_param("i", $productId);
$productQuery->execute();
$productResult = $productQuery->get_result();
$product = $productResult->fetch_assoc();

// Handle case if product is not found
if (!$product) {
    echo "<p>Product not found!</p>";
    exit;
}

// Function to check login status
function checkLogin() {
    return isset($_SESSION['user_id']); // Adjust 'user_id' to your session key
}

// Fetch top related items for "People Also May Like"
$relatedQuery = $conn->prepare("SELECT * FROM items WHERE id != ? ORDER BY sold_quantity DESC LIMIT 4");
$relatedQuery->bind_param("i", $productId);
$relatedQuery->execute();
$relatedProducts = $relatedQuery->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Description</title>
    <link rel="stylesheet" href="styles.css">
    <!-- <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        h1, h2, h3 {
            margin: 10px 0;
            color: #333;
        }

        p {
            color: #555;
            line-height: 1.5;
        }

        button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            background-color: #28a745;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #218838;
        }

        /* Product Description Container */
        .product-description-container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Product Details Section */
        .product-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .product-image img {
            max-width: 300px;
            border-radius: 8px;
        }

        .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-actions {
            margin-top: 20px;
        }

        /* Related Products Section */
        .related-products-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .related-product-card {
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: calc(25% - 20px);
        }

        .related-product-card img {
            max-width: 100%;
            border-radius: 8px;
        }

        .related-product-card h3 {
            margin: 10px 0;
            font-size: 1.1em;
        }

        .related-product-card p {
            margin: 5px 0;
        }

        .related-product-card button {
            background-color: #007bff;
            border-radius: 4px;
        }

        .related-product-card button:hover {
            background-color: #0056b3;
        }
    </style> -->
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #121212; /* Dark background */
            color: #e0e0e0; /* Light text for readability */
        }

        h1, h2, h3 {
            margin: 10px 0;
            color: #fff; /* White text for headers */
        }

        p {
            color: #ccc; /* Light grey for paragraphs */
            line-height: 1.5;
        }

        button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            background-color: #28a745; /* Green background for buttons */
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #218838; /* Darker green on hover */
        }

        /* Product Description Container */
        .product-description-container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
            background-color: #333; /* Dark background for the container */
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.7);
        }

        /* Product Details Section */
        .product-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .product-image img {
            max-width: 300px;
            border-radius: 8px;
            border: 3px solid #444; /* Subtle border for images */
        }

        .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-actions {
            margin-top: 20px;
        }

        /* Related Products Section */
        .related-products {
            margin-top: 40px;
        }

        .related-products-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .related-product-card {
            background-color: #444; /* Dark background for related products */
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.7);
            text-align: center;
            width: calc(25% - 20px);
        }

        .related-product-card img {
            max-width: 100%;
            border-radius: 8px;
        }

        .related-product-card h3 {
            margin: 10px 0;
            font-size: 1.1em;
            color: #fff; /* White for related product names */
        }

        .related-product-card p {
            margin: 5px 0;
            color: #ccc; /* Light grey for related product prices */
        }

        .related-product-card button {
            background-color: #007bff; /* Blue background for related product buttons */
            border-radius: 4px;
            color: white;
        }

        .related-product-card button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        /* Headings for Sections */
        h2 {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="product-description-container">
        <!-- Product Details Section -->
        <div class="product-details">
            <div class="product-image">
                <!-- <img src="uploads/<?php echo htmlspecialchars($product['image'] ?? 'placeholder.png'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"> -->
                <img src="<?php echo htmlspecialchars($product['img'] ?? 'uploads/placeholder.png'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">

            </div>
            <div class="product-info">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <!-- Display price in Nepali Rupees (Rs) -->
                <h3>Price: Rs. <?php echo number_format($product['price'], 2); ?></h3> <!-- Updated to display Nepali Rupees -->
                <div class="product-actions">
                    <button onclick="handleAction('order', <?php echo $product['id']; ?>)">Order</button>
                    <button onclick="handleAction('cart', <?php echo $product['id']; ?>)">Add to Cart</button>
                </div>
            </div>
        </div>

        <!-- People Also May Like Section -->
        <div class="related-products">
            <h2>People Also May Like</h2>
            <div class="related-products-grid">
                <?php foreach ($relatedProducts as $related): ?>
                <div class="related-product-card">
                    <!-- <img src="uploads/<?php echo htmlspecialchars($related['img'] ?? 'placeholder.png'); ?>" alt="<?php echo htmlspecialchars($related['name']); ?>"> -->
                    <img src="<?php echo htmlspecialchars($related['img'] ?? 'uploads/placeholder.png'); ?>" alt="<?php echo htmlspecialchars($related['name']); ?>">
                    <h3><?php echo htmlspecialchars($related['name']); ?></h3>
                    <!-- Display price in Nepali Rupees (Rs) for related products -->
                    <p>Price: Rs. <?php echo number_format($related['price'], 2); ?></p> <!-- Updated to display Nepali Rupees -->
                    <button onclick="window.location.href='productDescription.php?id=<?php echo $related['id']; ?>'">View</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>


<script>
     function handleAction(action, productId) {
        // JavaScript to handle the action with a login check
        fetch('check_login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action, productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                window.location.href = `${action}.php?id=${productId}`;
            } else {
                // alert("You must be logged in to perform this action!");
                window.location.href = 'login.php';
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>

<?php
// Close database connection
$conn->close();
?>
