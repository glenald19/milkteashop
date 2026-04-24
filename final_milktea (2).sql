-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2025 at 02:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `final_milktea`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_size_id` int(11) NOT NULL,
  `sugar_level_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `order_id` int(11) DEFAULT NULL,
  `added_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_item_id`, `user_id`, `product_size_id`, `sugar_level_id`, `quantity`, `order_id`, `added_at`) VALUES
(25, 1, 26, 5, 1, 10, '2025-04-18 15:24:25'),
(28, 1, 27, 5, 1, 11, '2025-04-21 21:00:57'),
(32, 1, 34, 5, 1, 12, '2025-05-05 13:37:47'),
(42, 1, 37, 5, 1, 14, '2025-05-07 23:48:59'),
(43, 1, 38, 5, 1, 16, '2025-05-07 23:52:26'),
(44, 1, 26, 5, 1, 17, '2025-05-08 15:14:42'),
(46, 1, 46, 5, 1, 19, '2025-05-13 12:18:10');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shipping_address_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `shipping_address_id`, `order_date`, `status`, `total_amount`) VALUES
(10, 1, 12, '2025-04-18 15:24:41', 'processing', 70.00),
(11, 1, 13, '2025-04-21 21:27:13', 'cancelled', 85.00),
(12, 1, 14, '2025-05-05 13:38:33', 'pending', 75.00),
(14, 1, 16, '2025-05-07 23:51:04', 'pending', 65.00),
(15, 1, 17, '2025-05-07 23:51:17', 'pending', 0.00),
(16, 1, 18, '2025-05-07 23:53:33', 'completed', 89.00),
(17, 1, 19, '2025-05-08 15:15:15', 'completed', 70.00),
(19, 1, 21, '2025-05-13 12:18:22', 'cancelled', 145.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `product_size_id` int(11) NOT NULL,
  `sugar_level_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `product_size_id`, `sugar_level_id`, `order_id`, `quantity`, `subtotal`) VALUES
(11, 26, 5, 10, 1, 70.00),
(12, 27, 5, 11, 1, 85.00),
(13, 34, 5, 12, 1, 75.00),
(15, 37, 5, 14, 1, 65.00),
(16, 38, 5, 16, 1, 89.00),
(17, 26, 5, 17, 1, 70.00),
(19, 46, 5, 19, 1, 145.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` varchar(50) DEFAULT 'Pending',
  `payment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `payment_method`, `payment_status`, `payment_date`) VALUES
(10, 10, 'COD', 'Pending', '2025-04-18 15:24:41'),
(11, 11, 'GCash', 'Pending', '2025-04-21 21:27:13'),
(12, 12, 'COD', 'Pending', '2025-05-05 13:38:33'),
(14, 14, 'GCash', 'Pending', '2025-05-07 23:51:04'),
(15, 15, 'GCash', 'Pending', '2025-05-07 23:51:17'),
(16, 16, 'GCash', 'Pending', '2025-05-07 23:53:33'),
(17, 17, 'COD', 'Pending', '2025-05-08 15:15:15'),
(19, 19, 'COD', 'Pending', '2025-05-13 12:18:22');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `image_url`, `created_at`) VALUES
(7, 'chocho', 'lami kaau ', '1744956203_Screenshot 2025-02-26 204035.png', '2025-04-18 14:03:23'),
(9, 'milk tea', 'lami', '1744962531_Screenshot 2025-02-28 143623.png', '2025-04-18 15:48:51'),
(10, 'hohay', 'ako nalang diay ?', '1745163427_Screenshot 2025-02-28 143442.png', '2025-04-20 23:37:07'),
(11, 'okay', 'lamssssss', '1747109541_Screenshot 2025-02-28 143145.png', '2025-05-13 12:12:21'),
(12, 'yes or no', 'kaya', '1747109779_Screenshot 2025-02-28 141010.png', '2025-05-13 12:16:19');

-- --------------------------------------------------------

--
-- Table structure for table `product_sizes`
--

CREATE TABLE `product_sizes` (
  `product_size_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_sizes`
--

INSERT INTO `product_sizes` (`product_size_id`, `product_id`, `size_id`, `price`) VALUES
(25, 7, 1, 45.00),
(26, 7, 2, 70.00),
(27, 7, 3, 85.00),
(28, 7, 4, 120.00),
(33, 9, 1, 50.00),
(34, 9, 2, 75.00),
(35, 9, 3, 95.00),
(36, 9, 4, 148.00),
(37, 10, 1, 65.00),
(38, 10, 2, 89.00),
(39, 10, 3, 110.00),
(40, 10, 4, 145.00),
(41, 11, 1, 100.00),
(42, 11, 2, 120.00),
(43, 11, 3, 150.00),
(44, 11, 4, 199.00),
(45, 12, 1, 130.00),
(46, 12, 2, 145.00),
(47, 12, 3, 160.00),
(48, 12, 4, 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_addresses`
--

CREATE TABLE `shipping_addresses` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `municipality` varchar(100) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `address_line` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_addresses`
--

INSERT INTO `shipping_addresses` (`address_id`, `user_id`, `municipality`, `barangay`, `address_line`) VALUES
(1, 1, 'sicaban', 'libertad bajo', 'purok casuy 2'),
(2, 1, 'sicaban', 'libertad alto', 'purok casuy 3'),
(3, 1, 'sicaban', 'libertad alto', 'purok casuy 3'),
(4, 1, 'sicaban', 'libertad alto', 'purok casuy 3'),
(5, 1, 'sicaban', 'libertad alto', 'purok casuy 3'),
(6, 1, 'sicaban', 'libertad alto', 'purok casuy 3'),
(7, 1, 'sicaban', 'libertad alto', 'purok casuy 3'),
(8, 1, 'sicaban', 'libertad alto', 'purok casuy 3'),
(9, 1, 'sicaban', 'libertad alto', 'purok casuy 3'),
(10, 1, 'sicaban', 'libertad alto', 'purok casuy 3'),
(11, 1, 'sicaban', 'libertad alto', 'purok casuy 3'),
(12, 1, 'sicaban', 'libertad alto', 'purok casuy 3'),
(13, 1, 'sicaban', 'libertad alto', 'purok casuy 5'),
(14, 1, 'sicaban', 'libertad alto', 'purok casuy 6'),
(16, 1, 'sicaban', 'libertad alto', 'purok casuy 1113'),
(17, 1, 'sicaban', 'libertad alto', 'purok casuy 1113'),
(18, 1, 'sicaban', 'libertad alto', 'purok casuy 11134'),
(19, 1, 'sicaban', 'libertad alto', 'purok casuy 111'),
(20, 6, 'sicaban', 'libertad alto', 'purok casuy 1113'),
(21, 1, 'sicaban', 'libertad alto', 'purok casuy 11134');

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `size_id` int(11) NOT NULL,
  `size_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`size_id`, `size_name`) VALUES
