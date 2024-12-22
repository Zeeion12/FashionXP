<?php
session_start();
include '../Authentication/db_connect.php'; // Pastikan file koneksi database ada

// Pastikan variabel koneksi database terdefinisi di db_connect.php
if (!isset($hostname, $database_name, $username, $password)) {
    die("Database connection variables are not set in db_connect.php");
}

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch users and their product counts
$query = "SELECT u.id, u.username, COUNT(p.id) AS total_products, GROUP_CONCAT(p.id SEPARATOR ', ') AS product_ids 
          FROM users u 
          LEFT JOIN produk p ON u.id = p.user_id 
          WHERE u.role = 'user' 
          GROUP BY u.id";
$users = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);


// Berguna untuk tombol tambah dan kurang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = (int)$_POST['user_id'];
    $action = $_POST['action'];

    // Validasi user dan produk
    $query = "SELECT COUNT(id) AS total_products FROM produk WHERE user_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Ambil total produk yang sedang ditampilkan
    $totalDisplayed = $_SESSION['display'][$userId] ?? 0;

    if ($action === 'add' && $totalDisplayed < $user['total_products']) {
        $totalDisplayed++;
    } elseif ($action === 'subtract' && $totalDisplayed > 0) {
        $totalDisplayed--;
    }

    // Simpan perubahan ke session
    $_SESSION['display'][$userId] = $totalDisplayed;
}


// Tombol Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_now'])) {
    // Reset semua produk jadi tidak tampil
    $pdo->exec("UPDATE produk SET displayed_on_home = 0");

    // Update produk sesuai "Total Product That Will Be Displayed"
    foreach ($_SESSION['display'] as $userId => $totalDisplayed) {
        $query = "SELECT id FROM produk WHERE user_id = :user_id LIMIT :limit";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $totalDisplayed, PDO::PARAM_INT);
        $stmt->execute();

        // Ambil ID produk yang akan ditampilkan
        $productIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (!empty($productIds)) {
            $productIdsList = implode(',', $productIds);
            $updateQuery = "UPDATE produk SET displayed_on_home = 1 WHERE id IN ($productIdsList)";
            $pdo->exec($updateQuery);
        }
    }

    // Kosongkan session setelah data disimpan
    unset($_SESSION['display']);

    // Redirect ke halaman home.php
    header("Location: ../Dashboard/Home/home.php");
    exit();
}


// Hitung Total Products at Home
$totalProductsAtHome = 0;

if (isset($_SESSION['display'])) {
    $totalProductsAtHome = array_sum($_SESSION['display']); // Jumlah semua produk yang akan ditampilkan
}

// Batas maksimal produk yang bisa ditampilkan di home
$maxProductsAtHome = 16;



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionXP | Admin-Home</title>

    <!-- Style -->
    <link rel="stylesheet" href="admin-home-product.css">

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
    <div class="sidebar">
        <div class="brand">FashionXP <br>Admin.</div>
        <nav>
            <div class="nav-item">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 24 24'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E"
                    alt="Users">
                <a href="./Admin-Users.php">Users</a>
            </div>
            <div class="nav-item">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 24 24'%3E%3Cpath d='M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z'/%3E%3C/svg%3E"
                    alt="Home">
                <a href="./Admin-Home.php">Home</a>
            </div>
            <div class="nav-item">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 24 24'%3E%3Cpath d='M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z'/%3E%3C/svg%3E"
                    alt="Catalog">
                <a href="./Admin-Product.php">Product</a>
            </div>
            <div class="nav-item">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 24 24'%3E%3Cpath d='M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z'/%3E%3C/svg%3E"
                    alt="Community">
                <a href="./Admin-Community.php">Community</a>
            </div>
        </nav>
        <button class="btn-logout"><a href="../Authentication/logout.php">LogOut</a></button>
    </div>

    <div class="main-content">
        <div class="main-content">
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path
                                d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4V6h16v12zM6 12h2v2H6zm0-3h2v2H6zm0-3h2v2H6zm3 6h8v2H9zm0-3h8v2H9zm0-3h8v2H9z" />
                        </svg>
                    </div>
                    <div>
                        <h1><span><?= $totalProductsAtHome ?></span>/<?= $maxProductsAtHome ?></h1>
                        <p>Total Products at Home</p>
                    </div>
                </div>
            </div>
            <!-- Tabel -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Total Product</th>
                            <th>Total Product That Will Displayed</th>
                            <th>ID Product</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>@<?= htmlspecialchars($user['username'] ?? 'Unknown') ?></td>
                                <td><?= htmlspecialchars($user['total_products'] ?? 0) ?></td>
                                <td><?= htmlspecialchars($_SESSION['display'][$user['id']] ?? 0) ?></td>
                                <td><?= htmlspecialchars($user['product_ids'] ?? 'None') ?></td>
                                <td class="edit-btn">
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id'] ?? '') ?>">
                                        <input type="hidden" name="action" value="add">
                                        <button class="add-btn" type="submit"><i class='bx bx-plus'></i></button>
                                    </form>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id'] ?? '') ?>">
                                        <input type="hidden" name="action" value="subtract">
                                        <button class="delete-btn" type="submit"><i class='bx bx-minus'></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="post-product" id="post-product-to-home">
                <form method="POST">
                    <input type="hidden" name="post_now" value="1">
                    <button type="submit">Post Now!</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>