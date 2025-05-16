-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 05:00 PM
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
-- Database: `jobapplication`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `job_category` varchar(50) NOT NULL,
  `job_position` varchar(50) NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','reviewed','interviewed','hired','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`id`, `full_name`, `email`, `contact_number`, `job_category`, `job_position`, `application_date`, `status`) VALUES
(1, 'Neil Alvin', 'neil123@gmail.com', '09898989898', 'teaching', 'it-support', '2025-05-16 13:39:08', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `status` enum('pending','reviewed','accepted','rejected') DEFAULT 'pending',
  `resume_path` varchar(255) NOT NULL,
  `cover_letter` text DEFAULT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `application_documents`
--

CREATE TABLE `application_documents` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `document_type` enum('resume','cover_letter','terms_of_reference','eligibility') NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_documents`
--

INSERT INTO `application_documents` (`id`, `application_id`, `document_type`, `file_name`, `file_path`, `file_type`, `file_size`) VALUES
(1, 1, 'resume', 'hehe.pdf', 'uploads/job_applications/app_1_resume_7588096d3681303e.pdf', 'application/pdf', 14606),
(2, 1, 'cover_letter', 'hehe.pdf', 'uploads/job_applications/app_1_cover_letter_d76da1fae5e87a9d.pdf', 'application/pdf', 14606),
(3, 1, 'terms_of_reference', 'hehe.pdf', 'uploads/job_applications/app_1_terms_of_reference_434bf6f88e9e1041.pdf', 'application/pdf', 14606),
(4, 1, 'eligibility', 'hehe.pdf', 'uploads/job_applications/app_1_eligibility_daf4e6f6bab267b0.pdf', 'application/pdf', 14606);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `department` varchar(50) NOT NULL,
  `requirements` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `description`, `department`, `requirements`, `is_active`, `created_at`) VALUES
(1, 'Administrative Officer', 'Handles office operations', 'Administration', 'Bachelor\'s degree, 2 years experience', 1, '2025-05-03 08:48:17'),
(2, 'Teacher I', 'Elementary level teaching', 'Education', 'Licensed teacher, 1 year experience', 1, '2025-05-03 08:48:17');

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `job_category` enum('teaching','non-teaching') NOT NULL,
  `job_position` varchar(50) NOT NULL,
  `elementary_school` varchar(100) NOT NULL,
  `high_school` varchar(100) NOT NULL,
  `senior_high` varchar(100) NOT NULL,
  `college` varchar(100) NOT NULL,
  `company1` varchar(100) NOT NULL,
  `position1` varchar(100) NOT NULL,
  `duration1` int(11) NOT NULL,
  `company2` varchar(100) DEFAULT NULL,
  `position2` varchar(100) DEFAULT NULL,
  `duration2` int(11) DEFAULT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','reviewed','accepted','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`id`, `full_name`, `email`, `contact_number`, `job_category`, `job_position`, `elementary_school`, `high_school`, `senior_high`, `college`, `company1`, `position1`, `duration1`, `company2`, `position2`, `duration2`, `application_date`, `status`) VALUES
(1, 'Neil Alvin', 'neil123@gmail.com', '09898989898', 'teaching', 'it-support', 'elem', 'hs', 'shs', 'lspu', 'asd', 'asd', 5, '', '', 0, '2025-05-16 13:39:08', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `address`, `is_admin`, `created_at`) VALUES
(1, 'admin', 'admin@jobapplication.com', 'Admin123', 'System Admin', NULL, NULL, 1, '2025-05-03 08:47:27'),
(2, 'testuser', 'user@jobapplication.com', 'User123', 'Test User', NULL, NULL, 0, '2025-05-03 08:47:57'),
(3, 'khynuss', 'Khyle Otano', 'Khyle123', 'khyleotano@gmail.com', NULL, NULL, 0, '2025-05-03 10:53:46'),
(4, 'johndoe', 'johndoe@email.com', 'John1234', 'John Doe', NULL, NULL, 0, '2025-05-03 11:10:28'),
(5, 'beingseros', 'serosbeing@email.com', 'Seros123', 'Seros Being', NULL, NULL, 0, '2025-05-04 06:36:58'),
(6, 'testing', 'test@example.com', 'Test1234', 'Testing user', NULL, NULL, 0, '2025-05-05 06:49:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `idx_document_type` (`document_type`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`);

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
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `application_documents`
--
ALTER TABLE `application_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD CONSTRAINT `application_documents_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `job_applications` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
