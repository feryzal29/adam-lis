-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 24, 2021 at 08:48 AM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sik_rsms`
--

-- --------------------------------------------------------

--
-- Table structure for table `h_item_pemeriksaan`
--

CREATE TABLE `h_item_pemeriksaan` (
  `id_periksa_lab` int(11) NOT NULL,
  `h_registrasi_no_lab` varchar(100) DEFAULT NULL,
  `kategori_pemeriksaan_nama` varchar(100) DEFAULT NULL,
  `kategori_pemeriksaan_no_urut` int(11) DEFAULT NULL,
  `sub_kategori_pemeriksaan_nama` varchar(100) DEFAULT NULL,
  `sub_kategori_pemeriksaan_no_urut` int(11) DEFAULT NULL,
  `item_pemeriksaan_no_urut` int(11) DEFAULT NULL,
  `item_pemeriksaan_kode` varchar(100) DEFAULT NULL,
  `item_pemeriksaan_nama` varchar(255) DEFAULT NULL,
  `item_pemeriksaan_metode` varchar(255) DEFAULT NULL,
  `item_pemeriksaan_satuan` varchar(255) DEFAULT NULL,
  `hasil_pemeriksaan` varchar(255) DEFAULT NULL,
  `nilai_rujukan` varchar(255) DEFAULT NULL,
  `flag_kode` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `h_registrasi`
--

CREATE TABLE `h_registrasi` (
  `no_reg_rs` varchar(100) DEFAULT NULL,
  `no_lab` varchar(100) NOT NULL,
  `waktu_registrasi` datetime DEFAULT NULL,
  `pasien_umur_tahun` varchar(10) DEFAULT NULL,
  `pasien_umur_bulan` varchar(50) DEFAULT NULL,
  `pasien_umur_hari` varchar(50) DEFAULT NULL,
  `pasien_alamat` text DEFAULT NULL,
  `pasien_nama` varchar(255) DEFAULT NULL,
  `pasien_no_rm` varchar(10) DEFAULT NULL,
  `pasien_jenis_kelamin` enum('L','P') DEFAULT NULL,
  `pasien_tanggal_lahir` date DEFAULT NULL,
  `pasien_no_telphone` varchar(255) DEFAULT NULL,
  `dokter_pengirim_kode` varchar(50) DEFAULT NULL,
  `dokter_pengirim_nama` varchar(255) DEFAULT NULL,
  `unit_asal_kode` varchar(50) DEFAULT NULL,
  `unit_asal_nama` varchar(100) DEFAULT NULL,
  `penjamin_nama` varchar(100) DEFAULT NULL,
  `penjamin_kode` varchar(50) DEFAULT NULL,
  `diagnosa_awal` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `maping_lab_adamlabs`
--

CREATE TABLE `maping_lab_adamlabs` (
  `id_maping` int(11) NOT NULL,
  `id_template` int(11) DEFAULT NULL,
  `jns_bridging` enum('I','P') DEFAULT NULL,
  `id_item_adam` varchar(100) DEFAULT NULL,
  `statuss` enum('0','1') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `h_item_pemeriksaan`
--
ALTER TABLE `h_item_pemeriksaan`
  ADD PRIMARY KEY (`id_periksa_lab`) USING BTREE,
  ADD KEY `h_item` (`h_registrasi_no_lab`) USING BTREE,
  ADD KEY `h_item_pemeriksaan` (`item_pemeriksaan_kode`) USING BTREE;

--
-- Indexes for table `h_registrasi`
--
ALTER TABLE `h_registrasi`
  ADD PRIMARY KEY (`no_lab`) USING BTREE;

--
-- Indexes for table `maping_lab_adamlabs`
--
ALTER TABLE `maping_lab_adamlabs`
  ADD PRIMARY KEY (`id_maping`) USING BTREE,
  ADD KEY `fk_template_simrs` (`id_template`) USING BTREE,
  ADD KEY `id_item_adam` (`id_item_adam`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `h_item_pemeriksaan`
--
ALTER TABLE `h_item_pemeriksaan`
  MODIFY `id_periksa_lab` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maping_lab_adamlabs`
--
ALTER TABLE `maping_lab_adamlabs`
  MODIFY `id_maping` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `h_item_pemeriksaan`
--
ALTER TABLE `h_item_pemeriksaan`
  ADD CONSTRAINT `h_item` FOREIGN KEY (`h_registrasi_no_lab`) REFERENCES `h_registrasi` (`no_lab`),
  ADD CONSTRAINT `h_item_pemeriksaan` FOREIGN KEY (`item_pemeriksaan_kode`) REFERENCES `maping_lab_adamlabs` (`id_item_adam`);

--
-- Constraints for table `maping_lab_adamlabs`
--
ALTER TABLE `maping_lab_adamlabs`
  ADD CONSTRAINT `fk_template_simrs` FOREIGN KEY (`id_template`) REFERENCES `template_laboratorium` (`id_template`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
