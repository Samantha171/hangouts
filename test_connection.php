<?php
require_once 'includes/config.php';  // Include the config file

// Test Query
try {
    $stmt = $conn->query("SELECT * FROM places LIMIT 5");
    $results = $stmt->fetchAll();

    if ($results) {
        echo "✅ Database Connection Successful!<br>";
        foreach ($results as $place) {
            echo "Place: " . $place['name'] . " - " . $place['location_address'] . "<br>";
        }
    } else {
        echo "⚠️ No data found in 'places' table.";
    }
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
