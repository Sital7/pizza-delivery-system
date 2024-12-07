<?php
// Include database connection
include('db_connect.php');

// Function to recommend pizzas based on user preferences
function recommendPizzas($userPreferences) {
    global $conn;

    // Sanitize user preferences
    $preferences = explode(',', $userPreferences); // example: 'vegetarian,cheese'

    // Fetch pizzas that match preferences from the 'items' table
    $sql = "SELECT * FROM items WHERE ";
    $conditions = [];

    foreach ($preferences as $preference) {
        $conditions[] = "tags LIKE '%$preference%'";
    }

    $sql .= implode(' OR ', $conditions); // Combine conditions with OR

    $result = $conn->query($sql);

    $recommendedPizzas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $recommendedPizzas[] = $row;
        }
    }

    return $recommendedPizzas;
}

// Example usage: User preferences are passed as a comma-separated string (e.g., vegetarian, cheese)
$userPreferences = "vegetarian,cheese";
$recommendedPizzas = recommendPizzas($userPreferences);
?>
