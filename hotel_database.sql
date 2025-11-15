-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2025 at 03:26 AM
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
-- Database: `hotel_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `Booking_Id` int(11) NOT NULL,
  `Room_Id` int(11) NOT NULL,
  `CredentialsID` int(11) NOT NULL,
  `Check_in` date NOT NULL,
  `Check_out` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`Booking_Id`, `Room_Id`, `CredentialsID`, `Check_in`, `Check_out`) VALUES
(1, 2, 2, '2025-11-21', '2025-11-30'),
(2, 1, 6, '2025-11-21', '2025-11-28'),
(4, 1, 6, '2025-12-05', '2025-12-12'),
(6, 3, 2, '2026-01-13', '2026-01-27');

-- --------------------------------------------------------

--
-- Table structure for table `hotels_rooms`
--

CREATE TABLE `hotels_rooms` (
  `Room_Id` int(11) NOT NULL,
  `Hotel_Id` int(11) NOT NULL,
  `Room_Number` varchar(20) NOT NULL,
  `Room_Type` enum('Standard','Deluxe','Suite') NOT NULL,
  `Room_Square_Meter` decimal(5,2) DEFAULT NULL,
  `Bed_Type` varchar(50) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `Status` enum('Available','Booked','Maintenance') DEFAULT 'Available',
  `Created_At` datetime DEFAULT current_timestamp(),
  `Updated_At` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Is_Active` tinyint(1) NOT NULL,
  `Image1` varchar(255) DEFAULT NULL,
  `Image2` varchar(255) DEFAULT NULL,
  `Image3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels_rooms`
--

