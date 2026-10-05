<?php
$conn = mysqli_connect("localhost", "root", "", "project");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (!isset($_GET['id'])) {
    die("Product ID is missing.");
}

$id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM product WHERE id = $id");
if (mysqli_num_rows($result) == 0) {
    die("Product not found.");
}

$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Order.css">
    <title><?php echo $row['product_name']; ?> - ElectroZone</title>

    <style>
        /* Ensure all images have same size */
        .image {
            width: 400px;
            height: 300px;
            object-fit: cover;
            /* maintain aspect ratio, crop if needed */
            border-radius: 10px;
        }

        .card {
            display: inline-block;
            padding: 20px;
            border: 1px solid lightgray;
            border-radius: 10px;
            width: 420px;
            text-align: left;
            font-family: Cambria, serif;
        }

        .quantity {
            width: 60px;
            height: 25px;
            margin-top: 5px;
        }

        .order {
            background-color: red;
            color: white;
            border: none;
            height: 35px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 150px;

            text-align: center;
        }

        .order:hover {
            background-color: darkred;
        }
    </style>
    <?php
    session_start();
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : "guest";
    ?>
</head>

<body>

    <h2>E</h2>
    <h3>lectroZone.in</h3>
    <br><br><br>

    <center>
        <strong><?php echo $row['product_name']; ?><br></strong>
        <img class="image" src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['product_name']; ?>">
    </center>

    <center>
        <p class="card">

            <?php echo nl2br($row['details']); ?><br><br>

            <b>Rupees: <strike><?php echo number_format($row['price'] + 5000); ?></strike>
                <?php echo number_format($row['price']); ?> only<br></b><br>

            <script>
                function checkLogin(productId) {

                    var username = "<?php echo $username; ?>";

                    if (username.toLowerCase() === "guest") {
                        alert("Please login to continue");
                        window.location.href = "Login.html";
                    } else {
                        window.location.href = "Order_process.php?id=" + productId;
                    }

                }
            </script>
            <button class="order" onclick="checkLogin(<?php echo $row['id']; ?>)">
                Order Now
            </button>

        </p>
        <br><br><br><br>
        <?php
        $conn = mysqli_connect("localhost", "root", "", "project");
        if (!$conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        /* STEP 1: Get product id from URL */
        if (!isset($_GET['id'])) {
            die("Product ID is missing.");
        }

        $product_id = intval($_GET['id']);

        /* STEP 2: Fetch only reviews for that product */
       $sql = "SELECT r.id, r.productvideo, r.username, r.productname, r.rating
        FROM reviews r
        INNER JOIN product p 
        ON r.productname = p.name
        WHERE p.id = $product_id";

        $result = mysqli_query($conn, $sql);

        $reviews = [];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $reviews[] = $row;
            }
        }
        ?>


        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Product Reviews</title>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
                rel="stylesheet">
            <style>
                *,
                *::before,
                *::after {
                    box-sizing: border-box;
                    margin: 0;
                    padding: 0;
                }

                body {
                    background: #f5f5f5;
                    font-family: 'Poppins', sans-serif;
                    color: #333;
                }

                /* TOP NAVBAR */
                .navbar {
                    background: red;
                    padding: 14px 24px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    position: sticky;
                    top: 0;
                    z-index: 100;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
                }

                .navbar-logo {
                    color: #fff;
                    font-size: 1.6rem;
                    font-weight: 700;
                    letter-spacing: -0.5px;
                }

                .navbar-logo span {
                    color: #ffdd57;
                }

                .navbar-right {
                    color: #fff;
                    font-size: 0.85rem;
                    opacity: 0.9;
                }

                /* PAGE CONTENT */
                .container {
                    max-width: 1000px;
                    margin: 0 auto;
                    padding: 24px 16px;
                }

                .section-title {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: #333;
                    margin-bottom: 4px;
                }

                .section-sub {
                    font-size: 0.82rem;
                    color: #888;
                    margin-bottom: 20px;
                }

                /* REVIEW CARD */
                .review-card {
                    background: #fff;
                    border-radius: 12px;
                    padding: 20px;
                    margin-bottom: 16px;
                    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
                    display: flex;
                    gap: 16px;
                    align-items: flex-start;
                    transition: box-shadow 0.2s;
                }

                .review-card:hover {
                    box-shadow: 0 4px 16px rgba(155, 43, 138, 0.12);
                }

                /* Avatar */
                .avatar {
                    width: 46px;
                    height: 46px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #9b2b8a, #c45cad);
                    color: #fff;
                    font-size: 1.2rem;
                    font-weight: 600;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                }

                .review-body {
                    flex: 1;
                }

                .reviewer-name {
                    font-weight: 600;
                    font-size: 0.95rem;
                    color: #222;
                }

                .reviewer-tag {
                    display: inline-block;
                    background: #f0e6ff;
                    color: red;
                    font-size: 0.7rem;
                    padding: 2px 8px;
                    border-radius: 20px;
                    margin-left: 8px;
                    font-weight: 500;
                }

                .product-label {
                    font-size: 0.78rem;
                    color: #aaa;
                    margin-top: 2px;
                    margin-bottom: 10px;
                }

                .product-label span {
                    color: red;
                    font-weight: 500;
                }

                /* Stars */
                .stars {
                    color: #f5a623;
                    font-size: 1rem;
                    letter-spacing: 2px;
                    margin-bottom: 10px;
                }

                /* Video thumbnail area */
                .video-section {
                    margin-top: 10px;
                }

                .video-label {
                    font-size: 0.78rem;
                    color: #888;
                    margin-bottom: 6px;
                }

                .video-thumb {
                    display: inline-flex;
                    align-items: center;
                    gap: 10px;
                    background: #fdf0fb;
                    border: 1.5px solid #e0b3d8;
                    border-radius: 10px;
                    padding: 10px 16px;
                    cursor: pointer;
                    transition: background 0.2s, border-color 0.2s;
                }

                .video-thumb:hover {
                    background: #f5d9f0;
                    border-color: red;
                }

                .play-circle {
                    width: 34px;
                    height: 34px;
                    background: red;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                }

                .play-circle::after {
                    content: '';
                    border-style: solid;
                    border-width: 6px 0 6px 11px;
                    border-color: transparent transparent transparent #fff;
                    margin-left: 3px;
                }

                .video-info {
                    display: flex;
                    flex-direction: column;
                }

                .video-name {
                    font-size: 0.82rem;
                    color: #555;
                    font-weight: 500;
                    max-width: 200px;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                .video-cta {
                    font-size: 0.72rem;
                    color: red;
                    font-weight: 600;
                    margin-top: 2px;
                }

                /* Review ID badge */
                .review-id-badge {
                    background: #f5f5f5;
                    color: #aaa;
                    font-size: 0.72rem;
                    padding: 4px 10px;
                    border-radius: 20px;
                    border: 1px solid #eee;
                    white-space: nowrap;
                    align-self: flex-start;
                    margin-top: 2px;
                }

                /* MODAL */
                .modal-overlay {
                    display: none;
                    position: fixed;
                    inset: 0;
                    background: rgba(0, 0, 0, 0.6);
                    z-index: 999;
                    align-items: center;
                    justify-content: center;
                }

                .modal-overlay.active {
                    display: flex;
                }

                .modal-box {
                    background: #fff;
                    border-radius: 16px;
                    width: 92%;
                    max-width: 640px;
                    overflow: hidden;
                    animation: slideUp 0.25s ease;
                    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
                }

                @keyframes slideUp {
                    from {
                        transform: translateY(40px);
                        opacity: 0;
                    }

                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }

                .modal-header {
                    background: red;
                    color: #fff;
                    padding: 14px 20px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }

                .modal-header h3 {
                    font-size: 1rem;
                    font-weight: 600;
                }

                .modal-close {
                    background: rgba(255, 255, 255, 0.2);
                    border: none;
                    color: #fff;
                    width: 30px;
                    height: 30px;
                    border-radius: 50%;
                    font-size: 1rem;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .modal-close:hover {
                    background: rgba(255, 255, 255, 0.35);
                }

                .modal-video {
                    width: 100%;
                    max-height: 400px;
                    background: #000;
                    display: block;
                }

                .modal-footer {
                    padding: 14px 20px;
                    font-size: 0.85rem;
                    color: #666;
                    border-top: 1px solid #f0f0f0;
                }

                /* No reviews */
                .no-reviews {
                    text-align: center;
                    padding: 60px 20px;
                    color: #aaa;
                    font-size: 1rem;
                }

                @media (max-width: 560px) {
                    .review-card {
                        flex-direction: column;
                    }

                    .review-id-badge {
                        align-self: flex-end;
                    }
                }

                /* Rating Highlight Box */

                .rating-box {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    background: #fff4f4;
                    border: 1.5px solid red;
                    border-radius: 8px;
                    padding: 6px 12px;
                    font-size: 0.9rem;
                    font-weight: 600;
                    margin-bottom: 10px;
                    width: fit-content;
                }

                .star-symbol {
                    color: #ffb400;
                    font-size: 1.2rem;
                }

                .rating-value {
                    color: red;
                    font-size: 1rem;
                }

                .rating-text {
                    color: #666;
                    font-size: 0.8rem;
                    font-weight: 500;
                }
            </style>
        </head>

        <body>

            <!-- Navbar -->
            <div class="navbar">
                <div class="navbar-logo">ElectroZone</div>
                <div class="navbar-right">Customer Reviews</div>
            </div>

            <!-- Video Modal -->
            <div class="modal-overlay" id="videoModal">
                <div class="modal-box">
                    <div class="modal-header">
                        <h3 id="modalProductName">Product Video</h3>
                        <button class="modal-close" onclick="closeModal()">✕</button>
                    </div>
                    <video class="modal-video" id="modalVideo" controls>
                        <source id="modalVideoSource" src="" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <div class="modal-footer" id="modalVideoName"></div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="container">
                <div class="section-title">Customer Reviews &amp; Videos</div>
                <div class="section-sub"><?= count($reviews) ?> review(s) found</div>

                <?php if (empty($reviews)): ?>
                    <div class="no-reviews">No reviews found.</div>
                <?php else: ?>
                    <?php foreach ($reviews as $i => $row): ?>
                        <div class="review-card">
                            <div class="avatar"><?= strtoupper(substr($row['username'], 0, 1)) ?></div>

                            <div class="review-body">
                                <div>
                                    <span class="reviewer-name"><?= htmlspecialchars($row['username']) ?></span>
                                    <span class="reviewer-tag">Verified Buyer</span>
                                </div>
                                <div class="product-label">Product: <span><?= htmlspecialchars($row['productname']) ?></span>
                                </div>
                                <div class="rating-box">
                                    <span class="star-symbol">★</span>
                                    <span class="rating-value">
                                        <?= htmlspecialchars($row['rating']) ?>
                                    </span>
                                    <span class="rating-text">out of 5</span>
                                </div>

                                <div class="video-section">
                                    <div class="video-label">Review Video</div>
                                    <div class="video-thumb"
                                        onclick="openModal('<?= htmlspecialchars($row['productvideo'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['productname'], ENT_QUOTES) ?>')">
                                        <div class="play-circle"></div>
                                        <div class="video-info">
                                            <div class="video-name"><?= htmlspecialchars($row['productvideo']) ?></div>
                                            <div class="video-cta">Tap to Watch Video</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="review-id-badge">#<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <script>
                function openModal(videoFile, productName) {
                    document.getElementById('modalProductName').textContent = productName;
                    document.getElementById('modalVideoName').textContent = videoFile;

                    var video = document.getElementById('modalVideo');
                    var source = document.getElementById('modalVideoSource');

                    source.src = 'uploads/' + videoFile;   // change path if needed

                    video.load();   // IMPORTANT
                    video.play();   // optional auto play

                    document.getElementById('videoModal').classList.add('active');
                }

                function closeModal() {
                    document.getElementById('videoModal').classList.remove('active');

                    var video = document.getElementById('modalVideo');
                    video.pause();
                    video.currentTime = 0;
                }

                document.getElementById('videoModal').addEventListener('click', function (e) {
                    if (e.target === this) closeModal();
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') closeModal();
                });
            </script>
        </body>

        </html>

    </center>

    <script>
        function ourOrder() {
            var number = document.getElementById("num").value;
            number = Number(number);
            if (number >= 1) {
                alert("Order successfully created for " + number + " item(s)");
            } else {
                alert("Please enter a valid quantity");
            }
        }
    </script>

</body>

</html>