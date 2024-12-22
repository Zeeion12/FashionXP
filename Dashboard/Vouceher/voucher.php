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
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher | FashionXP</title>

    <!-- Style -->
    <link rel="stylesheet" href="voucher.css">

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
                    <li><a href="../Community/community.php">Community</a></li>
                    <li><a href="../Products/product.php">Product</a></li>
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

    <div class="container">
        <!-- Left Section -->
        <div class="left-section">
            <h1 class="title">VOUCHERS TO CLAIM</h1>
            <div class="profile-card">
                <div class="profile-info">
                    <div class="avatar">
                        <img src="./voucher-img/profile.jpg" alt="Profile Avatar">
                    </div>
                    <span class="username">@cybernauts</span>
                </div>
                <div class="progress-bar">
                    <div class="progress"></div>
                </div>
            </div>
            <p class="description">
                Earn points by voting and commenting on product ideas. Redeem them for exclusive shopping vouchers!
            </p>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <!-- 2% Voucher -->
            <div class="voucher-card">
                <div class="discount-image">
                    <img src="./voucher-img/voucher_1.png" alt="voucher 1">
                </div>
                <div class="voucher-content">
                    <div class="voucher-description">
                        <h2 class="voucher-title">ALL PRODUCTS IN FASHIONXP</h2>
                        <p class="terms">Can be claimed with a minimum of 40 XP.</p>
                        <p class="terms">Applicable for blazer products only.</p>
                        <p class="terms">Valid for one-time use.</p>
                        <p class="terms">Voucher is valid until December 31, 2024</p>
                    </div>
                    <button class="claim-btn">CLAIM</button>
                </div>
            </div>
            <!-- 2% Voucher -->
            <div class="voucher-card">
                <div class="discount-image">
                    <img src="./voucher-img/voucher_2.png" alt="voucher 1">
                </div>
                <div class="voucher-content">
                    <div class="voucher-description">
                        <h2 class="voucher-title">ALL PRODUCTS IN FASHIONXP</h2>
                        <p class="terms">Can be claimed with a minimum of 40 XP.</p>
                        <p class="terms">Applicable for blazer products only.</p>
                        <p class="terms">Valid for one-time use.</p>
                        <p class="terms">Voucher is valid until December 31, 2024</p>
                    </div>
                    <button class="claim-btn">CLAIM</button>
                </div>
            </div>
            <!-- 2% Voucher -->
            <div class="voucher-card">
                <div class="discount-image">
                    <img src="./voucher-img/voucher_3.png" alt="voucher 1">
                </div>
                <div class="voucher-content">
                    <div class="voucher-description">
                        <h2 class="voucher-title">ALL PRODUCTS IN FASHIONXP</h2>
                        <p class="terms">Can be claimed with a minimum of 40 XP.</p>
                        <p class="terms">Applicable for blazer products only.</p>
                        <p class="terms">Valid for one-time use.</p>
                        <p class="terms">Voucher is valid until December 31, 2024</p>
                    </div>
                    <button class="claim-btn">CLAIM</button>
                </div>
            </div>
            <!-- 2% Voucher -->
            <div class="voucher-card">
                <div class="discount-image">
                    <img src="./voucher-img/voucher_4.png" alt="voucher 1">
                </div>
                <div class="voucher-content">
                    <div class="voucher-description">
                        <h2 class="voucher-title">ALL PRODUCTS IN FASHIONXP</h2>
                        <p class="terms">Can be claimed with a minimum of 40 XP.</p>
                        <p class="terms">Applicable for blazer products only.</p>
                        <p class="terms">Valid for one-time use.</p>
                        <p class="terms">Voucher is valid until December 31, 2024</p>
                    </div>
                    <button class="claim-btn">CLAIM</button>
                </div>
            </div>
            <!-- 2% Voucher -->
            <div class="voucher-card">
                <div class="discount-image">
                    <img src="./voucher-img/voucher_5.png" alt="voucher 1">
                </div>
                <div class="voucher-content">
                    <div class="voucher-description">
                        <h2 class="voucher-title">ALL PRODUCTS IN FASHIONXP</h2>
                        <p class="terms">Can be claimed with a minimum of 40 XP.</p>
                        <p class="terms">Applicable for blazer products only.</p>
                        <p class="terms">Valid for one-time use.</p>
                        <p class="terms">Voucher is valid until December 31, 2024</p>
                    </div>
                    <button class="claim-btn">CLAIM</button>
                </div>
            </div>
            <!-- 2% Voucher -->
            <div class="voucher-card">
                <div class="discount-image">
                    <img src="./voucher-img/voucher_6.png" alt="voucher 1">
                </div>
                <div class="voucher-content">
                    <div class="voucher-description">
                        <h2 class="voucher-title">ALL PRODUCTS IN FASHIONXP</h2>
                        <p class="terms">Can be claimed with a minimum of 40 XP.</p>
                        <p class="terms">Applicable for blazer products only.</p>
                        <p class="terms">Valid for one-time use.</p>
                        <p class="terms">Voucher is valid until December 31, 2024</p>
                    </div>
                    <button class="claim-btn">CLAIM</button>
                </div>
            </div>
            <!-- 2% Voucher -->
            <div class="voucher-card">
                <div class="discount-image">
                    <img src="./voucher-img/voucher_7.png" alt="voucher 1">
                </div>
                <div class="voucher-content">
                    <div class="voucher-description">
                        <h2 class="voucher-title">ALL PRODUCTS IN FASHIONXP</h2>
                        <p class="terms">Can be claimed with a minimum of 40 XP.</p>
                        <p class="terms">Applicable for blazer products only.</p>
                        <p class="terms">Valid for one-time use.</p>
                        <p class="terms">Voucher is valid until December 31, 2024</p>
                    </div>
                    <button class="claim-btn">CLAIM</button>
                </div>
            </div>
            <!-- 2% Voucher -->
            <div class="voucher-card">
                <div class="discount-image">
                    <img src="./voucher-img/voucher_8.png" alt="voucher 1">
                </div>
                <div class="voucher-content">
                    <div class="voucher-description">
                        <h2 class="voucher-title">ALL PRODUCTS IN FASHIONXP</h2>
                        <p class="terms">Can be claimed with a minimum of 40 XP.</p>
                        <p class="terms">Applicable for blazer products only.</p>
                        <p class="terms">Valid for one-time use.</p>
                        <p class="terms">Voucher is valid until December 31, 2024</p>
                    </div>
                    <button class="claim-btn">CLAIM</button>
                </div>
            </div>
        </div>
    </div>

    <script src="app.js"></script>
</body>

</html>