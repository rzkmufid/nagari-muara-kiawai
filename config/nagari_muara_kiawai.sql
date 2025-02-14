-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2025 at 05:53 AM
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
-- Database: `nagari_muara_kiawai`
--

-- --------------------------------------------------------

--
-- Table structure for table `demografi`
--

CREATE TABLE `demografi` (
  `id` int(11) NOT NULL,
  `ketinggian` decimal(10,2) NOT NULL,
  `luas_wilayah` decimal(10,2) NOT NULL,
  `batas_utara` varchar(100) DEFAULT NULL,
  `batas_selatan` varchar(100) DEFAULT NULL,
  `batas_timur` varchar(100) DEFAULT NULL,
  `batas_barat` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `jenis_kelamin` varchar(100) NOT NULL,
  `umur` int(11) NOT NULL,
  `pekerjaan` varchar(50) NOT NULL,
  `jorong` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `nama`, `nik`, `jenis_kelamin`, `umur`, `pekerjaan`, `jorong`, `created_at`) VALUES
(4, 'asd', '3453', 'Laki-laki', 36, 'Petani', 'qwe', '2025-01-27 15:12:16');

-- --------------------------------------------------------

--
-- Table structure for table `jorong`
--

CREATE TABLE `jorong` (
  `id` int(11) NOT NULL,
  `nama_jorong` varchar(100) NOT NULL,
  `kepala_jorong` varchar(100) NOT NULL,
  `luas_wilayah` decimal(10,2) NOT NULL,
  `jumlah_kk` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jorong`
--

INSERT INTO `jorong` (`id`, `nama_jorong`, `kepala_jorong`, `luas_wilayah`, `jumlah_kk`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'qwe', 'qwe', 324.00, 3, 'qwe', '2025-01-26 19:55:22', '2025-01-26 19:55:22');

-- --------------------------------------------------------

--
-- Table structure for table `penduduk`
--

CREATE TABLE `penduduk` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tempat_lahir` text NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `pekerjaan` varchar(50) NOT NULL,
  `jorong` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sarana_prasarana`
--

CREATE TABLE `sarana_prasarana` (
  `id` int(11) NOT NULL,
  `jenis` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `kondisi` enum('Baik','Kurang Baik','Rusak') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sarana_prasarana`
--

INSERT INTO `sarana_prasarana` (`id`, `jenis`, `jumlah`, `kondisi`, `keterangan`, `created_at`) VALUES
(1, 'asd', 2, 'Baik', 'asd', '2025-01-26 22:57:01'),
(2, 'asdasd', 23, 'Rusak', 'asd', '2025-01-26 22:57:14'),
(3, 'asd', 2, 'Kurang Baik', '22', '2025-01-29 03:42:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','wali_nagari') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(4, 'admin1', '$2y$10$LRnVjmiP1U1eseN8tC8ejedzSLUrhyJexyuuj6bvg.LMRlmtsB.zq', 'admin', '2024-12-27 09:08:33'),
(5, 'user1', '$2y$10$0CJVP3FnkluH.6D/zEltiufuqeDXkiI9ZtDulVSYOv3PJLaf.YC52', 'user', '2024-12-27 09:24:27'),
(6, 'user2', '$2y$10$/Y43VnB9hSCszjSO4uJdaOyZzLj.G1KsbGrLoeZIf7waYZqUsllOa', 'user', '2024-12-27 09:24:47'),
(7, 'admin2', '$2y$10$kxElBi2NIaZs0anKzsWP3u8u9vsQSamwLuq6iHN/KhUfQQ12XYxxy', 'admin', '2024-12-27 09:25:20'),
(13, 'wali', '$2y$10$fZ9EKrbpsKk2WFQz3rieV.bH2nqV4WojFT0Z5B.1Qd5eszc1WOQGm', 'wali_nagari', '2025-01-26 22:49:30'),
(14, 'user', '$2y$10$nSCu6ps2WWhCFR7rCabdtuMWx8BWSp2k/zB8FPXfDVn3PxQp.yNAi', 'user', '2025-01-26 23:07:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `demografi`
--
ALTER TABLE `demografi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jorong`
--
ALTER TABLE `jorong`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penduduk`
--
ALTER TABLE `penduduk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indexes for table `sarana_prasarana`
--
ALTER TABLE `sarana_prasarana`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `demografi`
--
ALTER TABLE `demografi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jorong`
--
ALTER TABLE `jorong`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `penduduk`
--
ALTER TABLE `penduduk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sarana_prasarana`
--
ALTER TABLE `sarana_prasarana`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
