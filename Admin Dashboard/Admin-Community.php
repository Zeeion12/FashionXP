<?php
session_start();
include '../Authentication/db_connect.php'; // Pastikan file koneksi database ada

// Koneksi ke database
try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Query untuk mengambil data barang
$query = "
    SELECT 
        users.id AS user_id,
        users.username, 
        COUNT(komunitas.id) AS total_products,
        SUM(CASE WHEN komunitas.displayed_on_community = 1 THEN 1 ELSE 0 END) as displayed_count,
        GROUP_CONCAT(komunitas.id) AS product_ids,
        GROUP_CONCAT(komunitas.status) AS product_statuses,
        GROUP_CONCAT(komunitas.displayed_on_community) AS display_statuses
    FROM users 
    LEFT JOIN komunitas ON users.id = komunitas.user_id
    GROUP BY users.id, users.username
";
$stmt = $pdo->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fungsi untuk memperbarui status barang
function updateStatus($productIds, $newStatuses)
{
    global $pdo;
    $stmt = $pdo->prepare("UPDATE komunitas SET status = ? WHERE id = ?");
    foreach ($productIds as $index => $productId) {
        $stmt->execute([$newStatuses[$index], $productId]);
    }
}

// Tangani pembaruan status barang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $productIds = $_POST['product_id'];
    $newStatuses = $_POST['new_status'];
    updateStatus($productIds, $newStatuses);
}

// Tambahkan fungsi ini di bagian atas file setelah koneksi database
function getTotalDisplayedProducts($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM komunitas WHERE displayed_on_community  = 1");
    return $stmt->fetchColumn();
}

function getUserDisplayedProducts($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM komunitas WHERE user_id = ? AND displayed_on_community  = 1");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}

function updateDisplayStatus($pdo, $productId, $action) {
    $totalDisplayed = getTotalDisplayedProducts($pdo);
    
    if ($action === 'add' && $totalDisplayed >= 10) {
        return false; // Maksimum produk yang dapat ditampilkan adalah 10
    }
    
    $newStatus = ($action === 'add') ? 1 : 0;
    $stmt = $pdo->prepare("UPDATE komunitas SET displayed_on_community  = ? WHERE id = ?");
    $stmt->execute([$newStatus, $productId]);
    
    return true;
}

// Handle form submission untuk update display status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_display'])) {
    $productId = $_POST['product_id'];
    $action = $_POST['action'];
    $result = updateDisplayStatus($pdo, $productId, $action);
    if (!$result) {
        $_SESSION['error'] = "Maksimum produk yang dapat ditampilkan adalah 10";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Dapatkan total produk yang ditampilkan untuk counter
$totalDisplayed = getTotalDisplayedProducts($pdo);

// Add this after your existing POST handling code in Admin-Community.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_now'])) {
    // First, reset all displayed_on_community flags
    $resetQuery = "UPDATE komunitas SET displayed_on_community = 0";
    $pdo->query($resetQuery);
    
    // Then, set displayed_on_community = 1 for approved posts, limited to 10
    $updateQuery = "UPDATE komunitas 
                   SET displayed_on_community = 1 
                   WHERE status = 'approved' 
                   ORDER BY created_at DESC 
                   LIMIT 10";
    $pdo->query($updateQuery);
    
    // Redirect back to refresh the page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

?>




<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionXP | Admin-Community</title>

    <!-- Style -->
    <link rel="stylesheet" href="admin-community.css">

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
        <div class="stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 24 24'%3E%3Cpath d='M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z'/%3E%3C/svg%3E"
                        alt="Community">
                </div>
                <div class="stat-info">
                    <h1><?php echo $totalDisplayed; ?>/10</h1>
                    <p>Total Products Displayed</p>
                </div>
            </div>
            <div class="post-product" id="post-product-to-productpage">
                <form method="POST">
                    <input type="hidden" name="post_now" value="1">
                    <button class="btn-post" type="submit">Post Now!</button>
                </form>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Total Prdouct</th>
                        <th>Total Product Displayed</th>
                        <th>ID Product</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): 
                        $productIds = explode(',', $row['product_ids']);
                        $productStatuses = explode(',', $row['product_statuses']);
                        $displayStatuses = explode(',', $row['display_statuses']);
                        foreach ($productIds as $index => $productId):
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_products']); ?></td>
                            <td><?php echo $row['displayed_count']; ?></td>
                            <td><?php echo htmlspecialchars($productId); ?></td>
                            <td>
                                <form method="POST" class="status-form">
                                    <input type="hidden" name="product_id[]" value="<?php echo $productId; ?>">

                                    <select name="new_status[]" onchange="this.form.submit()">
                                        <option value="approved" <?php echo ($productStatuses[$index] == 'approved') ? 'selected' : ''; ?>>
                                            Approved
                                        </option>
                                        <option value="pending" <?php echo ($productStatuses[$index] == 'pending') ? 'selected' : ''; ?>>
                                            Pending
                                        </option>
                                    </select>

                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="update_display" value="1">
                                    <button type="submit" class="delete-btn" <?php echo $displayStatuses[$index] ? '' : 'disabled'; ?>>
                                        <i class='bx bx-minus'></i>
                                    </button>
                                </form>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="update_display" value="1">
                                    <button type="submit" class="add-btn" <?php echo $displayStatuses[$index] ? 'disabled' : ''; ?>>
                                        <i class='bx bx-plus'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    endforeach; 
                    ?>
                </tbody>
        </div>
    </div>

</body>

</html>