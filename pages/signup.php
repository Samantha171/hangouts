<?php
require_once "../includes/db_connect.php"; // Ensure this file correctly connects to your database.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure all fields exist before accessing them
    $name = isset($_POST["name"]) ? trim($_POST["name"]) : '';
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';
    $phone_number = isset($_POST["phone"]) ? trim($_POST["phone"]) : '';

    // Check if required fields are empty
    if (empty($name) || empty($email) || empty($password) || empty($phone_number)) {
        die("❌ All fields are required.");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("❌ Invalid email format.");
    }

    // Hash the password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    if (!$stmt) {
        die("❌ Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "❌ Email already exists. Try another one.";
        $stmt->close();
        exit();
    }
    $stmt->close();

    // Corrected: Replaced 'phone' with 'phone_number'
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone_number, password) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("❌ Query preparation failed: " . $conn->error);
    }

    // Fix: Corrected bind_param to match the four fields
    $stmt->bind_param("ssss", $name, $email, $phone_number, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: login.html");
        exit();
    }  else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    die("❌ Invalid request.");
}
?>