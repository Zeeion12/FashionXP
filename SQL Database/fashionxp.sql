-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2025 at 07:34 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fashionxp`
--

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `status` enum('pending','checked_out') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id`, `user_id`, `product_id`, `quantity`, `status`, `created_at`, `stock`) VALUES
(2, 19, 26, 1, 'pending', '2024-12-20 16:18:06', NULL),
(27, 19, 28, 1, 'pending', '2024-12-21 17:07:38', NULL),
(30, 23, 33, 1, 'pending', '2024-12-23 06:09:33', NULL),
(31, 18, 22, 1, 'pending', '2025-01-03 08:13:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `komunitas`
--

CREATE TABLE `komunitas` (
  `id` int(11) NOT NULL,
  `foto_produk` varchar(255) DEFAULT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `deskripsi_produk` text DEFAULT NULL,
  `status` enum('approved','pending') DEFAULT 'pending',
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `displayed_on_community` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `komunitas`
--

INSERT INTO `komunitas` (`id`, `foto_produk`, `nama_produk`, `deskripsi_produk`, `status`, `user_id`, `created_at`, `displayed_on_community`) VALUES
(23, '../../AllProduct/BrownPants.png', 'AZ4REA Woman Brown Short Pants', 'The AZ4REA Woman Brown Short Pants offer a perfect balance of style and comfort. Made from high-quality, breathable fabric, these shorts feature a flattering fit and a versatile brown tone that pairs effortlessly with various outfits. Whether for a casual outing or a relaxed day at home, these shorts provide an elegant yet laid-back look for any occasion.', 'approved', 18, '2024-12-22 12:10:10', 1),
(24, '../../AllProduct/CreamPants.png', 'AZ4REA Woman Cream Pants', 'The AZ4REA Woman Cream Pants bring effortless elegance and versatility to your wardrobe. Crafted from high-quality, breathable fabric, these pants offer a sleek and flattering fit that ensures both comfort and style. The timeless cream color provides a sophisticated touch, making them perfect for a variety of occasions, from casual outings to semi-formal events. Pair them with your favorite tops and accessories to create effortlessly chic looks every day.', 'approved', 18, '2024-12-22 14:15:36', 1),
(25, '../../AllProduct/CorduroyShirtBlue.png', 'AZ4REA Man Corduroy  Shirt Bue', 'The AZ4REA Man Corduroy Shirt in Blue combines classic charm with modern versatility. Made from premium corduroy fabric, this shirt offers a soft texture and durable quality, ensuring both comfort and style. The rich blue hue adds a refined touch, making it suitable for casual outings or smart-casual events. With its tailored fit and timeless design, this shirt is a must-have staple for every man’s wardrobe.', 'approved', 18, '2024-12-22 14:17:12', 1),
(26, '../../AllProduct/KnitCardigan.png', 'LeDnim Knit Cardigan Cream & Blue', 'The LeDnim Knit Cardigan in Cream & Blue is a stylish blend of comfort and sophistication. Crafted from high-quality knit fabric, it offers a soft and cozy feel, perfect for layering in any season. The elegant combination of cream and blue hues adds a modern touch, making it a versatile choice for casual outings or relaxed evenings. With its timeless design and premium craftsmanship, this cardigan is a must-have for elevating your everyday look.', 'approved', 22, '2024-12-22 14:21:49', 1),
(27, '../../AllProduct/YellowDress.png', 'LeDnim Woman Yellow Dress', 'The LeDnim Woman Yellow Dress radiates effortless charm and vibrant elegance. Made from high-quality, lightweight fabric, this dress offers a comfortable fit that flows beautifully with every step. The cheerful yellow hue adds a touch of brightness, perfect for sunny days or uplifting your evening look. With its timeless silhouette and refined details, this dress is a versatile choice for casual outings, special occasions, or simply making a stylish statement.', 'approved', 22, '2024-12-22 14:23:13', 1),
(29, '../../AllProduct/AlucardVintage.png', 'Nanggong Alucard Tshirt', 'Cantek Kali bah anjaaay', 'approved', 23, '2024-12-23 06:46:39', 1),
(30, '../../AllProduct/RegularCorduroyPantsDarkBrown.png', 'Regular Corduroy Pants Brown', 'Bagus banget celananya menurut kalian gimana', 'approved', 18, '2024-12-23 16:11:08', 1),
(31, '../../AllProduct/BrownSweater.png', 'Brown Sweater', 'Gimana Menurut Kalian', 'pending', 22, '2024-12-24 07:50:26', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `total_amount` decimal(10,3) NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `product_photo` varchar(255) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `price` decimal(10,3) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` enum('male','female','unisex') NOT NULL,
  `size_chart` enum('S','M','L','XL','XXL') NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `displayed_on_home` tinyint(1) DEFAULT 0,
  `displayed_on_productpage` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `product_photo`, `brand`, `product_name`, `price`, `stock`, `category`, `size_chart`, `description`, `created_at`, `user_id`, `displayed_on_home`, `displayed_on_productpage`) VALUES
(20, '../../AllProduct/BaggyJeans.png', 'Loctra', 'Baggy Jeans Washed Neo Blue', 123.000, 111, 'female', 'L', 'res', '2024-12-18 18:20:37', 17, 1, 1),
(22, '../../AllProduct/KnitSweater.png', 'Loctra', 'Man Knit Vest Green Forest', 200.000, 100, 'male', 'L', 'Baju yang bagus digunakan untuk kehangatan', '2024-12-18 19:50:23', 17, 1, 0),
(26, '../../AllProduct/CardiganJacketBlue.png', 'Vintage', 'Cardigan Jacket Grey Blue Series', 250.000, 400, 'male', 'L', 'Kren abisz', '2024-12-20 16:07:16', 18, 1, 0),
(27, '../../AllProduct/TurtleNeckBrown.png', 'Padan', 'Man Turtleneck clothes, brown', 400.000, 100, 'male', 'L', 'Bagus', '2024-12-20 17:50:33', 21, 1, 0),
(28, '../../AllProduct/RegularCorduroyPantsDarkBrown.png', 'Padan', 'Regular fit Corduroy pants', 230.000, 100, 'unisex', 'L', 'Elevate your casual wardrobe with our Regular Fit Corduroy Pants. Made from soft, durable corduroy fabric, these pants offer a timeless style with a comfortable fit. Perfect for both casual outings and relaxed days at home, they provide flexibility and warmth without compromising on style. The classic corduroy texture and versatile fit make them an essential addition to any wardrobe. Whether paired with a casual tee or dressed up with a button-down shirt, these pants ensure you stay comfortable and stylish all day long.', '2024-12-20 17:52:20', 21, 1, 0),
(31, '../../AllProduct/CargoPantsGreenArmy.png', 'LeDnim', 'Baggy Cargo pants, green army', 400.000, 100, 'unisex', 'L', 'Pakaian skena', '2024-12-20 18:03:13', 22, 1, 0),
(32, '../../AllProduct/GreenDress.png', 'Padan', 'Woman Green Dres', 195.000, 100, 'female', 'M', 'Buat cewek', '2024-12-20 18:13:12', 21, 1, 0),
(33, '../../AllProduct/CardiganJacketGrey.png', 'Vintage', 'Cardigan Jacket Grey Series', 320.000, 50, 'unisex', 'L', 'Elevate your wardrobe with the timeless charm of the Cardigan Jacket Grey Series. Designed with comfort and versatility in mind, this stylish cardigan jacket features:\r\nPremium Quality Fabric: Soft, breathable, and durable for all-day wear.\r\nWhether you’re heading to the office, meeting friends, or relaxing at home, the Cardigan Jacket Grey Series is the perfect blend of sophistication and comfort.', '2024-12-21 13:01:43', 18, 1, 0),
(34, '../../AllProduct/PallazoPants.png', 'Nanggong', 'Pazola Pants Woman', 120.000, 10, '', 'L', 'WOKWOWKWOKWOWKK', '2024-12-23 06:43:21', 23, 0, 0),
(40, '../../AllProduct/RippedJeansBlue.png', 'LeDnim', 'Ripped Jeans Blue Sky', 210.000, 100, 'male', 'L', 'Celana Skena', '2024-12-24 07:49:55', 22, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `first_name`, `last_name`, `phone`, `address`, `profile_picture`, `role`) VALUES
(17, 'Garit', 'garitdewana90@gmail.com', '$2y$10$8FkAiKa47I.94uSICf4oWO/kg7rUwqy3fyd.fTz3mVAYC76CODYKG', '2024-12-18 15:05:06', 'Axer', 'Gamer', '', 'Jalan kolenel Sugiono. Gang cempaka No.1\r\nKETAPANG', '/FashionXP/Dashboard/ProfileUseer/profile-img/ProfileDefault/profile3.png', 'user'),
(18, 'GenZe', 'naiga21@gmail.com', '$2y$10$5RiRpz8H67Vsig9/lzGdH.WTdIPnHRkp1pXw6dE9wAA96qhzMxxYW', '2024-12-18 15:20:15', 'Agus', 'Sedih', '122494966', '234', '/FashionXP/Dashboard/ProfileUseer/profile-img/ProfileDefault/profile3.png', 'user'),
(19, 'admin', 'admin@example.com', '$2y$10$1CF7Cr7s/r/uCxrWZ.MiqOGkWafeGHEQukXYvG71HOCVm3fGG6BMa', '2024-12-19 09:18:08', NULL, NULL, NULL, NULL, NULL, 'admin'),
(21, 'AsepJamal', 'nathan123@gmail.com', '$2y$10$0AK8ycPfJHZyV6S245whpuw7vZnjj1YyFbYqfZpo5ma3sCpwbV5/6', '2024-12-20 17:48:59', 'Nathan Maulana', 'Achmahdi', '299596969696', 'Padang', '/FashionXP/Dashboard/ProfileUseer/profile-img/ProfileDefault/profile1.png', 'user'),
(22, 'Zeeion', 'zeeion12@gmail', '$2y$10$fEjOh9abn6.oZl5XgOh2FeTdClHBCocPx62G3obRKYSc3Ss5Q6IOK', '2024-12-20 17:58:16', 'Tio', 'Ganteng', '0896-6865-0591', 'Jalan Kolonel Sugiono', '../ProfileUseer/profile-img/ProfileDefault/Profile2.png', 'user'),
(23, 'Nanggolang', 'utan19@gmail.com', '$2y$10$Gezys9Z9ngpuOSLziNm7n.A2b/V/J0fHpaU8P5QZSj9DjL3SoEYo6', '2024-12-23 06:07:50', 'Sulthan Nanggolang ', 'Paryaman', '12345678910', 'Balik Papan', '../ProfileUseer/profile-img/ProfileDefault/Profile2.png', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `voting`
--

CREATE TABLE `voting` (
  `id` int(11) NOT NULL,
  `komunitas_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vote_type` enum('like','dislike') DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voting`
--

INSERT INTO `voting` (`id`, `komunitas_id`, `user_id`, `vote_type`, `comment`, `created_at`) VALUES
(2, 23, 18, 'like', 'Bagus banget bajunya saya suka', '2024-12-22 13:49:37'),
(3, 23, 17, 'dislike', NULL, '2024-12-22 14:05:05'),
(4, 24, 22, 'like', NULL, '2024-12-22 14:24:29'),
(5, 23, 22, 'like', NULL, '2024-12-22 14:24:42'),
(6, 27, 22, 'like', 'Nice Dress I Like It', '2024-12-22 14:27:56'),
(7, 23, 23, 'like', 'Bajunya elek gini hayoyo', '2024-12-23 06:10:17'),
(8, 25, 18, 'like', 'Bagus', '2025-01-03 08:11:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `komunitas`
--
ALTER TABLE `komunitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `cart_id` (`cart_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `voting`
--
ALTER TABLE `voting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `komunitas_id` (`komunitas_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `komunitas`
--
ALTER TABLE `komunitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `voting`
--
ALTER TABLE `voting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `komunitas`
--
ALTER TABLE `komunitas`
  ADD CONSTRAINT `komunitas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`cart_id`) REFERENCES `keranjang` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `voting`
--
ALTER TABLE `voting`
  ADD CONSTRAINT `voting_ibfk_1` FOREIGN KEY (`komunitas_id`) REFERENCES `komunitas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voting_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
