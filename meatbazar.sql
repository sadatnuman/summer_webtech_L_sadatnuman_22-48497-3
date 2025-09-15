-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 15, 2025 at 08:30 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meatbazar`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `full_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`full_name`, `email`, `user_name`, `phone_number`, `dob`, `password`) VALUES
('Md Doad', 'doadmohammad@gmail.com', 'mddoad', '01568171665', '2002-09-29', 'doad1@MB'),
('Papia Sultana Prianka', '22-48356-3@student.aiub.edu', 'priyanka', '01310378538', '2003-11-04', 'Num@n1234');

-- --------------------------------------------------------

--
-- Table structure for table `distributor`
--

CREATE TABLE `distributor` (
  `full_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `distributor`
--

INSERT INTO `distributor` (`full_name`, `email`, `user_name`, `phone_number`, `dob`, `password`) VALUES
('MD Doad', 'doadmohammad711@gmail.com', 'doad', '01568171665', '2002-09-29', 'doad1@MB'),
('Sadat', 'sadat@gmail.com', 'sadat', '1724972425', '2003-08-30', 'Num@n1234');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `category` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `category`, `name`, `price`, `image`, `description`) VALUES
(1, 'featured', 'Beef Tenderloin', 1200, 'tenderloin.png', 'Premium cut of beef, perfect for steaks and special occasions'),
(2, 'featured', 'Whole Chicken', 270, 'chiken_whole.png', 'Fresh whole chicken, perfect for roasting or curry preparation'),
(3, 'featured', 'Mutton Leg', 1050, 'mutton_leg.png', 'Tender mutton leg cut, ideal for biryanis and traditional dishes'),
(4, 'featured', 'Beef Brisket', 750, 'brisket.png', 'Slow-cooking favorite, perfect for BBQ and braising'),
(5, 'featured', 'Chicken Breast', 300, 'chiken_breast.png', 'Lean protein-rich chicken breast, perfect for healthy meals'),
(6, 'featured', 'Mutton Chops', 960, 'mutton_chops.png', 'Premium mutton chops, perfect for grilling and special occasions'),
(7, 'featured', 'Ground Beef', 650, 'ground beef.png', 'Freshly ground beef, perfect for burgers, meatballs and curries'),
(8, 'featured', 'Chicken Wings', 280, 'chicken_wings.png', 'Juicy chicken wings, perfect for appetizers and party snacks'),
(9, 'featured', 'Ground Mutton', 850, 'mutton_ground.png', 'Fresh minced mutton, perfect for kebabs and kofta'),
(10, 'beef', 'Beef Chuck', 700, 'chuck.png', 'Tender meat from shoulder area, perfect for slow cooking and curry'),
(11, 'beef', 'Beef Brisket', 750, 'brisket.png', 'Slow-cooking favorite, perfect for BBQ and braising'),
(12, 'beef', 'Beef Short Ribs', 800, 'short ribs.png', 'Bone-in ribs with rich marbling, excellent for grilling'),
(13, 'beef', 'Ground Beef', 650, 'ground beef.png', 'Freshly ground beef, perfect for burgers, meatballs and curries'),
(14, 'beef', 'Beef Liver', 350, 'liver.png', 'Fresh beef liver, rich in iron and vitamins'),
(15, 'beef', 'Beef Shank', 600, 'shank.png', 'Bone-in shank cut, perfect for traditional Bangladeshi curry'),
(16, 'beef', 'Beef Tongue', 900, 'tongue.png', 'Tender beef tongue, a delicacy for special occasions'),
(17, 'beef', 'Beef Tenderloin', 1200, 'tenderloin.png', 'Premium cut of beef, perfect for steaks and special occasions'),
(18, 'beef', 'Mixed Beef', 680, 'beef.png', 'Assorted beef cuts from different parts, perfect for general cooking'),
(19, 'mutton', 'Mutton Leg', 1050, 'mutton_leg.png', 'Tender mutton leg cut, ideal for biryanis and traditional dishes'),
(20, 'mutton', 'Mutton Shoulder', 900, 'mutton_shoulder.png', 'Tender shoulder cuts, ideal for curry and slow cooking'),
(21, 'mutton', 'Mutton Ribs', 900, 'mutton_ribs.png', 'Bone-in ribs with rich flavor, excellent for BBQ'),
(22, 'mutton', 'Ground Mutton', 850, 'mutton_ground.png', 'Fresh minced mutton, perfect for kebabs and kofta'),
(23, 'mutton', 'Mutton Chops', 950, 'mutton_chops.png', 'Premium mutton chops, perfect for grilling and special occasions'),
(24, 'mutton', 'Mutton Neck', 800, 'mutton_neck.png', 'Bone-in neck cuts, ideal for traditional stews and curry'),
(25, 'chicken', 'Whole Chicken', 260, 'chiken_whole.png', 'Fresh whole chicken, perfect for roasting or curry preparation'),
(26, 'chicken', 'Chicken Breast', 300, 'chiken_breast.png', 'Lean protein-rich chicken breast, perfect for healthy meals'),
(27, 'chicken', 'Chicken Thigh', 280, 'chicken_thigh.png', 'Juicy chicken thighs, perfect for curry and grilling'),
(28, 'chicken', 'Chicken Wings', 280, 'chicken_wings.png', 'Juicy chicken wings, perfect for appetizers and party snacks'),
(29, 'chicken', 'Chicken Drumstick', 290, 'chicken_drumstick.png', 'Tender drumsticks, great for kids and family dinners'),
(30, 'chicken', 'Ground Chicken', 255, 'chicken_ground.png', 'Fresh minced chicken, perfect for burgers and meatballs');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(15) NOT NULL,
  `customer_address` text NOT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`)),
  `subtotal` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_type` enum('cod','online') NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `order_status` enum('pending','confirmed','processing','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `assigned_distributor` varchar(50) DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `last_status_update` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_phone`, `customer_address`, `items`, `subtotal`, `delivery_fee`, `total_amount`, `payment_type`, `payment_method`, `order_status`, `created_at`, `updated_at`, `assigned_distributor`, `assigned_at`, `last_status_update`) VALUES
