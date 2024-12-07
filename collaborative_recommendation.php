<?php
// Database connection (update with your database credentials)
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'meropizza';

$conn = new mysqli($host, $username, $password, $dbname);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to calculate Cosine Similarity
function cosineSimilarity($vectorA, $vectorB) {
    $dotProduct = 0;
    $magnitudeA = 0;
    $magnitudeB = 0;

    // Calculate dot product and magnitudes
    for ($i = 0; $i < count($vectorA); $i++) {
        $dotProduct += $vectorA[$i] * $vectorB[$i];
        $magnitudeA += $vectorA[$i] * $vectorA[$i];
        $magnitudeB += $vectorB[$i] * $vectorB[$i];
    }

    // Calculate cosine similarity
    if ($magnitudeA == 0 || $magnitudeB == 0) {
        return 0;
    }

    return $dotProduct / (sqrt($magnitudeA) * sqrt($magnitudeB));
}

// Fetch user interaction data for a specific user
$user_id = 1; // Example user ID
$query = "SELECT pizza_id, rating FROM user_interactions WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Store user interactions
$userInteractions = [];
while ($row = $result->fetch_assoc()) {
    $userInteractions[$row['pizza_id']] = $row['rating'];
}

// Fetch all pizzas from the database
$pizzasQuery = "SELECT id, name FROM items";
$pizzasResult = $conn->query($pizzasQuery);
$pizzas = [];
while ($row = $pizzasResult->fetch_assoc()) {
    $pizzas[$row['id']] = $row['name'];
}

// Calculate similarity between pizzas based on user interactions
$recommendations = [];
foreach ($pizzas as $pizza_id => $pizza_name) {
    if (!isset($userInteractions[$pizza_id])) {
        // Fetch interactions for this pizza
        $pizzaInteractionsQuery = "SELECT rating FROM user_interactions WHERE pizza_id = ?";
        $pizzaStmt = $conn->prepare($pizzaInteractionsQuery);
        $pizzaStmt->bind_param("i", $pizza_id);
        $pizzaStmt->execute();
        $pizzaResult = $pizzaStmt->get_result();

        $pizzaInteractions = [];
        while ($pizzaRow = $pizzaResult->fetch_assoc()) {
            $pizzaInteractions[] = $pizzaRow['rating'];
        }

        // Compare current pizza with user's interactions using cosine similarity
        $similarity = cosineSimilarity(array_values($userInteractions), $pizzaInteractions);
        $recommendations[$pizza_id] = $similarity;
    }
}

// Sort pizzas by similarity score
arsort($recommendations);

// Get the top N recommendations (e.g., top 5)
$topRecommendations = array_slice($recommendations, 0, 5, true);

// Display the recommended pizzas
echo "<h3>Recommended Pizzas for You:</h3>";
foreach ($topRecommendations as $pizza_id => $similarity) {
    echo "Recommended Pizza: " . $pizzas[$pizza_id] . " (Similarity: " . round($similarity, 2) . ")<br>";
}

// Close the database connection
$conn->close();
?>
