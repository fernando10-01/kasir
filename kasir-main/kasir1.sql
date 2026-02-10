-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2026 at 08:10 AM
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
-- Database: `kasir1`
--

-- --------------------------------------------------------

--
-- Table structure for table `detailpenjualan`
--

CREATE TABLE `detailpenjualan` (
  `DetailID` int(11) NOT NULL,
  `PenjualanID` int(11) NOT NULL,
  `ProdukID` int(11) NOT NULL,
  `JumlahProduk` int(11) NOT NULL,
  `Subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detailpenjualan`
--

INSERT INTO `detailpenjualan` (`DetailID`, `PenjualanID`, `ProdukID`, `JumlahProduk`, `Subtotal`) VALUES
(5, 2, 7, 1, 110000.00),
(6, 2, 8, 4, 104000.00),
(7, 2, 3, 1, 7000.00),
(8, 3, 1, 5, 40000.00),
(9, 3, 5, 1, 60000.00),
(10, 4, 3, 3, 21000.00),
(11, 4, 2, 2, 30000.00),
(12, 4, 1, 1, 8000.00),
(13, 4, 5, 8, 480000.00),
(14, 4, 9, 1, 879999.00),
(15, 5, 6, 4, 40000.00),
(16, 5, 2, 3, 45000.00),
(17, 5, 5, 3, 180000.00),
(18, 5, 4, 1, 18000.00),
(19, 5, 7, 2, 220000.00),
(20, 6, 1, 11, 88000.00),
(21, 6, 3, 12, 84000.00),
(22, 6, 2, 5, 75000.00),
(23, 6, 4, 1, 18000.00),
(24, 6, 8, 1, 26000.00),
(25, 7, 15, 6, 7200000.00),
(26, 7, 10, 1, 2432000.00),
(27, 7, 2, 2, 30000.00),
(28, 7, 13, 50, 780000.00),
(29, 7, 12, 144, 86400000.00),
(30, 7, 11, 42, 4200000.00),
(31, 8, 12, 525, 99999999.99),
(32, 8, 11, 20, 2000000.00),
(35, 10, 11, 2, 200000.00);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `PelangganID` int(11) NOT NULL,
  `NamaPelanggan` varchar(255) NOT NULL,
  `Alamat` text NOT NULL,
  `NomorTelepon` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`PelangganID`, `NamaPelanggan`, `Alamat`, `NomorTelepon`) VALUES
(2, 'cherin', 'JL. Teropong', '08217399377'),
(3, 'fahri', 'JL. Arifin Ahmad', '08987655578'),
(4, 'ibnu', 'JL. Badak', '08923475943'),
(5, 'malik', 'JL. Katak', '082173945643'),
(6, 'karen', 'JL. Tangor', '08365326393'),
(7, 'yahya', 'JL. Lokomotif', '089766474357'),
(8, 'fatur', 'JL. Parit Indah', '08795742325'),
(9, 'yuda', 'JL. Tiung', '0821704252'),
(10, 'sem', 'Jl. kapling', '08812634127');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `PenjualanID` int(11) NOT NULL,
  `TanggalPenjualan` datetime NOT NULL DEFAULT current_timestamp(),
  `TotalHarga` decimal(10,2) NOT NULL,
  `PelangganID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`PenjualanID`, `TanggalPenjualan`, `TotalHarga`, `PelangganID`) VALUES
(2, '2026-02-09 14:10:25', 221000.00, 2),
(3, '2026-02-09 14:11:36', 100000.00, 3),
(4, '2026-02-09 14:18:23', 1418999.00, 4),
(5, '2026-02-09 14:21:53', 503000.00, 5),
(6, '2026-02-10 08:08:27', 291000.00, 6),
(7, '2026-02-10 08:25:07', 99999999.99, 7),
(8, '2026-02-10 08:28:08', 99999999.99, 8),
(10, '2026-02-10 13:23:54', 200000.00, 10);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `ProdukID` int(11) NOT NULL,
  `NamaProduk` varchar(255) NOT NULL,
  `Harga` decimal(10,2) NOT NULL,
  `Stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`ProdukID`, `NamaProduk`, `Harga`, `Stok`) VALUES
(1, 'sabun', 8000.00, 37),
(2, 'shampo', 15000.00, 21),
(3, 'sikat gigi', 7000.00, 81),
(4, 'kanebo', 18000.00, 6),
(5, 'celana gajah', 60000.00, 26),
(6, 'sendal', 10000.00, 16),
(7, 'setrika', 110000.00, 2),
(8, 'wipol', 26000.00, 9),
(9, 'jaket chmb', 879999.00, 2),
(10, 'Sepatu NB 2002r', 2432000.00, 8),
(11, 'batu es premium', 100000.00, 998),
(12, 'garam ruqyah', 600000.00, 4814),
(13, 'pasir ajaib', 15600.00, 2302),
(14, 'hawas ice', 800000.00, 17),
(15, 'Sepatu vans', 1200000.00, 37),
(16, 'cincin', 60000.00, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('admin','petugas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Role`) VALUES
(1, 'nando', '123', 'admin'),
(2, 'faraz', '123', 'petugas'),
(4, 'oke', '123', 'petugas');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detailpenjualan`
--
ALTER TABLE `detailpenjualan`
  ADD PRIMARY KEY (`DetailID`),
  ADD KEY `ProdukID` (`ProdukID`),
  ADD KEY `detailpenjualan_ibfk_1` (`PenjualanID`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`PelangganID`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`PenjualanID`),
  ADD KEY `penjualan_ibfk_1` (`PelangganID`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`ProdukID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detailpenjualan`
--
ALTER TABLE `detailpenjualan`
  MODIFY `DetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `PelangganID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `PenjualanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `ProdukID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detailpenjualan`
--
ALTER TABLE `detailpenjualan`
  ADD CONSTRAINT `detailpenjualan_ibfk_1` FOREIGN KEY (`PenjualanID`) REFERENCES `penjualan` (`PenjualanID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detailpenjualan_ibfk_2` FOREIGN KEY (`ProdukID`) REFERENCES `produk` (`ProdukID`) ON UPDATE CASCADE;

--
-- Constraints for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`PelangganID`) REFERENCES `pelanggan` (`PelangganID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
