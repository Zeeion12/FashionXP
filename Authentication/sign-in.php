<?php 
include "../Authentication/db_connect.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionXP - Sign In</title>

    <!-- Style -->
    <link rel="stylesheet" href="auth.css">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Icon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <section class="sign-container">
        <div class="sign-in-wrapper">
            <div class="left-side">
                <div class="logo">FashionXP</div>
                <div class="welcome-text">Welcome <br> Back</div>
                <div class="wave-container">
                    <!-- SVG Element -->
                </div>
            </div>
            <div class="right-side">
                <h1>Sign In</h1>
                <p>Sign in to your account to continue shopping and enjoy personalized features.</p>
                <form method="POST" action="../Authentication/check_login.php">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <div class="button-container">
                        <button type="submit" name="login" class="sign-btn">Sign In</button>
                        <?php if (!empty($loginMessage)): ?>
                            <div class="error-message"><?php echo htmlspecialchars($loginMessage); ?></div>
                        <?php endif; ?>
                    </div>
                    <p class="sign-text">Don’t have an account yet? <a href="sign-up.php">Sign Up</a></p>
                    <p class="sign-text"><a href="../Landing/landing.php">Back To Landing Page</a></p></p>
                </form>
            </div>
        </div>
    </section>
</body>

</html>
