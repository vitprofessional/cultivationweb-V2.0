-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 27, 2025 at 12:04 PM
-- Server version: 10.6.24-MariaDB
-- PHP Version: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET FOREIGN_KEY_CHECKS=0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cultivation_rhs`
--

-- --------------------------------------------------------

--
-- Table structure for table `new_admissions`
--

CREATE TABLE `new_admissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stdId` int(11) DEFAULT NULL,
  `fullName` varchar(255) DEFAULT NULL,
  `sureName` varchar(255) DEFAULT NULL,
  `father` varchar(255) DEFAULT NULL,
  `mother` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `blGroup` varchar(255) DEFAULT NULL,
  `religion` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `sessName` varchar(255) DEFAULT NULL,
  `className` varchar(255) DEFAULT NULL,
  `departmentName` varchar(255) DEFAULT NULL,
  `sectionName` varchar(255) DEFAULT NULL,
  `religiousSubjectId` bigint(20) UNSIGNED DEFAULT NULL,
  `rollNumber` varchar(255) DEFAULT NULL,
  `gurdianName` varchar(255) DEFAULT NULL,
  `gurdianMobile` varchar(255) DEFAULT NULL,
  `relationGurdian` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `new_admissions`
--

INSERT INTO `new_admissions` (`id`, `stdId`, `fullName`, `sureName`, `father`, `mother`, `gender`, `dob`, `blGroup`, `religion`, `address`, `mail`, `phone`, `avatar`, `sessName`, `className`, `departmentName`, `sectionName`, `religiousSubjectId`, `rollNumber`, `gurdianName`, `gurdianMobile`, `relationGurdian`, `status`, `created_at`, `updated_at`) VALUES
(1, 2025000001, 'MD HOSAIN', NULL, 'MD ABDUL ALIM KHAN', 'MST SAHINA AKTER', '1', '2013-07-22', '5', '1', 'Vill: Rajapur, P.O: Shankuchail Bazar-3500, P.S: Burichang, Dist: Cumilla.', NULL, '01704531265', '68959209220251101.jpg', '1', '1', '6', '1', NULL, '01', 'MD ABDUL ALIM KHAN', '01704531265', '1', 'newProfile', NULL, NULL),
(2, 2025000002, 'MD. EBRAHIM UTSOB', NULL, 'MD JAHANGGIR HOSSEN', 'LAKHI AKTER', '1', '2014-04-12', NULL, '1', 'Vill: Rajapur, P.O: Shankuchail Bazar-3500, P.S: Burichang, Dist: Cumilla.', NULL, '01779570100', '192704471920251101.jpg', '1', '1', '6', '1', NULL, '02', 'MD JAHANGGIR HOSSEN', '01779570100', '1', 'newProfile', NULL, NULL),
(3, 2025000003, 'MD ELIAS ALAM SAHID', NULL, 'MD JAHANGIR ALAM', 'MST FERDOUSI BEGUM', '1', '2012-12-20', NULL, '1', 'Vill: Rajapur, P.O: Shankuchail Bazar-3500, P.S: Burichang, Dist: Cumilla.', NULL, '01790449403', '44774620220251101.jpg', '1', '1', '6', '1', NULL, '03', 'MD JAHANGIR ALAM', '01790449403', '1', 'newProfile', NULL, NULL),
... (content truncated for brevity, includes all rows as in source) ...

--
-- Indexes for dumped tables
--

--
-- Indexes for table `new_admissions`
--
ALTER TABLE `new_admissions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `new_admissions`
--
ALTER TABLE `new_admissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=364;

SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
