<?php
session_start();
include '../../Authentication/db_connect.php'; // Ensure this line is present

// Pastikan pengguna sudah login
if (!isset($_SESSION["username"])) {
    header("Location: ../Authentication/sign-in.php");
    exit();
}


$userId = $_SESSION["user_id"];

// Ambil data foto profil dan username dari database
$stmt = $db->prepare("SELECT profile_picture, username FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Foto profil default jika tidak ada
$profile_picture = isset($user['profile_picture']) && !empty($user['profile_picture']) ? $user['profile_picture'] : '../Home/Home-img/Profile/User.png';
$username = isset($user['username']) ? $user['username'] : 'User';

// Untuk Menampilkan produk dari user dengan proses admin
$query = "SELECT product_photo, brand, product_name, price 
        FROM produk 
        WHERE displayed_on_productpage = 1";
$stmt = $pdo->query($query); // Gunakan $pdo untuk query
$featuredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT id, product_photo, product_name, brand, price FROM produk WHERE displayed_on_productpage = 1";
$result = mysqli_query($db, $query);
$featuredProducts = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fitur Tambah Keranjang
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = $_POST["product_id"];
    $user_id = $_SESSION["user_id"];

    // Cek apakah produk sudah ada di keranjang
    $query = "SELECT quantity FROM keranjang WHERE user_id = $user_id AND product_id = $product_id AND status = 'pending'";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        // Jika produk sudah ada, tambahkan jumlahnya
        $query = "UPDATE keranjang SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id AND status = 'pending'";
    } else {
        // Jika produk belum ada, tambahkan ke keranjang
        $query = "INSERT INTO keranjang (user_id, product_id, quantity, status) VALUES ($user_id, $product_id, 1, 'pending')";
    }

    $result = mysqli_query($db, $query);

    if ($result) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($db)]);
    }
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
    <link rel="stylesheet" href="product.css">

    <!-- Link Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <title>FashionXP - Product</title>
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

    <section class="slide-container">
        <div class="slider-container1">
            <img src="product-img/Slider4.png" alt="Slider">
        </div>
    </section>

    <div class="commounity-card">
        <div class="card-container">
            <a href="/Comunnity/comunity.html"><img src="product-img/Comunity.png" alt="Card1"></a>
        </div>
    </div>

    <div class="new-release-section">
        <h2>New Release</h2>
        <div class="slider-container">
            <button class="slider-arrow prev" id="prev">❮</button>
            <div class="slider-wrapper">
                <div class="slider">
                    <div class="product-card">
                        <img src="product-img/Men/RippedJeansBlue.png" alt="RippedJeansBlue">
                        <span class="brand">Aira</span>
                        <h3>Aira Man Ripped Jeans, Blue</h3>
                        <p class="price">Rp 200.000</p>
                        <button class="cart-btn">
                            <i class="bx bx-cart"></i>
                        </button>
                    </div>
                    <div class="product-card">
                        <img src="product-img/Men/TurtleNeckBrown.png" alt="Turtle Neck">
                        <span class="brand">AZ4REA</span>
                        <h3>AZ4REA Man TurtleNeck, Brown</h3>
                        <p class="price">Rp 400.000</p>
                        <button class="cart-btn">
                            <i class="bx bx-cart"></i>
                        </button>
                    </div>
                    <div class="product-card">
                        <img src="product-img/RegularCorduroyPantsDarkBrown.png" alt="Corduroy">
                        <span class="brand">Lorta</span>
                        <h3>Lorta Regular Corduroy Pants, Dark Brown</h3>
                        <p class="price">Rp 510.000</p>
                        <button class="cart-btn">
                            <i class="bx bx-cart"></i>
                        </button>
                    </div>
                    <div class="product-card">
                        <img src="product-img/CorduroyCargoPantsMaroon.png" alt="Corduroy">
                        <span class="brand">Lorta</span>
                        <h3>Lorta Curdory Cargo Pants, Red Maroon</h3>
                        <p class="price">Rp 640.000</p>
                        <button class="cart-btn">
                            <i class="bx bx-cart"></i>
                        </button>
                    </div>
                    <div class="product-card">
                        <img src="product-img/BrownSweater.png" alt="Brown Sweater">
                        <span class="brand">AZ4REA</span>
                        <h3>AZ4REA Baggy Cargo Pants, Green Army</h3>
                        <p class="price">Rp 240.000</p>
                        <button class="cart-btn">
                            <i class="bx bx-cart"></i>
                        </button>
                    </div>
                    <div class="product-card">
                        <img src="product-img/FlowerDressWhite.png" alt="Brown Sweater">
                        <span class="brand">Loreva</span>
                        <h3>Loreva Woman Flower Dress, White</h3>
                        <p class="price">Rp 570.000</p>
                        <button class="cart-btn">
                            <i class="bx bx-cart"></i>
                        </button>
                    </div>
                    <div class="product-card">
                        <img src="product-img/HoodieLightBrown.png" alt="Brown Sweater">
                        <span class="brand">AZ4REA</span>
                        <h3>AZ4REA Unisex Hoodie, Light Brown</h3>
                        <p class="price">Rp 350.000</p>
                        <button class="cart-btn">
                            <i class="bx bx-cart"></i>
                        </button>
                    </div>
                    <div class="product-card">
                        <img src="product-img/OversizeTshirtLightGreen.png" alt="Brown Sweater">
                        <span class="brand">AZ4REA</span>
                        <h3>AZ4REA Unisex Oversize T-Shirt, Light Green</h3>
                        <p class="price">Rp 140.000</p>
                        <button class="cart-btn">
                            <i class="bx bx-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
            <button class="slider-arrow next" id="next">❯</button>
        </div>
    </div>



    <div class="voucher-card">
        <div class="card-container">
            <a href="/Dashboard/Vouceher/voucher.html"><img src="product-img/Voucher.png" alt="Card1"></a>
        </div>
    </div>

    <!-- Product -->
    <section class="product-wrapper" id="Product">
        <div class="product-wrapper1">
            <h3>Feature Products</h3>
            <div class="products">
                <?php
                $displayPosition = 1;
                foreach ($featuredProducts as $product):
                    if (!isset($product['id'])) continue;
                ?>
                    <div class="product1" id="display<?php echo $displayPosition; ?>">
                        <a href="../Products/displayproduct.php?id=<?= htmlspecialchars($product['id']) ?>">
                            <img src="<?= htmlspecialchars($product['product_photo']) ?>"
                                alt="<?= htmlspecialchars($product['product_name']) ?>" 
                                height="200" 
                                class="imgzoom">
                        </a>
                        <a href="#"><?= htmlspecialchars($product['brand']) ?></a>
                        <h5><?= htmlspecialchars($product['product_name']) ?></h5>
                        <p>Rp <?php echo htmlspecialchars($product['price']); ?></p>
                        <button class="cart-button" data-product-id="<?= htmlspecialchars($product['id']) ?>">
                            <i class='bx bx-cart'></i>
                        </button>
                    </div>
                <?php
                    $displayPosition++;
                endforeach;

                // Fill remaining slots with empty product cards up to 16
                while ($displayPosition <= 28):
                ?>
                    <div class="product1" id="display<?php echo $displayPosition; ?>">
                        <!-- Empty product card structure -->
                        <img src="./product-img/Profile/BoxProduct.png" alt="Empty product" height="200" class="imgzoom">
                        <a href="#">&nbsp;</a>
                        <h5>&nbsp;</h5>
                        <p>&nbsp;</p>
                        <button class="cart-button" style="visibility: hidden;">
                            <i class='bx bx-cart'></i>
                        </button>
                    </div>
                <?php
                    $displayPosition++;
                endwhile;
                ?>
            </div>
        </div>
    </section>


    <!-- Wave Image -->
    <svg id="wave" xmlns="http://www.w3.org/2000/svg" width="1440" height="204" viewBox="0 0 1440 204" fill="none">
        <path fill-rule="evenodd" clip-rule="evenodd"
            d="M0 0L39.6 14C80.4 28 159.6 56 240 63C320.4 70 399.6 56 480 45.5C560.4 35 639.6 28 720 38.5C800.4 49 879.6 77 960 73.5C1040.4 70 1119.6 35 1200 31.5C1280.4 28 1359.6 56 1400.4 70L1440 84V189H1400.4C1359.6 189 1280.4 189 1200 189C1119.6 189 1040.4 189 960 189C879.6 189 800.4 189 720 189C639.6 189 560.4 189 480 189C399.6 189 320.4 189 240 189C159.6 189 80.4 189 39.6 189H0V0Z"
            fill="#98C5E8" />
        <path fill-rule="evenodd" clip-rule="evenodd"
            d="M0 169.556L34.8 160.945C68.4 152.334 136.8 135.111 205.2 117.889C274.8 100.667 343.2 83.4449 411.6 86.3153C480 89.1856 548.4 112.149 616.8 109.278C685.2 106.408 754.8 77.7042 823.2 89.1856C891.6 100.667 960 152.334 1028.4 166.685C1096.8 181.037 1165.2 158.074 1234.8 146.593C1303.2 135.111 1371.6 135.111 1405.2 135.111H1440V204H1405.2C1371.6 204 1303.2 204 1234.8 204C1165.2 204 1096.8 204 1028.4 204C960 204 891.6 204 823.2 204C754.8 204 685.2 204 616.8 204C548.4 204 480 204 411.6 204C343.2 204 274.8 204 205.2 204C136.8 204 68.4 204 34.8 204H0V169.556Z"
            fill="#C1DEF8" />
          
    </svg>

    <!-- Footer -->
    <footer class="footer">
        <div class="cta-section">
            <div>
                <h2>Ready to shop?</h2>
                <p>Discover the best products here!</p>
            </div>
            <div>
                <a href="#" class="shop-now-btn">Shop Now</a>
            </div>
        </div>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Contact Us</h3>
                <div class="contact-info">
                    <p>Email: support@cybernauts.com</p>
                    <p>Phone: +62 8123 456 789</p>
                    <p>Address: Kaliurang St No.Km 14,4, Lodadi, Umbulmartani, Ngemplak, Sleman Regency, Special Region
                        of
                        Yogyakarta 55584</p>
                </div>
            </div>
            <div class="footer-section">
                <h3>Further Information</h3>
                <div class="footer-links">
                    <a href="#">Terms & Condition</a>
                    <a href="#">Privacy Policy</a>
                </div>
            </div>

            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#" aria-label="Telegram"><i class='bx bxl-telegram'></i></a>
                    <a href="#" aria-label="Instagram"><i class='bx bxl-instagram'></i></a>
                    <a href="#" aria-label="Twitter"><i class='bx bxl-twitter'></i></a>
                    <a href="#" aria-label="Facebook"><i class='bx bxl-facebook-circle'></i></a>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p><b>FashionXP ©</b> 2024</p>
        </div>
    </footer>


    <!-- JS -->
    <script src="product.js"></script>


</body>

</html>