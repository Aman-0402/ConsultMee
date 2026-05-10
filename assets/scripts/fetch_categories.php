<?php
header('Content-Type: application/json');

// Enable error reporting for debugging (optional, remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Step 1: Connect to DB
$host = 'localhost';
$db   = 'yibnyzre_cme';
$user = 'yibnyzre_cme_admin';
$pass = 'As792002@';
$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4"); // ensure UTF-8 charset

// Check DB connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Step 2: Fetch categories having at least one consultant
$sql = "
    SELECT DISTINCT c.id, c.category_name AS name, c.img
    FROM categories c
    INNER JOIN consultants con 
        ON con.area_of_expertise COLLATE utf8mb4_general_ci = c.category_name
    ORDER BY c.category_name ASC
";

$result = $conn->query($sql);

// Step 3: Check for query error
if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Query failed: " . $conn->error]);
    exit;
}

// Step 4: Fetch results
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// Step 5: Handle empty result
if (empty($categories)) {
    echo json_encode(["message" => "No categories with consultants found"]);
} else {
    echo json_encode($categories);
}

// Step 6: Close DB connection
$conn->close();
?>