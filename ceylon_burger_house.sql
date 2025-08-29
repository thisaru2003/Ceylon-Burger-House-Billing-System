-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 29, 2025 at 09:15 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ceylon_burger_house`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(55) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `address` varchar(100) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `full_name`, `username`, `email`, `phone`, `address`, `profile_photo`, `is_verified`, `password`) VALUES
(1, 'Sohan Prasanna', 'sohan', 'sohan@gmail.com', '077123456', 'welegoda', NULL, 1, '$2y$10$E8lijCCnW8h.oqrbxpvUZ.4dexUT4cxRAjZ/D4gIhwZYMuBKWefO.');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_item` (`user_name`,`item_name`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `category`, `name`, `price`, `image_url`) VALUES
(7, 'Burger', 'Cripsy Chicken Burger', 600.00, '../Images/download.jpeg'),
(6, 'Burger', 'cheese buger', 123.00, '../Images/Addfood2.jpg'),
(5, 'Hotdog', 'cheese hot dog', 350.00, '../Images/Addfood2.jpg'),
(8, 'Burger', 'Grill Sea Food Burger', 1000.00, '../Images/download (1).jpeg'),
(9, 'Pasta', 'Creamy Chicken Pasta', 700.00, '../Images/download (2).jpeg'),
(10, 'Hot Dog', 'Classic Hot Dog', 350.00, '../Images/images.jpeg'),
(11, 'Hot Dog', 'Cheese Chicken Hot Dog', 500.00, '../Images/images (1).jpeg'),
(12, 'Hot Dog', 'Scrambled Egg, Sasage Hot Dog', 600.00, '../Images/download (3).jpeg'),
(13, 'Submarine', 'Chicken Submarine', 700.00, '../Images/download (4).jpeg'),
(14, 'Submarine', 'SeaFood Submarine', 900.00, '../Images/download (5).jpeg'),
(15, 'Beverage', 'Chocolate Milkshake', 350.00, '../Images/download (7).jpeg'),
(16, 'Beverage', 'Watermelon Milkshake', 400.00, '../Images/download (8).jpeg'),
(17, 'Beverage', 'Vanila Milkshake', 300.00, '../Images/download (6).jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `order_date` date NOT NULL,
  `order_time` time NOT NULL,
  `status` enum('Pending','Confirmed','Declined') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_name`, `order_date`, `order_time`, `status`) VALUES
(44, 'thisaru', '2025-08-29', '02:43:28', 'Pending'),
(43, 'thisaru', '2025-08-29', '02:42:43', 'Pending'),
(42, 'sohan', '2025-08-29', '01:52:48', 'Pending'),
(41, 'sohan', '2025-08-29', '01:47:39', 'Pending'),
(40, 'sohan', '2025-08-29', '01:46:06', 'Pending'),
(39, 'sohan', '2025-08-28', '03:44:24', 'Pending'),
(38, 'sohan', '2025-08-28', '03:43:29', 'Pending'),
(37, 'Staff', '2024-10-09', '12:11:06', 'Pending'),
(36, 'Staff', '2024-10-09', '12:10:36', 'Pending'),
(35, 'Staff', '2024-10-09', '12:08:41', 'Pending'),
(34, 'Staff', '2024-10-09', '12:07:02', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_name`, `quantity`, `price`) VALUES
(75, 44, 'Grill Sea Food Burger', 1, 1000.00),
(74, 44, 'Cripsy Chicken Burger', 1, 600.00),
(73, 43, 'cheese buger', 1, 123.00),
(72, 43, 'Cripsy Chicken Burger', 1, 600.00),
(71, 42, 'Cripsy Chicken Burger', 1, 600.00),
(70, 41, 'cheese buger', 1, 123.00),
(69, 41, 'Cripsy Chicken Burger', 1, 600.00),
(68, 40, 'cheese buger', 1, 123.00),
(67, 40, 'Cripsy Chicken Burger', 1, 600.00),
(66, 39, 'Grill Sea Food Burger', 1, 1000.00),
(65, 39, 'cheese buger', 1, 123.00),
(64, 39, 'Cripsy Chicken Burger', 1, 600.00),
(63, 38, 'cheese hot dog', 1, 350.00),
(62, 38, 'Grill Sea Food Burger', 1, 1000.00),
(61, 38, 'Cripsy Chicken Burger', 1, 600.00),
(60, 37, 'cheese buger', 1, 123.00),
(59, 36, 'cheese buger', 1, 123.00),
(58, 35, 'cheese buger', 1, 123.00),
(57, 34, 'cheese buger', 1, 123.00);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(55) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `address` varchar(100) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `full_name`, `username`, `email`, `phone`, `address`, `profile_photo`, `is_verified`, `password`) VALUES
(1, 'Thisaru Jayawickrama', 'thisaru', 'thisarunadishka2003@gmail.com', '0779364782', 'No 2/4, Ranaviru Udhara Wasana Mwatha,', NULL, 1, '$2y$10$au49vJ3KbpdNIBGVJjmbce8IUEeGon8yyUjmsYdgZNfTluA6QkY7.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(55) NOT NULL,
  `email` varchar(55) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(55) NOT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `profile_photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
