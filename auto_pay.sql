-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2026 at 03:24 AM
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
-- Database: `auto_pay`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `attempts` int(3) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `fullname`, `password`, `attempts`, `status`) VALUES
(32, 'ivan jay r. elago', '$2y$10$UgC3Lst01BEvAKNMqg19auOyzNx4vVFvVcHXGuRYJ7uWltBiWbLnG', 0, 'ACTIVATE'),
(33, 'Prince Doble', '$2y$10$6d7hrVBIr0JEe3KZLEqPYuH8Sr.3VQvDRuWWoc4ZMKqE.XpWuP7TO', 0, 'ACTIVATE'),
(34, 'Eduard Mendoza', '$2y$10$DV4sYFWy0TYhqEplP9pfj.lErBUbmDnBqjyaD9V6P0et4ugzGr9lq', 0, 'ACTIVATE');

-- --------------------------------------------------------

--
-- Table structure for table `admin_acc`
--

CREATE TABLE `admin_acc` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(15) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_acc`
--

INSERT INTO `admin_acc` (`id`, `admin_name`, `password`, `status`) VALUES
(7, 'admin', '$2y$10$Gr6GsHiHi.myWy8pywQbk.53uS0iVTHv7vJ8a7LQ4iTTSMT01T/Ym', 'ACTIVATE'),
(9, 'ivan', '$2y$10$1npNVCpB3FaDvUXZ9C..reqoG9RJZOOF4Rh1e3PCKFEAz205wlthW', 'ACTIVATE'),
(10, 'aj mendaros', '$2y$10$gJ0pAUZjcJfpPGReX7io8uIqjabF41CruKky/9Swi7hnaw/vPJlWy', 'ACTIVATE');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(100) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emp_grosspay`
--

CREATE TABLE `emp_grosspay` (
  `id` int(100) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `payment` int(20) NOT NULL,
  `sss` int(20) NOT NULL,
  `philhealth` int(20) NOT NULL,
  `pagibig` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_grosspay`
--

INSERT INTO `emp_grosspay` (`id`, `fullname`, `payment`, `sss`, `philhealth`, `pagibig`) VALUES
(66, 'ivan jay r. elago', 300, 600, 700, 800),
(67, 'Prince Doble', 300, 600, 700, 800),
(68, 'Eduard Mendoza', 200, 600, 700, 800);

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(100) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `payment` int(50) NOT NULL,
  `sss` int(50) NOT NULL,
  `philhealth` int(50) NOT NULL,
  `pagibig` int(50) NOT NULL,
  `days` int(10) NOT NULL,
  `total_days_pay` int(10) NOT NULL,
  `total_payment` int(10) NOT NULL,
  `month` varchar(10) NOT NULL,
  `year` int(10) NOT NULL,
  `receipt_status` varchar(11) NOT NULL,
  `generated_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `fullname`, `payment`, `sss`, `philhealth`, `pagibig`, `days`, `total_days_pay`, `total_payment`, `month`, `year`, `receipt_status`, `generated_at`) VALUES
(147, 'Prince Doble', 300, 600, 700, 800, 1, 300, -1800, '4', 2026, 'Received', '2026-04-17 00:55:30.448942');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--
-- Error reading structure for table auto_pay.payroll: #1932 - Table &#039;auto_pay.payroll&#039; doesn&#039;t exist in engine
-- Error reading data for table auto_pay.payroll: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `auto_pay`.`payroll`&#039; at line 1

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_acc`
--
ALTER TABLE `admin_acc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_grosspay`
--
ALTER TABLE `emp_grosspay`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `admin_acc`
--
ALTER TABLE `admin_acc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=269;

--
-- AUTO_INCREMENT for table `emp_grosspay`
--
ALTER TABLE `emp_grosspay`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
