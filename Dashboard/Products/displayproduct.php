<?php
session_start();
include '../../Authentication/db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: ../Authentication/sign-in.php");
    exit();
}

$userId = $_SESSION["user_id"];

// Proses penambahan ke keranjang
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    
    // Periksa apakah produk sudah ada di keranjang
    $check_query = "SELECT id, quantity FROM keranjang WHERE user_id = ? AND product_id = ? AND status = 'pending'";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bind_param("ii", $userId, $product_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Produk sudah ada di keranjang, update jumlahnya
        $cart_item = $check_result->fetch_assoc();
        $new_quantity = $cart_item['quantity'] + 1;
        $update_query = "UPDATE keranjang SET quantity = ? WHERE id = ?";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bind_param("ii", $new_quantity, $cart_item['id']);
        $success = $update_stmt->execute();
    } else {
        // Produk belum ada di keranjang, tambahkan item baru
        $insert_query = "INSERT INTO keranjang (user_id, product_id, quantity, status) VALUES (?, ?, 1, 'pending')";
        $insert_stmt = $db->prepare($insert_query);
        $insert_stmt->bind_param("ii", $userId, $product_id);
        $success = $insert_stmt->execute();
    }

    if ($success) {
        $message = "Produk berhasil ditambahkan ke keranjang.";
    } else {
        $message = "Gagal menambahkan produk ke keranjang: " . $db->error;
    }
}

// Get product ID from URL parameter
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product details
$query = "SELECT p.*, u.username as brand_name, u.profile_picture as brand_image 
          FROM produk p 
          LEFT JOIN users u ON p.user_id = u.id 
          WHERE p.id = ?";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    $profile_picture = $product['brand_image'];
    $username = $product['brand_name'];

} else {
    header("Location: ../Home/home.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Pixelify+Sans:wght@400..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Righteous&family=Silkscreen:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Link css, bootstrap, tailwind css -->
    <link rel="stylesheet" href="display.css">

    <!-- Link Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <title>FashionXP - Display Product</title>
</head>

<body>

    <!-- Navbar -->
    <header class="navbar-container">
        <nav class="nav-wrapper">
            <div class="nav-logo">
                <p>FashionXP</p>
            </div>
            <div class="nav-list" id="nav-container-list">
                <ul>
                    <li><a href="../Home/home.php">Home</a></li>
                    <li><a href="../Community/community.php">Community</a></li>
                    <li><a href="../Vouceher/voucher.php">Voucher</a></li>
                    <li><a href="./product.php">Product</a></li>
                </ul>
            </div>
            <div class="nav-icon-list">
                <ul>
                    <li><a href="../Cart/cart.php"><i class='bx bx-cart'></i></a></li>
                    <li class="profile-dropdown">
                        <button class="profile-btn"><i class='bx bx-user-circle'></i></button>
                        <div class="profile-dropdown">
                            <div class="dropdown-menu">
                                <div class="profile-drop">
                                    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture"
                                        class="profile-dropdown-img"
                                        onerror="this.src='/FashionXP/Dashboard/Home/Home-img/Profile/User.png'">
                                    <div class="name-level">
                                        <h4><?php echo htmlspecialchars($username); ?></h4>
                                        <p>Level 1</p>
                                    </div>
                                </div>

                                <hr>
                                <div class="dropdown-list">
                                    <i class='bx bxs-user'></i>
                                    <a href="../ProfileUseer/profile.php">Profile</a>
                                </div>
                                <div class="dropdown-list">
                                    <i class='bx bxs-store'></i>
                                    <a href="../Store/store.php">Create Your Own Store</a>
                                </div>
                                <div class="dropdown-list">
                                    <i class='bx bxs-door-open'></i>
                                    <a href="../../Authentication/logout.php">Logout</a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="nav-menu-btn">
                <i class='bx bx-menu' onclick="menuFunction()"></i>
            </div>
        </nav>
    </header>

    <section class="product-section">
        <div class="container">
            <div class="product-image">
                <img src="<?php echo htmlspecialchars($product['product_photo']); ?>" 
                     alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                     class="product-img">
            </div>
            <div class="product-info">
                <div class="brand">
                    <img class="brand-image" 
                         src="<?php echo htmlspecialchars($product['brand_image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['brand_name']); ?>">
                    <span><?php echo htmlspecialchars($product['brand']); ?></span>
                </div>
                <h1 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                <div class="category">Category: <?php echo htmlspecialchars($product['category']); ?></div>
                <div class="stock">
                    Stock Of The Product:
                    <input type="text" value="<?php echo htmlspecialchars($product['stock']); ?>" class="stock-input" readonly>
                </div>
                <div>
                    <div class="category">Size Chart</div>
                    <div class="size-chart">
                        <input type="text" value="<?php echo htmlspecialchars($product['size_chart']); ?>" class="stock-input" readonly>
                    </div>
                </div>
                <div class="price">Rp <?php echo htmlspecialchars($product['price']); ?></div>
                <form method="POST" action="" onsubmit="showAlert(event)">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <button type="submit" name="add_to_cart" class="add-to-cart">Add To Cart</button>
                </form>
            </div>
        </div>
    </section>    

    <!-- JS -->
    <script src="product.js"></script>


</body>

</html>