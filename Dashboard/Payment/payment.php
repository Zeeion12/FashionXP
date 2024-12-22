<?php 
session_start();
include '../../Authentication/db_connect.php'; // Ensure this line is present

// Pastikan pengguna sudah login
if (!isset($_SESSION["username"])) {
    header("Location: ../Authentication/sign-in.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch cart items with product details
$query = "SELECT p.product_photo, p.product_name, p.price, c.quantity, (c.quantity * p.price) as total_price 
          FROM keranjang c 
          JOIN produk p ON c.product_id = p.id 
          WHERE c.user_id = ? AND c.status = 'pending'";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Calculate total amount
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
    <link rel="stylesheet" href="payment.css">
    <link rel="stylesheet" href="/Source/Font/font.css">

    <title>FashionXP - Payment</title>
</head>

<!-- Tombol Back -->
<div class="back-button">
    <a href="../Cart/cart.php">
        <button><i class='bx bx-chevron-left'></i></button>
    </a>
</div>

<!-- Payment -->
<section class="payment-wrapper" id="payment">
    <div class="payment-wrapper2">
        <div class="box-payment">
            <h2 class="payment-header">ORDER SUMMARY</h2>
            <div class="box-payment2">

                <!-- Left -->
                <div class="left-desc">
                    <div class="order-items">
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <div class="item">
                                <img src="<?php echo htmlspecialchars($row['product_photo']); ?>" 
                                     alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                                <div class="item-price">
                                    <h4><?php echo htmlspecialchars($row['product_name']); ?></h4>
                                    <p>x<?php echo htmlspecialchars($row['quantity']); ?></p>
                                </div>
                                <p>Rp<?php echo htmlspecialchars($row['total_price']); ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                        
                    <div class="total">
                        <h2>TOTAL</h2>
                        <h2>Rp<?php echo htmlspecialchars($cart_total); ?></h2>
                    </div>

                    <div class="payment-continue">
                        <button>CONTINUE TO SECURE PAYMENT</button>
                    </div>
                    <div class="payment-cancel">
                        <a href="../Cart/cart.php">
                            <button>Cancel Payment</button>
                        </a>
                    </div>
                </div>

                <!-- right -->
                <div class="right-desc">
                    <h2>HOW WOULD YOU LIKE TO PAY</h2>
                    <div class="payment-option">
                        <div class="payment-method">
                            <!-- Opsi1 -->
                            <button><img src="Payment-img/logouang/logo-visa.png" alt="visa"></button>
                            <button><img src="Payment-img/logouang/logo-mastercard.png" alt="mastercard"></button>
                            <button><img src="Payment-img/logouang/logo-bca.png" alt="bca"></button>
                            <button><img src="Payment-img/logouang/logo-bri.png" alt="bri"></button>

                            <!-- Opsi2 -->
                            <button><img src="Payment-img/logouang/logo-bni.png" alt="bni"></button>
                            <button><img src="Payment-img/logouang/logo-mandiri.png" alt="mandiri"></button>
                            <button><img src="Payment-img/logouang/logo-ovo.png" alt="ovo"></button>
                            <button><img src="Payment-img/logouang/logo-dana.png" alt="dana"></button>
                        </div>
                    </div>
                    <p class="payment-notice">
                        Please select your preferred payment method to complete your order. We utilize the latest
                        technology to ensure all transactions are secure and seamless. Make sure your payment details
                        are correct before proceeding to the next step.
                    </p>
                    <footer class="payment-footer">
                        <p>Payment Processed by</p>
                        <div class="cybernauts">CYBERNAUTS</div>
                        <div class="footer-links">
                            <a href="#">About Cybernauts</a> |
                            <a href="#">Privacy Policy</a> |
                            <a href="#">Security</a>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</section>

<body>























</body>

</html>