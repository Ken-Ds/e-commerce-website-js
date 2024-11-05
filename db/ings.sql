-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 20, 2024 at 11:48 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ings`
--
CREATE SCHEMA `ings`;
USE `ings`;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

DROP TABLE IF EXISTS `organizations`;
CREATE TABLE IF NOT EXISTS `organizations` (
  `orgID` int NOT NULL AUTO_INCREMENT,
  `oName` varchar(255) NOT NULL,
  `oDesc` varchar(255) DEFAULT NULL,
  `oLogo` blob,
  PRIMARY KEY (`orgID`),
  UNIQUE KEY `unique_oname` (`oname`)
) ENGINE=InnoDb AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`orgID`, `oName`, `oDesc`, `oLogo`) VALUES
(1, 'Juan’s Sari-Sari Store', 'A one-stop shop for daily essentials, snacks, drinks, and household goods.', NULL),
(2, 'Lola’s Handicrafts', 'Selling local handmade crafts and souvenirs.', NULL),
(3, 'Pinoy Gadget Hub', 'The go-to place for affordable gadgets and electronics in the Philippines.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userID` int NOT NULL AUTO_INCREMENT,
  `uImg` blob,
  `username` varchar(100) NOT NULL,
  `type` ENUM('vendor', 'customer') NOT NULL,
  `email` varchar(150) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255)NOT NULL,
  `password` varchar(255) NOT NULL,
  `orgID` int DEFAULT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  CONSTRAINT `fk_orgID_users` FOREIGN KEY (`orgID`) REFERENCES `organizations` (`orgID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDb AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `uImg`, `username`, `type`, `email`, `firstname`, `lastname`, `password`, `orgID`) VALUES
(1, NULL, 'juan_store_owner', 'vendor', 'juan@gmail.com', 'Juan', 'Dela Cruz', 'password123', 1),
(2, NULL, 'lola_handicrafts', 'vendor', 'lola_handicrafts@gmail.com', 'Lola', 'Gonzales', 'password456', 2),
(3, NULL, 'gadget_admin', 'customer', 'gadgethub@gmail.com', 'Mark', 'Santos', 'password789', 3),
(4, NULL, 'sample', 'customer', 'sample@gmail.com', 'Sample', 'User', '1234', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `productID` int NOT NULL AUTO_INCREMENT,
  `orgID` int NOT NULL,
  `pName` varchar(255) NOT NULL,
  `pDesc` varchar(255) DEFAULT NULL,
  `pImage` longblob,
  `pCategory` varchar(100) DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`productID`),
  CONSTRAINT `fk_orgID_products` FOREIGN KEY (`orgID`) REFERENCES `organizations` (`orgID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDb AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productID`, `orgID`, `pName`, `pDesc`, `pImage`, `pCategory`, `stock`, `price`) VALUES
(1, 1, 'Instant Noodles', 'A pack of Filipino favorite instant noodles.', NULL, 'Food', 100, 15.00),
(2, 1, 'Laundry Detergent', '500g laundry detergent for everyday use.', NULL, 'Household', 50, 45.00),
(3, 2, 'Handwoven Bags', 'Locally handcrafted bags made from native materials.', NULL, 'Crafts', 25, 500.00),
(4, 2, 'Wooden Figurines', 'Hand-carved figurines from local wood.', NULL, 'Crafts', 15, 300.00),
(5, 3, 'Smartphone', 'Affordable smartphone with 32GB storage.', NULL, 'Electronics', 30, 4999.00),
(6, 3, 'Bluetooth Speaker', 'Portable Bluetooth speaker with long battery life.', NULL, 'Electronics', 20, 1500.00);

-- --------------------------------------------------------

--
-- Table structure for table `booths`
--

DROP TABLE IF EXISTS `booths`;
CREATE TABLE IF NOT EXISTS `booths` (
  `boothID` int NOT NULL AUTO_INCREMENT,
  `orgID` int NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`boothID`),
  CONSTRAINT `fk_orgID_booths` FOREIGN KEY (`orgID`) REFERENCES `organizations` (`orgID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDb AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booths`
--

INSERT INTO `booths` (`boothID`, `orgID`, `location`) VALUES
(1, 1, 'Market Area, Quezon City'),
(2, 2, 'Handicraft Lane, Baguio City'),
(3, 3, 'Tech Mall, Manila');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--
DROP TABLE IF EXISTS `schedules`;
CREATE TABLE IF NOT EXISTS `schedules` (
  `boothID` int NOT NULL,
  `dayAvailable` ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
  `timeStart` TIME NOT NULL,
  `timeEnd` TIME NOT NULL,
  PRIMARY KEY (`boothID`, `dayAvailable`),
  CONSTRAINT `fk_boothID_schedules` FOREIGN KEY (`boothID`) REFERENCES `booths` (`boothID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`boothID`, `dayAvailable`, `timeStart`, `timeEnd`) VALUES
(1, 'Monday', '9:00:00', '17:00:00'),
(1, 'Wednesday', '8:00:00', '16:00:00'),
(1, 'Friday', '9:00:00', '17:00:00'),
(2, 'Saturday', '8:00:00', '16:00:00'),
(2, 'Sunday', '7:30:00', '17:00:00'),
(3, 'Tuesday', '10:00:00', '18:00:00'),
(3, 'Thursday', '10:00:00', '18:00:00'),
(3, 'Saturday', '9:30:00', '16:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `orderID` int NOT NULL AUTO_INCREMENT,
  `userID` int NOT NULL,
  `orgID` int NOT NULL,
  `totalAmount` decimal(10,2) DEFAULT NULL,
  `dateOrdered` date DEFAULT NULL,
  `dateServed` date DEFAULT NULL,
  `custStatus` varchar(50) DEFAULT NULL,
  `vendStatus` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`orderID`),
  CONSTRAINT `fk_orgID_orders` FOREIGN KEY (`orgID`) REFERENCES `organizations` (`orgID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_userID_orders` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDb AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `userID`, `orgID`, `totalAmount`, `dateOrdered`, `dateServed`, `custStatus`, `vendStatus`) VALUES
(1, 1, 1, 150.00, '2024-10-01', '2024-10-02', 'Delivered', 'Completed'),
(2, 2, 2, 800.00, '2024-10-03', '2024-10-04', 'Pending', 'Processing'),
(3, 3, 3, 6499.00, '2024-10-05', '2024-10-06', 'Delivered', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

DROP TABLE IF EXISTS `orderdetails`;
CREATE TABLE IF NOT EXISTS `orderdetails` (
  `orderID` int NOT NULL,
  `productID` int NOT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`orderID`,`productID`),
  CONSTRAINT `fk_orderID_orderdetails` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_productID_orderdetails` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDb DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`orderID`, `productID`, `quantity`, `price`) VALUES
(1, 1, 10, 150.00),
(2, 3, 2, 1000.00),
(3, 5, 1, 4999.00),
(3, 6, 2, 3000.00);



COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
