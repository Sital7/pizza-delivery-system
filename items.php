<!-- <?php
// Include database connection
include('../db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $img = $_POST['img'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $ingredients = $_POST['ingredients'];
    $tags = $_POST['tags'];

    // Insert into the database
    $sql = "INSERT INTO items (name, price, img, description, category, ingredients, tags) 
            VALUES ('$name', '$price', '$img', '$description', '$category', '$ingredients', '$tags')";
    $conn->query($sql);
}

?> -->

<?php
// Include database connection
include('db_connect.php');

// Data to insert
$name = "Veggie Supreme";
$price = "700";
$img = "/meropizza/uploads/pizza/veggie_supreme.jpg";
$description = "A supreme mix of veggies.";
$category = "veg";
$ingredients = "Bell Peppers, Onions, Black Olives, Cheese";
$tags = "vegetarian,veggies,supreme";

// Insert query
$sql = "INSERT INTO items (name, price, img, description, category, ingredients, tags)
        VALUES ('$name', '$price', '$img', '$description', '$category', '$ingredients', '$tags')";

if ($conn->query($sql) === TRUE) {
    echo "New item inserted successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Pizza</title>
</head>
<body>

    <h2>Add New Pizza</h2>

    <form action="items.php" method="POST">
        <label for="name">Pizza Name:</label>
        <input type="text" name="name" required><br>
        
        <label for="price">Price:</label>
        <input type="text" name="price" required><br>

        <label for="img">Image URL:</label>
        <input type="text" name="img" required><br>

        <label for="description">Description:</label>
        <input type="text" name="description" required><br>

        <label for="category">Category:</label>
        <select name="category" required>
            <option value="veg">Vegetarian</option>
            <option value="non-veg">Non-Vegetarian</option>
        </select><br>

        <label for="ingredients">Ingredients:</label>
        <textarea name="ingredients" required></textarea><br>

        <label for="tags">Tags (comma separated):</label>
        <input type="text" name="tags" required><br>

        <button type="submit">Add Pizza</button>
    </form>

</body>
</html>
