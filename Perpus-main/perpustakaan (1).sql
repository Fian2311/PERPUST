-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 12, 2026 at 06:52 AM
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
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int NOT NULL,
  `nama` text NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `nama`, `username`, `password`) VALUES
(1, 'Administrator Utama', 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `tb_buku`
--

CREATE TABLE `tb_buku` (
  `id_buku` int NOT NULL,
  `judul_buku` varchar(100) NOT NULL,
  `pengarang` varchar(100) NOT NULL,
  `penerbit` varchar(100) NOT NULL,
  `tahun_terbit` year DEFAULT NULL,
  `stok` int DEFAULT NULL,
  `status_tampil` enum('Ya','Tidak') NOT NULL DEFAULT 'Ya'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_buku`
--

INSERT INTO `tb_buku` (`id_buku`, `judul_buku`, `pengarang`, `penerbit`, `tahun_terbit`, `stok`, `status_tampil`) VALUES
(2, 'Belajar MVC', 'Rina', 'Media Kita', 2024, 4, 'Ya'),
(4, 'belajar css', 'kalom', 'Media kita', 2023, 5, 'Ya'),
(5, 'ppkn', 'listy', 'Media kita', 2021, 10, 'Ya'),
(7, 'INDONESIA', 'ALAN', 'ARIIQ', 2020, 99, 'Ya'),
(11, 'Temanku karbit', 'yanto', 'ptnimay', 2021, 15, 'Ya');

-- --------------------------------------------------------

--
-- Table structure for table `tb_peminjaman`
--

CREATE TABLE `tb_peminjaman` (
  `id_peminjaman` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_buku` int DEFAULT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `STATUS` varchar(50) NOT NULL DEFAULT 'Menunggu Persetujuan',
  `id_admin` int DEFAULT NULL,
  `alasan_penolakan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_peminjaman`
--

INSERT INTO `tb_peminjaman` (`id_peminjaman`, `id_user`, `id_buku`, `tanggal_pinjam`, `tanggal_kembali`, `STATUS`, `id_admin`, `alasan_penolakan`) VALUES
(728110, 1, 2, '2026-02-06', '2026-02-13', 'Dikembalikan', NULL, NULL),
(728112, 1, 4, '2026-02-09', '2026-02-23', 'Ditolak', NULL, NULL),
(728113, 1, 2, '2026-02-09', '2026-02-16', 'Ditolak', NULL, NULL),
(728114, 1, 2, '2026-02-09', '2026-02-11', 'Dikembalikan', NULL, NULL),
(728115, 1, 2, '2026-02-09', '2026-02-12', 'Dikembalikan', NULL, NULL),
(728116, 1, 2, '2026-02-10', '2026-02-23', 'Dikembalikan', NULL, NULL),
(728117, 1, 7, '2026-02-11', '2026-03-11', 'Dikembalikan', NULL, NULL),
(728118, NULL, 2, NULL, NULL, 'Menunggu Persetujuan', NULL, NULL),
(728119, 1, 2, '2026-02-12', '2026-02-23', 'Dikembalikan', NULL, NULL),
(728120, 3, 2, '2026-02-23', '2026-03-11', 'Dikembalikan', NULL, NULL),
(728121, 3, 7, '2026-02-12', '2026-03-11', 'Dikembalikan', NULL, NULL),
(728122, 1, 4, '2026-02-23', '2026-03-11', 'Dikembalikan', NULL, NULL),
(728123, 3, 4, '2026-04-02', '2026-04-02', 'Dikembalikan', NULL, NULL),
(728124, 3, 11, '2026-04-02', '2026-04-02', 'Dikembalikan', NULL, NULL),
(728125, 3, 7, '2026-04-02', '2026-04-02', 'Dikembalikan', NULL, NULL),
(728126, 3, 2, '2026-04-02', '2026-04-02', 'Dikembalikan', NULL, NULL),
(728127, 3, 11, '2026-04-02', '2026-04-02', 'Dikembalikan', NULL, NULL),
(728128, 3, 4, '2026-04-02', '2026-04-09', 'Dikembalikan', NULL, NULL),
(728129, 3, 4, '2026-04-09', '2026-04-09', 'Dikembalikan', NULL, NULL),
(728130, 3, 4, '2026-04-09', '2026-04-09', 'Dikembalikan', NULL, NULL),
(728131, 3, 2, '2026-04-09', '2026-04-09', 'Dikembalikan', NULL, NULL),
(728132, 3, 4, '2026-04-09', '2026-04-09', 'Dikembalikan', NULL, NULL),
(728133, 3, 4, '2026-04-09', '2026-04-09', 'Dikembalikan', NULL, NULL),
(728134, 3, 2, '2026-04-09', '2026-04-09', 'Dikembalikan', NULL, NULL),
(728135, 3, 4, '2026-04-09', '2026-04-09', 'Dikembalikan', NULL, NULL),
(728136, 3, 4, '2026-04-09', '2026-04-09', 'Dikembalikan', NULL, NULL),
(728137, 3, 4, '2026-04-09', '2026-04-09', 'Dikembalikan', NULL, NULL),
(728138, 3, 4, '2026-04-09', '2026-04-09', 'Dikembalikan', NULL, NULL),
(728139, 3, 4, '2026-04-09', '2026-04-09', 'Dikembalikan', NULL, NULL),
(728140, 3, 4, NULL, NULL, 'Ditolak', NULL, NULL),
(728141, 3, 4, NULL, NULL, 'Ditolak', NULL, NULL),
(728142, 3, 4, NULL, NULL, 'Ditolak', NULL, NULL),
(728143, 3, 2, '2026-04-09', NULL, 'Dipinjam', NULL, NULL),
(728144, 3, 2, NULL, '2026-04-09', 'Ditolak', NULL, 'karena buku sudah habis stok'),
(728145, 3, 7, '2026-04-10', NULL, 'Dipinjam', NULL, NULL),
(728146, 3, 11, NULL, NULL, 'Menunggu Persetujuan', NULL, NULL),
(728147, 3, 5, NULL, NULL, 'Menunggu Persetujuan', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `status_aktif` enum('Ya','Tidak') NOT NULL DEFAULT 'Ya'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `nama`, `email`, `password`, `jenis_kelamin`, `status`, `role`, `status_aktif`) VALUES
(1, 'Budi Santoso', 'budi@mail.com', '12345', 'Laki-laki', 'Aktif', 'user', 'Ya'),
(3, 'alin', 'alin@mail.com', '12345\r\n', NULL, NULL, 'user', 'Ya'),
(4, 'ARIS', 'ARIIS@MAIL.COM', '123', NULL, NULL, 'user', 'Tidak'),
(7, 'koko setiawan', 'koko123@gmail.com', '123456', NULL, NULL, 'user', 'Ya'),
(8, 'yahyah sunarya', 'yahyah@gmail.com', '123456', NULL, NULL, 'user', 'Ya'),
(9, 'popol', 'popol@gmail.com', '123456', NULL, NULL, 'user', 'Ya');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `tb_buku`
--
ALTER TABLE `tb_buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `tb_peminjaman`
--
ALTER TABLE `tb_peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_buku` (`id_buku`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_buku`
--
ALTER TABLE `tb_buku`
  MODIFY `id_buku` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_peminjaman`
--
ALTER TABLE `tb_peminjaman`
  MODIFY `id_peminjaman` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=728148;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_peminjaman`
--
ALTER TABLE `tb_peminjaman`
  ADD CONSTRAINT `tb_peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_peminjaman_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `tb_buku` (`id_buku`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_peminjaman_ibfk_3` FOREIGN KEY (`id_admin`) REFERENCES `tb_admin` (`id_admin`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
