<?php
session_start();
include '../../Authentication/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: ../Authentication/sign-in.php");
    exit();
}
// At the top of the file, after session_start()
if (!isset($_SESSION["user_id"])) {
    header("Location: ../Authentication/sign-in.php");
    exit();
}

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product details
$stmt = $db->prepare("SELECT * FROM komunitas WHERE id = ? AND status = 'approved' AND displayed_on_community = 1");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// If product not found or not approved/displayed, redirect back to community
if (!$product) {
    header("Location: community.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionXP - Voting</title>

    <!-- Style -->
    <link rel="stylesheet" href="voting.css">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="Source/Style/font.css">

    <!-- Icon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <a href="./community.php" class="back-button" aria-label="Go back"><i class='bx bx-chevron-left'></i></a>
    <div class="container">
        <div class="left-section">
            <h1 class="main-heading">VOTE & SHARE YOUR THOUGHTS AND GET THE POINTS!</h1>
            <img src="<?php echo htmlspecialchars($product['foto_produk']); ?>" 
                 alt="<?php echo htmlspecialchars($product['nama_produk']); ?>" 
                 class="product-image">
        </div>

        <div class="right-section">
            <div class="product-content">
                <h3 class="product-title"><?php echo htmlspecialchars($product['nama_produk']); ?></h3>
                <p class="product-description">
                    <?php echo htmlspecialchars($product['deskripsi_produk']); ?>
                </p>
            </div>

            <div class="voting-section" data-komunitas-id="<?php echo $product_id; ?>">
                <div class="progress-container">
                    <div class="progress-bar like-bar" style="width: 0%"></div>
                </div>
                <div class="vote-label">
                    <span>Likes</span>
                    <span class="like-percentage">0%</span>
                </div>

                <div class="progress-container">
                    <div class="progress-bar dislike-bar" style="width: 0%"></div>
                </div>
                <div class="vote-label">
                    <span>Dislikes</span>
                    <span class="dislike-percentage">0%</span>
                </div>

                <div class="vote-buttons">
                    <button class="btn like-btn" data-vote-type="like">LIKE</button>
                    <button class="btn dislike-btn" data-vote-type="dislike">DISLIKE</button>
                </div>
            </div>

            <div class="comment-section">
                <h4 class="comment-header">COMMENT</h4>
                <form id="comment-form" class="comment-form">
                    <textarea 
                        class="comment-input" 
                        placeholder="Your comment will appear here..." 
                        rows="4"
                        required
                    ></textarea>
                    <button type="submit" class="btn submit-comment" >Submit Comment</button>
                </form>

                <div class="comment-list">
                    
                </div>
            </div>

            

            


        </div>
    </div>

    <script src="voting.js"></script>
</body>

</html>