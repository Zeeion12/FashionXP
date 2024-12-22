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

// Ambil data produk untuk ditampilkan
$stmt = $db->prepare("SELECT * FROM produk WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$products = $stmt->get_result(); // Pastikan ini didefinisikan  

// Tambahkan logika untuk menyimpan produk baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['brandProduct'])) {
    // Hanya proses form produk
    $brand = $_POST['brandProduct'];
    $productName = $_POST['productName'];
    $price = $_POST['productPrice'];
    $stock = $_POST['productStock'];
    $category = $_POST['productSize'];
    $sizeChart = $_POST['sizeChart'];
    $description = $_POST['productDescription'];

    // Proses upload foto produk
    if (!empty($_FILES["productPhoto"]["name"])) {
        $targetDir = "../../AllProduct/";
        $targetFile = $targetDir . basename($_FILES["productPhoto"]["name"]);
        move_uploaded_file($_FILES["productPhoto"]["tmp_name"], $targetFile);
    } else {
        $targetFile = null;
    }

    // Simpan data produk ke database
    $stmt = $db->prepare("INSERT INTO produk (product_photo, brand, product_name, price, stock, category, size_chart, description, user_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdisssi", $targetFile, $brand, $productName, $price, $stock, $category, $sizeChart, $description, $userId);
    $stmt->execute();
}
// Tambahkan logika untuk mengedit produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $editId = $_POST['edit_id'];
    $brand = $_POST['brandProduct'];
    $productName = $_POST['productName'];
    $price = $_POST['productPrice'];
    $stock = $_POST['productStock'];
    $category = $_POST['productSize'];
    $sizeChart = $_POST['sizeChart'];
    $description = $_POST['productDescription'];

    // Proses upload foto produk jika ada
    if (!empty($_FILES["productPhoto"]["name"])) {
        $targetDir = "../../AllProduct/";
        $targetFile = $targetDir . basename($_FILES["productPhoto"]["name"]);
        move_uploaded_file($_FILES["productPhoto"]["tmp_name"], $targetFile);
    } else {
        $targetFile = $_POST['existing_photo']; // Ambil foto yang sudah ada
    }

    // Update data produk di database
    $stmt = $db->prepare("UPDATE produk SET product_photo = ?, brand = ?, product_name = ?, price = ?, stock = ?, category = ?, size_chart = ?, description = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sssdisssii", $targetFile, $brand, $productName, $price, $stock, $category, $sizeChart, $description, $editId, $userId);
    $stmt->execute();
}
// Tambahkan logika untuk menghapus produk
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $stmt = $db->prepare("DELETE FROM produk WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $deleteId, $userId);
    $stmt->execute();
    header("Location: store.php"); // Redirect setelah menghapus
    exit();
}


// Tambahkan logika untuk menyimpan komunitas baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_produk'])) {
    // Hanya proses form komunitas
    $productName = $_POST['nama_produk'];
    $description = $_POST['deskripsi_produk'];

    // Proses upload foto komunitas
    if (!empty($_FILES["foto_produk"]["name"])) {
        $targetDir = "../../AllProduct/";
        $targetFile = $targetDir . basename($_FILES["foto_produk"]["name"]);
        move_uploaded_file($_FILES["foto_produk"]["tmp_name"], $targetFile);
    } else {
        $targetFile = null;
    }

    // Simpan data komunitas ke database
    $stmt = $db->prepare("INSERT INTO komunitas (foto_produk, nama_produk, deskripsi_produk, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $targetFile, $productName, $description, $userId);
    $stmt->execute();
}

