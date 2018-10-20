-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Oct 20, 2018 at 06:47 PM
-- Server version: 5.6.34-log
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(60) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `email`, `first_name`, `last_name`, `password`) VALUES
(1, 'user1@test.com', 'firsttest', 'lasttest', '$2y$10$Dwb9k0S4phspMV9CJ3SnnunewvwbAMKiSCULdkC.VD/tu6uQNl5di'),
(2, 'chieko@test.com', 'Chieko', 'Yamamoto', '$2y$10$WYUSd.s8CtMHHPf1gSR53ONwz3caSDnB.DAeoQSglXB2UeYKL5kki');

-- --------------------------------------------------------

--
-- Table structure for table `mst_category`
--

CREATE TABLE `mst_category` (
  `code` int(11) NOT NULL,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mst_category`
--

INSERT INTO `mst_category` (`code`, `name`) VALUES
(1, 'Produce'),
(2, 'Meat and Seafood'),
(3, 'Diary'),
(4, 'Bakely');

-- --------------------------------------------------------

--
-- Table structure for table `mst_order`
--

CREATE TABLE `mst_order` (
  `id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `customer_email` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `stripe_id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mst_orderline`
--

CREATE TABLE `mst_orderline` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mst_order_shipping`
--

CREATE TABLE `mst_order_shipping` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(5) NOT NULL,
  `zip` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mst_product`
--

CREATE TABLE `mst_product` (
  `code` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` int(10) NOT NULL,
  `unit_code` int(11) NOT NULL,
  `image` varchar(150) NOT NULL,
  `category_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mst_product`
--

INSERT INTO `mst_product` (`code`, `name`, `price`, `unit_code`, `image`, `category_code`) VALUES
(1, 'Organic Carrot', 1200, 1, '1536867108_006a3b16b8c0e5a4cf96554879d479bf.jpg', 1),
(4, 'Natural Hot dog', 728, 1, '1536867124_7cbbb394ad23c141f1c96178d13651c9.jpg', 2),
(6, 'Organic Chicken breast', 4149, 4, '1536866673_72cdfcdab96002903b52c7eef76dd67d.jpg', 2),
(7, 'Organic Blueberry', 449, 1, '1536867080_9828147e9392a1a5a9089dd14b7c557f.jpg', 1),
(9, 'Organic Onion', 215, 1, '1536867034_e51e49b7c214d57e898de814cd0a3cf3.jpg', 1),
(12, 'Cage Free Eggs', 299, 4, '1539380861_e4867e85e70e5040e7b372876312713a.jpg', 3),
(13, 'Organic Milk', 345, 4, '1539380894_8de603e557c2efb58f564902172c8127.jpg', 3),
(14, 'Natural Butter', 350, 4, '1539380926_c5f08b5f27ecf88edf8b6ac116c175d1.jpg', 3),
(15, 'KC Natural Cheese', 915, 4, '1539380967_3a15afb716f95c7c4bb091bfa4df128b.jpg', 3),
(16, 'Natural Cream Cheese', 215, 4, '1539380997_c1d4981c9e44acc9771baead1d1f6356.jpg', 3),
(17, 'Original Berry Yogrut', 469, 3, '1539381043_231fc6892d281094114e4b2df9ca1aec.jpg', 3),
(18, 'Blueberry Muffins', 115, 3, '1539381083_01285d3e6c794af0572c7d80d5c377a7.jpg', 4),
(19, 'French Bread', 120, 3, '1539381129_0928f0b9a695a78e820cc7965d13cee6.jpg', 4),
(20, 'Croissant', 399, 4, '1539381189_03df0ded4ec6bc6676df0702b1e40f87.jpg', 4),
(21, 'Chocolate Cupcakes', 399, 4, '1539381229_57f856234092cffbfa8588cfc6607029.jpg', 4),
(22, 'Macarons', 100, 3, '1539381266_1ff1b423c92f4f1e03218ca1338c558d.jpg', 4),
(23, 'Dinner rolls', 340, 4, '1539381294_05c664347bc7969ee7f6de114665b7c0.jpg', 4),
(24, 'Original Rolls', 150, 3, '1539381328_91e6b2acd5b8ea721460b6237a1d386b.jpg', 4),
(25, 'Natural Pumpkin', 229, 1, '1539381877_3d8a037c444e2494129a70ba86196767.jpg', 1),
(26, 'KC Aungus Beef', 990, 1, '1539381916_9771421cb7e56626ca4760928fd51a9f.jpg', 2),
(27, 'Organic Beef', 4500, 4, '1539381963_ba482f90029539e1a86406711312dc17.jpg', 2),
(28, 'Natural Chicken Wings', 199, 1, '1539381996_c5fc9a581a213b9d865ec500f7867aaa.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `mst_staff`
--

CREATE TABLE `mst_staff` (
  `id` varchar(30) NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mst_staff`
--

INSERT INTO `mst_staff` (`id`, `name`, `password`, `role`) VALUES
('manager1', 'manager1', '$2y$10$kuggNprwBa/ADQdUChhyUe0SZpIwjEa4f9HyYKSjYTWQw5ycYgRAm', 'MANAGER'),
('staff1', 'staff1', '$2y$10$HOFpkC20r9Jb59d7k2udxebdqZe0hJx7DGROBngzCMp6UhV9w9WDu', 'STAFF');

-- --------------------------------------------------------

--
-- Table structure for table `mst_unit`
--

CREATE TABLE `mst_unit` (
  `code` int(11) NOT NULL,
  `name` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mst_unit`
--

INSERT INTO `mst_unit` (`code`, `name`) VALUES
(1, 'lb.'),
(2, 'oz.'),
(3, 'ea.'),
(4, 'package');

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `id` int(11) NOT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`id`, `points`) VALUES
(1, 15),
(2, 53);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_category`
--
ALTER TABLE `mst_category`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `mst_order`
--
ALTER TABLE `mst_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_orderline`
--
ALTER TABLE `mst_orderline`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `mst_order_shipping`
--
ALTER TABLE `mst_order_shipping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_product`
--
ALTER TABLE `mst_product`
  ADD PRIMARY KEY (`code`),
  ADD KEY `category` (`category_code`),
  ADD KEY `unit` (`unit_code`);

--
-- Indexes for table `mst_staff`
--
ALTER TABLE `mst_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_unit`
--
ALTER TABLE `mst_unit`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `mst_category`
--
ALTER TABLE `mst_category`
  MODIFY `code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `mst_order`
--
ALTER TABLE `mst_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `mst_orderline`
--
ALTER TABLE `mst_orderline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `mst_order_shipping`
--
ALTER TABLE `mst_order_shipping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `mst_product`
--
ALTER TABLE `mst_product`
  MODIFY `code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `mst_unit`
--
ALTER TABLE `mst_unit`
  MODIFY `code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `mst_orderline`
--
ALTER TABLE `mst_orderline`
  ADD CONSTRAINT `mst_orderline_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `mst_order` (`id`),
  ADD CONSTRAINT `mst_orderline_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `mst_product` (`code`);

--
-- Constraints for table `mst_order_shipping`
--
ALTER TABLE `mst_order_shipping`
  ADD CONSTRAINT `mst_order_shipping_ibfk_1` FOREIGN KEY (`id`) REFERENCES `mst_order` (`id`);

--
-- Constraints for table `mst_product`
--
ALTER TABLE `mst_product`
  ADD CONSTRAINT `mst_product_ibfk_1` FOREIGN KEY (`category_code`) REFERENCES `mst_category` (`code`),
  ADD CONSTRAINT `mst_product_ibfk_2` FOREIGN KEY (`unit_code`) REFERENCES `mst_unit` (`code`);

--
-- Constraints for table `rewards`
--
ALTER TABLE `rewards`
  ADD CONSTRAINT `rewards_ibfk_1` FOREIGN KEY (`id`) REFERENCES `customer` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
