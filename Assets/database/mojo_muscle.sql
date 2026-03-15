-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2026 at 04:23 PM
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
-- Database: `mojo_muscle`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `booked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gym_classes`
--

CREATE TABLE `gym_classes` (
  `id` int(11) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `instructor` varchar(100) NOT NULL,
  `schedule_day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `max_slots` int(11) DEFAULT 20,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gym_classes`
--

INSERT INTO `gym_classes` (`id`, `class_name`, `instructor`, `schedule_day`, `start_time`, `end_time`, `max_slots`, `created_at`) VALUES
(1, 'Power Lifting', 'Coach Mojo', 'Monday', '08:00:00', '09:00:00', 15, '2026-03-03 01:39:01'),
(2, 'Cardio Blast', 'Coach Jojo', 'Wednesday', '10:00:00', '11:00:00', 20, '2026-03-03 01:39:01'),
(3, 'Yoga & Stretch', 'Coach Buttercup', 'Friday', '07:00:00', '08:00:00', 12, '2026-03-03 01:39:01'),
(4, 'HIIT Training', 'Coach Mojo', 'Tuesday', '06:00:00', '07:00:00', 18, '2026-03-03 01:39:01'),
(5, 'Core Crusher', 'Coach Jojo', 'Thursday', '09:00:00', '10:00:00', 15, '2026-03-03 01:39:01'),
(6, 'Weekend Warrior', 'Coach Mojo', 'Saturday', '09:00:00', '10:30:00', 25, '2026-03-03 01:39:01');

-- --------------------------------------------------------

--
-- Table structure for table `home_content`
--

CREATE TABLE `home_content` (
  `id` int(11) NOT NULL,
  `content_key` varchar(50) NOT NULL,
  `content_value` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_content`
--

INSERT INTO `home_content` (`id`, `content_key`, `content_value`, `updated_at`) VALUES
(1, 'active_members', '120', '2026-03-03 01:39:01'),
(2, 'upcoming_events', 'None scheduled', '2026-03-03 01:39:01'),
(3, 'announcement', '', '2026-03-03 01:39:01'),
(4, 'hours_monday', '6 AM - 10 PM', '2026-03-03 01:39:01'),
(5, 'hours_tuesday', '6 AM - 10 PM', '2026-03-03 01:39:01'),
(6, 'hours_wednesday', '6 AM - 10 PM', '2026-03-03 01:39:01'),
(7, 'hours_thursday', '6 AM - 10 PM', '2026-03-03 01:39:01'),
(8, 'hours_friday', '6 AM - 10 PM', '2026-03-03 01:39:01'),
(9, 'hours_saturday', '8 AM - 8 PM', '2026-03-03 01:39:01'),
(10, 'hours_sunday', '10 AM - 6 PM', '2026-03-03 01:39:01');

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan` enum('Monthly','Quarterly','Annual') NOT NULL,
  `status` enum('active','expired','cancelled') DEFAULT 'active',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`id`, `user_id`, `plan`, `status`, `start_date`, `end_date`, `created_at`) VALUES
(2, 3, '', 'active', '2026-03-15', '2126-02-19', '2026-03-15 10:39:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('member','admin') DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'MojoAdmin', 'admin@mojo.com', '$2y$10$npOgU2a1AhJsj2C11JalBuSMh1gWq1brnnZDi6uRdp6OKy43ujbuu', 'admin', '2026-03-03 01:39:01'),
(3, 'kiemverly', 'kiemverly@gmail.com', '$2y$10$xmoey77WN5ZnBKuHN7W2leQGQxmwfsL8rYaSn3g.86DnRk66CthLm', 'member', '2026-03-15 10:39:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_double_book` (`user_id`,`class_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `gym_classes`
--
ALTER TABLE `gym_classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_content`
--
ALTER TABLE `home_content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `content_key` (`content_key`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gym_classes`
--
ALTER TABLE `gym_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `home_content`
--
ALTER TABLE `home_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `gym_classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `memberships`
--
ALTER TABLE `memberships`
  ADD CONSTRAINT `memberships_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
