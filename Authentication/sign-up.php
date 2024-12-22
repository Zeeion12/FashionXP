<?php 
include "../Authentication/db_connect.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionXP - Sign Up</title>

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
        <div class="sign-up-wrapper">
            <div class="left-side">
                <div class="logo">FashionXP</div>
                <div class="welcome-text">Let’s Get You Started!</div>
                <div class="wave-container">
                    <!-- SVG Element -->
                </div>
            </div>
            <div class="right-side">
                <h1>Sign Up</h1>
                <form method="POST" action="../Authentication/check_regist.php">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Choose a username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                    </div>
                    <div class="checkbox-btn">
                        <input type="checkbox" id="agree" name="agree" required>
                        <label for="agree">I agree with terms and privacy policy</label>
                    </div>
                    <div class="button-container">
                        <button type="submit" name="register" class="sign-btn">Sign Up</button>
                        <?php if (!empty($registerMessage)): ?>
                            <div class="error-message"><?php echo htmlspecialchars($registerMessage); ?></div>
                        <?php endif; ?>
                    </div>
                    <p class="sign-text">Already have an account? <a href="sign-in.php">Log in here</a></p>
                </form>
            </div>
        </div>
    </section>
</body>

</html>
