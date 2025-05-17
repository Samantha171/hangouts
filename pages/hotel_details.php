<?php
require_once "../includes/db_connect.php"; // Database connection

// Get the hotel ID from the URL
$hotel_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($hotel_id > 0) {
    // Fetch hotel details
    $stmt = $conn->prepare("SELECT * FROM places WHERE place_id = ?");
    $stmt->bind_param("i", $hotel_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $hotel = $result->fetch_assoc();

    if (!$hotel) {
        echo "Hotel not found.";
        exit;
    }

    // Fetch all images related to the place_id
    $image_stmt = $conn->prepare("SELECT image_path FROM place_images WHERE place_id = ?");
    $image_stmt->bind_param("i", $hotel_id);
    $image_stmt->execute();
    $image_result = $image_stmt->get_result();

    // Fetch reviews
    $review_stmt = $conn->prepare("SELECT name, review, review_date FROM reviews WHERE place_id = ? ORDER BY review_date DESC");
    $review_stmt->bind_param("i", $hotel_id);
    $review_stmt->execute();
    $reviews_result = $review_stmt->get_result();
} else {
    echo "Invalid hotel ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($hotel['name']) ?> - Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body{
            background:#D76C82;
        }
        .memories-box { display: none;
        background:#EBE8DB }
        .review-box {
            background: #EBE8DB;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .review-box strong { font-size: 16px; }
        .review-box p { margin-bottom: 5px; }
        .review-date { font-size: 12px; color: gray; }

        .direction-btn {
            background-color: #082B78;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin: 20px;
        }
        .direction-btn i {
            color: white;
            font-size: 22px;
        }
        .direction-btn:hover {
            background-color: #0a3b9f;
        }
        body {
        font-size: 20px; /* Adjust size as needed */
        }

        
    </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<div class="container mt-4">
    <h1><strong><?= htmlspecialchars($hotel['name']) ?></strong></h1>
    <p> • <?= htmlspecialchars($hotel['category']) ?>📍<?= htmlspecialchars($hotel['location_address']) ?></p>
    <p>📞 <?= htmlspecialchars($hotel['phone']) ?> &nbsp;&nbsp; 🕓 <?= htmlspecialchars($hotel['opening_hours']) ?> &nbsp;&nbsp; ⭐ <?= number_format($hotel['ratings'], 1) ?></p>
    <p>
    <?php if (!empty($hotel['maps'])): ?>
    <button id="openMapModal" class="direction-btn">
        <i class="fas fa-directions"></i>
    </button>
<?php endif; ?></p>

<!-- Google Maps Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color:#EBE8DB">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">Location Map</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="mapFrame" width="100%" height="450" style="border:0;background-color:#EBE8DB" allowfullscreen loading="lazy"></iframe>
            </div>
        </div>
    </div>
</div>

    <div class="row">
        <?php while ($image = $image_result->fetch_assoc()): ?>
            <div class="col-md-4">
                <img src="<?= htmlspecialchars('../' . $image['image_path']) ?>" alt="<?= htmlspecialchars($hotel['name']) ?>" class="img-fluid rounded" style="height: 280px; object-fit: cover; width: 500px;">
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Checklist Section -->
<div class="container mt-4">
    <form id="checklistForm">
        <input type="hidden" name="place_id" value="<?= $hotel_id ?>">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="addToChecklist" name="addToChecklist" value="1" >
            <label class="form-check-label" for="addToChecklist">Add to Checklist</label>
        </div>
        <div class="mt-3">
            <label for="visited_on">Visited On:</label>
            <input type="date" id="visited_on" name="visited_on" class="form-control" required style="background:#EBE8DB;">
        </div>
        <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" id="shareMemories">
            <label class="form-check-label" for="shareMemories">Do you want to share your memories?</label>
        </div>
        <div id="memoriesBox" class="memories-box mt-3">
            <textarea name="memories" class="form-control" placeholder="Share your memories here..." style="background:#EBE8DB;"></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>

<!-- Reviews Section -->
<div class="container mt-5">
    <h3>Reviews</h3>
    <?php if ($reviews_result->num_rows > 0): ?>
        <?php while ($review = $reviews_result->fetch_assoc()): ?>
            <div class="review-box d-flex align-items-start">
                <img src="../assets/user.jpg" width="32" height="32" alt="User Profile" class="rounded-circle me-2">
                <div>
                    <strong><?= htmlspecialchars($review['name']) ?></strong>
                    <p><?= nl2br(htmlspecialchars($review['review'])) ?></p>
                    <span class="review-date">Reviewed on <?= date("d F Y", strtotime($review['review_date'])) ?></span>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No reviews yet. Be the first to review!</p>
    <?php endif; ?>
</div>

<script>
    // Show/hide memories box based on checkbox
    $('#shareMemories').change(function() {
        $('#memoriesBox').slideToggle(this.checked);
    });
    
    // AJAX submission to save checklist
    $('#checklistForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'save_checklist.php',  // Separate PHP file to handle DB insertion
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                alert(response);
            },
            error: function() {
                alert('Error saving checklist.');
            }
        });
    });

   
    $(document).on('click', '#openMapModal', function() {
    var mapUrl = "<?= htmlspecialchars($hotel['maps']) ?>";
    if (mapUrl.trim() !== "") {
        $('#mapFrame').attr('src', mapUrl);
        $('#mapModal').modal('show');
    } else {
        alert("Map link is not available.");
    }
});

    
</script>

</body>
</html>