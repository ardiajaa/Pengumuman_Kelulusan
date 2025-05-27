-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 27, 2025 at 05:55 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smk_kelulusan`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `terakhir_login` datetime DEFAULT NULL,
  `dibuat_pada` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `email`, `password`, `terakhir_login`, `dibuat_pada`) VALUES
(1, 'Administrator', 'admin@admin.com', '$2y$10$eYjPAdecB3XB1Q4dAPG8au0CLz2sW9IT6REAAHWUyfpr/P7wa39x2', '2025-05-27 05:49:45', '2025-05-27 11:47:48');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_login`
--

CREATE TABLE `riwayat_login` (
  `id` int NOT NULL,
  `admin_id` int NOT NULL,
  `waktu_login` datetime DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_general_ci,
  `lokasi` text COLLATE utf8mb4_general_ci,
  `perangkat` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat_login`
--

INSERT INTO `riwayat_login` (`id`, `admin_id`, `waktu_login`, `ip_address`, `user_agent`, `lokasi`, `perangkat`) VALUES
(1, 1, '2025-05-27 12:11:17', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'Lokasi tidak diketahui', 'Desktop - Google Chrome 136.0.0.0 on Windows'),
(2, 1, '2025-05-27 12:49:56', '192.168.0.74', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Mobile Safari/537.36', 'Lokasi tidak diketahui', 'Mobile - Google Chrome 126.0.0.0 on Linux');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `nama_sekolah` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `tahun_kelulusan` varchar(10) NOT NULL,
  `tanggal_kelulusan` datetime NOT NULL,
  `link_sekolah` varchar(255) DEFAULT NULL,
  `background_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `nama_sekolah`, `logo`, `tahun_kelulusan`, `tanggal_kelulusan`, `link_sekolah`, `background_image`) VALUES
(1, 'SMKN 1 CERME', 'logo-1748314727.png', '2025/2026', '2025-05-20 00:00:00', 'https://smkn1cermegresik.sch.id/', 'bg-1748243512.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `absen` int NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `status` enum('Lulus','Tidak Lulus') NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nisn`, `nama`, `kelas`, `absen`, `tanggal_lahir`, `status`, `foto`) VALUES
(170, '11928', 'Andi', 'XII TKJ 1', 1, '2005-04-11', 'Lulus', '68353907518fb.jpg'),
(171, '11929', 'Bella', 'XII TKJ 1', 2, '2005-09-18', 'Tidak Lulus', 'siswa/68342a88396b8.jpg'),
(172, '11930', 'Cahya', 'XII TKJ 1', 3, '2005-04-25', 'Tidak Lulus', 'siswa/68342a883ab8d.jpg'),
(173, '11931', 'Dian', 'XII TKJ 1', 4, '2005-12-05', 'Lulus', 'siswa/68342a883b5f9.jpg'),
(174, '11932', 'Eko', 'XII TKJ 1', 5, '2005-06-20', 'Tidak Lulus', 'siswa/68342a883bdb8.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `riwayat_login`
--
ALTER TABLE `riwayat_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nisn` (`nisn`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `riwayat_login`
--
ALTER TABLE `riwayat_login`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `riwayat_login`
--
ALTER TABLE `riwayat_login`
  ADD CONSTRAINT `riwayat_login_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
