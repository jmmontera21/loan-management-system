-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2024 at 11:37 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int(2) NOT NULL,
  `email` varchar(155) NOT NULL,
  `password` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `firstname` varchar(55) NOT NULL,
  `middlename` varchar(55) NOT NULL,
  `lastname` varchar(55) NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `TIN_no` varchar(15) NOT NULL,
  `city` varchar(155) NOT NULL,
  `barangay` varchar(155) NOT NULL,
  `specific_address` text DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(155) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `firstname`, `middlename`, `lastname`, `contact_no`, `TIN_no`, `city`, `barangay`, `specific_address`, `email`, `password`, `date_created`) VALUES
(3, 'John Marlon', 'Villaruel', 'Montera', '09123851321', '439284241423', 'Lucena City', 'Ilayang Dupay', '43', 'marlonjm@gmail.com', '$2y$10$06/i9ZBf32FjAOYs.9cbm.L5ooEsACLbnw06CSZoDMLjBp5e', '2024-07-10 21:12:40'),
(5, 'Deriel', 'Edzel', 'Quisao', '09345678486', '4918493549342', 'Infanta', 'Binulasan', '81', 'quisaoderiel@gmail.com', '$2y$10$PgpN0qpk71J5DH0J5wD6W.EvM17UIDc9WspAgblS6p6N9AkS', '2024-07-10 22:39:58'),
(7, 'Christian', 'J', 'Ocampo', '09452624321', '763532452525', 'Lucena City', 'Barangay 2', '23', 'jake@gmail.com', '$2y$10$KFIino4JqvOCVAI3JI8SzOfY0gKxsR/Wh3UlxcF6RGaFy0Jj', '2024-07-11 15:37:36');

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `loan_id` int(11) NOT NULL,
  `ref_no` varchar(50) NOT NULL,
  `loan_type` varchar(100) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `purpose` text NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `lplan_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `total_plan` decimal(12,2) NOT NULL DEFAULT 0.00,
  `monthly_payment` decimal(12,2) NOT NULL DEFAULT 0.00,
  `overdue_penalty` decimal(12,2) NOT NULL DEFAULT 0.00,
  `date_released` datetime NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan`
--

INSERT INTO `loan` (`loan_id`, `ref_no`, `loan_type`, `customer_id`, `purpose`, `amount`, `lplan_id`, `status`, `total_plan`, `monthly_payment`, `overdue_penalty`, `date_released`, `date_created`) VALUES
(10, '74087086', 'SSS', 3, 'dw', '10000.00', 3, 'Rejected', '10500.00', '583.33', '105.00', '0000-00-00 00:00:00', '2024-07-10 21:39:13'),
(12, '33846853', 'PVAO', 4, 'dada', '10000.00', 5, 'Released', '10800.00', '540.00', '216.00', '0000-00-00 00:00:00', '2024-07-10 22:08:51'),
(15, '02725591', 'PVAO', 5, 'Yehey', '30000.00', 5, 'For Approval', '32400.00', '1620.00', '648.00', '0000-00-00 00:00:00', '2024-07-11 07:52:22'),
(17, '65844974', 'SSS', 7, 'For housing ', '20000.00', 2, 'For Approval', '22000.00', '1833.33', '220.00', '0000-00-00 00:00:00', '2024-07-11 15:39:17');

-- --------------------------------------------------------

--
-- Table structure for table `loan_plan`
--

CREATE TABLE `loan_plan` (
  `lplan_id` int(11) NOT NULL,
  `lplan_month` int(11) NOT NULL,
  `lplan_interest` float NOT NULL,
  `lplan_penalty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_plan`
--

INSERT INTO `loan_plan` (`lplan_id`, `lplan_month`, `lplan_interest`, `lplan_penalty`) VALUES
(2, 12, 10, 1),
(3, 18, 5, 1),
(5, 20, 8, 2),
(6, 24, 10, 1),
(11, 24, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `payee` varchar(100) DEFAULT NULL,
  `pay_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `monthly_payment` decimal(12,2) NOT NULL DEFAULT 0.00,
  `amount_paid` decimal(12,2) NOT NULL DEFAULT 0.00,
  `overdue_penalty` decimal(12,2) NOT NULL DEFAULT 0.00,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(155) NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `user_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `email`, `password`, `contact_no`, `user_type`) VALUES
(13, 'John Marlon', 'marlonj@gmail.com', '$2y$10$nJ/sX7wGvsgVjWSHDh2xgun4TuRDWQEPbxf6SNMGQ4QyjNmxfWwoy', '09317483243', 'employee'),
(14, 'admin', 'admin@gmail.com', '$2y$10$ABI.3hyoz7UctPBHLJ7LuOowc5TboJcXWjbf0qbpKn9yBNZSwhwj6', '0937436163', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `loan_plan`
--
ALTER TABLE `loan_plan`
  ADD PRIMARY KEY (`lplan_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `loan_plan`
--
ALTER TABLE `loan_plan`
  MODIFY `lplan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
