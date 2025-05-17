<?php
// Start the session to access the logged-in user's data
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: You must be logged in to save to the checklist.";
    exit;
}

// Get the user_id from the session
$user_id = intval($_SESSION['user_id']); // Ensure it's an integer

require_once "../includes/db_connect.php";

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data with sanitization
$place_id = isset($_POST['place_id']) ? intval($_POST['place_id']) : 0;
$visited_on = isset($_POST['visited_on']) ? trim($_POST['visited_on']) : '';
$memories = isset($_POST['memories']) ? trim($_POST['memories']) : '';
$add_to_checklist = isset($_POST['addToChecklist']) ? intval($_POST['addToChecklist']) : 0;

// Validate user_id exists in users table (optional, but good practice)
$check_user_sql = "SELECT user_id FROM users WHERE user_id = ?";
$check_user_stmt = $conn->prepare($check_user_sql);
$check_user_stmt->bind_param("i", $user_id);
$check_user_stmt->execute();
$check_user_result = $check_user_stmt->get_result();

if ($check_user_result->num_rows === 0) {
    echo "Error: User ID $user_id does not exist in the system.";
    $check_user_stmt->close();
    $conn->close();
    exit;
}
$check_user_stmt->close();

// Validate inputs
if ($place_id <= 0) {
    echo "Invalid hotel selected.";
    exit;
}

if (empty($visited_on)) {
    echo "Please select a visit date.";
    exit;
}

// Only process if adding to checklist
if ($add_to_checklist) {
    // Check if this place is already in the user's checklist
    $check_sql = "SELECT * FROM checklist WHERE user_id = ? AND place_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $place_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    // Prepare main query
    if ($check_result->num_rows > 0) {
        // Update existing record
        $sql = "UPDATE checklist SET visited_on = ?, memories = ? WHERE user_id = ? AND place_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $visited_on, $memories, $user_id, $place_id);
    } else {
        // Insert new record
        $sql = "INSERT INTO checklist (user_id, place_id, visited_on, memories) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $user_id, $place_id, $visited_on, $memories);
    }

    // Execute the query
    if ($stmt->execute()) {
        echo "Checklist saved successfully!";
    } else {
        if (strpos($conn->error, 'FOREIGN KEY') !== false) {
            echo "Error: The user ID does not exist in the system.";
        } else {
            echo "Error saving checklist: " . $conn->error;
        }
    }

    $stmt->close();
    $check_stmt->close();
} else {
    echo "Please check 'Add to Checklist' to save.";
}

$conn->close();
?>