// Tambahkan logka untuk mengedit posting komunitas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_community_id'])) {
    $editCommunityId = $_POST['edit_community_id'];
    $productName = $_POST['nama_produk'];
    $description = $_POST['deskripsi_produk'];

    // Proses upload foto komunitas jika ada
    if (!empty($_FILES["foto_produk"]["name"])) {
        $targetDir = "../../AllProduct/";
        $targetFile = $targetDir . basename($_FILES["foto_produk"]["name"]);
        move_uploaded_file($_FILES["foto_produk"]["tmp_name"], $targetFile);
    } else {
        // Hapus bagian ini
        // $targetFile = $_POST['existing_photo']; // Ambil foto yang sudah ada
        $targetFile = null; // Atau Anda bisa mengatur ke null jika tidak ada foto baru
    }

    // Update data komunitas di database
    $stmt = $db->prepare("UPDATE komunitas SET foto_produk = ?, nama_produk = ?, deskripsi_produk = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssiii", $targetFile, $productName, $description, $editCommunityId, $userId);
    $stmt->execute();
}
// Fetch community posts to display
$stmt = $db->prepare("SELECT * FROM komunitas WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$communityPosts = $stmt->get_result();

// Tambahkan logika untuk menghapus posting komunitas
if (isset($_GET['delete_community_id'])) {
    $deleteCommunityId = $_GET['delete_community_id'];
    $stmt = $db->prepare("DELETE FROM komunitas WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $deleteCommunityId, $userId);
    $stmt->execute();
    header("Location: store.php"); // Redirect setelah menghapus
    exit();
}

// Update the query to fetch community posts to include the status
$stmt = $db->prepare("SELECT * FROM komunitas WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$communityPosts = $stmt->get_result();

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
    <link rel="stylesheet" href="store.css">

    <title>FashionXP - Your Store</title>
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
                <a href="#" class="nav-item active" data-section="store">
                    <i class='bx bxs-store-alt'></i>
                    Your Store
                </a>
                <a href="#" class="nav-item" data-section="form-brand">
                    <i class='bx bx-table'></i>
                    Start Your Brand
                </a>
                <a href="#" class="nav-item" data-section="community">
                    <i class='bx bx-chat'></i>
                    Ask The Community
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
                <img src="store-img/Header.png" alt="FashionXP Banner">
            </div>

            <div id="store" class="content-section">
                <div class="store-content">
                    <div id="productsContainer" class="products-grid">
                        <?php if (isset($products) && $products->num_rows > 0): ?>

                        <div class="products-header">
                            <h2>Your Product</h2>
                        </div>
                        <div class="product-card-container">
                            <?php while ($product = $products->fetch_assoc()): ?>
                            <div class="product-card">
                                <img src="<?php echo htmlspecialchars($product['product_photo']); ?>"
                                    class="product-image"
                                    alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                <div class="product-info">
                                    <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                                    <div class="product-name"><?php echo htmlspecialchars($product['product_name']); ?>
                                    </div>
                                    <div class="product-price"><?php echo htmlspecialchars($product['price']); ?></div>
                                    <a href="?delete_id=<?php echo $product['id']; ?>"
                                        class="delete-product-btn">Delete</a> <!-- Tombol hapus -->
                                    <a href="#" class="edit-product-btn" data-id="<?php echo $product['id']; ?>"
                                        data-brand="<?php echo htmlspecialchars($product['brand']); ?>"
                                        data-name="<?php echo htmlspecialchars($product['product_name']); ?>"
                                        data-price="<?php echo htmlspecialchars($product['price']); ?>"
                                        data-stock="<?php echo htmlspecialchars($product['stock']); ?>"
                                        data-category="<?php echo htmlspecialchars($product['category']); ?>"
                                        data-size="<?php echo htmlspecialchars($product['size_chart']); ?>"
                                        data-description="<?php echo htmlspecialchars($product['description']); ?>">Edit</a>
                                    <!-- Tombol edit -->
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <?php else: ?>
                        <div id="emptyState" class="store-empty">
                            <h2>There's Nothing Here</h2>
                            <p>Let's Go Start Your New Journey With Us,<br>Make Your Own Brand Popular</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="content-store">
                <div id="form-brand" class="content-section" style="display: none;">
                    <div class="brand-form-wrapper">
                        <h2>Start Your Own Brand</h2>
                        <form id="productForm" method="POST" enctype="multipart/form-data">
                            <div class="photo-upload">
                                <div class="upload-box" id="uploadBox"
                                    onclick="document.getElementById('productPhoto').click();">
                                    <i class='bx bx-plus'></i>
                                    <p>Insert Photo For The Product</p>
                                </div>
                                <input type="file" name="productPhoto" id="productPhoto" hidden accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="brandProduct">Brand Product</label>
                                <input type="text" name="brandProduct" id="brandProduct" required>
                            </div>
                            <div class="form-group">
                                <label for="productName">Name Of The Product</label>
                                <input type="text" name="productName" id="productName" required>
                            </div>
                            <div class="form-group">
                                <label for="productPrice">Price</label>
                                <input type="number" name="productPrice" id="productPrice" required>
                            </div>
                            <div class="form-group">
                                <label for="productStock">Stock Of The Product</label>
                                <input type="number" name="productStock" id="productStock" required>
                            </div>
                            <div class="form-group">
                                <label for="productSize">Category</label>
                                <select name="productSize" id="productSize" required>
                                    <option value="">Select Category</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Unisex">Unisex</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sizeChart">Size Chart</label>
                                <select name="sizeChart" id="sizeChart" required>
                                    <option value="">Select Size</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="productDescription">Description</label>
                                <textarea name="productDescription" id="productDescription" rows="4"
                                    required></textarea>
                            </div>
                            <button type="submit" class="post-product-btn">Post Your Product</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="content-community">
                <div id="community" class="content-section">
                    <div class="community-form-container">
                        <h2 id="form-title">Ask The Community</h2>
                        <form action="store.php" method="POST" enctype="multipart/form-data" class="community-form"
                            id="communityForm">
                            <div class="community-input-group">
                                <div class="upload-box" id="uploadBox"
                                    onclick="document.getElementById('foto_produk').click();">
                                    <i class='bx bx-plus'></i>
                                    <p>Insert Photo For The Product</p>
                                </div>
                                <input type="file" name="foto_produk" id="foto_produk" hidden accept="image/*">
                            </div>
                            <div class="community-input-group">
                                <label for="nama_produk">Name of Product</label>
                                <input type="text" name="nama_produk" id="nama_produk" required>
                            </div>
                            <div class="community-input-group">
                                <label for="deskripsi_produk">Description</label>
                                <textarea name="deskripsi_produk" id="deskripsi_produk" rows="4" required></textarea>
                            </div>
                            <input type="hidden" name="edit_community_id" id="editCommunityId">
                            <!-- Hidden field for edit -->
                            <button type="submit" class="community-submit-btn">Post to Community</button>
                            <!-- Teks tombol ini akan diubah oleh JavaScript -->
                        </form>
                    </div>

                    <div class="community-card-container">
                        <h2>Your Community Posts</h2>
                        <?php if ($communityPosts->num_rows > 0): ?>
                        <?php while ($post = $communityPosts->fetch_assoc()): ?>
                        <div class="card-community">
                            <div class="card-community-content">
                                <div class="card-community-image">
                                    <img src="<?php echo htmlspecialchars($post['foto_produk']); ?>"
                                        alt="Photo Of The Product">
                                </div>
                                <div class="card-info">
                                    <div class="product-header">
                                        <h2 class="product-title"><?php echo htmlspecialchars($post['nama_produk']); ?>
                                        </h2>
                                        <div class="product-actions">
                                            <button class="edit-button" data-id="<?php echo $post['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($post['nama_produk']); ?>"
                                                data-description="<?php echo htmlspecialchars($post['deskripsi_produk']); ?>">Edit</button>
                                            <button class="delete-button"><a
                                                    href="?delete_community_id=<?php echo $post['id']; ?>"
                                                    class="delete-community-btn">Delete</a></button>
                                        </div>
                                    </div>
                                    <p class="product-description">
                                        <?php echo htmlspecialchars($post['deskripsi_produk']); ?></p>
                                    <div class="product-status">
                                        <span class="status-label">Status: </span>
                                        <span class="status-<?php echo strtolower($post['status']); ?>">
                                            <?php echo ucfirst($post['status']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button class="check-button">Check Your Post</button>
                        </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <div id="emptyState" class="store-empty">
                            <h2>There's Nothing Here</h2>
                            <p>Let's Go Start Your New Journey With Us,<br>Make Your Own Brand Popular</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="store.js"></script>

</body>

</html>