<?php
session_start();
include '../../Authentication/db_connect.php'; // Ensure this line is present

// Pastikan pengguna sudah login
if (!isset($_SESSION["user_id"])) {
    header("Location: ../Authentication/sign-in.php");
    exit();
}

$userId = $_SESSION["user_id"];

// Ambil data pengguna dari database
$stmt = $db->prepare("SELECT username, email, first_name, last_name, phone, address, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc(); // Ambil semua data user

// Proses Update Password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newPassword'])) {
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validasi Password Baru
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Password tidak cocok!');</script>";
    } else {
        // Hash password baru
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password di database
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $userId);

        if ($stmt->execute()) {
            echo "<script>alert('Password berhasil diubah. Silakan login kembali.'); window.location.href='../../Authentication/sign-in.php';</script>";
            session_destroy(); // Hapus sesi setelah password diperbarui
            exit();
        } else {
            echo "<script>alert('Gagal mengubah password. Coba lagi.');</script>";
        }
    }
}

// Proses Update Data Profil Pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $profilePicture = $_POST["profile_picture"]; // Foto profil yang dipilih

    // Update data profil
    $stmt = $db->prepare("UPDATE users SET username = ?, email = ?, first_name = ?, last_name = ?, phone = ?, address = ?, profile_picture = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $username, $email, $firstName, $lastName, $phone, $address, $profilePicture, $userId);

    if ($stmt->execute()) {
        // Tambahan: Perbarui sesi dengan foto profil terbaru
        $_SESSION["username"] = $username;
        $_SESSION["profile_picture"] = $profilePicture; // Update session foto profil
        echo "<script>alert('Profil berhasil diperbarui');</script>";
    } else {
        echo "<script>alert('Gagal memperbarui profil');</script>";
    }
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
    <link rel="stylesheet" href="profile.css">

    <title>FashionXP - Profile</title>
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
                    <li><a href="/Comunnity/comunity.html">Community</a></li>
                    <li><a href="../Vouceher/voucher.php">Voucher</a></li>
                    <li><a href="../Products/product.php">Product</a></li>
                </ul>
            </div>
            <div class="nav-icon-list">
                <a href="../Cart/cart.php"><i class='bx bx-cart'></i></a>
            </div>
            <div class="nav-menu-btn">
                <i class='bx bx-menu' onclick="menuFunction()"></i>
            </div>
        </nav>
    </header>

    <main class="profile-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="user-profile">
                <img src="<?php echo isset($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'profile-img/User.png'; ?>"
                    alt="Profile Picture">
                <h2 class="username"><?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
            </div>

            <nav class="sidebar-nav">
                <a href="#" class="nav-item active" data-section="account">
                    <i class='bx bx-user'></i>
                    Account
                </a>
                <a href="#" class="nav-item" data-section="password">
                    <i class='bx bx-lock-alt'></i>
                    Password
                </a>
                <a href="#" class="nav-item" data-section="purchased-item">
                    <i class='bx bx-purchase-tag-alt'></i>
                    Purchased Item
                </a>
            </nav>

            <div class="sidebar-footer">
                <h3 class="cybernauts">CYBERNAUTS</h3>
                <div class="footer-links">
                    <a href="#">About Cybernauts</a> |
                    <a href="#">Privacy Policy</a> |
                    <a href="#">Security</a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <section class="main-content">
            <div class="header-banner">
                <img src="profile-img/Header.png" alt="FashionXP Banner">
            </div>

            <!-- Account Section -->
            <div class="content-account">
                <div class="content-section active" id="account">
                    <div class="profile-form-wrapper">
                        <div class="profile-picture-container">
                            <div class="profile-picture">
                                <img src="<?php echo isset($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'profile-img/User.png'; ?>"
                                    alt="Profile Picture">
                                <button class="camera-btn" onclick="openProfilePicturePopup()">
                                    <i class='bx bx-camera'></i>
                                </button>
                            </div>
                        </div>

                        <div class="profilePicturePopup" id="profilePicturePopup" style="display:none;">
                            <h2>Pilih Foto Profil</h2>
                            <div class="profile-pictures">
                                <img src="../ProfileUseer/profile-img/ProfileDefault/Profile1.png" alt="Profile 1"
                                    onclick="selectProfilePicture('../ProfileUseer/profile-img/ProfileDefault/Profile1.png')">
                                <img src="../ProfileUseer/profile-img/ProfileDefault/Profile2.png" alt="Profile 1"
                                    onclick="selectProfilePicture('../ProfileUseer/profile-img/ProfileDefault/Profile2.png')">
                                <img src="../ProfileUseer/profile-img/ProfileDefault/Profile3.png" alt="Profile 1"
                                    onclick="selectProfilePicture('../ProfileUseer/profile-img/ProfileDefault/Profile3.png')">
                                <img src="../ProfileUseer/profile-img/ProfileDefault/Profile4.png" alt="Profile 1"
                                    onclick="selectProfilePicture('../ProfileUseer/profile-img/ProfileDefault/Profile4.png')">
                            </div>
                            <input type="file" id="uploadProfilePicture" accept="image/*">
                            <button class="btn-profile-img" onclick="uploadProfilePicture()">Upload</button>
                        </div>

                        <form class="profile-form" method="POST" action="profile.php">
                            <!-- Tambahkan method POST -->
                            <!-- Inside the form -->
                            <input type="hidden" name="profile_picture" id="profile_picture"
                                value="<?php echo isset($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : ''; ?>">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstName">First Name</label>
                                    <input type="text" id="firstName" name="firstName"
                                        value="<?php echo isset($user['first_name']) ? htmlspecialchars($user['first_name']) : ''; ?>"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" id="lastName" name="lastName"
                                        value="<?php echo isset($user['last_name']) ? htmlspecialchars($user['last_name']) : ''; ?>"
                                        required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username"
                                    value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email"
                                    value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">No Telp</label>
                                <input type="tel" id="phone" name="phone"
                                    value="<?php echo isset($user['phone']) ? htmlspecialchars($user['phone']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="address">Home Address</label>
                                <textarea id="address" name="address"
                                    rows="4"><?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?></textarea>
                            </div>
                            <button type="submit" class="save-btn">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Password Section -->
            <div class="content-password">
                <div class="content-section" id="password">
                    <div class="profile-form-wrapper">
                        <div class="password-header">
                            <h2>Want To Change<br>Your Password?</h2>
                            <p>"Create a stronger password to secure your account, protect your personal information
                                from unauthorized access, and ensure peace of mind every time you log in."</p>
                        </div>

                        <form class="profile-form" method="POST" action="profile.php">
                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <div class="password-input">
                                    <input type="password" id="newPassword" name="newPassword" required>
                                    <i class='bx bx-hide password-toggle'></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="confirmPassword">Confirm Password</label>
                                <div class="password-input">
                                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                                    <i class='bx bx-hide password-toggle'></i>
                                </div>
                            </div>

                            <button type="submit" class="save-btn">Upgrade Password</button>
                        </form>

                    </div>
                </div>
            </div>

            <!-- Purchase Item -->
            <div class="content-purchased">
                <div class="content-section" id="purchased-item">
                    <div class="profile-form-wrapper">
                        <!-- Empty State -->
                        <div class="purchased-empty" id="purchasedEmpty">
                            <h2>No New Item Here</h2>
                            <p>Come on, buy your new clothes now! What are you waiting for?</p>
                        </div>

                        <!-- Purchased Items List -->
                        <div class="purchased-items" id="purchasedItems">
                            <!-- Sample Item Card -->
                            <div class="purchase-card">
                                <div class="store-info">
                                    <h3>AZ4REA Official Store</h3>
                                    <span class="status">Already Paid</span>
                                </div>

                                <div class="product-info">
                                    <div class="product-image">
                                        <img src="profile-img/PurchasedItem.png" alt="T-Shirt">
                                    </div>
                                    <div class="product-details">
                                        <h4>Unisex Oversize T-Shirt, Light Green</h4>
                                        <p class="quantity">x1</p>
                                    </div>
                                    <div class="price-info">
                                        <p class="total-price">Total Price: <span>Rp 140.000,-</span></p>
                                        <button class="buy-again">Buy Again</button>
                                    </div>
                                </div>

                                <div class="shipping-status">
                                    <p>Items are being shipped</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </section>
    </main>

    <script src="profile.js"></script>

</body>

</html>