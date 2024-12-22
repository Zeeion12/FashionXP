<?php 
session_start();
include '../../Authentication/db_connect.php'; // Ensure this line is present

// Pastikan pengguna sudah login
if (!isset($_SESSION["username"])) {
    header("Location: ../Authentication/sign-in.php");
    exit();
}

$user_id = $_SESSION["user_id"]; // Ambil user_id dari session

// Query untuk menghitung jumlah barang di keranjang
$query = "SELECT SUM(quantity) AS total_items FROM keranjang WHERE user_id = ? AND status = 'pending'";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_items = $row['total_items'] ?? 0; // Jika null, set default 0
$stmt->close();



// Add this query before the cart items query to get the total
$total_query = "SELECT SUM(c.quantity * p.price) as cart_total 
                FROM keranjang c 
                JOIN produk p ON c.product_id = p.id 
                WHERE c.user_id = ? AND c.status = 'pending'";
$stmt = $db->prepare($total_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_result = $stmt->get_result();
$total_row = $total_result->fetch_assoc();
$cart_total = $total_row['cart_total'] ?? 0;
$stmt->close();


// Ambil data barang di keranjang
$query = "SELECT c.id, p.product_name, p.brand, p.product_photo, c.quantity, p.price, p.stock, (c.quantity * p.price) AS total_price 
          FROM keranjang c 
          JOIN produk p ON c.product_id = p.id 
          WHERE c.user_id = ? AND c.status = 'pending'";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Tangani request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = $_POST['product_id'] ?? 0;

    if ($action === 'delete' && $product_id) {
        // Prepare and execute delete query
        $delete_query = "DELETE FROM keranjang WHERE id = ? AND user_id = ?";
        $stmt = $db->prepare($delete_query);
        $stmt->bind_param("ii", $product_id, $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete item"]);
        }
        $stmt->close();
        exit;
    } elseif ($action === 'add' || $action === 'subtract') {
        // Determine quantity change
        $quantityChange = ($action === 'add') ? 1 : -1;

        // Check current quantity
        $stmt = $db->prepare("SELECT k.quantity, p.stock 
                            FROM keranjang k 
                            JOIN produk p ON k.product_id = p.id 
                            WHERE k.id = ? AND k.user_id = ?");
        $stmt->bind_param("ii", $product_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        if (!$data) {
            echo json_encode(["status" => "error", "message" => "Product not found"]);
            exit;
        }

        $currentQuantity = $data['quantity'];
        $stockLimit = $data['stock'];
        
        // Calculate new quantity
        $newQuantity = $currentQuantity + $quantityChange;
        
        // Validate new quantity
        if ($newQuantity < 1) {
            echo json_encode(["status" => "error", "message" => "Quantity cannot be less than 1"]);
            exit;
        }
        
        if ($newQuantity > $stockLimit) {
            echo json_encode(["status" => "error", "message" => "Cannot exceed available stock"]);
            exit;
        }

        // Update quantity
        $updateStmt = $db->prepare("UPDATE keranjang SET quantity = ? WHERE id = ? AND user_id = ?");
        $updateStmt->bind_param("iii", $newQuantity, $product_id, $user_id);
        
        if ($updateStmt->execute()) {
            echo json_encode([
                "status" => "success",
                "newQuantity" => $newQuantity,
                "stock" => $stockLimit
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update quantity"]);
        }
        $updateStmt->close();
        exit;
    } else {
        echo json_encode(["status" => "invalid"]);
        exit;
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart | FashionXP</title>

    <!-- Style -->
    <link rel="stylesheet" href="cart.css">

    <!-- Font -->
    <link rel=" preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Icon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="container">
        <section class="cart-section">
            <div class="cart-container">
                <a href="../Home/home.php" class="back-button" aria-label="Go back"><i class='bx bx-chevron-left'></i></a>
                <div class="cart-header">
                    <h1>Shopping Cart</h1>
                    <span><?php echo $total_items; ?> Items</span>
                </div>

                <div class="table-header">
                    <span>PRODUCT DETAILS</span>
                    <span></span>
                    <span>QUANTITY</span>
                    <span>PRICE</span>
                    <span>TOTAL</span>
                </div>
            </div>

            <div class="cart-items">
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <div class="cart-item">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($row['product_photo']); ?>" alt="<?= htmlspecialchars($row['product_name']); ?>">
                        </div>
                        <div class="product-details">
                            <h3><?php echo $row['product_name']; ?></h3>
                            <p><?php echo $row['brand']; ?></p>
                        </div>
                        <div class="quantity-controls">
                            <button class="quantity-btn subtract" data-product-id="<?php echo $row['id']; ?>" 
                                <?php echo $row['quantity'] <= 1 ? 'disabled' : ''; ?>>
                                <i class='bx bx-minus'></i>
                            </button>
                            <span class="quantity"><?php echo $row['quantity']; ?></span>
                            <button class="quantity-btn add" data-product-id="<?php echo $row['id']; ?>" 
                                <?php echo $row['quantity'] >= $row['stock'] ? 'disabled' : ''; ?>>
                                <i class='bx bx-plus'></i>
                            </button>
                        </div>
                        <div class="price">Rp <?php echo htmlspecialchars($row['price']); ?></div>
                        <div class="total">Rp <?php echo htmlspecialchars($row['total_price']); ?></div>
                        <button class="delete-list" data-product-id="<?= htmlspecialchars($row['id']); ?>">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <section class="order-summary">
            <h1>Order Summary</h1>
            <div class="total-amount">
                <div class="summary-row">
                    <span>Total</span>
                    <span>Rp <?php echo htmlspecialchars($cart_total); ?></span>
                </div>
            </div>

            <div class="voucher-section">
                <h3>Select Voucher</h3>
                <select class="voucher-select">
                    <option>Voucher 50%</option>
                </select>
                <div class="apply-btn">
                    <button>Apply</button>
                </div>
            </div>

            <div class="summary-details">
                <div class="summary-container">
                    <div class="summary-row">
                        <h3>Subtotal: Rp <?php echo htmlspecialchars($cart_total); ?></h3>
                    </div>
                </div>
                <div class="summary-row">
                    <span>Total</span>
                    <span>Rp <?php echo htmlspecialchars($cart_total); ?></span>
                </div>
            </div>

            <div class="checkout-btn">
                <a href="../Payment/payment.php">
                    <button>Check Out</button>
                </a>
            </div>
        </section>
    </div>

    <script src="cart.js"></script>
</body>

</html>