(1, 'Small'),
(2, 'Medium'),
(3, 'Large'),
(4, 'Extra Large');

-- --------------------------------------------------------

--
-- Table structure for table `sugar_levels`
--

CREATE TABLE `sugar_levels` (
  `sugar_level_id` int(11) NOT NULL,
  `sugar_level_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sugar_levels`
--

INSERT INTO `sugar_levels` (`sugar_level_id`, `sugar_level_name`) VALUES
(1, '0%'),
(2, '25%'),
(3, '50%'),
(4, '75%'),
(5, '100%');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `role` enum('admin','customer') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'Glenald', 'karlo@gamil.com', '$2y$10$X5.N1TnEROD8NX2IuPuCLOlQNMmro8xMoeJYd7yjn9wHbf8yYsyCW', '2025-04-15 08:46:26', 'customer'),
(6, 'Glenald Cagula ', 'cagulapurol@gmail.com', '$2y$10$QC0/Rn1XrQAal1RfBWnb8e1md4qgHv4Ra5KMVCXmpqYwhLXp.3sbu', '2025-04-18 14:27:44', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_size_id` (`product_size_id`),
  ADD KEY `sugar_level_id` (`sugar_level_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `shipping_address_id` (`shipping_address_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_size_id` (`product_size_id`),
  ADD KEY `sugar_level_id` (`sugar_level_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`product_size_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Indexes for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`size_id`);

--
-- Indexes for table `sugar_levels`
--
ALTER TABLE `sugar_levels`
  ADD PRIMARY KEY (`sugar_level_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `product_size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sugar_levels`
--
ALTER TABLE `sugar_levels`
  MODIFY `sugar_level_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_size_id`) REFERENCES `product_sizes` (`product_size_id`),
  ADD CONSTRAINT `cart_items_ibfk_3` FOREIGN KEY (`sugar_level_id`) REFERENCES `sugar_levels` (`sugar_level_id`),
  ADD CONSTRAINT `cart_items_ibfk_4` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`shipping_address_id`) REFERENCES `shipping_addresses` (`address_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_size_id`) REFERENCES `product_sizes` (`product_size_id`),
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`sugar_level_id`) REFERENCES `sugar_levels` (`sugar_level_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD CONSTRAINT `product_sizes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_sizes_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`size_id`) ON DELETE CASCADE;

--
-- Constraints for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD CONSTRAINT `shipping_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
