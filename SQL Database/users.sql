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
-- Struktur dari tabel `users`
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
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `first_name`, `last_name`, `phone`, `address`, `profile_picture`, `role`) VALUES
(17, 'Garit', 'garitdewana90@gmail.com', '$2y$10$8FkAiKa47I.94uSICf4oWO/kg7rUwqy3fyd.fTz3mVAYC76CODYKG', '2024-12-18 15:05:06', 'Axer', 'Gamer', '', 'Jalan kolenel Sugiono. Gang cempaka No.1\r\nKETAPANG', '/FashionXP/Dashboard/ProfileUseer/profile-img/ProfileDefault/profile3.png', 'user'),
(18, 'GenZe', 'naiga21@gmail.com', '$2y$10$5RiRpz8H67Vsig9/lzGdH.WTdIPnHRkp1pXw6dE9wAA96qhzMxxYW', '2024-12-18 15:20:15', 'Agus', 'Sedih', '122494966', '234', '/FashionXP/Dashboard/ProfileUseer/profile-img/ProfileDefault/profile3.png', 'user'),
(19, 'admin', 'admin@example.com', '$2y$10$1CF7Cr7s/r/uCxrWZ.MiqOGkWafeGHEQukXYvG71HOCVm3fGG6BMa', '2024-12-19 09:18:08', NULL, NULL, NULL, NULL, NULL, 'admin'),
(21, 'AsepJamal', 'nathan123@gmail.com', '$2y$10$0AK8ycPfJHZyV6S245whpuw7vZnjj1YyFbYqfZpo5ma3sCpwbV5/6', '2024-12-20 17:48:59', 'Nathan Maulana', 'Achmahdi', '299596969696', 'Padang', '/FashionXP/Dashboard/ProfileUseer/profile-img/ProfileDefault/profile1.png', 'user'),
(22, 'Zeeion', 'zeeion12@gmail', '$2y$10$fEjOh9abn6.oZl5XgOh2FeTdClHBCocPx62G3obRKYSc3Ss5Q6IOK', '2024-12-20 17:58:16', 'Tio', 'Ganteng', '0896-6865-0591', 'Jalan Kolonel Sugiono', '/FashionXP/Dashboard/ProfileUseer/profile-img/ProfileDefault/profile3.png', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
