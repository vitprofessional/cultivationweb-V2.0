-- Data-only import for `new_admissions`
-- Wraps inserts with FK checks disabled to avoid parent missing errors

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET FOREIGN_KEY_CHECKS=0;
USE `cultivation_rhs`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Dumping data for table `new_admissions`

INSERT INTO `new_admissions` (`id`, `stdId`, `fullName`, `sureName`, `father`, `mother`, `gender`, `dob`, `blGroup`, `religion`, `address`, `mail`, `phone`, `avatar`, `sessName`, `className`, `departmentName`, `sectionName`, `religiousSubjectId`, `rollNumber`, `gurdianName`, `gurdianMobile`, `relationGurdian`, `status`, `created_at`, `updated_at`) VALUES
(1, 2025000001, 'MD HOSAIN', NULL, 'MD ABDUL ALIM KHAN', 'MST SAHINA AKTER', '1', '2013-07-22', '5', '1', 'Vill: Rajapur, P.O: Shankuchail Bazar-3500, P.S: Burichang, Dist: Cumilla.', NULL, '01704531265', '68959209220251101.jpg', '1', '1', '6', '1', NULL, '01', 'MD ABDUL ALIM KHAN', '01704531265', '1', 'newProfile', NULL, NULL),
(2, 2025000002, 'MD. EBRAHIM UTSOB', NULL, 'MD JAHANGGIR HOSSEN', 'LAKHI AKTER', '1', '2014-04-12', NULL, '1', 'Vill: Rajapur, P.O: Shankuchail Bazar-3500, P.S: Burichang, Dist: Cumilla.', NULL, '01779570100', '192704471920251101.jpg', '1', '1', '6', '1', NULL, '02', 'MD JAHANGGIR HOSSEN', '01779570100', '1', 'newProfile', NULL, NULL),
(3, 2025000003, 'MD ELIAS ALAM SAHID', NULL, 'MD JAHANGIR ALAM', 'MST FERDOUSI BEGUM', '1', '2012-12-20', NULL, '1', 'Vill: Rajapur, P.O: Shankuchail Bazar-3500, P.S: Burichang, Dist: Cumilla.', NULL, '01790449403', '44774620220251101.jpg', '1', '1', '6', '1', NULL, '03', 'MD JAHANGIR ALAM', '01790449403', '1', 'newProfile', NULL, NULL),
(4, 2025000004, 'MD HASANUR RAHMAN JONAYET', NULL, 'MD. MIZANUR RAHMAN', 'TAMANNA AKTER', '1', '2014-02-12', NULL, '1', 'Vill: Rajapur, P.O: Shankuchail Bazar-3500, P.S: Burichang, Dist: Cumilla.', NULL, '01624247096', '29793293020251101.jpg', '1', '1', '6', '1', NULL, '04', 'MD. MIZANUR RAHMAN', NULL, '1', 'newProfile', NULL, NULL),
(5, 2025000005, 'MD. ANAMUL HAQUE', NULL, 'MD KHURSHED ALAM', 'MST RINA AKTER', '1', '2013-11-01', NULL, '1', 'Vill: Rajapur, P.O: Shankuchail Bazar-3500, P.S: Burichang, Dist: Cumilla.', NULL, '01988085899', '72827388820251101.jpg', '1', '1', '6', '1', NULL, '05', 'MD KHURSHED ALAM', '01988085899', '1', 'newProfile', NULL, NULL),
(6, 2025000006, 'NOMAN', NULL, 'MD EKBAL HOSEN', 'FATEMA AKTER', '1', '2013-03-28', NULL, '1', 'Vill: Rajapur, P.O: Shankuchail Bazar-3500, P.S: Burichang, Dist: Cumilla.', NULL, '01866383187', NULL, '1', '1', '6', '1', NULL, '07', 'MD EKBAL HOSEN', '01866383187', '1', 'newProfile', NULL, NULL),
(7, 2025000007, 'IFTEKHAR AHMED SABIT', NULL, 'MOHAMMAD SHAJAHAN', 'TAMANA AKTER', '1', '2014-03-16', NULL, '1', 'Vill: Rajapur, P.O: Shankuchail Bazar-3500, P.S: Burichang, Dist: Cumilla.', NULL, '01833803477', '120049579920251101.jpg', '1', '1', '6', '1', NULL, '08', 'TAMANA AKTER', '01833803477', '2', 'newProfile', NULL, NULL),
(9, 2025000008, 'MD MEHIDY HASAN', NULL, 'MOHAMMAD RABIUL HASAN', 'MOSAMMAT NARGIS AKTER', '1', '2011-12-30', NULL, '1', 'Vill: Shashidol, P.O: Shashidol, P.S: Brahmanpara, Dist: Cumilla', NULL, '01796315980', NULL, '1', '3', '6', '1', NULL, '01', 'MOHAMMAD RABIUL HASAN', '01796315980', '1', 'newProfile', NULL, NULL),
... (remaining INSERT rows copied from original file) ...

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
