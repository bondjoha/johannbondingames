-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2025 at 03:25 AM
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
  `User_Id` int(11) NOT NULL,
  `Hotel_Id` int(11) NOT NULL,
  `Room_Id` int(11) NOT NULL,
  `Room_Type` varchar(255) NOT NULL,
  `Price_Per_Night` decimal(10,2) NOT NULL,
  `Total_Price` decimal(10,2) NOT NULL,
  `Check_in` date NOT NULL,
  `Check_out` date NOT NULL,
  `Booking_Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`Booking_Id`, `User_Id`, `Hotel_Id`, `Room_Id`, `Room_Type`, `Price_Per_Night`, `Total_Price`, `Check_in`, `Check_out`, `Booking_Date`) VALUES
(51, 38, 2, 1, 'Standard', 100.00, 600.00, '2025-12-15', '2025-12-21', '2025-12-05 01:37:57'),
(52, 2, 17, 150, 'Standard', 130.00, 130.00, '2025-12-17', '2025-12-18', '2025-12-05 02:33:55');

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
(1, 2, '1', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-06 15:31:52', '2025-11-27 13:26:18', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(2, 2, '2', 'Standard', 20.00, 'Two Single Beds', 150.00, 'Available', '2025-11-06 15:43:30', '2025-11-15 03:33:13', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(3, 1, '1', 'Standard', 20.00, 'Two Single Beds', 59.00, 'Available', '2025-11-12 22:45:10', '2025-11-27 13:42:54', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(4, 2, '3', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-13 17:57:17', '2025-12-05 00:59:44', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(5, 2, '4', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-13 17:58:59', '2025-11-15 03:30:09', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(6, 1, '2', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(7, 1, '3', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(8, 1, '4', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(9, 1, '5', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(10, 1, '6', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(11, 1, '7', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(12, 1, '8', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(13, 1, '9', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(14, 1, '10', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(15, 2, '5', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(16, 2, '6', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(17, 2, '7', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(18, 2, '8', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(19, 2, '9', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(20, 2, '10', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(21, 2, '11', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(22, 2, '12', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(23, 2, '13', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:42', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(24, 3, '1', 'Standard', 20.00, 'Two Single Beds', 90.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(25, 3, '2', 'Standard', 20.00, 'Two Single Beds', 90.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(26, 3, '3', 'Standard', 20.00, 'Two Single Beds', 90.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(27, 3, '4', 'Deluxe', 35.00, 'Queen Bed', 140.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(28, 3, '5', 'Deluxe', 35.00, 'Queen Bed', 140.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(29, 3, '6', 'Deluxe', 35.00, 'Queen Bed', 140.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(30, 3, '7', 'Suite', 50.00, 'Queen Bed', 190.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(31, 3, '8', 'Suite', 50.00, 'Queen Bed', 190.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(32, 3, '9', 'Suite', 50.00, 'Queen Bed', 190.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(33, 4, '1', 'Standard', 20.00, 'Two Single Beds', 95.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(34, 4, '2', 'Standard', 20.00, 'Two Single Beds', 95.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(35, 4, '3', 'Standard', 20.00, 'Two Single Beds', 95.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(36, 4, '4', 'Deluxe', 35.00, 'Queen Bed', 145.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(37, 4, '5', 'Deluxe', 35.00, 'Queen Bed', 145.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(38, 4, '6', 'Deluxe', 35.00, 'Queen Bed', 145.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(39, 4, '7', 'Suite', 50.00, 'Queen Bed', 195.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(40, 4, '8', 'Suite', 50.00, 'Queen Bed', 195.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(41, 4, '9', 'Suite', 50.00, 'Queen Bed', 195.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(42, 5, '1', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(43, 5, '2', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(44, 5, '3', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(45, 5, '4', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(46, 5, '5', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(47, 5, '6', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(48, 5, '7', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(49, 5, '8', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(50, 5, '9', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(51, 6, '1', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(52, 6, '2', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(53, 6, '3', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(54, 6, '4', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(55, 6, '5', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(56, 6, '6', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(57, 6, '7', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(58, 6, '8', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(59, 6, '9', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(60, 7, '1', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(61, 7, '2', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(62, 7, '3', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(63, 7, '4', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(64, 7, '5', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(65, 7, '6', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(66, 7, '7', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(67, 7, '8', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(68, 7, '9', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(69, 8, '1', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(70, 8, '2', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(71, 8, '3', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(72, 8, '4', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(73, 8, '5', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(74, 8, '6', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(75, 8, '7', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(76, 8, '8', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(77, 8, '9', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(78, 9, '1', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(79, 9, '2', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(80, 9, '3', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(81, 9, '4', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(82, 9, '5', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(83, 9, '6', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(84, 9, '7', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(85, 9, '8', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(86, 9, '9', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(87, 10, '1', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(88, 10, '2', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(89, 10, '3', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(90, 10, '4', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(91, 10, '5', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(92, 10, '6', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(93, 10, '7', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(94, 10, '8', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(95, 10, '9', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(96, 11, '1', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(97, 11, '2', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(98, 11, '3', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(99, 11, '4', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(100, 11, '5', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(101, 11, '6', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(102, 11, '7', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(103, 11, '8', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(104, 11, '9', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(105, 12, '1', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(106, 12, '2', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(107, 12, '3', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(108, 12, '4', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(109, 12, '5', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(110, 12, '6', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(111, 12, '7', 'Suite', 50.00, 'Queen Bed', 200.00, 'Booked', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(112, 12, '8', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(113, 12, '9', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(114, 13, '1', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(115, 13, '2', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(116, 13, '3', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(117, 13, '4', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(118, 13, '5', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(119, 13, '6', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(120, 13, '7', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(121, 13, '8', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(122, 13, '9', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(123, 14, '1', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-12-04 16:07:28', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(124, 14, '2', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(125, 14, '3', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(126, 14, '4', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(127, 14, '5', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(128, 14, '6', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(129, 14, '7', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(130, 14, '8', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(131, 14, '9', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(132, 15, '1', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(133, 15, '2', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(134, 15, '3', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(135, 15, '4', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(136, 15, '5', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(137, 15, '6', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(138, 15, '7', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(139, 15, '8', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(140, 15, '9', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(141, 16, '1', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(142, 16, '2', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(143, 16, '3', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(144, 16, '4', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(145, 16, '5', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(146, 16, '6', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(147, 16, '7', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(148, 16, '8', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(149, 16, '9', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(150, 17, '1', 'Standard', 20.00, 'Two Single Beds', 130.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(151, 17, '2', 'Standard', 20.00, 'Two Single Beds', 130.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(152, 17, '3', 'Standard', 20.00, 'Two Single Beds', 130.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(153, 17, '4', 'Deluxe', 35.00, 'Queen Bed', 190.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(154, 17, '5', 'Deluxe', 35.00, 'Queen Bed', 190.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(155, 17, '6', 'Deluxe', 35.00, 'Queen Bed', 190.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(156, 17, '7', 'Suite', 50.00, 'Queen Bed', 230.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(157, 17, '8', 'Suite', 50.00, 'Queen Bed', 230.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(158, 17, '9', 'Suite', 50.00, 'Queen Bed', 230.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(159, 18, '1', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(160, 18, '2', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(161, 18, '3', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(162, 18, '4', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(163, 18, '5', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(164, 18, '6', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(165, 18, '7', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(166, 18, '8', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(167, 18, '9', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(168, 19, '1', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(169, 19, '2', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(170, 19, '3', 'Standard', 20.00, 'Two Single Beds', 110.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(171, 19, '4', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(172, 19, '5', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(173, 19, '6', 'Deluxe', 35.00, 'Queen Bed', 160.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(174, 19, '7', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(175, 19, '8', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(176, 19, '9', 'Suite', 50.00, 'Queen Bed', 210.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(177, 20, '1', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(178, 20, '2', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(179, 20, '3', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(180, 20, '4', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(181, 20, '5', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(182, 20, '6', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(183, 20, '7', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(184, 20, '8', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(185, 20, '9', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(186, 21, '1', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(187, 21, '2', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(188, 21, '3', 'Standard', 20.00, 'Two Single Beds', 120.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(189, 21, '4', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(190, 21, '5', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(191, 21, '6', 'Deluxe', 35.00, 'Queen Bed', 180.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(192, 21, '7', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(193, 21, '8', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(194, 21, '9', 'Suite', 50.00, 'Queen Bed', 220.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(195, 22, '1', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(196, 22, '2', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(197, 22, '3', 'Standard', 20.00, 'Two Single Beds', 100.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(198, 22, '4', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(199, 22, '5', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(200, 22, '6', 'Deluxe', 35.00, 'Queen Bed', 150.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room10.jpg', 'images/rooms/room11.jpg', 'images/rooms/room12.jpg'),
(201, 22, '7', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room1.jpg', 'images/rooms/room2.jpg', 'images/rooms/room3.jpg'),
(202, 22, '8', 'Suite', 50.00, 'Queen Bed', 200.00, 'Available', '2025-11-14 19:24:43', '2025-11-25 16:47:56', 1, 'images/rooms/room4.jpg', 'images/rooms/room5.jpg', 'images/rooms/room6.jpg'),
(297, 30, '1', 'Standard', 20.00, 'Two Single Bed', 100.00, 'Available', '2025-12-01 08:38:55', '2025-12-01 08:43:02', 1, 'images/rooms/room7.jpg', 'images/rooms/room8.jpg', 'images/rooms/room9.jpg'),
(298, 30, '2', 'Deluxe', 35.00, 'Queen Bed', 200.00, 'Available', '2025-12-01 09:05:23', '2025-12-01 09:05:23', 1, 'images/rooms/room_692d4c43e5329_room10.jpg', 'images/rooms/room_692d4c43e5989_room11.jpg', 'images/rooms/room_692d4c43e5c46_room12.jpg'),
(299, 30, '3', 'Suite', 55.00, 'Queen Bed', 340.00, 'Available', '2025-12-01 09:05:59', '2025-12-01 09:11:42', 1, 'images/rooms/room_692d4c67b5214_room_692d4c43e5c46_room12.jpg', 'images/rooms/room_692d4c67b5510_room_692d4c43e5329_room10.jpg', 'images/rooms/room_692d4c67b5721_room_692d4c43e5989_room11.jpg');

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
  `Hotel_Image2` varchar(255) DEFAULT NULL,
  `Hotel_Image3` varchar(255) DEFAULT NULL,
  `Created_At` datetime DEFAULT current_timestamp(),
  `Updated_At` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Is_Active` tinyint(1) DEFAULT 1,
  `Latitude` decimal(9,6) DEFAULT NULL,
  `Longitude` decimal(9,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_details`
--

INSERT INTO `hotel_details` (`Hotel_Id`, `Hotel_Name`, `Hotel_Street_Name`, `Hotel_City_Name`, `Hotel_Country_Name`, `Phone_Number`, `Email`, `Star_Rating`, `Number_of_Rooms`, `Hotel_Image`, `Hotel_Image2`, `Hotel_Image3`, `Created_At`, `Updated_At`, `Is_Active`, `Latitude`, `Longitude`) VALUES
(1, 'Bond Hotel', 'Triq il-konvoja ta santa marija', 'Mqabba', 'Malta', '+3567777777777', 'bondhotel@stc.com', 5, 10, 'images/bond_hotel.jpg', 'uploads/1764882292_BondHotel1.jpg', 'uploads/1764882292_BondHotel2.jpg', '2025-11-06 13:05:25', '2025-12-04 22:04:52', 1, 35.843346, 14.468560),
(2, 'Phoenicia Hotel', 'Vjal ir-Re Dwardu VII', 'Floriana', 'Malta', '+3567777777777', 'phoeniciahotel@stc.com', 4, 13, 'images/phoenicia.jpg', 'images/hotels/PhoeniciaHotel1.jpg', 'images/hotels/PhoeniciaHotel2.jpg', '2025-11-06 14:49:44', '2025-11-27 03:11:29', 1, 35.895520, 14.507130),
(3, 'Hotel Roma Elegante', 'Via dei Fori Imperiali 1', 'Rome', 'Italy', '+39 06 1234567', 'info@roma-elegante.it', 2, 100, 'images/hotel_roma_elegante.jpg', 'images/hotels/HotelRomaElegante1.jpg', 'images/hotels/HotelRomaElegante2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 41.892500, 12.485300),
(4, 'Lago di Garda Resort', 'Via Lago 12', 'Garda', 'Italy', '+39 045 7654321', 'contact@gardaresort.it', 3, 80, 'images/lago_garda_resort.jpg', 'images/hotels/LagoDiGardaResort1.jpg', 'images/hotels/LagoDiGardaResort2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 45.604300, 10.638000),
(5, 'Sicilia Sea View', 'Via Marina 34', 'Taormina', 'Sicily', '+39 0942 345678', 'info@siciliaseaview.it', 4, 60, 'images/sicilia_sea_view.jpg', 'images/hotels/SiciliaSeaView1.jpg', 'images/hotels/SiciliaSeaView2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 37.851000, 15.287000),
(6, 'Catania Boutique', 'Via Etnea 220', 'Catania', 'Sicily', '+39 095 1234567', 'hello@cataniaboutique.it', 1, 50, 'images/catania_boutique.jpg', 'images/hotels/CataniaBoutique1.jpg', 'images/hotels/CataniaBoutique2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 37.502100, 15.087300),
(7, 'Berlin Grand', 'Unter den Linden 5', 'Berlin', 'Germany', '+49 30 12345678', 'stay@berlingrand.de', 2, 120, 'images/berlin_grand.jpg', 'images/hotels/BerlinGrand1.jpg', 'images/hotels/BerlinGrand2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 52.517000, 13.388900),
(8, 'Munich Bavarian', 'Marienplatz 3', 'Munich', 'Germany', '+49 89 87654321', 'info@munichbavarian.de', 2, 90, 'images/munich_bavarian.jpg', 'images/hotels/MunichBavarian1.jpg', 'images/hotels/MunichBavarian2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 48.137200, 11.575600),
(9, 'Vienna Imperial Hotel', 'Ringstrasse 45', 'Vienna', 'Austria', '+43 1 2345678', 'stay@viennaimperial.at', 4, 110, 'images/vienna_imperial.jpg', 'images/hotels/ViennaImperialHotel1.jpg', 'images/hotels/ViennaImperialHotel2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 48.208200, 16.373800),
(10, 'Salzburg Alpine', 'Getreidegasse 9', 'Salzburg', 'Austria', '+43 662 9876543', 'contact@salzburgalpine.at', 4, 70, 'images/salzburg_alpine.jpg', 'images/hotels/SalzburgAlpine1.jpg', 'images/hotels/SalzburgAlpine2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 47.809500, 13.055000),
(11, 'Stockholm Nordic', 'Drottninggatan 20', 'Stockholm', 'Sweden', '+46 8 1234567', 'hello@stockholmnordic.se', 5, 100, 'images/stockholm_nordic.jpg', 'images/hotels/StockholmNordic1.jpg', 'images/hotels/StockholmNordic2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 59.329300, 18.068600),
(12, 'Gothenburg Harbor Hotel', 'Södra Hamngatan 10', 'Gothenburg', 'Sweden', '+46 31 7654321', 'info@gothenburgharbor.se', 1, 80, 'images/gothenburg_harbor.jpg', 'images/hotels/GothenburgHarborHotel1.jpg', 'images/hotels/GothenburgHarborHotel2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 57.708900, 11.974600),
(13, 'Brussels Heritage', 'Rue Royale 100', 'Brussels', 'Belgium', '+32 2 2345678', 'stay@brusselsheritage.be', 2, 90, 'images/brussels_heritage.jpg', 'images/hotels/BrusselsHeritage1.jpg', 'images/hotels/BrusselsHeritage2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 50.850300, 4.351700),
(14, 'Antwerp Classic', 'Meir 30', 'Antwerp', 'Belgium', '+32 3 8765432', 'info@antwerpclassic.be', 3, 70, 'images/antwerp_classic.jpg', 'images/hotels/AntwerpClassic1.jpg', 'images/hotels/AntwerpClassic2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 51.219400, 4.402500),
(15, 'Zurich Lakeside Hotel', 'Seestrasse 10', 'Zurich', 'Switzerland', '+41 44 1234567', 'hello@zurichlakeside.ch', 2, 95, 'images/zurich_lakeside.jpg', 'images/hotels/ZurichLakesideHotel1.jpg', 'images/hotels/ZurichLakesideHotel2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 47.376900, 8.541700),
(16, 'Lucerne Mountain View', 'Pilatusstrasse 5', 'Lucerne', 'Switzerland', '+41 41 9876543', 'info@lucernemountain.ch', 5, 60, 'images/lucerne_mountain.jpg', 'images/hotels/LucerneMountainView1.jpg', 'images/hotels/LucerneMountainView2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 47.050200, 8.309300),
(17, 'London Thames Grand', 'Victoria Embankment 12', 'London', 'England', '+44 20 12345678', 'contact@londonthamesgrand.co.uk', 4, 130, 'images/london_thames_grand.jpg', 'images/hotels/LondonThamesGrand1.jpg', 'images/hotels/LondonThamesGrand2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 51.507400, -0.127800),
(18, 'Bath Historic Inn', 'Pierrepont Street 9', 'Bath', 'England', '+44 1225 876543', 'stay@bathhistoricinn.co.uk', 1, 50, 'images/bath_historic_inn.jpg', 'images/hotels/BathHistoricInn1.jpg', 'images/hotels/BathHistoricInn2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 51.381300, -2.359000),
(19, 'Dublin Georgian House', 'Merrion Square 5', 'Dublin', 'Ireland', '+353 1 2345678', 'info@dublingeorgian.ie', 3, 90, 'images/dublin_georgian.jpg', 'images/hotels/DublinGeorgianHouse1.jpg', 'images/hotels/DublinGeorgianHouse2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 53.333100, -6.248900),
(20, 'Cork Riverside Hotel', 'Lapps Quay', 'Cork', 'Ireland', '+353 21 9876543', 'stay@corkriverside.ie', 5, 70, 'images/cork_riverside.jpg', 'images/hotels/CorkRiversideHotel1.jpg', 'images/hotels/CorkRiversideHotel2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 51.898500, -8.475600),
(21, 'Edinburgh Castle View', 'Castlehill 2', 'Edinburgh', 'Scotland', '+44 131 1234567', 'info@edinburghcastleview.co.uk', 2, 80, 'images/edinburgh_castle_view.jpg', 'images/hotels/EdinburghCastleView1.jpg', 'images/hotels/EdinburghCastleView2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 55.953300, -3.188300),
(22, 'Glasgow City Hotel', 'George Square 4', 'Glasgow', 'Scotland', '+44 141 8765432', 'contact@glasgowcity.co.uk', 4, 75, 'images/glasgow_city_hotel.jpg', 'images/hotels/GlasgowCityHotel1.jpg', 'images/hotels/GlasgowCityHotel2.jpg', '2025-11-14 11:44:42', '2025-11-25 22:47:42', 1, 55.860700, -4.251400),
(28, 'New Hotel 2', 'new streey', 'siggiewi', 'Malta', '79299323', 'newhotel@stc.com', 0, 0, 'images/phoenicia.jpg', 'images/phoenicia.jpg', 'images/phoenicia.jpg', '2025-11-27 18:36:17', '2025-11-27 18:36:17', 1, NULL, NULL),
(29, 'Old hotel 2', '74 St Andrew, triq il-konvoj ta Santa marija', 'Mqabba', 'Malta', '79299323', 'johannbondin@yahoo.co.uk', 0, 0, 'images/phoenicia.jpg', '', '', '2025-11-27 19:02:18', '2025-11-27 19:02:18', 1, NULL, NULL),
(30, 'Cavalieri Art Hotel', 'Spinola Road, Water\'s Edge', 'St Julian\'s', 'Malta', '+3567777777777', 'cavalieriHotel@stc.com', 4, 258, 'uploads/1764574556_CavalieriArtHotel1.jpg', 'uploads/1764574556_CavalieriArtHotel2.jpg', 'uploads/1764574556_CavalieriArtHotel3.jpg', '2025-12-01 06:50:26', '2025-12-01 08:35:56', 1, 35.919660, 14.494400);

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
  `last_login` timestamp NULL DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logincredentials`
--

INSERT INTO `logincredentials` (`user_id`, `first_name`, `last_name`, `user_email`, `user_password`, `user_role`, `phone_number`, `date_created`, `last_login`, `approved`) VALUES
(1, 'Admin', 'Admin', 'admin@stc.com', '$2y$10$r7a02cuW8QoVgKxwz.oKmOGLrlwvEV0EEIxqRubIXQ8uV9f5h2ZL2', 'admin', '+356777777777777', '2025-11-06 00:38:12', NULL, 0),
(2, 'Johann', 'Bondin', 'johann@stc.com', '$2y$10$6.dVybfJJSJpzaj7GIZhiuerenQoU7HjuoPTveFofk2JPC8OcS6HO', 'customer', '+35679299323', '2025-11-06 00:43:47', NULL, 0),
(8, 'admin', 'BondHotel', 'adminBondHotel@stc.com', '$2y$10$15un0t6por8kRhbhw4iEn.m0pWcDV7GQTcpczU3yUq0Nwe6QeNyha', 'staff', '+356777777777777', '2025-11-23 10:40:08', NULL, 1),
(9, 'admin', 'PhoeniciaHotel', 'adminphoeniciahotel@stc.com', '$2y$10$fp6F21O2sj4H7J7v5XC5ZOmj3NeEP/htGGZ1kbLYdKF/JJaS.UGrK', 'staff', '+3567777777777', '2025-11-26 12:24:43', NULL, 1),
(10, 'admin', 'HotelRomaElegante', 'adminhotelroma@stc.com', '$2y$10$LgVl1SWjvjKB7jNDld.c4uJH0nKxUMIpktOxhKs8aium51Fhpn.xi', 'staff', '+39061234567', '2025-11-26 12:24:43', NULL, 1),
(11, 'admin', 'LagoDiGardaResort', 'adminlagodigarda@stc.com', '$2y$10$FOwK2r.KUIf/MLBAC29yPOpZielzSDdy4xlgcLi22Cc.mylmERWcy', 'staff', '+390457654321', '2025-11-26 12:24:43', NULL, 1),
(12, 'admin', 'SiciliaSeaView', 'adminsiciliaseaview@stc.com', '$2y$10$jz1C6bGgXXewIsCcQwMYP.hqKQGQELqkLbgdUdcv5JPPYNwEfEBAe', 'staff', '+390942345678', '2025-11-26 12:24:43', NULL, 1),
(13, 'admin', 'CataniaBoutique', 'admincataniaboutique@stc.com', '$2y$10$LlRSSOFn/jghLq4abo8oFu8wD5hDGHPHXyVfrJ9VRcK2MLDhr4Hha', 'staff', '+390951234567', '2025-11-26 12:24:43', NULL, 1),
(14, 'admin', 'BerlinGrand', 'adminberlingrand@stc.com', '$2y$10$OvgwopewH0Gh59bljt.5kemf8aCYGg9V/vaQOckCK5vHgySSP.evC', 'staff', '+493012345678', '2025-11-26 12:24:43', NULL, 1),
(15, 'admin', 'MunichBavarian', 'adminmunichbavarian@stc.com', '$2y$10$G47ts/9kutO2V7ZtIB0GGOtvIQD.eVE4RMDfXj7/VIdR4tWbQTbNq', 'staff', '+498987654321', '2025-11-26 12:24:43', NULL, 1),
(16, 'admin', 'ViennaImperialHotel', 'adminviennaimperial@stc.com', '$2y$10$NCq5xCphD2K8zfjuN8SUseZV4KsjRuk0dyqTzM1rvMYSoVjykCegy', 'staff', '+4312345678', '2025-11-26 12:24:43', NULL, 1),
(17, 'admin', 'SalzburgAlpine', 'adminsalzburgalpine@stc.com', '$2y$10$d9nHNHzHhjJXqdU0nzuyq.q13G0StSBXL2EJ249tf/YtkO3N/jPw6', 'staff', '+436629876543', '2025-11-26 12:24:43', NULL, 1),
(18, 'admin', 'StockholmNordic', 'adminstockholmnordic@stc.com', '$2y$10$DnU.oxDf9MKEyYhtXJ3WJeM0TFem79YaKeKi87UKk2dObroZV3wri', 'staff', '+4681234567', '2025-11-26 12:24:43', NULL, 1),
(19, 'admin', 'GothenburgHarborHotel', 'admingothenburgharbor@stc.com', '$2y$10$BPmXTeHd0s6vNy70lctvC.LgvVmXnSwdi.afLWhBPl./CoK7pEBmu', 'staff', '+46317654321', '2025-11-26 12:24:43', NULL, 1),
(20, 'admin', 'BrusselsHeritage', 'adminbrusselsheritage@stc.com', '$2y$10$1NvXoDOQnY46iDElVlFbousaFJ2ohalxP0.zZQzvk7mA8VDaJmkA.', 'staff', '+3222345678', '2025-11-26 12:24:43', NULL, 1),
(21, 'admin', 'AntwerpClassic', 'adminantwerpclassic@stc.com', '$2y$10$Od3xEh/fseQqtM4VfPYuFeGPm6rlk38tCtCL3AFT.oRzA9k4oA8v6', 'staff', '+3238765432', '2025-11-26 12:24:43', NULL, 1),
(22, 'admin', 'ZurichLakesideHotel', 'adminzurichlakeside@stc.com', '$2y$10$TJfO17R6xSxqpcz0Y5bqfu3O8QEu9MF.981.lhYpIjBTUiCylgQKW', 'staff', '+41441234567', '2025-11-26 12:24:43', NULL, 1),
(23, 'admin', 'LucerneMountainView', 'adminlucernemountain@stc.com', '$2y$10$r7mvpqGNRexbsftG1HsuuuKo9cXSuDTYjFGV1j4tK5XGB8E/AbmxW', 'staff', '+41419876543', '2025-11-26 12:24:43', NULL, 1),
(24, 'admin', 'LondonThamesGrand', 'adminlondonthames@stc.com', '$2y$10$LjgRmZFxDo82Pn69fVc4QOlVNfQnQ4Dvd23tXljZ8Vmkb6pZvFPVO', 'staff', '+442012345678', '2025-11-26 12:24:43', NULL, 1),
(25, 'admin', 'BathHistoricInn', 'adminbathhistoric@stc.com', '$2y$10$l.1tOA0e8sBixyUX87o6keJBdOryanLb97NLX7nPwtaneO7MRUkkG', 'staff', '+441225876543', '2025-11-26 12:24:43', NULL, 1),
(26, 'admin', 'DublinGeorgianHouse', 'admindublingeorgian@stc.com', '$2y$10$.uoNfiVZ4BYctbYBkyvX.uXiYORVnj3eYJWTC3RSgdAR0TYlb6zeu', 'staff', '+35312345678', '2025-11-26 12:24:43', NULL, 1),
(27, 'admin', 'CorkRiversideHotel', 'admincorkriverside@stc.com', '$2y$10$tXQjpaSSIJ/npdkxnCh0leOSwo18/BHbuF3PueeLcq.axog0HYd8O', 'staff', '+353219876543', '2025-11-26 12:24:43', NULL, 1),
(28, 'admin', 'EdinburghCastleView', 'adminedinburghcastle@stc.com', '$2y$10$9pIeTZsy6SxigRm2E.A2vuKaoVZSSGFe0jyyI8.P6tF.w0HkVsIPC', 'staff', '+441311234567', '2025-11-26 12:24:43', NULL, 1),
(29, 'admin', 'GlasgowCityHotel', 'adminglasgowcity@stc.com', '$2y$10$66lM8bstiWyJO38SbX9wneb7Evmk1TlRo5LRQDCxn/LnASJnuQc3O', 'staff', '+441412345678', '2025-11-26 12:24:43', NULL, 1),
(30, 'Matthew', 'Mizzi', 'matt@stc.com', '$2y$10$QYimeaN1yi8CyDMnN0cEAe2/EovVQ4Kk72oaGsc5079KQk0JBpHwC', 'customer', '+356777777777777', '2025-11-26 17:47:41', NULL, 0),
(31, 'steve', 'camilleri', 'steve@stc.com', '$2y$10$sF5/YBc85Le4wm7vt9h4teWB/TkYdMfNRWmHOSx.rG.W0IKpGm3xO', '', '+356777777777777', '2025-11-27 16:43:16', NULL, 0),
(32, 'Noel', 'farrugia', 'noel@stc.com', '$2y$10$X0WW4FxPkDD.I/Xm7mwe6eQVrxne4TYdloS.eFgLHqdvjF7d87y1G', '', '+356777777777777', '2025-11-30 23:59:43', NULL, 0),
(33, 'Tom', 'Hill', 'tom@stc.com', '$2y$10$mYLUse2rPwr0lwckIk/izuM/FkDSV/RUMNKTXsQVM8382PraZSO82', '', '+356777777777777', '2025-12-01 00:11:00', NULL, 0),
(36, 'Staff', 'cavalieri', 'adminCavalieriHotel@stc.com', '$2y$10$HJzVCI1TeeyK0aFoJAGcGOnFGtCpLHgIw5HasT3E5JyO/fP3Ygw4y', 'staff', '+356777777777777', '2025-12-01 05:53:18', NULL, 1),
(37, 'Keith', 'Bondin', 'keith@stc.com', '$2y$10$gHkBCsNoky.mvsvh5dfEmOCNFFD8VycetMRsbBH9PTqiBrI9N2K9W', '', '79299323', '2025-12-02 23:55:37', NULL, 0),
(38, 'John', 'Tanti', 'john@stc.com', '$2y$10$6z9ca20IDSlPltLGdXNwuOsr3ftrG37PJid.0ok1/GT8z9p1N0Tla', '', '+356777777777777', '2025-12-04 22:02:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `usershotels`
--

CREATE TABLE `usershotels` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `role` enum('staff','admin') NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usershotels`
--

INSERT INTO `usershotels` (`id`, `user_id`, `hotel_id`, `role`, `assigned_at`) VALUES
(6, 9, 2, 'staff', '2025-11-26 13:54:40'),
(7, 10, 3, 'staff', '2025-11-26 13:54:59'),
(8, 11, 4, 'staff', '2025-11-26 13:55:19'),
(9, 12, 5, 'staff', '2025-11-26 13:55:37'),
(10, 13, 6, 'staff', '2025-11-26 13:55:50'),
(11, 14, 7, 'staff', '2025-11-26 13:56:06'),
(12, 15, 8, 'staff', '2025-11-26 13:56:24'),
(13, 16, 9, 'staff', '2025-11-26 13:56:45'),
(14, 17, 10, 'staff', '2025-11-26 13:57:05'),
(15, 18, 11, 'staff', '2025-11-26 13:57:24'),
(16, 19, 12, 'staff', '2025-11-26 13:57:44'),
(17, 20, 13, 'staff', '2025-11-26 13:58:09'),
(18, 21, 14, 'staff', '2025-11-26 13:58:26'),
(19, 22, 15, 'staff', '2025-11-26 13:58:47'),
(20, 23, 16, 'staff', '2025-11-26 13:59:13'),
(21, 24, 17, 'staff', '2025-11-26 13:59:33'),
(22, 25, 18, 'staff', '2025-11-26 13:59:53'),
(23, 26, 19, 'staff', '2025-11-26 14:00:14'),
(24, 27, 20, 'staff', '2025-11-26 14:00:35'),
(25, 28, 21, 'staff', '2025-11-26 14:00:51'),
(26, 29, 22, 'staff', '2025-11-26 14:01:12'),
(27, 8, 28, 'staff', '2025-11-27 17:36:17'),
(28, 8, 29, 'staff', '2025-11-27 18:02:18'),
(35, 36, 30, 'staff', '2025-12-01 07:35:56'),
(36, 8, 1, 'staff', '2025-12-04 21:04:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`Booking_Id`),
  ADD KEY `fk_booking_user` (`User_Id`),
  ADD KEY `fk_booking_hotel` (`Hotel_Id`),
  ADD KEY `fk_booking_room` (`Room_Id`);

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
-- Indexes for table `usershotels`
--
ALTER TABLE `usershotels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_hotel` (`user_id`,`hotel_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `Booking_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `hotels_rooms`
--
ALTER TABLE `hotels_rooms`
  MODIFY `Room_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=300;

--
-- AUTO_INCREMENT for table `hotel_details`
--
ALTER TABLE `hotel_details`
  MODIFY `Hotel_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `logincredentials`
--
ALTER TABLE `logincredentials`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `usershotels`
--
ALTER TABLE `usershotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `fk_booking_hotel` FOREIGN KEY (`Hotel_Id`) REFERENCES `hotel_details` (`Hotel_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_booking_room` FOREIGN KEY (`Room_Id`) REFERENCES `hotels_rooms` (`Room_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_booking_user` FOREIGN KEY (`User_Id`) REFERENCES `logincredentials` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hotels_rooms`
--
ALTER TABLE `hotels_rooms`
  ADD CONSTRAINT `hotels_rooms_ibfk_1` FOREIGN KEY (`Hotel_Id`) REFERENCES `hotel_details` (`Hotel_Id`) ON DELETE CASCADE;

--
-- Constraints for table `usershotels`
--
ALTER TABLE `usershotels`
  ADD CONSTRAINT `usershotels_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `logincredentials` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usershotels_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotel_details` (`Hotel_Id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
