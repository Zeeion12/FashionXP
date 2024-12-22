-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Des 2024 pada 10.52
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

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
-- Struktur dari tabel `produk`
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
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `product_photo`, `brand`, `product_name`, `price`, `stock`, `category`, `size_chart`, `description`, `created_at`, `user_id`, `displayed_on_home`, `displayed_on_productpage`) VALUES
(20, '../../AllProduct/BaggyJeans.png', 'Loctra', 'Baggy Jeans Washed Neo Blue', 123.000, 111, 'female', 'L', 'res', '2024-12-18 18:20:37', 17, 1, 0),
(22, '../../AllProduct/KnitSweater.png', 'Loctra', 'Man Knit Vest Green Forest', 200.000, 100, 'male', 'L', 'Baju yang bagus digunakan untuk kehangatan', '2024-12-18 19:50:23', 17, 1, 0),
(26, '../../AllProduct/CardiganJacketBlue.png', 'Vintage', 'Cardigan Jacket Grey Blue Series', 250.000, 400, 'male', 'L', 'Kren abisz', '2024-12-20 16:07:16', 18, 1, 0),
(27, '../../AllProduct/TurtleNeckBrown.png', 'Padan', 'Man Turtleneck clothes, brown', 400.000, 100, 'male', 'L', 'Bagus', '2024-12-20 17:50:33', 21, 1, 0),
(28, '../../AllProduct/RegularCorduroyPantsDarkBrown.png', 'Padan', 'Regular fit Corduroy pants', 230.000, 100, 'unisex', 'L', 'Elevate your casual wardrobe with our Regular Fit Corduroy Pants. Made from soft, durable corduroy fabric, these pants offer a timeless style with a comfortable fit. Perfect for both casual outings and relaxed days at home, they provide flexibility and warmth without compromising on style. The classic corduroy texture and versatile fit make them an essential addition to any wardrobe. Whether paired with a casual tee or dressed up with a button-down shirt, these pants ensure you stay comfortable and stylish all day long.', '2024-12-20 17:52:20', 21, 1, 0),
(30, '../../AllProduct/RegularDenimPants.png', 'LeDnim', 'Regular fit denim pants, blue', 350.000, 100, 'unisex', 'L', 'Bagus ni buat bapak bapak', '2024-12-20 18:01:57', 22, 1, 0),
(31, '../../AllProduct/CargoPantsGreenArmy.png', 'LeDnim', 'Baggy Cargo pants, green army', 400.000, 100, 'unisex', 'L', 'Pakaian skena', '2024-12-20 18:03:13', 22, 1, 0),
(32, '../../AllProduct/GreenDress.png', 'Padan', 'Woman Green Dres', 195.000, 100, 'female', 'M', 'Buat cewek', '2024-12-20 18:13:12', 21, 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
