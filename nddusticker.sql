-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2024 at 09:15 AM
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
-- Database: `nddusticker`
--

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image_name` text NOT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `controlnum` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `user_id`, `image_name`, `created_at`, `controlnum`) VALUES
(345, 7878, '203514.jpg', '2024-05-12 14:28:20.000000', 155);

-- --------------------------------------------------------

--
-- Table structure for table `parking_stickers`
--

CREATE TABLE `parking_stickers` (
  `sticker_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_id` varchar(50) NOT NULL,
  `expiry_date` date NOT NULL,
  `issued_date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `IDnum` int(11) NOT NULL,
  `password` text NOT NULL,
  `Phone number` text NOT NULL,
  `User_Type` text NOT NULL DEFAULT 'student',
  `created_Date` datetime NOT NULL DEFAULT current_timestamp(),
  `vehicle_count` int(11) NOT NULL DEFAULT 0,
  `type` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`firstname`, `lastname`, `email`, `IDnum`, `password`, `Phone number`, `User_Type`, `created_Date`, `vehicle_count`, `type`) VALUES
('Aleah', 'Guarin', 'aleahguarin@nddu.edu.ph', 10, '0001', '0921388472', 'student', '2024-03-08 13:23:17', 0, 0),
('Joshua', 'Garingo', 'garingojoshua@nddu.edu.ph', 7777, '7777', '0987654323', 'student', '2024-05-05 20:52:35', 0, 0),
('Alexiss', 'Hayag', 'brofist0702@gmail.com', 7878, '7878', '0909090909', 'student', '2024-02-15 20:21:06', 1, 0),
('Jan', 'Geraldez', 'geraldezjananthony@nddu.edu.ph', 7979, '7979', '09206161733', 'student', '2024-02-28 20:34:19', 0, 0),
('joyce', 'Pedrosa', 'joycehayag@gmail.com', 10001, 'admin01', '09090909', 'ADMIN', '2024-02-27 14:22:02', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `date` date NOT NULL,
  `brand` varchar(255) NOT NULL,
  `yearmodel` year(4) NOT NULL,
  `vehicletype` text NOT NULL,
  `platenumber` varchar(50) NOT NULL,
  `amountpaid` double NOT NULL,
  `ORnum` varchar(50) NOT NULL,
  `typeofapplicant` varchar(50) NOT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `Expiration_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(255) NOT NULL,
  `controlnumber` int(11) NOT NULL,
  `color` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`date`, `brand`, `yearmodel`, `vehicletype`, `platenumber`, `amountpaid`, `ORnum`, `typeofapplicant`, `status`, `Expiration_date`, `user_id`, `controlnumber`, `color`) VALUES
('2024-05-12', 'Ford', '2024', 'Two-Wheel', 'KKK 000', 500, '5646', 'renew', 'accepted', '2021-11-11 06:33:43', 7878, 155, 'White');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userconnection` (`user_id`),
  ADD KEY `controlnum` (`controlnum`);

--
-- Indexes for table `parking_stickers`
--
ALTER TABLE `parking_stickers`
  ADD PRIMARY KEY (`sticker_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`IDnum`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `Phone number` (`Phone number`) USING HASH;

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`controlnumber`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=346;

--
-- AUTO_INCREMENT for table `parking_stickers`
--
ALTER TABLE `parking_stickers`
  MODIFY `sticker_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `controlnumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
