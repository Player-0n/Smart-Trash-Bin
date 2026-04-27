-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 27, 2026 at 09:31 PM
-- Server version: 10.5.26-MariaDB-cll-lve
-- PHP Version: 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `marbcsah_stb_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_accounts_tbl`
--

CREATE TABLE `admin_accounts_tbl` (
  `admin_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email_address` varchar(150) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `role` enum('Administrator') DEFAULT 'Administrator',
  `reset_password_token` varchar(255) DEFAULT NULL,
  `password_token_expiry` datetime DEFAULT NULL,
  `locked_account` enum('Yes','No') DEFAULT 'No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_accounts_tbl`
--

INSERT INTO `admin_accounts_tbl` (`admin_id`, `first_name`, `middle_name`, `last_name`, `email_address`, `admin_password`, `role`, `reset_password_token`, `password_token_expiry`, `locked_account`, `created_at`, `updated_at`) VALUES
(1003, 'Joshua', 'Forro', 'Borra', 'bondlycommunity@gmail.com', '$2y$10$qF7f3UIxex6d/OBBB97tT.ILNS4.jLEc/s6BY/dYw644dZjmWofW.', 'Administrator', 'NAkpmblNUdjJqAGr', '2025-07-29 15:00:09', 'No', '2025-06-25 04:58:40', '2026-04-26 12:17:15'),
(1008, 'Arland Vincent', 'Dela Cruz', 'Hervias', 'arahland@gmail.com', '$2y$10$A/Q12/XMgYcwkWGg13X9JOGEk1RvD4tgxHKvV6MHMbYzFW4QKx5Xe', 'Administrator', NULL, NULL, 'No', '2025-07-02 11:00:43', '2025-07-02 11:00:43'),
(1009, 'Christian Paul', 'Hiponia', 'Bascoguin', 'jbascoguin@gmail.com', '$2y$10$bprXBfO/1xEcerXKvfHXUe88iiKAT59DHvf7.cCEeXh29L/fucIKu', 'Administrator', NULL, NULL, 'No', '2026-04-26 11:48:14', '2026-04-26 11:48:27');

-- --------------------------------------------------------

--
-- Table structure for table `admin_profile_tbl`
--

CREATE TABLE `admin_profile_tbl` (
  `tbl_row_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female','Others') DEFAULT 'Others',
  `civil_status` enum('Single','Married','Divorced','Widowed') DEFAULT 'Single',
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `facebook_link` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_profile_tbl`
--

INSERT INTO `admin_profile_tbl` (`tbl_row_id`, `admin_id`, `profile_picture`, `date_of_birth`, `gender`, `civil_status`, `address`, `phone_number`, `facebook_link`, `updated_at`) VALUES
(2, 1003, 'admin_1003_69ee023b199fb.jpg', '2004-01-25', 'Male', 'Single', 'Janiuay, Iloilo', '09927415812', 'https://www.facebook.com/UnknownUserkie', '2026-04-26 12:17:15'),
(5, 1008, NULL, NULL, 'Male', 'Single', 'Calaparan, Villa Arevalo, Iloilo City', '09165670916', '', '2025-07-02 11:00:43'),
(6, 1009, NULL, NULL, 'Male', 'Single', 'Sara, Iloilo', '09123456789', '', '2026-04-26 11:48:14');

-- --------------------------------------------------------

--
-- Table structure for table `bins_tbl`
--

CREATE TABLE `bins_tbl` (
  `bin_id` int(11) NOT NULL,
  `unique_bin_id` varchar(100) NOT NULL,
  `bin_name` varchar(255) NOT NULL,
  `bin_location` int(11) DEFAULT NULL,
  `plastic_fill_level` enum('0','25','50','75','100') DEFAULT '0',
  `metal_fill_level` enum('0','25','50','75','100') DEFAULT '0',
  `bin_status` enum('Active','Inactive') DEFAULT 'Active',
  `last_emptied` timestamp NULL DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bins_tbl`
--

INSERT INTO `bins_tbl` (`bin_id`, `unique_bin_id`, `bin_name`, `bin_location`, `plastic_fill_level`, `metal_fill_level`, `bin_status`, `last_emptied`, `added_at`, `updated_at`) VALUES
(112, 'bin_0956', 'Main Hall Bin 1', 109, '0', '0', 'Active', '2026-04-26 12:33:09', '2025-07-02 11:08:44', '2026-04-26 12:33:09'),
(113, 'bin_051304', 'Bin Gate 1', 108, '0', '0', 'Active', '2026-04-26 14:53:48', '2025-07-02 11:26:56', '2026-04-26 14:53:48'),
(114, 'bin_09562', 'Main Hall Bin 2', 109, '0', '0', 'Active', '2025-08-03 01:41:50', '2025-07-02 11:27:15', '2025-11-27 16:07:27');

-- --------------------------------------------------------

--
-- Table structure for table `bin_locations_tbl`
--

CREATE TABLE `bin_locations_tbl` (
  `location_id` int(11) NOT NULL,
  `target_location` varchar(255) NOT NULL,
  `location_status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bin_locations_tbl`
--

INSERT INTO `bin_locations_tbl` (`location_id`, `target_location`, `location_status`, `created_at`, `updated_at`) VALUES
(108, 'Gate 1', 'Active', '2025-07-02 11:07:58', '2025-07-02 11:07:58'),
(109, 'Main Hall', 'Active', '2025-07-02 11:08:04', '2025-07-02 11:08:04');

-- --------------------------------------------------------

--
-- Table structure for table `disposal_logs_tbl`
--

CREATE TABLE `disposal_logs_tbl` (
  `disposal_log_id` int(11) NOT NULL,
  `disposed_by` int(11) DEFAULT NULL,
  `bin_disposed` int(11) DEFAULT NULL,
  `disposed_item_type` enum('Plastic','Metal') DEFAULT 'Plastic',
  `weight` decimal(10,2) DEFAULT 0.00,
  `disposed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `disposal_logs_tbl`
--

INSERT INTO `disposal_logs_tbl` (`disposal_log_id`, `disposed_by`, `bin_disposed`, `disposed_item_type`, `weight`, `disposed_at`) VALUES
(3, 1006, 113, 'Plastic', 4.81, '2025-11-17 00:00:00'),
(4, 1005, 112, 'Plastic', 9.21, '2025-11-17 00:00:00'),
(5, 1006, 114, 'Plastic', 9.76, '2025-11-17 00:00:00'),
(6, 1005, 113, 'Plastic', 4.44, '2025-12-01 00:00:00'),
(7, 1005, 112, 'Plastic', 2.53, '2025-12-01 00:00:00'),
(8, 1005, 114, 'Plastic', 6.12, '2025-12-01 00:00:00'),
(9, 1005, 113, 'Plastic', 3.52, '2025-12-15 00:00:00'),
(10, 1006, 112, 'Plastic', 9.78, '2025-12-15 00:00:00'),
(11, 1005, 114, 'Plastic', 8.14, '2025-12-15 00:00:00'),
(12, 1006, 113, 'Plastic', 8.57, '2025-12-29 00:00:00'),
(13, 1006, 112, 'Plastic', 7.68, '2025-12-29 00:00:00'),
(14, 1006, 114, 'Plastic', 2.69, '2025-12-29 00:00:00'),
(15, 1006, 113, 'Plastic', 4.02, '2026-01-12 00:00:00'),
(16, 1006, 112, 'Plastic', 2.10, '2026-01-12 00:00:00'),
(17, 1006, 114, 'Plastic', 9.66, '2026-01-12 00:00:00'),
(18, 1005, 113, 'Plastic', 2.76, '2026-01-26 00:00:00'),
(19, 1005, 112, 'Plastic', 3.49, '2026-01-26 00:00:00'),
(20, 1005, 114, 'Plastic', 7.40, '2026-01-26 00:00:00'),
(21, 1005, 113, 'Plastic', 3.25, '2026-02-09 00:00:00'),
(22, 1005, 112, 'Plastic', 9.05, '2026-02-09 00:00:00'),
(23, 1005, 114, 'Plastic', 4.76, '2026-02-09 00:00:00'),
(24, 1005, 113, 'Plastic', 7.07, '2026-02-23 00:00:00'),
(25, 1006, 112, 'Plastic', 9.38, '2026-02-23 00:00:00'),
(26, 1005, 114, 'Plastic', 8.56, '2026-02-23 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `enrolled_students_tbl`
--

CREATE TABLE `enrolled_students_tbl` (
  `tbl_row_id` int(11) NOT NULL,
  `student_lrn` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrolled_students_tbl`
--

INSERT INTO `enrolled_students_tbl` (`tbl_row_id`, `student_lrn`, `first_name`, `last_name`, `created_at`, `updated_at`) VALUES
(36, '04-2223-033394', 'Joshua ', 'Borra', '2025-11-28 00:28:26', '2025-11-28 00:28:26'),
(37, '04-2223-090987', 'Maximus Decimus', 'Mende', '2025-12-14 23:59:04', '2026-04-14 00:28:45'),
(38, '04-2223-054694', 'Eric', 'Tagana', '2025-12-15 00:08:52', '2026-04-14 00:28:03'),
(89, '112394857261', 'Juan Miguel', 'Dela Cruz', '2026-04-20 11:08:09', '2026-04-20 11:08:09'),
(90, '198472635019', 'Maria Angelica', 'Villanueva', '2026-04-20 11:08:31', '2026-04-20 11:08:31'),
(91, '121938475602', 'Patricia Anne', 'Mendoza', '2026-04-20 11:09:06', '2026-04-20 11:09:06'),
(92, '112234809256', 'Daniel', 'Lopez', '2026-04-20 11:09:29', '2026-04-20 11:09:29'),
(93, '132847596018', 'Angela Marie', 'Garcia', '2026-04-20 11:09:54', '2026-04-20 11:09:54'),
(94, '113928374651', 'John Paul', 'Fernandez', '2026-04-20 11:10:50', '2026-04-20 11:10:50'),
(95, '114649283750', 'Bryan Michael', 'Reyes', '2026-04-20 11:11:34', '2026-04-20 11:11:34');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_tbl`
--

CREATE TABLE `maintenance_tbl` (
  `maintenance_id` int(11) NOT NULL,
  `maintenance_title` varchar(255) NOT NULL,
  `maintenance_bin` int(11) NOT NULL,
  `maintenance_description` text DEFAULT 'None',
  `assigned_personnel` int(11) DEFAULT NULL,
  `maintenance_status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `reported_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_tbl`
--

INSERT INTO `maintenance_tbl` (`maintenance_id`, `maintenance_title`, `maintenance_bin`, `maintenance_description`, `assigned_personnel`, `maintenance_status`, `reported_at`) VALUES
(113, 'Check for damages', 114, 'No damages found.', 1004, 'Completed', '2025-07-02 11:30:23'),
(114, 'Check for damages', 113, 'Working on it.', 1004, 'In Progress', '2025-07-02 11:30:10'),
(115, 'damage', 113, 'None', 1005, 'Completed', '2025-10-28 16:13:24'),
(116, 'Check for damages', 114, 'None', 1006, 'In Progress', '2026-04-26 12:33:38');

-- --------------------------------------------------------

--
-- Table structure for table `personnel_accounts_tbl`
--

CREATE TABLE `personnel_accounts_tbl` (
  `personnel_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email_address` varchar(150) NOT NULL,
  `personnel_password` varchar(255) NOT NULL,
  `role` enum('Personnel') DEFAULT 'Personnel',
  `reset_password_token` varchar(255) DEFAULT NULL,
  `password_token_expiry` datetime DEFAULT NULL,
  `locked_account` enum('Yes','No') DEFAULT 'No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personnel_accounts_tbl`
--

INSERT INTO `personnel_accounts_tbl` (`personnel_id`, `first_name`, `middle_name`, `last_name`, `email_address`, `personnel_password`, `role`, `reset_password_token`, `password_token_expiry`, `locked_account`, `created_at`, `created_by`, `updated_at`) VALUES
(1004, 'Christian Paul', 'Hiponia', 'Bascoguin', 'stephmarvin30@gmail.com', '$2y$10$SF3SdBNQKNcBxso/URy9NuPhwIVqommobGOO4evBh87.7g3GMCdMm', 'Personnel', NULL, NULL, 'No', '2025-07-02 11:01:27', NULL, '2025-08-03 01:27:29'),
(1005, 'Channy', 'blesel', 'Bascoguin', 'fmal@gmail.com', '$2y$10$9H8UUmBWovetUdeTGTIFc.tOLMXw.izC5ww36Np1yVNkiDwX0haSK', 'Personnel', 'szovdgRHVlDgyGlY', '2025-10-28 23:40:53', 'No', '2025-10-28 15:32:50', NULL, '2025-10-28 15:35:57'),
(1006, 'Joshua', 'Forro', 'Borra', 'jborra@gmail.com', '$2y$10$Dd4ncni8FZBWk3Mmm3M6Ce1v/.m6NnTecfMbtFtEBfar.4t302HiS', 'Personnel', NULL, NULL, 'Yes', '2026-04-26 11:48:53', NULL, '2026-04-26 13:01:53');

-- --------------------------------------------------------

--
-- Table structure for table `personnel_profile_tbl`
--

CREATE TABLE `personnel_profile_tbl` (
  `tbl_row_id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female','Others') DEFAULT 'Others',
  `civil_status` enum('Single','Married','Divorced','Widowed') DEFAULT 'Single',
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `facebook_link` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personnel_profile_tbl`
--

INSERT INTO `personnel_profile_tbl` (`tbl_row_id`, `personnel_id`, `profile_picture`, `date_of_birth`, `gender`, `civil_status`, `address`, `phone_number`, `facebook_link`, `updated_at`) VALUES
(4, 1004, 'personnel_1004_68651be8b165c.png', '2004-02-18', 'Male', 'Single', 'Sara, Iloilo', '09927415812', '', '2025-07-02 11:45:44'),
(5, 1005, NULL, NULL, 'Male', 'Single', 'sara, iloilo', '09199678512', '', '2025-10-28 15:32:50'),
(6, 1006, NULL, NULL, 'Male', 'Single', 'Janiuay, Iloilo', '09123456789', '', '2026-04-26 11:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `reward_items_tbl`
--

CREATE TABLE `reward_items_tbl` (
  `item_id` int(11) NOT NULL,
  `item_image` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_description` text DEFAULT 'N/A',
  `item_points` int(11) NOT NULL,
  `item_stocks` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reward_items_tbl`
--

INSERT INTO `reward_items_tbl` (`item_id`, `item_image`, `item_name`, `item_description`, `item_points`, `item_stocks`, `added_at`, `updated_at`) VALUES
(10010, 'item_Pencil_686514938b5b0.png', 'Pencil', 'Used for writing or drawing.', 10, 99, '2025-07-02 11:14:27', '2026-04-26 12:45:09'),
(10011, 'item_Ballpen_686514afaa9a0.png', 'Ballpen', 'Choose 3 colors; red, blue, black.', 15, 45, '2025-07-02 11:14:55', '2026-04-26 12:58:50'),
(10013, 'item_Notebook_69e60bdaa53c6.jpg', 'Notebook', 'Used for writing notes, recording information, doing schoolwork, and organizing ideas.', 30, 15, '2026-04-20 11:19:54', '2026-04-20 11:19:54'),
(10014, 'item_White Folder_69e60c0e41b06.jpg', 'White Folder', 'Used for storing, organizing, and protecting important documents and papers.', 10, 34, '2026-04-20 11:20:46', '2026-04-20 11:20:46'),
(10015, 'item_Eraser_69e60c2a7cfb8.jpg', 'Eraser', '', 5, 50, '2026-04-20 11:21:14', '2026-04-20 11:21:14'),
(10016, 'item_Sticky note paper_69e60c44aea9e.jpg', 'Sticky note paper', '', 15, 30, '2026-04-20 11:21:40', '2026-04-20 11:21:40'),
(10017, 'item_Yellow pad paper_69e60c6518271.jpg', 'Yellow pad paper', '', 15, 30, '2026-04-20 11:22:13', '2026-04-20 11:22:13');

-- --------------------------------------------------------

--
-- Table structure for table `student_accounts_tbl`
--

CREATE TABLE `student_accounts_tbl` (
  `tbl_row_id` int(11) NOT NULL,
  `student_lrn` varchar(255) NOT NULL,
  `student_rfid` varchar(255) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email_address` varchar(150) NOT NULL,
  `student_password` varchar(255) NOT NULL,
  `role` enum('Student') DEFAULT 'Student',
  `grade_level` enum('Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12') DEFAULT NULL,
  `student_points` int(11) NOT NULL DEFAULT 0,
  `reset_password_token` varchar(255) DEFAULT NULL,
  `password_token_expiry` datetime DEFAULT NULL,
  `locked_account` enum('Yes','No') DEFAULT 'No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_accounts_tbl`
--

INSERT INTO `student_accounts_tbl` (`tbl_row_id`, `student_lrn`, `student_rfid`, `first_name`, `middle_name`, `last_name`, `email_address`, `student_password`, `role`, `grade_level`, `student_points`, `reset_password_token`, `password_token_expiry`, `locked_account`, `created_at`, `updated_at`) VALUES
(6, '04-2223-033394', '1234', 'Joshua ', 'Forro', 'Borra', 'hackingplayer97@gmail.com', '$2y$10$10X7BKDpshddunhnF8aCvOhIj0c0yEvCbyeJlHs.HwklXRbKyKNI6', 'Student', 'Grade 12', 0, NULL, NULL, 'No', '2025-11-28 00:32:28', '2025-11-28 00:33:22'),
(7, '04-2223-090987', '', 'Maximus Decimus', 'Meridius', 'Mende', 'MaxMende@gmail.com', '$2y$10$ngoK.LFzKgJW3ZS5HmPXieaUqI1kAKYbXbrNPFvbZ9dd0JYTSQgD6', 'Student', 'Grade 11', 0, NULL, NULL, 'No', '2026-04-14 00:08:23', '2026-04-14 00:08:23'),
(26, '113928374651', '1236', 'John Paul', '', 'Fernandez', 'jp123@gmail.com', '$2y$10$L0L.hK8aLhPOUB6OiGAwm.AibIgKWm5zREcAJk8jJh3T6k0FnqevS', 'Student', 'Grade 10', 0, NULL, NULL, 'No', '2026-04-20 13:55:43', '2026-04-21 07:50:04'),
(27, '132847596018', '1237', 'Angela Marie', '', 'Garcia', 'amgarcia@gmail.com', '$2y$10$FFrZqEHjhfq7JO9W6r3th.aTXbhmEeLsC.H0o00lZHAu8jYSd9C82', 'Student', 'Grade 10', 0, NULL, NULL, 'No', '2026-04-20 13:58:22', '2026-04-21 07:50:57'),
(28, '198472635019', '12356', 'Maria Angelica', '', '	Villanueva', 'villanueva34@gmail.com', '$2y$10$y7Lwiyyo6WwFh.PO26BW5.meM8q2nKIajbPlrA7sf91ioSt35eUJm', 'Student', 'Grade 8', 0, NULL, NULL, 'No', '2026-04-21 07:44:16', '2026-04-26 12:13:49');

-- --------------------------------------------------------

--
-- Table structure for table `student_disposal_log_tbl`
--

CREATE TABLE `student_disposal_log_tbl` (
  `student_disposal_log_id` int(11) NOT NULL,
  `disposed_by` varchar(100) DEFAULT NULL,
  `bin_used` int(11) NOT NULL,
  `disposed_item_type` enum('Plastic','Metal') DEFAULT 'Plastic',
  `points_gained` int(11) DEFAULT 0,
  `disposed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_disposal_log_tbl`
--

INSERT INTO `student_disposal_log_tbl` (`student_disposal_log_id`, `disposed_by`, `bin_used`, `disposed_item_type`, `points_gained`, `disposed_at`) VALUES
(1260, '198472635019', 114, 'Plastic', 3, '2026-01-12 03:00:00'),
(1262, '198472635019', 113, 'Plastic', 2, '2026-03-03 04:00:00'),
(1263, '132847596018', 114, 'Plastic', 6, '2026-01-06 05:00:00'),
(1264, '132847596018', 113, 'Plastic', 3, '2026-01-16 07:00:00'),
(1265, '198472635019', 112, 'Plastic', 9, '2025-11-22 07:00:00'),
(1266, '113928374651', 113, 'Plastic', 7, '2026-01-27 08:00:00'),
(1267, '198472635019', 113, 'Plastic', 9, '2025-11-22 05:00:00'),
(1268, '198472635019', 113, 'Plastic', 5, '2026-01-28 07:00:00'),
(1269, '198472635019', 112, 'Plastic', 3, '2026-03-10 08:00:00'),
(1270, '132847596018', 114, 'Plastic', 9, '2026-03-02 04:00:00'),
(1271, '198472635019', 113, 'Plastic', 2, '2026-02-14 04:00:00'),
(1272, '132847596018', 112, 'Plastic', 4, '2026-03-21 08:00:00'),
(1273, '132847596018', 112, 'Plastic', 5, '2026-02-14 03:00:00'),
(1274, '132847596018', 114, 'Plastic', 5, '2025-12-04 04:00:00'),
(1276, '198472635019', 112, 'Plastic', 3, '2026-01-16 08:00:00'),
(1277, '132847596018', 114, 'Plastic', 6, '2026-03-24 04:00:00'),
(1278, '198472635019', 113, 'Plastic', 4, '2025-12-13 01:00:00'),
(1280, '198472635019', 113, 'Plastic', 9, '2026-01-20 08:00:00'),
(1281, '113928374651', 114, 'Plastic', 10, '2025-12-08 00:00:00'),
(1283, '132847596018', 114, 'Plastic', 8, '2025-11-27 03:00:00'),
(1284, '113928374651', 112, 'Plastic', 4, '2026-01-05 02:00:00'),
(1285, '113928374651', 114, 'Plastic', 3, '2026-03-12 00:00:00'),
(1286, '113928374651', 114, 'Plastic', 7, '2026-02-22 08:00:00'),
(1287, '132847596018', 113, 'Plastic', 9, '2025-11-19 07:00:00'),
(1288, '113928374651', 113, 'Plastic', 4, '2026-01-19 07:00:00'),
(1289, '132847596018', 113, 'Plastic', 4, '2026-02-09 03:00:00'),
(1290, '198472635019', 113, 'Plastic', 5, '2026-01-13 00:00:00'),
(1291, '132847596018', 113, 'Plastic', 2, '2025-11-30 04:00:00'),
(1292, '132847596018', 112, 'Plastic', 10, '2026-01-24 07:00:00'),
(1293, '198472635019', 112, 'Plastic', 7, '2025-11-24 04:00:00'),
(1294, '113928374651', 112, 'Plastic', 4, '2026-02-25 08:00:00'),
(1295, '198472635019', 112, 'Plastic', 3, '2025-11-26 00:00:00'),
(1296, '132847596018', 113, 'Plastic', 4, '2025-11-24 03:00:00'),
(1297, '113928374651', 113, 'Plastic', 2, '2025-11-18 07:00:00'),
(1298, '113928374651', 113, 'Plastic', 4, '2026-03-18 07:00:00'),
(1301, '132847596018', 112, 'Plastic', 6, '2025-11-27 07:00:00'),
(1302, '113928374651', 112, 'Plastic', 4, '2026-02-03 02:00:00'),
(1303, '113928374651', 114, 'Plastic', 9, '2026-01-31 04:00:00'),
(1306, '132847596018', 112, 'Plastic', 8, '2025-12-05 06:00:00'),
(1307, '113928374651', 114, 'Plastic', 5, '2025-11-23 08:00:00'),
(1308, '198472635019', 113, 'Plastic', 2, '2025-11-18 08:00:00'),
(1309, '198472635019', 113, 'Plastic', 10, '2025-11-17 01:00:00'),
(1310, '113928374651', 112, 'Plastic', 3, '2026-01-28 03:00:00'),
(1311, '113928374651', 112, 'Plastic', 7, '2026-03-03 03:00:00'),
(1312, '132847596018', 114, 'Plastic', 10, '2026-02-24 00:00:00'),
(1313, '113928374651', 113, 'Plastic', 4, '2026-03-11 05:00:00'),
(1315, '198472635019', 112, 'Plastic', 7, '2025-12-12 00:00:00'),
(1316, '113928374651', 114, 'Plastic', 6, '2026-03-16 00:00:00'),
(1317, '113928374651', 113, 'Plastic', 7, '2026-03-20 01:00:00'),
(1318, '198472635019', 113, 'Plastic', 7, '2026-03-07 03:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `student_profile_tbl`
--

CREATE TABLE `student_profile_tbl` (
  `tbl_row_id` int(11) NOT NULL,
  `student_lrn` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Others') DEFAULT 'Others',
  `civil_status` enum('Single','Married','Divorced','Widowed') DEFAULT 'Single',
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `facebook_link` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_profile_tbl`
--

INSERT INTO `student_profile_tbl` (`tbl_row_id`, `student_lrn`, `profile_picture`, `date_of_birth`, `gender`, `civil_status`, `address`, `phone_number`, `facebook_link`, `updated_at`) VALUES
(6, '04-2223-033394', NULL, '2004-01-25', 'Male', 'Single', 'Star, rita Janiuay Iloilo', '09309455644', '', '2025-11-28 00:32:28'),
(7, '04-2223-090987', NULL, '1981-06-25', 'Male', 'Married', 'Calumpang Iloilo City', '09293847621', '', '2026-04-14 00:08:23'),
(8, '113928374651', NULL, '2026-04-09', 'Male', 'Single', 'Zone 12 Calaparan, Arevalo, Iloilo City', '09173826158', '', '2026-04-20 13:55:43'),
(9, '132847596018', NULL, '2010-07-20', 'Female', 'Single', 'Calumpang, Iloilo City', '09175427432', '', '2026-04-20 13:58:22'),
(10, '198472635019', NULL, '2016-02-21', 'Female', 'Single', 'Zone 9 Calumpang, Iloilo City', '09442231687', '', '2026-04-21 07:44:16');

-- --------------------------------------------------------

--
-- Table structure for table `transactions_tbl`
--

CREATE TABLE `transactions_tbl` (
  `transaction_id` int(11) NOT NULL,
  `redeemed_by` varchar(255) DEFAULT NULL,
  `item_redeemed` int(11) NOT NULL,
  `item_quantity` int(11) NOT NULL,
  `points_per_item` int(11) NOT NULL,
  `total_points` int(11) NOT NULL,
  `redeem_status` enum('Approved','Declined','Pending') DEFAULT 'Pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `redeemed_at` varchar(100) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions_tbl`
--

INSERT INTO `transactions_tbl` (`transaction_id`, `redeemed_by`, `item_redeemed`, `item_quantity`, `points_per_item`, `total_points`, `redeem_status`, `requested_at`, `redeemed_at`) VALUES
(100015, NULL, 10010, 3, 10, 30, 'Declined', '2025-07-02 11:22:05', 'Declined'),
(100016, NULL, 10011, 2, 15, 30, 'Approved', '2025-07-02 11:22:12', '2025-07-02 19:23:55'),
(100018, NULL, 10011, 1, 15, 15, 'Declined', '2026-04-26 12:44:50', 'Declined'),
(100019, NULL, 10010, 1, 10, 10, 'Approved', '2026-04-26 12:45:09', '2026-04-26 20:57:27'),
(100021, NULL, 10011, 3, 15, 45, 'Approved', '2026-04-26 12:58:50', '2026-04-26 20:58:57');

-- --------------------------------------------------------

--
-- Table structure for table `uploaded_files_tbl`
--

CREATE TABLE `uploaded_files_tbl` (
  `file_id` int(11) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` decimal(11,2) DEFAULT 0.00,
  `uploaded_by` int(11) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_accounts_tbl`
--
ALTER TABLE `admin_accounts_tbl`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email_address` (`email_address`);

--
-- Indexes for table `admin_profile_tbl`
--
ALTER TABLE `admin_profile_tbl`
  ADD PRIMARY KEY (`tbl_row_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `bins_tbl`
--
ALTER TABLE `bins_tbl`
  ADD PRIMARY KEY (`bin_id`),
  ADD UNIQUE KEY `unique_bin_id` (`unique_bin_id`),
  ADD KEY `bins_tbl_ibfk1` (`bin_location`);

--
-- Indexes for table `bin_locations_tbl`
--
ALTER TABLE `bin_locations_tbl`
  ADD PRIMARY KEY (`location_id`),
  ADD UNIQUE KEY `target_location` (`target_location`);

--
-- Indexes for table `disposal_logs_tbl`
--
ALTER TABLE `disposal_logs_tbl`
  ADD PRIMARY KEY (`disposal_log_id`),
  ADD KEY `disposed_by` (`disposed_by`),
  ADD KEY `bin_disposed` (`bin_disposed`);

--
-- Indexes for table `enrolled_students_tbl`
--
ALTER TABLE `enrolled_students_tbl`
  ADD PRIMARY KEY (`tbl_row_id`),
  ADD UNIQUE KEY `student_lrn` (`student_lrn`);

--
-- Indexes for table `maintenance_tbl`
--
ALTER TABLE `maintenance_tbl`
  ADD PRIMARY KEY (`maintenance_id`),
  ADD KEY `maintenance_bin` (`maintenance_bin`),
  ADD KEY `assigned_personnel` (`assigned_personnel`);

--
-- Indexes for table `personnel_accounts_tbl`
--
ALTER TABLE `personnel_accounts_tbl`
  ADD PRIMARY KEY (`personnel_id`),
  ADD UNIQUE KEY `email_address` (`email_address`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `personnel_profile_tbl`
--
ALTER TABLE `personnel_profile_tbl`
  ADD PRIMARY KEY (`tbl_row_id`),
  ADD KEY `personnel_id` (`personnel_id`);

--
-- Indexes for table `reward_items_tbl`
--
ALTER TABLE `reward_items_tbl`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `student_accounts_tbl`
--
ALTER TABLE `student_accounts_tbl`
  ADD PRIMARY KEY (`tbl_row_id`),
  ADD UNIQUE KEY `student_lrn` (`student_lrn`),
  ADD UNIQUE KEY `email_address` (`email_address`),
  ADD UNIQUE KEY `student_rfid` (`student_rfid`);

--
-- Indexes for table `student_disposal_log_tbl`
--
ALTER TABLE `student_disposal_log_tbl`
  ADD PRIMARY KEY (`student_disposal_log_id`),
  ADD KEY `disposed_by` (`disposed_by`),
  ADD KEY `bin_used` (`bin_used`);

--
-- Indexes for table `student_profile_tbl`
--
ALTER TABLE `student_profile_tbl`
  ADD PRIMARY KEY (`tbl_row_id`),
  ADD KEY `student_lrn` (`student_lrn`);

--
-- Indexes for table `transactions_tbl`
--
ALTER TABLE `transactions_tbl`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `redeemed_by` (`redeemed_by`),
  ADD KEY `item_redeemed` (`item_redeemed`);

--
-- Indexes for table `uploaded_files_tbl`
--
ALTER TABLE `uploaded_files_tbl`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_accounts_tbl`
--
ALTER TABLE `admin_accounts_tbl`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1010;

--
-- AUTO_INCREMENT for table `admin_profile_tbl`
--
ALTER TABLE `admin_profile_tbl`
  MODIFY `tbl_row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bins_tbl`
--
ALTER TABLE `bins_tbl`
  MODIFY `bin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `bin_locations_tbl`
--
ALTER TABLE `bin_locations_tbl`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `disposal_logs_tbl`
--
ALTER TABLE `disposal_logs_tbl`
  MODIFY `disposal_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `enrolled_students_tbl`
--
ALTER TABLE `enrolled_students_tbl`
  MODIFY `tbl_row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `maintenance_tbl`
--
ALTER TABLE `maintenance_tbl`
  MODIFY `maintenance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `personnel_accounts_tbl`
--
ALTER TABLE `personnel_accounts_tbl`
  MODIFY `personnel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1007;

--
-- AUTO_INCREMENT for table `personnel_profile_tbl`
--
ALTER TABLE `personnel_profile_tbl`
  MODIFY `tbl_row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reward_items_tbl`
--
ALTER TABLE `reward_items_tbl`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10019;

--
-- AUTO_INCREMENT for table `student_accounts_tbl`
--
ALTER TABLE `student_accounts_tbl`
  MODIFY `tbl_row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `student_disposal_log_tbl`
--
ALTER TABLE `student_disposal_log_tbl`
  MODIFY `student_disposal_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1326;

--
-- AUTO_INCREMENT for table `student_profile_tbl`
--
ALTER TABLE `student_profile_tbl`
  MODIFY `tbl_row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transactions_tbl`
--
ALTER TABLE `transactions_tbl`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100022;

--
-- AUTO_INCREMENT for table `uploaded_files_tbl`
--
ALTER TABLE `uploaded_files_tbl`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_profile_tbl`
--
ALTER TABLE `admin_profile_tbl`
  ADD CONSTRAINT `admin_profile_tbl_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin_accounts_tbl` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `bins_tbl`
--
ALTER TABLE `bins_tbl`
  ADD CONSTRAINT `bins_tbl_ibfk1` FOREIGN KEY (`bin_location`) REFERENCES `bin_locations_tbl` (`location_id`) ON DELETE SET NULL;

--
-- Constraints for table `disposal_logs_tbl`
--
ALTER TABLE `disposal_logs_tbl`
  ADD CONSTRAINT `disposal_logs_tbl_ibfk_1` FOREIGN KEY (`disposed_by`) REFERENCES `personnel_accounts_tbl` (`personnel_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `disposal_logs_tbl_ibfk_2` FOREIGN KEY (`bin_disposed`) REFERENCES `bins_tbl` (`bin_id`) ON DELETE SET NULL;

--
-- Constraints for table `maintenance_tbl`
--
ALTER TABLE `maintenance_tbl`
  ADD CONSTRAINT `maintenance_tbl_ibfk_1` FOREIGN KEY (`maintenance_bin`) REFERENCES `bins_tbl` (`bin_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenance_tbl_ibfk_2` FOREIGN KEY (`assigned_personnel`) REFERENCES `personnel_accounts_tbl` (`personnel_id`) ON DELETE SET NULL;

--
-- Constraints for table `personnel_accounts_tbl`
--
ALTER TABLE `personnel_accounts_tbl`
  ADD CONSTRAINT `personnel_accounts_tbl_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admin_accounts_tbl` (`admin_id`) ON DELETE SET NULL;

--
-- Constraints for table `personnel_profile_tbl`
--
ALTER TABLE `personnel_profile_tbl`
  ADD CONSTRAINT `personnel_profile_tbl_ibfk_1` FOREIGN KEY (`personnel_id`) REFERENCES `personnel_accounts_tbl` (`personnel_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_accounts_tbl`
--
ALTER TABLE `student_accounts_tbl`
  ADD CONSTRAINT `student_accounts_tbl_ibfk_1` FOREIGN KEY (`student_lrn`) REFERENCES `enrolled_students_tbl` (`student_lrn`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_disposal_log_tbl`
--
ALTER TABLE `student_disposal_log_tbl`
  ADD CONSTRAINT `student_disposal_log_tbl_ibfk_1` FOREIGN KEY (`disposed_by`) REFERENCES `student_accounts_tbl` (`student_lrn`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_disposal_log_tbl_ibfk_2` FOREIGN KEY (`bin_used`) REFERENCES `bins_tbl` (`bin_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_profile_tbl`
--
ALTER TABLE `student_profile_tbl`
  ADD CONSTRAINT `student_profile_tbl_ibfk_1` FOREIGN KEY (`student_lrn`) REFERENCES `student_accounts_tbl` (`student_lrn`) ON DELETE CASCADE;

--
-- Constraints for table `transactions_tbl`
--
ALTER TABLE `transactions_tbl`
  ADD CONSTRAINT `transactions_tbl_ibfk_1` FOREIGN KEY (`redeemed_by`) REFERENCES `student_accounts_tbl` (`student_lrn`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_tbl_ibfk_2` FOREIGN KEY (`item_redeemed`) REFERENCES `reward_items_tbl` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `uploaded_files_tbl`
--
ALTER TABLE `uploaded_files_tbl`
  ADD CONSTRAINT `uploaded_files_tbl_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `admin_accounts_tbl` (`admin_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
