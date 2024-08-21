-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 21, 2024 at 03:11 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `srsssmsc_smartlab`
--

-- --------------------------------------------------------

--
-- Table structure for table `jenis_sampel`
--

CREATE TABLE `jenis_sampel` (
  `id` int NOT NULL,
  `nama` varchar(50) NOT NULL,
  `progress` varchar(50) NOT NULL,
  `kode` varchar(3) NOT NULL,
  `parameter_analisis` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jenis_sampel`
--

INSERT INTO `jenis_sampel` (`id`, `nama`, `progress`, `kode`, `parameter_analisis`) VALUES
(1, 'Daun', '4,5,6,7,8,9,10,11,12,13,14,15,16', 'L', 'N, P, K, Mg, Ca, B, Cu, Zn, Fe, Mn, Kadar Air, Kadar Abu, Karbohidrat, Lemak Kasar, Serat Kasar, Protein BB, BK, TDN, C-Organik, Sulfur, Kadar Minyak, Preparasi Sampel'),
(2, 'Tanah', '4,5,6,7,8,9,10,11,12,13,14,15,16', 'S', 'Kadar Air, Tekstur, Total P (H2SO4 : HCIO4), ph H2O (1:5), pH KCI (1:5), N - Total, Organic Carbon, P - Bray II, P - Bray I, CEC (NH4OAC; pH 7), Exch. K (NH4OAC; ph7), Exch. Mg (NH4OAC; ph7), Exch. Ca (NH4OAC; ph7), Exch. Na (NH4OAC; ph7), K - Total, Mg - Total, Ca - Total, Cu - Total, Zn - Total, Mn - Total, Fe - Total, Al - dd 1 n KCI, H- dd 1 N KCI, B- Hot Water, Al+H, KB, DHL, Sulfat, Fe-tersedia, LOI, Kadar Minyak, Serat Gambut, Preparasi Sampel'),
(3, 'Rachis', '4,5,6,7,8,9,10,11,12,13,14,15,16', 'R', 'N, P, K, Mg, Ca, B, Cu, Zn, Fe, Mn, Kadar Air, Kadar Abu, Karbohidrat, Lemak Kasar, Serat Kasar, Protein BB, BK, TDN, C-Organik, Sulfur, Kadar Minyak, Preparasi Sampel'),
(4, 'Pupuk Anorganik', '4,5,17,18,19,9,10,11,12,13,14,15,16', 'F', 'KA, K2O, MgO, P2O5, N- Total, B2O3, P2O5 in CAS 2%, Fe2O3, S, Mn-total, CaO, ZnO, Ca-total, Fe-total, pH, C-organik, Mesh 20, Mesh 25, Mesh 80, CaCO2, Berat daun, Berat Kering, Asam Humat, C/N, Kadar Air, Kadar Minyak, Elektrik Konduktif, Preparasi Sampel'),
(5, 'Air', '4,9,10,11,12,13,14,15,16', 'W', 'PH, N Total (TKN), K Total, Mg Total, Ca Total, Zn Total, Cu Total, Fe Terlarut, Mn Terlarut, K Terlarut, Mg Terlarut, CA Terlarut, Zn Terlarut, Cu Terlarut, Fosfat sebagai Ortho Fosfat, COD, BOD, Sulfat, DHL, Minyak & Lemak, C-organik, TSS, TDS, TS'),
(6, 'CPO', '4,9,10,11,12,13,14,15,16', 'C', 'KA, Dirt, FFA, PV, IV, DOBI, B. Carotene'),
(7, 'TBS', '4,20,9,21,10,11,12,13,14,15,16', 'T', 'KA, O/DM, O/VM, O/F, WM/F, F/B, O/B, DM/F, K/B, F/S, K/F, FFA, Berat Messocrap Segar, Berat Messocrap Kering, Berat Biji Segar, Berat Biji Kering, Berat Cangkang, Berat Kernel, Berat Brondolan Sampel, B-Carotene, DOBI, Preparasi Sampel'),
(8, 'Pupuk Analisa Solid Waste/Decanter Cake', '4,5,17,18,19,9,10,11,12,13,14,15,16', 'F', ''),
(9, 'Daun Analisa Bahan Pangan', '4,5,17,18,19,9,10,11,12,13,14,15,16', 'L', 'N, P, K, Mg, Ca, B, Cu, Zn, Fe, Mn, Kadar Air, Kadar Abu, Karbohidrat, Lemak Kasar, Serat Kasar, Protein BB, BK, TDN, C-Organik, Sulfur, Kadar Minyak, Preparasi Sampel'),
(10, 'Pupuk Organik', '4,5,17,18,19,9,10,11,12,13,14,15,16', 'F', 'KA, K2O, MgO, P2O5, N - Total, B2O3, P2O5 in Cas 2%, Fe2O3, S, Mn - Total, CuO, ZnO, Ca-Total, Fe-Total, pH, C-Organik, Mesh 20, Mesh 25, Mesh 80, CaCO3, Berat Basah, Berat Kering, Asam Humat, C/N, Kadar Air, Kadar Minyak, Elektrik Konduktif, Preparasi Sampel');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jenis_sampel`
--
ALTER TABLE `jenis_sampel`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenis_sampel`
--
ALTER TABLE `jenis_sampel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
