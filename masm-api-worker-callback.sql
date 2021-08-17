-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2021 at 02:43 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `masm-api-worker-callback`
--

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `uid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `appId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operating_system` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`uid`, `appId`, `language`, `operating_system`, `token`) VALUES
('1', '2', 'tr', 'android', '$2y$10$tmEaSuCDDlIHOfLbnUzxIetEszQ0SBO0hVX6O7FgaT7CsDmgeAI8O'),
('2', '2', 'tr', 'ios', '$2y$10$MnT2PL2MYhWg6CABN4ZQGe4oMIXZxUFEIm9krJPTVPaOa1UUCS.si'),
('3', '2', 'tr', 'ios', '$2y$10$n8QFcRKm3dVImz5quHTIvOi0GIO3zpE..bhUGl0jor/HXw33P0njS'),
('4', '2', 'tr', 'ios', '$2y$10$qd/L5qCfrVko4JeqQ8c9ZeUXMLRLMJSsQ8YzijzreqgLFkF8F1dgC'),
('5', '3', 'tr', 'ios', '$2y$10$ouuOE4ChSXDNrxmoKNU9mO3FBPk3PmRPGKTcniKrCB1SMdtTfJaVi');

-- --------------------------------------------------------

--
-- Table structure for table `endpoints`
--

CREATE TABLE `endpoints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `endpoint` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `endpoints`
--

INSERT INTO `endpoints` (`id`, `name`, `endpoint`) VALUES
(1, 'ios', 'ios'),
(2, 'google', 'google'),
(3, 'callback', 'callback');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2021_08_14_122907_device', 1),
(3, '2021_08_15_120549_subscription', 1),
(4, '2021_08_16_173932_endpoints', 1),
(5, '2021_08_17_091715_report', 2);

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `appId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` date NOT NULL,
  `operating_system` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `started_piece` int(11) NOT NULL,
  `renewed_piece` int(11) NOT NULL,
  `canceled_piece` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `appId`, `day`, `operating_system`, `started_piece`, `renewed_piece`, `canceled_piece`) VALUES
(1, '2', '2021-08-17', 'android', 1, 12, 2),
(2, '2', '2021-08-17', 'ios', 2, 18, 3),
(3, '3', '2021-08-17', 'ios', 5, 5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

CREATE TABLE `subscription` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receipt` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `uid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `expire_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `device`
--
ALTER TABLE `device`
  ADD UNIQUE KEY `device_uid_unique` (`uid`);

--
-- Indexes for table `endpoints`
--
ALTER TABLE `endpoints`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `endpoints_id_unique` (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription`
--
ALTER TABLE `subscription`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_receipt_unique` (`receipt`) USING HASH;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `endpoints`
--
ALTER TABLE `endpoints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subscription`
--
ALTER TABLE `subscription`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
