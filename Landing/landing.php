<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionXP</title>

    <!-- Style -->
    <link rel="stylesheet" href="landing.css">
    <link rel="stylesheet" href="/Source/Font/font.css">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Icon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="main">
    <header class="navbar-container">
        <nav class="nav-wrapper">
            <div class="nav-logo">
                <p>FashionXP</p>
            </div>
            <div class="nav-list" id="nav-container-list">
                <ul>
                    <li><a href="#About-Us">About Us</a></li>
                    <li><a href="#Community">Community</a></li>
                    <li><a href="#Product">Product</a></li>
                </ul>
            </div>
            <a href="../Authentication/sign-in.php" class="nav-login-btn">
                <button>Login</button>
            </a>
            <div class="nav-menu-btn">
                <i class='bx bx-menu' onclick="menuFunction()"></i>
            </div>
        </nav>
    </header>

    <main>
        <!-- Hero -->
        <section class="hero-container" id="Hero">
            <div class="hero-wrapper">
                <div class="hero-content">
                    <div class="hero-title">
                        <h2>"Élegance <br> Réinventée “ </h2>
                    </div>
                    <div class="hero-description">
                        <p>Discover a World Beyond Trends, with Curated Collections Designed to Highlight Your Unique
                            Style
                            and Express Your True Identity"
                        </p>
                    </div>
                    <a href="../Authentication/sign-in.php" class="hero-btn">
                        <button>Shop Now</button>
                    </a>
                </div>
                <div class="hero-img">
                    <img src="./Landing-img/hero_Img_Profile.png" alt="Hero-Image">
                </div>
            </div>
            <div class="landing-footer">
                <div class="footer-warp">
                    <div class="scrolling-text">
                        <p>"Welcome to our world of haute couture and élégance! Here, we believe fashion is an art form
                            that speaks without words. Discover curated collections that blend chic style with timeless
                            grace. Bienvenue – let your style journey begin!"</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About -->
        <section class="about-container" id="About-Us">
            <div class="about-wrapper">
                <div class="about-card">
                    <h4>About Us</h4>
                    <div class="about-description">
                        <h2><span>Level Up</span> Your Fashion<br>Journey with Us</h2>
                        <p>Our e-commerce platform is designed to facilitate direct interaction between sellers and
                            buyers,
                            enabling users to provide feedback on new product ideas. With a point-based gamification
                            system
                            that can be redeemed for discount vouchers, we boost user engagement and loyalty while
                            helping
                            brands develop products aligned with market preferences.</p>
                    </div>
                </div>
                <div class="explore-card">
                    <div class="explore-img">
                        <img src="./Landing-img/About_Img.png" alt="About-img">
                    </div>
                    <div class="explore-button">
                        <p>Have a say in upcoming products by voting for your favorites.</p>
                        <a href="../Authentication/sign-in.php" class="explore-btn">
                            <button>Explore Now</button>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Community -->
        <section class="community-container" id="Community">
            <div class="community-wrapper">
                <div class="community-img">
                    <img src="./Landing-img/Community_Img.png" alt="Community-Image">
                </div>
                <div class="community-description">
                    <h2>CONNECT AND CREATE <br>IN OUR STYLE COMMUNITY</h2>
                    <p>Our Community lets sellers and buyers collaborate to shape new products. Sellers can share
                        product ideas and get direct feedback from buyers through polls, allowing everyone to have a say
                        in upcoming releases. Join us to help create products you’ll love.</p>
                    <a href="../Authentication/sign-in.php" class="btn-community">
                        <button>JOIN OUR COMMUNITY</button>
                    </a>
                </div>
            </div>
        </section>

        <!-- Best Selling -->
        <svg id="wave" style="transform:rotate(0deg); transition: 0.3s" viewBox="0 0 1440 490" version="1.1"
            xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="sw-gradient-0" x1="0" x2="0" y1="1" y2="0">
                    <stop stop-color="rgba(193, 222, 248, 1)" offset="0%"></stop>
                    <stop stop-color="rgba(255, 255, 255, 1)" offset="100%"></stop>
                </linearGradient>
            </defs>
            <path style="transform:translate(0, 0px); opacity:1" fill="url(#sw-gradient-0)"
                d="M0,147L80,179.7C160,212,320,278,480,326.7C640,376,800,408,960,367.5C1120,327,1280,212,1440,155.2C1600,98,1760,98,1920,114.3C2080,131,2240,163,2400,155.2C2560,147,2720,98,2880,65.3C3040,33,3200,16,3360,57.2C3520,98,3680,196,3840,236.8C4000,278,4160,261,4320,269.5C4480,278,4640,310,4800,318.5C4960,327,5120,310,5280,310.3C5440,310,5600,327,5760,334.8C5920,343,6080,343,6240,359.3C6400,376,6560,408,6720,375.7C6880,343,7040,245,7200,187.8C7360,131,7520,114,7680,147C7840,180,8000,261,8160,261.3C8320,261,8480,180,8640,163.3C8800,147,8960,196,9120,204.2C9280,212,9440,180,9600,187.8C9760,196,9920,245,10080,269.5C10240,294,10400,294,10560,277.7C10720,261,10880,229,11040,228.7C11200,229,11360,261,11440,277.7L11520,294L11520,490L11440,490C11360,490,11200,490,11040,490C10880,490,10720,490,10560,490C10400,490,10240,490,10080,490C9920,490,9760,490,9600,490C9440,490,9280,490,9120,490C8960,490,8800,490,8640,490C8480,490,8320,490,8160,490C8000,490,7840,490,7680,490C7520,490,7360,490,7200,490C7040,490,6880,490,6720,490C6560,490,6400,490,6240,490C6080,490,5920,490,5760,490C5600,490,5440,490,5280,490C5120,490,4960,490,4800,490C4640,490,4480,490,4320,490C4160,490,4000,490,3840,490C3680,490,3520,490,3360,490C3200,490,3040,490,2880,490C2720,490,2560,490,2400,490C2240,490,2080,490,1920,490C1760,490,1600,490,1440,490C1280,490,1120,490,960,490C800,490,640,490,480,490C320,490,160,490,80,490L0,490Z">
            </path>
        </svg>
        <section class="product-container" id="Product">
            <div class="product-wrapper">
                <div class="best-sell-container">
                    <div class="best-sell-header">
                        <h1>Our Best Selling on This<br>Month!</h1>
                    </div>
                    <div class="card-container">
                        <!-- Product Card 1 -->
                        <div class="product-card">
                            <h3>Lapel Jacket</h3>
                            <p class="price">IDR 723.500</p>
                            <hr>
                            <div class="product-image">
                                <img src="./Landing-img/product-1-Img.png" alt="Lapel Jacket">
                            </div>
                            <button class="cart-btn">
                                <i class='bx bx-cart'></i>
                            </button>
                        </div>
                        <!-- Product Card 2 -->
                        <div class="product-card">
                            <h3>Leather Jacket</h3>
                            <p class="price">IDR 499.000</p>
                            <hr>
                            <div class="product-image">
                                <img src="./Landing-img/product-2-Img.png" alt="Leather Jacket">
                            </div>
                            <button class="cart-btn">
                                <i class='bx bx-cart'></i>
                            </button>
                        </div>
                        <!-- Product Card 3 -->
                        <div class="product-card">
                            <h3>Winter Coat</h3>
                            <p class="price">IDR 680.000</p>
                            <hr>
                            <div class="product-image">
                                <img src="./Landing-img/product-3-Img.png" alt="Winter Coat">
                            </div>
                            <button class="cart-btn">
                                <i class='bx bx-cart'></i>
                            </button>
                        </div>
                        <!-- Product Card 4 -->
                        <div class="product-card">
                            <h3>Leather Pants</h3>
                            <p class="price">IDR 879.900</p>
                            <hr>
                            <div class="product-image">
                                <img src="./Landing-img/product-4-Img.png" alt="Leather Pants">
                            </div>
                            <button class="cart-btn">
                                <i class='bx bx-cart'></i>
                            </button>
                        </div>
                        <!-- Product Card 5 -->
                        <div class="product-card">
                            <h3>Ribbon Jeans</h3>
                            <p class="price">IDR 399.000</p>
                            <hr>
                            <div class="product-image">
                                <img src="./Landing-img/product-5-Img.png" alt="Ribbon Jeans">
                            </div>
                            <button class="cart-btn">
                                <i class='bx bx-cart'></i>
                            </button>
                        </div>
                        <!-- Product Card 6 -->
                        <div class="product-card">
                            <h3>Cargo Pants</h3>
                            <p class="price">IDR 250.000</p>
                            <hr>
                            <div class="product-image">
                                <img src="./Landing-img/product-6-Img.png" alt="Cargo Pants">
                            </div>
                            <button class="cart-btn">
                                <i class='bx bx-cart'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <svg id="wave" xmlns="http://www.w3.org/2000/svg" width="1440" height="204" viewBox="0 0 1440 204"
                fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M0 0L39.6 14C80.4 28 159.6 56 240 63C320.4 70 399.6 56 480 45.5C560.4 35 639.6 28 720 38.5C800.4 49 879.6 77 960 73.5C1040.4 70 1119.6 35 1200 31.5C1280.4 28 1359.6 56 1400.4 70L1440 84V189H1400.4C1359.6 189 1280.4 189 1200 189C1119.6 189 1040.4 189 960 189C879.6 189 800.4 189 720 189C639.6 189 560.4 189 480 189C399.6 189 320.4 189 240 189C159.6 189 80.4 189 39.6 189H0V0Z"
                    fill="#98C5E8" />
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M0 169.556L34.8 160.945C68.4 152.334 136.8 135.111 205.2 117.889C274.8 100.667 343.2 83.4449 411.6 86.3153C480 89.1856 548.4 112.149 616.8 109.278C685.2 106.408 754.8 77.7042 823.2 89.1856C891.6 100.667 960 152.334 1028.4 166.685C1096.8 181.037 1165.2 158.074 1234.8 146.593C1303.2 135.111 1371.6 135.111 1405.2 135.111H1440V204H1405.2C1371.6 204 1303.2 204 1234.8 204C1165.2 204 1096.8 204 1028.4 204C960 204 891.6 204 823.2 204C754.8 204 685.2 204 616.8 204C548.4 204 480 204 411.6 204C343.2 204 274.8 204 205.2 204C136.8 204 68.4 204 34.8 204H0V169.556Z"
                    fill="#C1DEF8" />
            </svg>
        </section>
    </main>

    <footer class="footer">
        <div class="cta-section">
            <div>
                <h2>Ready to shop?</h2>
                <p>Discover the best products here!</p>
            </div>
            <div>
                <a href="../Authentication/sign-in.php" class="shop-now-btn">Shop Now</a>
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


    <script src="Source/Script/app.js"></script>
</body>

</html>