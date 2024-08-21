-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 21, 2024 at 03:09 AM
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
-- Table structure for table `progress_pengerjaan`
--

CREATE TABLE `progress_pengerjaan` (
  `id` int NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `progress_pengerjaan`
--

INSERT INTO `progress_pengerjaan` (`id`, `nama`) VALUES
(4, 'Registrasi dan penerimaan sampel'),
(5, 'Pengeringan/open sampel'),
(6, 'Antrian proses grinding'),
(7, 'Proses grinding'),
(8, 'Penimbangan dan destruksi kering'),
(9, 'Preparasi'),
(10, 'Analisa (pengukuran)'),
(11, 'Proses recheck'),
(12, 'Penginputan hasil analisa'),
(13, 'Pembuatan draft'),
(14, 'Pembuatan sertifikat'),
(15, 'Penerbitan sertifikat'),
(16, 'Terbit sertifikat'),
(17, 'Antrian proses penumbukan'),
(18, 'Proses penumbukan'),
(19, 'Penimbangan '),
(20, 'Scraping'),
(21, 'Ekstraksi soxhlet'),
(4, 'Registrasi dan penerimaan sampel'),
(5, 'Pengeringan/open sampel'),
(6, 'Antrian proses grinding'),
(7, 'Proses grinding'),
(8, 'Penimbangan dan destruksi kering'),
(9, 'Preparasi'),
(10, 'Analisa (pengukuran)'),
(11, 'Proses recheck'),
(12, 'Penginputan hasil analisa'),
(13, 'Pembuatan draft'),
(14, 'Pembuatan sertifikat'),
(15, 'Penerbitan sertifikat'),
(16, 'Terbit sertifikat'),
(17, 'Antrian proses penumbukan'),
(18, 'Proses penumbukan'),
(19, 'Penimbangan '),
(20, 'Scraping'),
(21, 'Ekstraksi soxhlet');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
