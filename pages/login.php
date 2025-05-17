<?php
session_start();
include '../includes/db_connect.php'; // Ensure correct path to DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password = $_POST['password'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id']; // Store user session
            header("Location: index.html"); // Redirect to dashboard
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this name.";
    }

    $stmt->close();
    $conn->close();
}
?>