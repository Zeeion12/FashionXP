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
        WHERE displayed_on_home = 1";
$stmt = $pdo->query($query); // Gunakan $pdo untuk query
$featuredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT id, product_photo, product_name, brand, price FROM produk WHERE displayed_on_home = 1";
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
        href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Righteous&family=Silkscreen:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Link css, bootstrap, tailwind css -->
    <link rel="stylesheet" href="home.css">

    <title>FashionXP - Home</title>
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
                    <li><a href="../Products/product.php">Product</a></li>
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
                                        onerror="this.src='../Home/Home-img/Profile/User.png'">
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

    <!-- Section Slider -->
    <div class="slider-wrpr" id="Slider">
        <div class="slider-wrpr2">
            <div class="slider1">
            </div>
            <div class="slider2">
            </div>
            <div class="slider3">
            </div>
            <div class="slider4">
            </div>
            <div class="slider-controls">
                <input type="radio" name="slider" id="slide1" checked>
                <input type="radio" name="slider" id="slide2">
                <input type="radio" name="slider" id="slide3">
                <input type="radio" name="slider" id="slide4">
            </div>
        </div>
    </div>

    <!-- Section Menu -->
    <section class="menu-wraper" id="Menu">
        <div class="menu-wraper2">
            <div class="card1">
                <h4>Women</h4>
                <div class="content-card1">
                    <div class="text-card1">
                        <h3>Discover the <span>“Must-Have”</span><br>Styles for Women!</h3>
                        <a href="#WomanC" class="bton-woman">
                            <button>Check Below</button>
                        </a>
                    </div>
                    <img src="Home-img/WomenCard.png" alt="Model1" height="400">
                </div>
            </div>
            <div class="menu-wraper3">
                <div class="card2">
                    <h4>Winter</h4>
                    <div class="content-card2">
                        <div class="text-card2">
                            <p>Winter season has come! Discover your cozy winter collection now!</p>
                        </div>
                        <img src="Home-img/WinterModel.png" alt="Model2" class="img-card">
                    </div>
                </div>
                <div class="card2">
                    <h4>News</h4>
                    <div class="content-card2">
                        <div class="text-card3">
                            <p>From unique designs to quality materials, <span>Elevé Collection</span> is winning over
                                fashion lovers everywhere. Check out their latest collections!</p>
                        </div>
                        <img src="Home-img/NewsModel.png" alt="Model3" class="img-card">
                    </div>
                </div>
            </div>

            <div class="menu-wraper4">
                <div class="card4">
                    <h4>Discounts</h4>
                    <div class="content-card4">
                        <div class="text-card4">
                            <h2>Celebrate the New Year<br>with Amazing<br>Discounts!</h2>
                        </div>
                        <img src="Home-img/VoucherModel.png" alt="Model4" class="img-card1">
                    </div>
                </div>
            </div>
        </div>
        <div class="card5">
            <img src="Home-img/WCAS.png" alt="WCAS" class="card-image1">
            <img src="Home-img/WCAS2.png" alt="WCAS2" class="card-image2">
        </div>
    </section>



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
                        <a href="displayproduct.php?id=<?= htmlspecialchars($product['id']) ?>">
                            <?= htmlspecialchars($product['brand']) ?>
                        </a>
                        <h5>
                            <a href="displayproduct.php?id=<?= htmlspecialchars($product['id']) ?>" 
                            style="text-decoration: none; color: inherit;">
                                <?= htmlspecialchars($product['product_name']) ?>
                            </a>
                        </h5>
                        <p>Rp <?php echo htmlspecialchars($product['price']); ?></p>
                        <button class="cart-button" data-product-id="<?= htmlspecialchars($product['id']) ?>">
                            <i class='bx bx-cart'></i>
                        </button>
                    </div>
                <?php
                    $displayPosition++;
                endforeach;

                // Fill remaining slots with empty product cards up to 8
                while ($displayPosition <= 8):
                ?>
                    <div class="product1" id="display<?php echo $displayPosition; ?>">
                        <!-- Empty product card structure -->
                        <img src="./Home-img/BoxProduct.png" alt="Empty product" height="200" class="imgzoom">
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

    <section class="categpry-wrapper" id="Category">
        <div class="category-wrapper1">
            <h3>Popular Category</h3>
            <div class="categorys-slider">
                <button id="prev-slide" class="button-slide"><i class='bx bxs-chevron-left'></i></button>
                <div class="categorys">
                    <div class="category1">
                        <img src="Home-img/Group1.png" alt="Ctg1">
                        <a href="#" class="category-button">
                            <button>Check Out ></button>
                        </a>
                    </div>
                    <div class="category1">
                        <img src="Home-img/Group2.png" alt="Ctg2">
                        <a href="#" class="category-button">
                            <button>Check Out ></button>
                        </a>
                    </div>
                    <div class="category1">
                        <img src="Home-img/Group3.png" alt="Ctg3">
                        <a href="#" class="category-button">
                            <button>Check Out ></button>
                        </a>
                    </div>
                    <div class="category1">
                        <img src="Home-img/Group4.png" alt="Ctg4">
                        <a href="#" class="category-button">
                            <button>Check Out ></button>
                        </a>
                    </div>
                    <div class="category1">
                        <img src="Home-img/Group5.png" alt="Ctg5">
                        <a href="#" class="category-button">
                            <button>Check Out ></button>
                        </a>
                    </div>
                    <div class="category1">
                        <img src="Home-img/Group6.png" alt="Ctg6" height="235">
                        <a href="#" class="category-button">
                            <button>Check Out ></button>
                        </a>
                    </div>
                </div>
                <button id="next-slide" class="button-slide"><i class='bx bxs-chevron-right'></i></button>
            </div>
        </div>

        <section class="product-wrapper" id="WomanC">
            <div class="product-wrapper2">
                <h3>Woman Clothes</h3>
                <div class="products">
                    <div class="product1">
                        <img src="Home-img/Products/GreenDress.png" alt="Dress" height="200" class="imgzoom">
                        <a href="#">AZ4REA</a>
                        <h5>AZ4REA Woman Dress, Green </h5>
                        <p>Rp 400.000</p>
                        <button class="cart-button">
                            <i class='bx bx-cart'></i>
                        </button>
                    </div>
                    <div class="product1">
                        <img src="Home-img/Products/BrownSweater.png" alt="Sweater" height="200" class="imgzoom">
                        <a href="#">AZ4REA</a>
                        <h5>AZ4REA Unisex Sweater, Brown Dust</h5>
                        <p>Rp 240.000</p>
                        <button class="cart-button">
                            <i class='bx bx-cart'></i>
                        </button>
                    </div>
                    <div class="product1">
                        <img src="Home-img/Products/BrownPants.png" alt="Pants" height="200" class="imgzoom">
                        <a href="#">Loreva</a>
                        <h5>Loreva Woman Hotpants, Brown</h5>
                        <p>Rp 259.000</p>
                        <button class="cart-button">
                            <i class='bx bx-cart'></i>
                        </button>
                    </div>
                    <div class="product1">
                        <img src="Home-img/Products/BluePants.png" alt="Pants" height="200" class="imgzoom">
                        <a href="#">Loreva</a>
                        <h5>Loreva Woman Trouser Pants, Blue</h5>
                        <p>Rp 385.000</p>
                        <button class="cart-button">
                            <i class='bx bx-cart'></i>
                        </button>
                    </div>
                    <div class="product1">
                        <img src="Home-img/Products/Croptop.png" alt="Croptop" height="200" class="imgzoom">
                        <a href="#">Loreva</a>
                        <h5>Loreva Croptop Blouse, Sage Green </h5>
                        <p>Rp 340.000</p>
                        <button class="cart-button">
                            <i class='bx bx-cart'></i>
                        </button>
                    </div>
                    <div class="product1">
                        <img src="Home-img/Products/CreamPants.png" alt="Pants" height="200" class="imgzoom">
                        <a href="#">Loreva</a>
                        <h5>Loreva HighWaist Pants, Cream</h5>
                        <p>Rp. 459.000</p>
                        <button class="cart-button">
                            <i class='bx bx-cart'></i>
                        </button>
                    </div>
                    <div class="product1">
                        <img src="Home-img/Products/WhiteDress.png" alt="Dress" height="200" class="imgzoom">
                        <a href="#">Loreva</a>
                        <h5>Loreva Woman Floral Dress, White</h5>
                        <p>Rp. 450.000</p>
                        <button class="cart-button">
                            <i class='bx bx-cart'></i>
                        </button>
                    </div>
                    <div class="product1">
                        <img src="Home-img/Products/YellowDress.png" alt="Dress" height="200" class="imgzoom">
                        <a href="#">Loreva</a>
                        <h5>Zeika, Woman Crinkle Dress, Yellow</h5>
                        <p>Rp 200.000</p>
                        <button class="cart-button">
                            <i class='bx bx-cart'></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </section>

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
                <a href="/Dashboard/Product/product.php." class="shop-now-btn">Shop Now</a>
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




    <script src="home.js"></script>
    <!-- SwiperJS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</body>

</html>