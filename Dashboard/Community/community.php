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
$profile_picture = isset($user['profile_picture']) && !empty($user['profile_picture']) ? $user['profile_picture'] : '/FashionXP/Dashboard/Home/Home-img/Profile/User.png';
$username = isset($user['username']) ? $user['username'] : 'User';

// Fetch approved and displayed community posts
$query = "SELECT * FROM komunitas WHERE status = 'approved' AND displayed_on_community = 1 ORDER BY created_at DESC LIMIT 10";
$result = $db->query($query);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionXP-Community</title>

    <!-- Style -->
    <link rel="stylesheet" href="community.css">

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
    <!-- Navbar -->
    <header class="navbar-container">
        <nav class="nav-wrapper">
            <div class="nav-logo">
                <p>FashionXP</p>
            </div>
            <div class="nav-list" id="nav-container-list">
                <ul>
                    <li><a href="../Home/home.php">Home</a></li>
                    <li><a href="../Products/product.php">Product</a></li>
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
                                    <img 
                                        src="<?php echo htmlspecialchars($profile_picture); ?>" 
                                        alt="Profile Picture" 
                                        class="profile-dropdown-img"
                                        onerror="this.src='/FashionXP/Dashboard/Home/Home-img/Profile/User.png'"
                                    >
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
    <section class="slider-wrpr" id="Slider">
        <div class="slider-wrpr2">
            <div class="slider1">
            </div>
            <div class="slider2">
            </div>
            <div class="slider-controls">
                <input type="radio" name="slider" id="slide1" checked>
                <input type="radio" name="slider" id="slide2">
            </div>
        </div>
    </section>


    <!-- Voting Section -->
    <section class="voting-section">
        <h2>Your Choice Matters: Vote Now!</h2>
        <div class="product-grid">
            <?php while($post = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($post['foto_produk']); ?>" 
                         alt="<?php echo htmlspecialchars($post['nama_produk']); ?>">
                    <div class="product-wrapper">
                        <h3>Vote for <?php echo htmlspecialchars($post['nama_produk']); ?>!</h3>
                        <a href="voting.php?id=<?php echo $post['id']; ?>" class="vote-btn">VOTE</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
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


    <script src="comm.js"></script>
</body>

</html>