INSERT INTO `hotels_rooms` (`Room_Id`, `Hotel_Id`, `Room_Number`, `Room_Type`, `Room_Square_Meter`, `Bed_Type`, `Price`, `Status`, `Created_At`, `Updated_At`, `Is_Active`, `Image1`, `Image2`, `Image3`) VALUES
(1, 2, '1', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Booked', '2025-11-06 15:31:52', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(2, 2, '2', 'Standard', 20.00, 'Two Single Beds', 150.00, 'Available', '2025-11-06 15:43:30', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(3, 1, '2', 'Standard', 20.00, 'Two Single Beds', 59.00, 'Booked', '2025-11-12 22:45:10', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(4, 2, '3', 'Deluxe', 35.00, 'Queen Bed', 500.00, 'Available', '2025-11-13 17:57:17', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(5, 2, '4', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-13 17:58:59', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(6, 1, '101', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(7, 1, '102', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(8, 1, '103', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(9, 1, '104', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(10, 1, '105', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(11, 1, '106', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(12, 1, '107', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(13, 1, '108', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(14, 1, '109', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(15, 2, '201', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(16, 2, '202', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(17, 2, '203', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(18, 2, '204', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(19, 2, '205', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(20, 2, '206', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(21, 2, '207', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(22, 2, '208', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(23, 2, '209', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:42', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(24, 3, '301', 'Standard', 20.00, 'Two Single Beds', 90.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(25, 3, '302', 'Standard', 20.00, 'Two Single Beds', 90.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(26, 3, '303', 'Standard', 20.00, 'Two Single Beds', 90.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(27, 3, '304', 'Deluxe', 35.00, 'Queen Bed', 140.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(28, 3, '305', 'Deluxe', 35.00, 'Queen Bed', 140.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(29, 3, '306', 'Deluxe', 35.00, 'Queen Bed', 140.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(30, 3, '307', 'Suite', 50.00, 'Queen Bed', 190.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(31, 3, '308', 'Suite', 50.00, 'Queen Bed', 190.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(32, 3, '309', 'Suite', 50.00, 'Queen Bed', 190.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(33, 4, '401', 'Standard', 20.00, 'Two Single Beds', 95.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(34, 4, '402', 'Standard', 20.00, 'Two Single Beds', 95.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(35, 4, '403', 'Standard', 20.00, 'Two Single Beds', 95.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(36, 4, '404', 'Deluxe', 35.00, 'Queen Bed', 145.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(37, 4, '405', 'Deluxe', 35.00, 'Queen Bed', 145.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(38, 4, '406', 'Deluxe', 35.00, 'Queen Bed', 145.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(39, 4, '407', 'Suite', 50.00, 'Queen Bed', 195.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(40, 4, '408', 'Suite', 50.00, 'Queen Bed', 195.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(41, 4, '409', 'Suite', 50.00, 'Queen Bed', 195.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(42, 5, '501', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(43, 5, '502', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(44, 5, '503', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(45, 5, '504', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(46, 5, '505', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(47, 5, '506', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(48, 5, '507', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(49, 5, '508', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(50, 5, '509', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(51, 6, '601', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(52, 6, '602', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(53, 6, '603', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(54, 6, '604', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(55, 6, '605', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(56, 6, '606', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(57, 6, '607', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(58, 6, '608', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(59, 6, '609', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(60, 7, '701', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(61, 7, '702', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(62, 7, '703', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(63, 7, '704', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(64, 7, '705', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(65, 7, '706', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(66, 7, '707', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(67, 7, '708', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(68, 7, '709', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(69, 8, '801', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(70, 8, '802', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(71, 8, '803', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(72, 8, '804', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(73, 8, '805', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(74, 8, '806', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(75, 8, '807', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(76, 8, '808', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(77, 8, '809', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(78, 9, '901', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(79, 9, '902', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(80, 9, '903', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(81, 9, '904', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(82, 9, '905', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(83, 9, '906', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(84, 9, '907', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(85, 9, '908', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(86, 9, '909', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(87, 10, '1001', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(88, 10, '1002', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(89, 10, '1003', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(90, 10, '1004', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(91, 10, '1005', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(92, 10, '1006', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(93, 10, '1007', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(94, 10, '1008', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(95, 10, '1009', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(96, 11, '1101', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(97, 11, '1102', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(98, 11, '1103', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(99, 11, '1104', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(100, 11, '1105', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(101, 11, '1106', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(102, 11, '1107', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(103, 11, '1108', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(104, 11, '1109', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(105, 12, '1201', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(106, 12, '1202', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(107, 12, '1203', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(108, 12, '1204', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(109, 12, '1205', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(110, 12, '1206', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(111, 12, '1207', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(112, 12, '1208', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(113, 12, '1209', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(114, 13, '1301', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(115, 13, '1302', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(116, 13, '1303', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(117, 13, '1304', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(118, 13, '1305', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(119, 13, '1306', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(120, 13, '1307', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(121, 13, '1308', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(122, 13, '1309', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(123, 14, '1401', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(124, 14, '1402', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(125, 14, '1403', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(126, 14, '1404', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(127, 14, '1405', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(128, 14, '1406', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(129, 14, '1407', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(130, 14, '1408', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(131, 14, '1409', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(132, 15, '1501', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(133, 15, '1502', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(134, 15, '1503', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(135, 15, '1504', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(136, 15, '1505', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(137, 15, '1506', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(138, 15, '1507', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(139, 15, '1508', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(140, 15, '1509', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(141, 16, '1601', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(142, 16, '1602', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(143, 16, '1603', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(144, 16, '1604', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(145, 16, '1605', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(146, 16, '1606', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(147, 16, '1607', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(148, 16, '1608', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(149, 16, '1609', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(150, 17, '1701', 'Standard', 20.00, 'Two Single Beds', 130.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(151, 17, '1702', 'Standard', 20.00, 'Two Single Beds', 130.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(152, 17, '1703', 'Standard', 20.00, 'Two Single Beds', 130.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(153, 17, '1704', 'Deluxe', 35.00, 'Queen Bed', 190.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(154, 17, '1705', 'Deluxe', 35.00, 'Queen Bed', 190.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(155, 17, '1706', 'Deluxe', 35.00, 'Queen Bed', 190.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(156, 17, '1707', 'Suite', 50.00, 'Queen Bed', 230.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(157, 17, '1708', 'Suite', 50.00, 'Queen Bed', 230.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(158, 17, '1709', 'Suite', 50.00, 'Queen Bed', 230.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(159, 18, '1801', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(160, 18, '1802', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(161, 18, '1803', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(162, 18, '1804', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(163, 18, '1805', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(164, 18, '1806', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:07', 1, NULL, NULL, NULL),
(165, 18, '1807', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(166, 18, '1808', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(167, 18, '1809', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(168, 19, '1901', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(169, 19, '1902', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(170, 19, '1903', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(171, 19, '1904', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(172, 19, '1905', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(173, 19, '1906', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(174, 19, '1907', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(175, 19, '1908', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(176, 19, '1909', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(177, 20, '2001', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(178, 20, '2002', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(179, 20, '2003', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(180, 20, '2004', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(181, 20, '2005', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(182, 20, '2006', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(183, 20, '2007', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(184, 20, '2008', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(185, 20, '2009', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(186, 21, '2101', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(187, 21, '2102', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(188, 21, '2103', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(189, 21, '2104', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(190, 21, '2105', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(191, 21, '2106', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(192, 21, '2107', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(193, 21, '2108', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(194, 21, '2109', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(195, 22, '2201', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(196, 22, '2202', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(197, 22, '2203', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(198, 22, '2204', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(199, 22, '2205', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(200, 22, '2206', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(201, 22, '2207', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(202, 22, '2208', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL),
(203, 22, '2209', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-14 19:41:08', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_details`
--

CREATE TABLE `hotel_details` (
  `Hotel_Id` int(11) NOT NULL,
  `Hotel_Name` varchar(255) NOT NULL,
  `Hotel_Street_Name` varchar(255) NOT NULL,
  `Hotel_City_Name` varchar(100) DEFAULT NULL,
  `Hotel_Country_Name` varchar(100) DEFAULT NULL,
  `Phone_Number` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Star_Rating` tinyint(4) DEFAULT NULL,
  `Number_of_Rooms` int(11) DEFAULT NULL,
  `Hotel_Image` varchar(255) DEFAULT NULL,
  `Created_At` datetime DEFAULT current_timestamp(),
  `Updated_At` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Is_Active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_details`
--

INSERT INTO `hotel_details` (`Hotel_Id`, `Hotel_Name`, `Hotel_Street_Name`, `Hotel_City_Name`, `Hotel_Country_Name`, `Phone_Number`, `Email`, `Star_Rating`, `Number_of_Rooms`, `Hotel_Image`, `Created_At`, `Updated_At`, `Is_Active`) VALUES
(1, 'Bond Hotel', 'Triq il-konvoja ta santa marija', 'Mqabba', 'Malta', '+3567777777777', 'bondhotel@stc.com', 5, 10, 'images/bond_hotel.jpg', '2025-11-06 13:05:25', '2025-11-14 19:25:10', 1),
(2, 'Phoenicia Hotel', 'Vjal ir-Re Dwardu VII', 'Floriana', 'Malta', '+3567777777777', 'phoeniciahotel@stc.com', 5, 13, 'images/phoenicia.jpg', '2025-11-06 14:49:44', '2025-11-14 19:25:19', 1),
(3, 'Hotel Roma Elegante', 'Via dei Fori Imperiali 1', 'Rome', 'Italy', '+39 06 1234567', 'info@roma-elegante.it', 4, 100, 'images/hotel_roma_elegante.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(4, 'Lago di Garda Resort', 'Via Lago 12', 'Garda', 'Italy', '+39 045 7654321', 'contact@gardaresort.it', 5, 80, 'images/lago_garda_resort.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(5, 'Sicilia Sea View', 'Via Marina 34', 'Taormina', 'Italy', '+39 0942 345678', 'info@siciliaseaview.it', 5, 60, 'images/sicilia_sea_view.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(6, 'Catania Boutique', 'Via Etnea 220', 'Catania', 'Italy', '+39 095 1234567', 'hello@cataniaboutique.it', 4, 50, 'images/catania_boutique.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(7, 'Berlin Grand', 'Unter den Linden 5', 'Berlin', 'Germany', '+49 30 12345678', 'stay@berlingrand.de', 5, 120, 'images/berlin_grand.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(8, 'Munich Bavarian', 'Marienplatz 3', 'Munich', 'Germany', '+49 89 87654321', 'info@munichbavarian.de', 4, 90, 'images/munich_bavarian.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(9, 'Vienna Imperial Hotel', 'Ringstrasse 45', 'Vienna', 'Austria', '+43 1 2345678', 'stay@viennaimperial.at', 5, 110, 'images/vienna_imperial.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(10, 'Salzburg Alpine', 'Getreidegasse 9', 'Salzburg', 'Austria', '+43 662 9876543', 'contact@salzburgalpine.at', 4, 70, 'images/salzburg_alpine.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(11, 'Stockholm Nordic', 'Drottninggatan 20', 'Stockholm', 'Sweden', '+46 8 1234567', 'hello@stockholmnordic.se', 5, 100, 'images/stockholm_nordic.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(12, 'Gothenburg Harbor Hotel', 'Södra Hamngatan 10', 'Gothenburg', 'Sweden', '+46 31 7654321', 'info@gothenburgharbor.se', 4, 80, 'images/gothenburg_harbor.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(13, 'Brussels Heritage', 'Rue Royale 100', 'Brussels', 'Belgium', '+32 2 2345678', 'stay@brusselsheritage.be', 5, 90, 'images/brussels_heritage.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(14, 'Antwerp Classic', 'Meir 30', 'Antwerp', 'Belgium', '+32 3 8765432', 'info@antwerpclassic.be', 4, 70, 'images/antwerp_classic.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(15, 'Zurich Lakeside Hotel', 'Seestrasse 10', 'Zurich', 'Switzerland', '+41 44 1234567', 'hello@zurichlakeside.ch', 5, 95, 'images/zurich_lakeside.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(16, 'Lucerne Mountain View', 'Pilatusstrasse 5', 'Lucerne', 'Switzerland', '+41 41 9876543', 'info@lucernemountain.ch', 4, 60, 'images/lucerne_mountain.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(17, 'London Thames Grand', 'Victoria Embankment 12', 'London', 'England', '+44 20 12345678', 'contact@londonthamesgrand.co.uk', 5, 130, 'images/london_thames_grand.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(18, 'Bath Historic Inn', 'Pierrepont Street 9', 'Bath', 'England', '+44 1225 876543', 'stay@bathhistoricinn.co.uk', 4, 50, 'images/bath_historic_inn.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(19, 'Dublin Georgian House', 'Merrion Square 5', 'Dublin', 'Ireland', '+353 1 2345678', 'info@dublingeorgian.ie', 5, 90, 'images/dublin_georgian.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(20, 'Cork Riverside Hotel', 'Lapps Quay', 'Cork', 'Ireland', '+353 21 9876543', 'stay@corkriverside.ie', 4, 70, 'images/cork_riverside.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(21, 'Edinburgh Castle View', 'Castlehill 2', 'Edinburgh', 'Scotland', '+44 131 1234567', 'info@edinburghcastleview.co.uk', 5, 80, 'images/edinburgh_castle_view.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1),
(22, 'Glasgow City Hotel', 'George Square 4', 'Glasgow', 'Scotland', '+44 141 8765432', 'contact@glasgowcity.co.uk', 4, 75, 'images/glasgow_city_hotel.jpg', '2025-11-14 11:44:42', '2025-11-14 11:44:42', 1);

-- --------------------------------------------------------

--
-- Table structure for table `logincredentials`
--

CREATE TABLE `logincredentials` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_role` enum('admin','staff','customer') DEFAULT 'customer',
  `phone_number` varchar(20) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logincredentials`
--

INSERT INTO `logincredentials` (`user_id`, `first_name`, `last_name`, `user_email`, `user_password`, `user_role`, `phone_number`, `date_created`, `last_login`) VALUES
(1, 'admin', 'admin', 'admin@stc.com', '$2y$10$yoxnpjTUd4pceojfqhxHAOZG5tUuiM5iNO2/r7Q4I2H53fSgmis0e', 'admin', '', '2025-11-06 00:38:12', NULL),
(2, 'Johann', 'Bondin', 'johann@stc.com', '$2y$10$P0hkslZ0s5U92AcDWixz8uiIoMA/rPQtkSDzZGrp5ULQkH1hPKlrO', 'customer', '+35679299323', '2025-11-06 00:43:47', NULL),
(5, 'John', 'Smith', 'john@stc.com', '$2y$10$3gsLXef0wDwW4Y6PEAw4CeCEjWkp4FQ0nj1qEGHdsM0kRxFxtEJFa', 'customer', '+356777777777777', '2025-11-06 01:09:58', NULL),
(6, 'David', 'Cini', 'david@stc.com', '$2y$10$riUPsHjEY9yTIbOq3xgxU.Z/D5AwcIfphyLtwWgbj2JGMSHXfjt.q', 'customer', '+356777777777777', '2025-11-06 07:58:17', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`Booking_Id`),
  ADD KEY `Room_Id` (`Room_Id`),
  ADD KEY `CredentialsID` (`CredentialsID`);

--
-- Indexes for table `hotels_rooms`
--
ALTER TABLE `hotels_rooms`
  ADD PRIMARY KEY (`Room_Id`),
  ADD KEY `Hotel_Id` (`Hotel_Id`);

--
-- Indexes for table `hotel_details`
--
ALTER TABLE `hotel_details`
  ADD PRIMARY KEY (`Hotel_Id`);

--
-- Indexes for table `logincredentials`
--
ALTER TABLE `logincredentials`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `Booking_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hotels_rooms`
--
ALTER TABLE `hotels_rooms`
  MODIFY `Room_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=294;

--
-- AUTO_INCREMENT for table `hotel_details`
--
ALTER TABLE `hotel_details`
  MODIFY `Hotel_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `logincredentials`
--
ALTER TABLE `logincredentials`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`Room_Id`) REFERENCES `hotels_rooms` (`Room_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`CredentialsID`) REFERENCES `logincredentials` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hotels_rooms`
--
ALTER TABLE `hotels_rooms`
  ADD CONSTRAINT `hotels_rooms_ibfk_1` FOREIGN KEY (`Hotel_Id`) REFERENCES `hotel_details` (`Hotel_Id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
