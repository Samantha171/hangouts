<?php
// Database Connection
$conn = new mysqli("localhost", "root", "", "hotel_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get hotel ID from URL parameter
$hotel_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Fetch hotel details
$sql = "SELECT * FROM hotels WHERE id = $hotel_id";
$result = $conn->query($sql);
$hotel = $result->fetch_assoc();

// Fetch images
$sql_images = "SELECT image_url FROM hotel_images WHERE hotel_id = $hotel_id";
$result_images = $conn->query($sql_images);

// Fetch reviews
$sql_reviews = "SELECT user_name, review_text FROM hotel_reviews WHERE hotel_id = $hotel_id";
$result_reviews = $conn->query($sql_reviews);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $hotel['name']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { width: 80%; margin: auto; }
        .tabs { display: flex; gap: 20px; cursor: pointer; padding: 10px 0; border-bottom: 2px solid #ddd; }
        .tab { padding: 10px; font-weight: bold; }
        .tab:hover { color: blue; }
        .content { display: none; padding: 20px 0; }
        .active { display: block; }
        .gallery img { width: 150px; height: 100px; margin: 5px; cursor: pointer; }
        .large-image { width: 100%; max-height: 400px; object-fit: cover; margin-bottom: 10px; }
        .review { background: #f9f9f9; padding: 10px; margin-bottom: 10px; border-left: 5px solid #007bff; }
        .directions { display: inline-block; background: red; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
<div class="container">
    <h1><?php echo $hotel['name']; ?></h1>
    <p><?php echo $hotel['address']; ?></p>
    <a href="<?php echo $hotel['map_link']; ?>" class="directions" target="_blank">📍 Get Directions</a>

    <!-- Tabs -->
    <div class="tabs">
        <div class="tab" onclick="showTab('overview')">Overview</div>
        <div class="tab" onclick="showTab('reviews')">Reviews</div>
        <div class="tab" onclick="showTab('photos')">Photos</div>
    </div>

    <!-- Overview -->
    <div id="overview" class="content active">
        <p><?php echo $hotel['description']; ?></p>
    </div>

    <!-- Reviews -->
    <div id="reviews" class="content">
        <?php while ($review = $result_reviews->fetch_assoc()) { ?>
            <div class="review">
                <strong><?php echo $review['user_name']; ?></strong>
                <p><?php echo $review['review_text']; ?></p>
            </div>
        <?php } ?>
    </div>

    <!-- Photos -->
    <div id="photos" class="content">
        <div class="gallery">
            <?php while ($image = $result_images->fetch_assoc()) { ?>
                <img src="<?php echo $image['image_url']; ?>" onclick="showLarge(this)">
            <?php } ?>
        </div>
        <img id="largeView" class="large-image" src="">
    </div>
</div>

<script>
    function showTab(tab) {
        document.querySelectorAll('.content').forEach(el => el.style.display = 'none');
        document.getElementById(tab).style.display = 'block';
    }

    function showLarge(img) {
        document.getElementById('largeView').src = img.src;
    }
</script>
</body>
</html>
<?php $conn->close(); ?>