(1, 'MD Doad', '1568171665', 'house-1, road-5, block-h, bansree,dhaka', '[{\"name\":\"Beef Brisket\",\"price\":750,\"image\":\"brisket.png\",\"quantity\":2,\"total\":1500},{\"name\":\"Beef Short Ribs\",\"price\":800,\"image\":\"short ribs.png\",\"quantity\":1,\"total\":800}]', 2300.00, 50.00, 2350.00, 'cod', 'cash_on_delivery', 'delivered', '2025-09-12 16:18:35', '2025-09-12 18:05:52', NULL, NULL, NULL),
(2, 'MD Doad', '1568171665', 'house-1, road-5, block-h, bansree,dhaka', '[{\"name\":\"Beef Brisket\",\"price\":750,\"image\":\"brisket.png\",\"quantity\":1,\"total\":750},{\"name\":\"Beef Short Ribs\",\"price\":800,\"image\":\"short ribs.png\",\"quantity\":1,\"total\":800}]', 1550.00, 50.00, 1600.00, 'cod', 'cash_on_delivery', 'delivered', '2025-09-12 16:25:22', '2025-09-13 17:28:41', NULL, NULL, NULL),
(3, 'MD Doad', '1568171665', 'house-1, road-5, block-h, bansree,dhaka', '[{\"name\":\"Mutton Shoulder\",\"price\":800,\"image\":\"mutton_shoulder.png\",\"quantity\":1,\"total\":800},{\"name\":\"Mutton Ribs\",\"price\":900,\"image\":\"mutton_ribs.png\",\"quantity\":1,\"total\":900},{\"name\":\"Ground Chicken\",\"price\":330,\"image\":\"ground_chicken.png\",\"quantity\":1,\"total\":330},{\"name\":\"Beef Brisket\",\"price\":750,\"image\":\"brisket.png\",\"quantity\":1,\"total\":750}]', 2780.00, 50.00, 2830.00, 'online', 'bkash', 'delivered', '2025-09-12 17:24:05', '2025-09-12 18:12:49', NULL, NULL, NULL),
(4, 'MD Doad', '1568171665', 'house-1, road-5, block-h, bansree,dhaka', '[{\"name\":\"Chicken Breast\",\"price\":350,\"image\":\"chicken_breast.png\",\"quantity\":1,\"total\":350},{\"name\":\"Beef Tenderloin\",\"price\":1200,\"image\":\"tenderloin.png\",\"quantity\":1,\"total\":1200},{\"name\":\"Mutton Leg\",\"price\":850,\"image\":\"mutton_leg.png\",\"quantity\":1,\"total\":850},{\"name\":\"Ground Beef\",\"price\":650,\"image\":\"ground beef.png\",\"quantity\":1,\"total\":650},{\"name\":\"Mutton Chops\",\"price\":950,\"image\":\"mutton_chops.png\",\"quantity\":1,\"total\":950}]', 4000.00, 50.00, 4050.00, 'online', 'card', 'processing', '2025-09-12 17:43:57', '2025-09-12 18:12:40', NULL, NULL, NULL),
(5, 'MD Doad', '1568171665', 'house-1, road-5, block-h, bansree,dhaka', '[{\"name\":\"Beef Chuck\",\"price\":700,\"image\":\"chuck.png\",\"quantity\":1,\"total\":700},{\"name\":\"Beef Brisket\",\"price\":750,\"image\":\"brisket.png\",\"quantity\":1,\"total\":750},{\"name\":\"Chicken Breast\",\"price\":350,\"image\":\"chicken_breast.png\",\"quantity\":1,\"total\":350},{\"name\":\"Mutton Ribs\",\"price\":900,\"image\":\"mutton_ribs.png\",\"quantity\":2,\"total\":1800}]', 3600.00, 50.00, 3650.00, 'online', 'bkash', 'delivered', '2025-09-12 18:24:25', '2025-09-12 18:27:49', NULL, NULL, NULL),
(6, 'MD Doad', '1568171665', 'house-1, road-5, block-h, bansree,dhaka', '[{\"name\":\"Mutton Shoulder\",\"price\":800,\"image\":\"mutton_shoulder.png\",\"quantity\":1,\"total\":800},{\"name\":\"Mutton Ribs\",\"price\":900,\"image\":\"mutton_ribs.png\",\"quantity\":1,\"total\":900},{\"name\":\"Chicken Thigh\",\"price\":320,\"image\":\"chicken_thigh.png\",\"quantity\":1,\"total\":320},{\"name\":\"Whole Chicken\",\"price\":300,\"image\":\"chiken_whole.png\",\"quantity\":1,\"total\":300}]', 2320.00, 50.00, 2370.00, 'cod', 'cash_on_delivery', 'confirmed', '2025-09-13 08:47:06', '2025-09-13 08:47:40', NULL, NULL, NULL),
(7, 'Sadat Numan', '1724972425', 'No address provided', '[{\"name\":\"Whole Chicken\",\"price\":260,\"image\":\"chiken_whole.png\",\"quantity\":1,\"total\":260},{\"name\":\"Chicken Thigh\",\"price\":280,\"image\":\"chicken_thigh.png\",\"quantity\":1,\"total\":280},{\"name\":\"Mutton Shoulder\",\"price\":900,\"image\":\"mutton_shoulder.png\",\"quantity\":1,\"total\":900}]', 1440.00, 50.00, 1490.00, 'cod', 'cash_on_delivery', 'confirmed', '2025-09-13 16:32:11', '2025-09-14 19:59:18', 'sadat', '2025-09-14 19:59:18', '2025-09-14 19:59:18'),
(8, 'Sadat Numan', '1724972425', 'No address provided', '[{\"name\":\"Mutton Chops\",\"price\":950,\"image\":\"mutton_chops.png\",\"quantity\":1,\"total\":950},{\"name\":\"Ground Beef\",\"price\":650,\"image\":\"ground beef.png\",\"quantity\":1,\"total\":650},{\"name\":\"Chicken Wings\",\"price\":280,\"image\":\"chicken_wings.png\",\"quantity\":1,\"total\":280}]', 1880.00, 50.00, 1930.00, 'online', 'bkash', 'confirmed', '2025-09-13 16:33:35', '2025-09-14 20:03:43', 'sadat', '2025-09-14 20:03:43', '2025-09-14 20:03:43'),
(9, 'Sadat Numan', '1724972425', 'Bashundhara', '[{\"name\":\"Whole Chicken\",\"price\":260,\"image\":\"chiken_whole.png\",\"quantity\":2,\"total\":520},{\"name\":\"Mutton Leg\",\"price\":1050,\"image\":\"mutton_leg.png\",\"quantity\":4,\"total\":4200}]', 4720.00, 50.00, 4770.00, 'cod', 'cash_on_delivery', 'processing', '2025-09-13 16:40:39', '2025-09-13 19:16:56', NULL, NULL, NULL),
(10, 'Sadat Numan', '1724972425', 'Bashundhara', '[{\"name\":\"Whole Chicken\",\"price\":270,\"image\":\"chiken_whole.png\",\"quantity\":4,\"total\":1080}]', 1080.00, 50.00, 1130.00, 'cod', 'cash_on_delivery', 'delivered', '2025-09-13 18:33:26', '2025-09-13 19:47:14', NULL, NULL, NULL),
(11, 'Sadat Numan', '1724972425', 'Bashundhara', '[{\"name\":\"Chicken Drumstick\",\"price\":290,\"image\":\"chicken_drumstick.png\",\"quantity\":3,\"total\":870},{\"name\":\"Beef Tenderloin\",\"price\":1200,\"image\":\"tenderloin.png\",\"quantity\":1,\"total\":1200}]', 2070.00, 50.00, 2120.00, 'cod', 'cash_on_delivery', 'delivered', '2025-09-14 05:43:34', '2025-09-14 05:45:44', NULL, NULL, NULL),
(12, 'hasib', '1746009390', 'rampura dhaka ', '[{\"name\":\"Mutton Chops\",\"price\":950,\"image\":\"mutton_chops.png\",\"quantity\":2,\"total\":1900}]', 1900.00, 50.00, 1950.00, 'cod', 'cash_on_delivery', 'confirmed', '2025-09-14 06:23:34', '2025-09-14 19:59:11', 'sadat', '2025-09-14 19:59:11', '2025-09-14 19:59:11'),
(13, 'hasib', '1746009390', 'rampura dhaka ', '[{\"name\":\"Ground Mutton\",\"price\":850,\"image\":\"mutton_ground.png\",\"quantity\":1,\"total\":850}]', 850.00, 50.00, 900.00, 'online', 'bkash', 'confirmed', '2025-09-14 06:24:17', '2025-09-14 06:30:27', NULL, NULL, NULL),
(14, 'Sadat Numan', '1724972425', 'Bashundhara', '[{\"name\":\"Ground Beef\",\"price\":650,\"image\":\"ground beef.png\",\"quantity\":1,\"total\":650},{\"name\":\"Mutton Leg\",\"price\":1050,\"image\":\"mutton_leg.png\",\"quantity\":1,\"total\":1050},{\"name\":\"Beef Tenderloin\",\"price\":1200,\"image\":\"tenderloin.png\",\"quantity\":1,\"total\":1200}]', 2900.00, 50.00, 2950.00, 'cod', 'cash_on_delivery', 'delivered', '2025-09-14 17:45:53', '2025-09-14 17:46:54', NULL, NULL, NULL),
(15, 'Sadat Numan', '1724972425', 'Bashundhara', '[{\"name\":\"Ground Mutton\",\"price\":850,\"image\":\"mutton_ground.png\",\"quantity\":2,\"total\":1700}]', 1700.00, 50.00, 1750.00, 'cod', 'cash_on_delivery', 'confirmed', '2025-09-14 18:45:02', '2025-09-14 19:59:06', 'sadat', '2025-09-14 19:59:06', '2025-09-14 19:59:06'),
(16, 'Sadat Numan', '1724972425', 'Bashundhara', '[{\"name\":\"Whole Chicken\",\"price\":270,\"image\":\"chiken_whole.png\",\"quantity\":1,\"total\":270},{\"name\":\"Mutton Leg\",\"price\":1050,\"image\":\"mutton_leg.png\",\"quantity\":1,\"total\":1050},{\"name\":\"Beef Brisket\",\"price\":750,\"image\":\"brisket.png\",\"quantity\":1,\"total\":750}]', 2070.00, 50.00, 2120.00, 'cod', 'cash_on_delivery', 'delivered', '2025-09-14 18:45:17', '2025-09-15 05:54:23', 'doad', '2025-09-14 19:54:05', '2025-09-15 05:54:23'),
(17, 'Sadat Numan', '1724972425', 'Bashundhara', '[{\"name\":\"Ground Mutton\",\"price\":850,\"image\":\"mutton_ground.png\",\"quantity\":3,\"total\":2550}]', 2550.00, 50.00, 2600.00, 'cod', 'cash_on_delivery', 'delivered', '2025-09-14 19:46:22', '2025-09-14 19:56:24', 'sadat', '2025-09-14 19:53:41', '2025-09-14 19:56:24'),
(18, 'Sadat Numan', '1724972425', 'Bashundhara', '[{\"name\":\"Mutton Chops\",\"price\":950,\"image\":\"mutton_chops.png\",\"quantity\":1,\"total\":950},{\"name\":\"Ground Mutton\",\"price\":850,\"image\":\"mutton_ground.png\",\"quantity\":1,\"total\":850},{\"name\":\"Mutton Ribs\",\"price\":900,\"image\":\"mutton_ribs.png\",\"quantity\":1,\"total\":900}]', 2700.00, 50.00, 2750.00, 'cod', 'cash_on_delivery', 'processing', '2025-09-14 20:02:54', '2025-09-14 20:04:56', 'sadat', '2025-09-14 20:03:30', '2025-09-14 20:04:56'),
(19, 'Sadat Numan', '1724972425', 'Bashundhara', '[{\"name\":\"Mutton Leg\",\"price\":1050,\"image\":\"mutton_leg.png\",\"quantity\":2,\"total\":2100}]', 2100.00, 50.00, 2150.00, 'cod', 'cash_on_delivery', 'confirmed', '2025-09-14 20:49:36', '2025-09-15 07:21:24', NULL, NULL, '2025-09-15 07:21:24'),
(20, 'Sadat Numan', '1724972425', 'Bashundhara', '[{\"name\":\"Beef Tenderloin\",\"price\":1200,\"image\":\"tenderloin.png\",\"quantity\":6,\"total\":7200}]', 7200.00, 50.00, 7250.00, 'cod', 'cash_on_delivery', 'delivered', '2025-09-14 20:50:27', '2025-09-14 20:53:06', 'sadat', '2025-09-14 20:52:07', '2025-09-14 20:53:06'),
(21, 'MD Doad', '1568171665', 'house-1, road-5, block-h, bansree,dhaka', '[{\"name\":\"Mixed Beef\",\"price\":680,\"image\":\"beef.png\",\"quantity\":1,\"total\":680},{\"name\":\"Mutton Leg\",\"price\":1050,\"image\":\"mutton_leg.png\",\"quantity\":1,\"total\":1050},{\"name\":\"Chicken Drumstick\",\"price\":290,\"image\":\"chicken_drumstick.png\",\"quantity\":1,\"total\":290}]', 2020.00, 50.00, 2070.00, 'online', 'card', 'confirmed', '2025-09-15 05:52:27', '2025-09-15 05:57:22', 'doad', '2025-09-15 05:57:22', '2025-09-15 05:57:22'),
(22, 'Sadat Numan', '1724972425', 'Bashundhara', '[{\"name\":\"Mutton Chops\",\"price\":950,\"image\":\"mutton_chops.png\",\"quantity\":1,\"total\":950},{\"name\":\"Chicken Drumstick\",\"price\":290,\"image\":\"chicken_drumstick.png\",\"quantity\":1,\"total\":290},{\"name\":\"Ground Mutton\",\"price\":850,\"image\":\"mutton_ground.png\",\"quantity\":4,\"total\":3400}]', 4640.00, 50.00, 4690.00, 'cod', 'cash_on_delivery', 'delivered', '2025-09-15 07:22:48', '2025-09-15 07:24:45', 'sadat', '2025-09-15 07:23:39', '2025-09-15 07:24:45');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `item_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `product_price`, `product_image`, `quantity`, `item_total`) VALUES
(1, 1, 'Beef Brisket', 750.00, 'brisket.png', 2, 1500.00),
(2, 1, 'Beef Short Ribs', 800.00, 'short ribs.png', 1, 800.00),
(3, 2, 'Beef Brisket', 750.00, 'brisket.png', 1, 750.00),
(4, 2, 'Beef Short Ribs', 800.00, 'short ribs.png', 1, 800.00),
(5, 3, 'Mutton Shoulder', 800.00, 'mutton_shoulder.png', 1, 800.00),
(6, 3, 'Mutton Ribs', 900.00, 'mutton_ribs.png', 1, 900.00),
(7, 3, 'Ground Chicken', 330.00, 'ground_chicken.png', 1, 330.00),
(8, 3, 'Beef Brisket', 750.00, 'brisket.png', 1, 750.00),
(9, 4, 'Chicken Breast', 350.00, 'chicken_breast.png', 1, 350.00),
(10, 4, 'Beef Tenderloin', 1200.00, 'tenderloin.png', 1, 1200.00),
(11, 4, 'Mutton Leg', 850.00, 'mutton_leg.png', 1, 850.00),
(12, 4, 'Ground Beef', 650.00, 'ground beef.png', 1, 650.00),
(13, 4, 'Mutton Chops', 950.00, 'mutton_chops.png', 1, 950.00),
(14, 5, 'Beef Chuck', 700.00, 'chuck.png', 1, 700.00),
(15, 5, 'Beef Brisket', 750.00, 'brisket.png', 1, 750.00),
(16, 5, 'Chicken Breast', 350.00, 'chicken_breast.png', 1, 350.00),
(17, 5, 'Mutton Ribs', 900.00, 'mutton_ribs.png', 2, 1800.00),
(18, 6, 'Mutton Shoulder', 800.00, 'mutton_shoulder.png', 1, 800.00),
(19, 6, 'Mutton Ribs', 900.00, 'mutton_ribs.png', 1, 900.00),
(20, 6, 'Chicken Thigh', 320.00, 'chicken_thigh.png', 1, 320.00),
(21, 6, 'Whole Chicken', 300.00, 'chiken_whole.png', 1, 300.00),
(22, 7, 'Whole Chicken', 260.00, 'chiken_whole.png', 1, 260.00),
(23, 7, 'Chicken Thigh', 280.00, 'chicken_thigh.png', 1, 280.00),
(24, 7, 'Mutton Shoulder', 900.00, 'mutton_shoulder.png', 1, 900.00),
(25, 8, 'Mutton Chops', 950.00, 'mutton_chops.png', 1, 950.00),
(26, 8, 'Ground Beef', 650.00, 'ground beef.png', 1, 650.00),
(27, 8, 'Chicken Wings', 280.00, 'chicken_wings.png', 1, 280.00),
(28, 9, 'Whole Chicken', 260.00, 'chiken_whole.png', 2, 520.00),
(29, 9, 'Mutton Leg', 1050.00, 'mutton_leg.png', 4, 4200.00),
(30, 10, 'Whole Chicken', 270.00, 'chiken_whole.png', 4, 1080.00),
(31, 11, 'Chicken Drumstick', 290.00, 'chicken_drumstick.png', 3, 870.00),
(32, 11, 'Beef Tenderloin', 1200.00, 'tenderloin.png', 1, 1200.00),
(33, 12, 'Mutton Chops', 950.00, 'mutton_chops.png', 2, 1900.00),
(34, 13, 'Ground Mutton', 850.00, 'mutton_ground.png', 1, 850.00),
(35, 14, 'Ground Beef', 650.00, 'ground beef.png', 1, 650.00),
(36, 14, 'Mutton Leg', 1050.00, 'mutton_leg.png', 1, 1050.00),
(37, 14, 'Beef Tenderloin', 1200.00, 'tenderloin.png', 1, 1200.00),
(38, 15, 'Ground Mutton', 850.00, 'mutton_ground.png', 2, 1700.00),
(39, 16, 'Whole Chicken', 270.00, 'chiken_whole.png', 1, 270.00),
(40, 16, 'Mutton Leg', 1050.00, 'mutton_leg.png', 1, 1050.00),
(41, 16, 'Beef Brisket', 750.00, 'brisket.png', 1, 750.00),
(42, 17, 'Ground Mutton', 850.00, 'mutton_ground.png', 3, 2550.00),
(43, 18, 'Mutton Chops', 950.00, 'mutton_chops.png', 1, 950.00),
(44, 18, 'Ground Mutton', 850.00, 'mutton_ground.png', 1, 850.00),
(45, 18, 'Mutton Ribs', 900.00, 'mutton_ribs.png', 1, 900.00),
(46, 19, 'Mutton Leg', 1050.00, 'mutton_leg.png', 2, 2100.00),
(47, 20, 'Beef Tenderloin', 1200.00, 'tenderloin.png', 6, 7200.00),
(48, 21, 'Mixed Beef', 680.00, 'beef.png', 1, 680.00),
(49, 21, 'Mutton Leg', 1050.00, 'mutton_leg.png', 1, 1050.00),
(50, 21, 'Chicken Drumstick', 290.00, 'chicken_drumstick.png', 1, 290.00),
(51, 22, 'Mutton Chops', 950.00, 'mutton_chops.png', 1, 950.00),
(52, 22, 'Chicken Drumstick', 290.00, 'chicken_drumstick.png', 1, 290.00),
(53, 22, 'Ground Mutton', 850.00, 'mutton_ground.png', 4, 3400.00);

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `full_name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `user_name` varchar(50) NOT NULL,
  `phone_number` int(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT 'No address provided'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`full_name`, `email`, `user_name`, `phone_number`, `dob`, `password`, `address`) VALUES
