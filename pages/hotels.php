<?php
require_once "../includes/db_connect.php";  // Adjust the path if needed

// Fetch hotels from the database with or without search filter
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT place_id, name, opening_hours, image_path, ratings FROM places WHERE category = 'Hotel'";

if (!empty($searchTerm)) {
    // If there is a search term, filter hotels by name
    $searchTerm = $conn->real_escape_string($searchTerm);  // Prevent SQL Injection
    $sql .= " AND name LIKE '%$searchTerm%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hangouts - Hotel Listings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
    <style>
        body {
            background:#3D0301;
            font-family: Arial, sans-serif;
        }
        .restaurant-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
        .restaurant-card {
            background: #EBE8DB;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Hotels and Restaurants</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="hotels.php">Hotels</a></li>
                    <li class="nav-item"><a class="nav-link" href="entertainment.php">Entertainment</a></li>
                    <li class="nav-item"><a class="nav-link" href="emergency.php">Emergencies</a></li>
                    <li class="nav-item"><a class="nav-link" href="checklist.php">Checklist</a></li>
                </ul>
                <!-- Search Form -->
                <form class="d-flex" method="get" action="hotels.php" id="searchForm">
                    <input class="form-control me-2" type="search" name="search" id="searchInput" value="<?= htmlspecialchars($searchTerm); ?>" placeholder="Search Hotels" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit" style="display: none;">Search</button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="row" id="hotelResults">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): 
                    // Define correct image path
                    $imagePath = !empty($row['image_path']) && file_exists("../assets/" . $row['image_path']) 
                    ? "../assets/" . htmlspecialchars($row['image_path']) 
                    : "../assets/default.jpg";
                ?>
                    <div class="col-md-4 mb-4">
                    <a href="hotel_details.php?id=<?= $row['place_id']; ?>" class="text-decoration-none" style="color: black;">
                        <div class="restaurant-card">
                            <img src="<?= $imagePath ?>" alt="Hotel Image"><br><br>
                            <h5><strong><?= htmlspecialchars($row['name']); ?></strong></h5>
                            <p>🕓<?= htmlspecialchars($row['opening_hours']); ?>
                            | Rating: <?= htmlspecialchars($row['ratings']); ?> ⭐</p>
                        </div>
                    </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <p>No hotels found matching your search term. Please try again with a different term.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Function to handle real-time search
            $('#searchInput').on('input', function() {
                var searchTerm = $(this).val();
                $.ajax({
                    url: 'hotels.php',
                    type: 'GET',
                    data: { search: searchTerm },  // Send the search term to the server
                    success: function(response) {
                        // Parse the response and update the hotel results section
                        var resultHtml = $(response).find('#hotelResults').html();
                        $('#hotelResults').html(resultHtml);  // Update the results dynamically
                    }
                });
            });
        });
    </script>
</body>
</html>
