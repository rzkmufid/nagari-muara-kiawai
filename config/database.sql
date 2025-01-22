-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2025 at 09:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

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
-- Table structure for table `historis`
--

CREATE TABLE `historis` (
  `id` int(11) NOT NULL,
  `penduduk_id` int(11) NOT NULL,
  `field` varchar(100) NOT NULL,
  `old_value` varchar(255) NOT NULL,
  `new_value` varchar(255) NOT NULL,
  `changed_at` timestamp NULL DEFAULT current_timestamp(),
  `changed_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `penduduk`
--

CREATE TABLE `penduduk` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `umur` int(11) NOT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user1','user2') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(4, 'admin1', '$2y$10$LRnVjmiP1U1eseN8tC8ejedzSLUrhyJexyuuj6bvg.LMRlmtsB.zq', 'admin', '2024-12-27 16:08:33'),
(5, 'user1', '$2y$10$0CJVP3FnkluH.6D/zEltiufuqeDXkiI9ZtDulVSYOv3PJLaf.YC52', 'user1', '2024-12-27 16:24:27'),
(6, 'user2', '$2y$10$/Y43VnB9hSCszjSO4uJdaOyZzLj.G1KsbGrLoeZIf7waYZqUsllOa', 'user2', '2024-12-27 16:24:47'),
(7, 'admin2', '$2y$10$kxElBi2NIaZs0anKzsWP3u8u9vsQSamwLuq6iHN/KhUfQQ12XYxxy', 'admin', '2024-12-27 16:25:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `demografi`
--
ALTER TABLE `demografi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `historis`
--
ALTER TABLE `historis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penduduk_id` (`penduduk_id`),
  ADD KEY `changed_by` (`changed_by`);

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
-- AUTO_INCREMENT for table `historis`
--
ALTER TABLE `historis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jorong`
--
ALTER TABLE `jorong`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penduduk`
--
ALTER TABLE `penduduk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sarana_prasarana`
--
ALTER TABLE `sarana_prasarana`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `historis`
--
ALTER TABLE `historis`
  ADD CONSTRAINT `historis_ibfk_1` FOREIGN KEY (`penduduk_id`) REFERENCES `penduduk` (`id`),
  ADD CONSTRAINT `historis_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
