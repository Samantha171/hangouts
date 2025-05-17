<?php
// Start session and get user ID
session_start();
require_once "../includes/db_connect.php";

$userId = $_SESSION['user_id'];  // Get the user ID from session

// Use prepared statements
$sql = "SELECT p.name, c.memories, c.visited_on 
        FROM checklist c
        JOIN places p ON c.place_id = p.place_id
        JOIN users u ON c.user_id = u.user_id
        WHERE u.user_id = ?  -- Filter by user
        ORDER BY c.visited_on DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);  // "i" -> integer
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Travel Memories</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <style>
        body{
            background:#EBE8DB;
        }
        .memory-card {
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
        .navbar {
        background-color: #B03052 !important;
    }
    .navbar .navbar-brand, .navbar .nav-link {
        color: #EBE8DB !important;
    }
    .navbar .nav-link:hover, .navbar .nav-link.active {
        color: #3D0301 !important;
    }
    .navbar-toggler-icon {
        background-color: #EBE8DB;
    }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Hangouts</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="hotels.php">Hotels</a></li>
                    <li class="nav-item"><a class="nav-link" href="entertainment.php">Entertainment</a></li>
                    <li class="nav-item"><a class="nav-link" href="emergency.php">Emergencies</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="container text-center my-4">
        <h1>My Travel Memories</h1>
        <p>Explore the beautiful places I have visited and the memories I made.</p>
    </div>

<!-- Display Memories -->
<div class="container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="memory-card">';
            echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
            echo '<p><strong>Visited on:</strong> ' . htmlspecialchars($row["visited_on"]) . '</p>';
            echo '<p>"' . htmlspecialchars($row["memories"]) . '"</p>';
            echo '</div>';
        }
    } else {
        echo "<p>No memories yet.</p>"; // Updated message
    }
    ?>
</div>


</body>
</html>