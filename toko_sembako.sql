-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2026 at 02:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_sembako`
--
CREATE DATABASE IF NOT EXISTS `toko_sembako` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `toko_sembako`;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pembelian`
--

DROP TABLE IF EXISTS `detail_pembelian`;
CREATE TABLE `detail_pembelian` (
  `id_detail_pembelian` int(11) NOT NULL,
  `id_pembelian` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah_pembelian` int(11) NOT NULL,
  `harga_pembelian` int(11) NOT NULL,
  `status` enum('Y','N') DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pembelian`
--

INSERT INTO `detail_pembelian` (`id_detail_pembelian`, `id_pembelian`, `id_produk`, `jumlah_pembelian`, `harga_pembelian`, `status`) VALUES
(5, 4, 5, 1000, 70000, 'Y'),
(6, 5, 6, 20, 0, 'Y'),
(7, 6, 11, 21, 13000, 'Y'),
(8, 6, 12, 43, 0, 'Y'),
(9, 7, 5, 5, 0, 'Y'),
(10, 8, 5, 26, 0, 'Y'),
(11, 8, 6, 25, 0, 'Y'),
(12, 9, 9, 35, 0, 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `detail_penjualan`
--

DROP TABLE IF EXISTS `detail_penjualan`;
CREATE TABLE `detail_penjualan` (
  `id_detail_penjualan` int(11) NOT NULL,
  `id_penjualan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah_penjualan` int(11) NOT NULL,
  `harga_penjualan` int(11) NOT NULL,
  `harga_pembelian` int(11) DEFAULT 0,
  `status` enum('Y','N') DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_penjualan`
--

INSERT INTO `detail_penjualan` (`id_detail_penjualan`, `id_penjualan`, `id_produk`, `jumlah_penjualan`, `harga_penjualan`, `harga_pembelian`, `status`) VALUES
(10432, 1248, 772, 1, 52000, 0, 'Y'),
(10433, 1248, 144, 1, 357500, 0, 'Y'),
(38434, 3947, 787, 100, 45000, 0, 'Y'),
(38435, 3947, 151, 10, 322500, 0, 'Y'),
(38436, 3947, 433, 5, 600000, 0, 'Y'),
(38437, 3947, 471, 1, 198000, 0, 'Y'),
(38438, 3947, 351, 2, 95500, 0, 'Y'),
(38439, 3947, 808, 4, 50000, 0, 'Y'),
(38440, 3947, 795, 1, 198000, 0, 'Y'),
(38442, 3949, 462, 2, 655000, 0, 'Y'),
(38443, 3950, 5, 1, 75000, 0, 'Y'),
(38444, 3950, 275, 3, 11000, 0, 'Y'),
(38445, 3950, 345, 1, 88000, 0, 'Y'),
(38446, 3950, 567, 6, 7000, 0, 'Y'),
(38447, 3950, 8, 6, 9250, 0, 'Y'),
(38448, 3950, 7, 10, 2500, 0, 'Y'),
(38449, 3950, 353, 2, 79000, 0, 'Y'),
(38450, 3950, 279, 1, 490000, 0, 'Y'),
(38451, 3951, 487, 1, 76000, 0, 'Y'),
(38462, 3952, 280, 5, 89000, 0, 'Y'),
(38463, 3952, 257, 2, 57000, 0, 'Y'),
(38464, 3952, 70, 1, 145000, 0, 'Y'),
(38465, 3952, 555, 2, 27000, 0, 'Y'),
(38466, 3953, 353, 1, 79000, 0, 'Y'),
(38467, 3953, 819, 1, 298000, 0, 'Y'),
(38468, 3953, 378, 1, 145000, 0, 'Y'),
(38469, 3953, 237, 1, 152000, 0, 'Y'),
(38470, 3953, 583, 1, 51500, 0, 'Y'),
(38471, 3953, 590, 1, 255000, 0, 'Y'),
(38472, 3954, 279, 1, 490000, 0, 'Y'),
(38473, 3954, 157, 3, 390000, 0, 'Y'),
(38474, 3954, 21, 4, 174000, 0, 'Y'),
(38475, 3954, 20, 2, 132000, 0, 'Y'),
(38488, 3955, 787, 10, 45000, 0, 'Y'),
(38489, 3955, 844, 1, 450000, 0, 'Y'),
(38490, 3955, 281, 3, 89000, 0, 'Y'),
(38491, 3955, 169, 1, 197000, 0, 'Y'),
(38492, 3955, 283, 12, 5500, 0, 'Y'),
(38493, 3955, 166, 1, 109000, 0, 'Y'),
(38494, 3955, 28, 1, 580000, 0, 'Y'),
(38495, 3955, 358, 1, 128000, 0, 'Y'),
(38496, 3955, 438, 1, 235000, 0, 'Y'),
(38497, 3956, 754, 1, 20000, 0, 'Y'),
(38498, 3956, 433, 2, 600000, 0, 'Y'),
(38499, 3956, 360, 1, 800000, 0, 'Y'),
(38500, 3956, 378, 1, 145000, 0, 'Y'),
(38504, 3957, 144, 1, 365000, 0, 'Y'),
(38505, 3957, 142, 1, 374000, 0, 'Y'),
(38506, 3957, 433, 1, 600000, 0, 'Y'),
(38507, 3958, 368, 5, 19500, 0, 'Y'),
(38508, 3959, 695, 1, 49000, 0, 'Y'),
(38509, 3959, 815, 1, 19000, 0, 'Y'),
(38510, 3959, 371, 1, 19000, 0, 'Y'),
(38511, 3959, 199, 2, 35500, 0, 'Y'),
(38512, 3959, 621, 1, 20000, 0, 'Y'),
(38513, 3959, 617, 1, 20000, 0, 'Y'),
(38514, 3959, 281, 1, 89000, 0, 'Y'),
(38515, 3959, 11, 1, 220000, 0, 'Y'),
(38516, 3959, 353, 1, 79000, 0, 'Y'),
(38517, 3959, 345, 1, 88000, 0, 'Y'),
(38518, 3959, 152, 10, 16200, 0, 'Y'),
(38519, 3959, 149, 1, 370000, 0, 'Y'),
(38520, 3960, 863, 1, 61500, 0, 'Y'),
(38521, 3960, 141, 4, 45000, 0, 'Y'),
(38522, 3960, 147, 6, 74500, 0, 'Y'),
(38523, 3961, 433, 1, 600000, 0, 'Y'),
(38525, 3962, 660, 3, 5500, 0, 'Y'),
(38531, 3963, 775, 1, 245000, 0, 'Y'),
(38532, 3963, 510, 3, 20000, 0, 'Y'),
(38533, 3963, 218, 1, 266000, 0, 'Y'),
(38534, 3963, 479, 3, 21500, 0, 'Y'),
(38535, 3963, 239, 1, 102500, 0, 'Y'),
(38536, 3964, 198, 1, 18500, 0, 'Y'),
(38537, 3964, 195, 1, 14500, 0, 'Y'),
(38538, 3964, 371, 2, 19000, 0, 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `id_member` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nomor_hp` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id_member`, `nama`, `nomor_hp`) VALUES
(2, 'Ibu Ina', '-'),
(3, 'Pak Imam', '-'),
(4, 'Pak Ali ', '-');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

DROP TABLE IF EXISTS `pembelian`;
CREATE TABLE `pembelian` (
  `id_pembelian` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `jumlah_pembelian` int(11) NOT NULL,
  `status` enum('Y','N') DEFAULT 'Y',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `id_user`, `id_supplier`, `jumlah_pembelian`, `status`, `created_by`, `created_at`) VALUES
(4, 1, 0, 1000, 'Y', 1, '2025-08-23 04:19:14'),
(5, 1, 0, 20, 'Y', 1, '2025-09-11 13:58:18'),
(6, 1, 0, 64, 'Y', 1, '2025-09-11 14:17:45'),
(7, 1, 0, 5, 'Y', 1, '2025-09-11 14:21:08'),
(8, 1, 0, 51, 'Y', 1, '2025-09-11 14:22:31'),
(9, 1, 0, 35, 'Y', 1, '2025-09-11 14:25:41');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

DROP TABLE IF EXISTS `penjualan`;
CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_member` int(11) DEFAULT NULL,
  `nama_pembeli` varchar(100) DEFAULT '',
  `jumlah_penjualan` int(11) NOT NULL,
  `total_pembayaran` int(11) DEFAULT 0,
  `total_ongkir` int(11) DEFAULT 0,
  `status` enum('Y','N') DEFAULT 'Y',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id_penjualan`, `id_produk`, `id_user`, `id_member`, `nama_pembeli`, `jumlah_penjualan`, `total_pembayaran`, `total_ongkir`, `status`, `created_by`, `created_at`) VALUES
(1248, 0, 3, NULL, 'Pak kacamata', 1164500, 1165000, 0, 'Y', 3, '2025-11-05 01:45:56'),
(1249, 0, 4, NULL, 'pak baju biru', 357500, 360000, 0, 'Y', 4, '2025-11-05 01:46:34'),
(1250, 0, 7, NULL, 'baju merah', 51000, 100000, 0, 'Y', 7, '2025-11-05 01:47:20'),
(1251, 0, 9, NULL, 'pak baju merah', 401500, 420000, 0, 'Y', 9, '2025-11-05 02:01:55'),
(1252, 0, 9, NULL, 'Mba Wulan', 1085000, 1100000, 0, 'Y', 9, '2025-11-05 02:23:35'),
(1253, 0, 9, NULL, 'Pak baju ungu', 6743000, 6800000, 0, 'Y', 9, '2025-11-05 02:30:57'),
(1254, 0, 9, NULL, 'Ibu baju bunga', 396250, 400000, 0, 'Y', 9, '2025-11-05 02:33:54'),
(1255, 0, 9, NULL, '', 373000, 350000, 0, 'Y', 9, '2025-11-05 02:38:22'),
(1256, 0, 9, NULL, 'pak ', 294000, 300000, 0, 'Y', 9, '2025-11-05 02:40:45'),
(1257, 0, 9, NULL, 'pak rambut pink', 1072500, 1100000, 0, 'Y', 9, '2025-11-05 02:44:27'),
(1258, 0, 9, NULL, 'pak foto', 2525250, 2530000, 0, 'Y', 9, '2025-11-05 02:49:38'),
(1259, 0, 9, NULL, '', 263500, 264000, 0, 'Y', 9, '2025-11-05 03:11:11'),
(1260, 0, 9, NULL, '', 365000, 400000, 0, 'Y', 9, '2025-11-05 03:10:32'),
(1261, 0, 9, NULL, 'pak baju hijau', 338500, 350000, 0, 'Y', 9, '2025-11-05 03:20:34'),
(1262, 0, 8, NULL, '', 619000, 620000, 0, 'Y', 8, '2025-11-05 03:34:56'),
(1263, 0, 9, NULL, '', 980000, 1000000, 0, 'Y', 9, '2025-11-05 03:36:15'),
(1264, 0, 9, NULL, '', 2613000, 2650000, 0, 'Y', 8, '2025-11-05 03:41:16'),
(1265, 0, 8, NULL, '', 2949500, 3000000, 0, 'Y', 5, '2025-11-05 03:45:59'),
(1266, 0, 8, NULL, 'Ibu masker biru muda', 668500, 670000, 0, 'Y', 8, '2025-11-05 03:48:13'),
(1267, 0, 9, NULL, 'ibu baju abu abu', 1182000, 1200000, 0, 'Y', 9, '2025-11-05 03:49:36'),
(1268, 0, 9, NULL, '', 72500, 100000, 0, 'Y', 9, '2025-11-05 03:53:20'),
(1269, 0, 9, NULL, 'Pak imam', 3600000, 3600000, 0, 'Y', 9, '2025-11-05 03:53:49'),
(1270, 0, 9, NULL, '', 46000, 50000, 0, 'Y', 9, '2025-11-05 03:54:50'),
(1271, 0, 9, NULL, 'pak ipul', 577500, 600000, 0, 'Y', 9, '2025-11-05 04:14:25'),
(1272, 0, 7, NULL, '', 150000, 150000, 0, 'Y', 7, '2025-11-05 04:23:36'),
(1273, 0, 7, NULL, 'taosa', 2619750, 2620000, 0, 'Y', 7, '2025-11-05 04:28:08'),
(1274, 0, 7, NULL, 'taosa', 7006000, 7100000, 0, 'Y', 7, '2025-11-05 04:31:31'),
(1275, 0, 7, NULL, 'taosa', 45000, 0, 0, 'Y', 7, '2025-11-05 04:29:24'),
(3962, 0, 1, NULL, '', 16500, 11500, 0, 'Y', 1, '2026-02-26 08:36:43'),
(3963, 0, 1, NULL, '', 738000, 800000, 0, 'Y', 5, '2026-02-26 08:38:37'),
(3964, 0, 1, NULL, '', 71000, 100000, 0, 'Y', 1, '2026-02-26 08:41:18');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id_product` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_satuan` int(11) NOT NULL,
  `nama_product` varchar(50) NOT NULL,
  `harga_beli_product` int(11) NOT NULL,
  `harga_jual_product` int(11) NOT NULL,
  `stok_product` int(11) NOT NULL,
  `status` enum('Y','N') DEFAULT 'Y',
  `description` text DEFAULT '\'\''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id_product`, `id_supplier`, `id_satuan`, `nama_product`, `harga_beli_product`, `harga_jual_product`, `stok_product`, `status`, `description`) VALUES
(5, 5, 12, 'Beras Pin-Pin 5 Kg', 0, 75000, -2314, 'Y', '1 Karung isi 8'),
(6, 5, 8, 'Kecap Bango RTG', 0, 10000, -202, 'Y', '-'),
(7, 5, 10, 'Kecap Bango Refill K', 0, 2500, -1726, 'Y', '-'),
(8, 5, 10, 'Kecap Bango Refill T', 0, 9250, -301, 'Y', '-'),
(9, 5, 11, 'Kecap Bango RTG', 0, 116000, -17, 'Y', '-'),
(10, 5, 11, 'Kecap Bango Refill K', 0, 116000, -62, 'Y', '-'),
(11, 5, 11, 'Kecap Bango Refill T', 0, 220000, -13, 'Y', '-'),
(12, 5, 10, 'Kecap Bango Refill B', 0, 22000, -75, 'Y', '-'),
(13, 5, 11, 'Kecap Bango Refill B', 0, 260000, -50, 'Y', '-'),
(14, 5, 10, 'Kecap Sedap Refill Kecil', 0, 1750, -613, 'Y', '-'),
(15, 5, 11, 'Kecap Sedap Refill Kecil', 0, 75000, -92, 'Y', '-'),
(838, 5, 8, 'Shampoo Lifeboy Biru', 0, 5000, -188, 'Y', '-'),
(839, 5, 11, 'Shampoo Lifeboy Biru', 0, 180000, 10, 'Y', '-'),
(840, 5, 8, 'Shampoo Zinc Hijau', 0, 5000, -99, 'Y', '-'),
(841, 5, 11, 'Shampoo Zinc Hijau', 0, 94000, 6, 'Y', '-'),
(842, 5, 8, 'Shampoo Rejoice Hijau', 0, 9500, -9, 'Y', '-'),
(843, 5, 8, 'Mamypoko L', 0, 19000, -6, 'Y', '-'),
(844, 5, 7, 'Beras Pin-pin 3kg', 0, 450000, -330, 'Y', '-'),
(845, 5, 10, 'Sarden ABC Besar', 0, 24000, 3, 'Y', '-'),
(846, 5, 11, 'Kecap Sedap Refill Kecil Promo', 0, 64000, -43, 'Y', '-'),
(847, 5, 11, 'Minyak Filma 2L', 0, 220000, 10, 'Y', '-'),
(848, 5, 10, 'Minyak Tawon DD', 0, 29000, 2, 'Y', '-'),
(849, 5, 11, 'Tepung Kobe', 0, 230000, 10, 'Y', '-'),
(850, 5, 11, 'Boncabe ', 0, 190000, 10, 'Y', '-'),
(851, 5, 11, 'Tepung Kobe Nasi Goreng Pedas', 0, 208000, 10, 'Y', '-'),
(852, 5, 12, 'Tepung Kobe Nasi Goreng Pedas', 0, 18000, 10, 'Y', '-'),
(853, 5, 11, 'Lilin', 0, 350000, 9, 'Y', '-'),
(854, 5, 12, 'Bawang Putih Desaku', 0, 58000, -24, 'Y', '-'),
(855, 5, 10, 'Botol kosong', 0, 1000, 6, 'Y', '-'),
(856, 5, 11, 'Bawang Putih Desaku', 0, 345000, 6, 'Y', '-'),
(857, 5, 11, 'Minyak Kita Refill 2L', 0, 205000, -56, 'Y', '-'),
(858, 5, 11, 'Daia 5000', 0, 100000, 8, 'Y', '-'),
(859, 5, 17, 'Koyo Cabe', 0, 215000, 9, 'Y', '-'),
(860, 5, 11, 'Minyak Cap Manggis 1L Botol', 0, 210000, 8, 'Y', '-'),
(861, 5, 11, 'Minyak Kunci Emas Refill 2L', 0, 230000, 4, 'Y', '-'),
(862, 5, 11, 'Larutan Botol Kecil', 0, 149000, 9, 'Y', '-'),
(863, 5, 12, 'Soffel Kuning', 0, 61500, -11, 'Y', '-'),
(864, 5, 17, 'Konidin Kotak Kecil', 0, 58000, 10, 'Y', '-'),
(865, 5, 10, 'Silet Garuk', 0, 7000, -2, 'Y', '-'),
(866, 5, 12, 'Hongkwe Merah', 0, 19500, 8, 'Y', '-'),
(867, 5, 12, 'Hongkwe Hijau', 0, 19500, 6, 'Y', '-'),
(868, 5, 12, 'Hongkwe Cokelat', 0, 19500, 10, 'Y', '-'),
(869, 5, 11, 'Larutan Kaleng Jambu', 0, 126000, 9, 'Y', '-'),
(870, 5, 10, 'Tukar Uang', 0, 1, 9, 'Y', '-'),
(871, 5, 20, 'Gula Merah Kecil', 0, 147000, 10, 'Y', '-');

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

DROP TABLE IF EXISTS `satuan`;
CREATE TABLE `satuan` (
  `id_satuan` int(11) NOT NULL,
  `nama_satuan` varchar(50) NOT NULL,
  `status` enum('Y','N') DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`id_satuan`, `nama_satuan`, `status`) VALUES
(5, 'Kg', 'Y'),
(6, 'Sak', 'Y'),
(7, 'Krg', 'Y'),
(8, 'RTG', 'Y'),
(9, 'RTG', 'Y'),
(10, 'Pcs', 'Y'),
(11, 'Dus', 'Y'),
(12, 'Pak', 'Y'),
(13, 'Slop', 'Y'),
(14, 'Lusin', 'Y'),
(15, 'Tas', 'Y'),
(16, 'Gross', 'Y'),
(17, 'Kotak', 'Y'),
(18, 'Box', 'Y'),
(19, 'Bandet', 'Y'),
(20, 'Bal', 'Y'),
(21, 'Iket', 'Y'),
(22, '(1/2 Lusin)', 'Y'),
(23, 'Bt', 'Y'),
(24, 'Botol', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(50) NOT NULL,
  `status` enum('Y','N') DEFAULT 'Y',
  `nomor_hp` varchar(16) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `status`, `nomor_hp`) VALUES
(5, '-', 'Y', '-'),
(6, '-', 'Y', '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` enum('admin','pegawai') DEFAULT 'pegawai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin', 'admin'),
(2, 'pegawai', 'pegawai', 'pegawai'),
(3, 'Alex', 'tabtabs123', 'pegawai'),
(4, 'Ambon', 'tabtabs123', 'pegawai'),
(5, 'Kancil', 'tabtabs123', 'pegawai'),
(6, 'Eko', 'tabtbas123', 'pegawai'),
(7, 'Andi', 'tabtabs123', 'pegawai'),
(8, 'Lan', 'tabtabs123', 'pegawai'),
(9, 'Titi', 'tabtabs123', 'pegawai');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD PRIMARY KEY (`id_detail_pembelian`);

--
-- Indexes for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id_detail_penjualan`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id_member`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_product`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`id_satuan`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  MODIFY `id_detail_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  MODIFY `id_detail_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38539;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id_member` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3965;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=872;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `id_satuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