('MD Doad', 'doadmohammad711@gmail.com', 'doad', 1568171665, '2002-09-29', 'doad1@MB', 'house-1, road-5, block-h, bansree,dhaka'),
('hasib', 'hasib123@gmail.com', 'hasib21', 1746009390, '1000-02-02', 'Hasib@21', 'rampura dhaka '),
('Sadat Numan', 'n@gmail.com', 'numan', 1724972425, '2003-08-30', 'Num@n1234', 'Bashundhara'),
('MD. Nazmus Sadat Numan', 'nazmussadatnuman93@gmail.com', 'numan123', 1724972425, '2002-02-01', 'Num@n1234', 'bashundara'),
('MD. Nazmus Sadat Numan', 'n1@gmail.com', 'numan1234', 1724972425, '2001-01-01', 'Num@n1234', 'Bashundhara '),
('Shakib Al Hasan', 'shakib@gmail.com', 'shakib', 1724972425, '1997-08-15', 'Num@n1234', 'No address provided');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`user_name`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_name` (`user_name`,`email`);

--
-- Indexes for table `distributor`
--
ALTER TABLE `distributor`
  ADD PRIMARY KEY (`user_name`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_name` (`user_name`,`email`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_assigned_distributor` (`assigned_distributor`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_assigned_distributor` FOREIGN KEY (`assigned_distributor`) REFERENCES `distributor` (`user_name`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
