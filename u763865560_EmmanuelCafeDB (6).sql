-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 14, 2026 at 09:57 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u763865560_EmmanuelCafeDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(64) NOT NULL,
  `entity_type` varchar(64) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip`, `user_agent`, `created_at`) VALUES
(1, 2, 'login_success_otp_verified', 'users', 2, '[]', '124.104.172.164', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:18:14'),
(2, 3, 'login_success_otp_verified', 'users', 3, '[]', '124.104.172.164', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:27:41'),
(3, 2, 'login_success', 'users', 2, '[]', '124.104.172.164', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 03:46:03'),
(4, 2, 'login_success', 'users', 2, '[]', '180.191.36.244', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 05:22:34'),
(5, 3, 'login_success', 'users', 3, '[]', '180.191.36.244', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 05:23:11'),
(6, 4, 'user_registered', 'users', 4, '{\"username\":\"Dendi\"}', '111.90.232.199', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', '2025-11-20 05:26:08'),
(7, 2, 'login_success', 'users', 2, '[]', '2001:fd8:2992:8cc4:ed5a:aa2b:aa4e:83e2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 08:47:52'),
(8, 5, 'login_success_otp_verified', 'users', 5, '[]', '111.125.106.82', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', '2025-11-21 05:08:00'),
(9, 3, 'login_success', 'users', 3, '[]', '2405:8d40:4c0d:5528:8481:f3b6:922a:a335', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-21 09:23:18'),
(10, 2, 'login_success', 'users', 2, '[]', '2405:8d40:4c0d:5528:8481:f3b6:922a:a335', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-21 09:35:33'),
(11, 2, 'profile_picture_updated', 'users', 2, '{\"filename\":\"user_2_1763718086.jpg\"}', '2405:8d40:4c0d:5528:8481:f3b6:922a:a335', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-21 09:41:26'),
(12, 2, 'login_success', 'users', 2, '[]', '120.29.68.160', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-21 12:07:00'),
(13, 3, 'login_success', 'users', 3, '[]', '175.176.11.112', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 09:45:39'),
(14, 2, 'login_success', 'users', 2, '[]', '175.176.11.112', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 09:55:47'),
(15, 2, 'login_success', 'users', 2, '[]', '131.226.96.117', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 04:20:06'),
(16, 2, 'login_success', 'users', 2, '[]', '111.90.197.81', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 04:50:07'),
(17, 6, 'login_success_otp_verified', 'users', 6, '[]', '161.49.149.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 15:31:26'),
(18, 2, 'login_success', 'users', 2, '[]', '161.49.149.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 16:15:33'),
(19, 2, 'login_success', 'users', 2, '{\"page\":\"product.php\"}', '161.49.149.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 16:18:38'),
(20, 3, 'login_success', 'users', 3, '[]', '161.49.149.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 16:39:20'),
(21, 2, 'login_success', 'users', 2, '[]', '161.49.149.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 16:41:28'),
(22, 2, 'login_success', 'users', 2, '[]', '161.49.149.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 16:43:43'),
(23, 3, 'login_success', 'users', 3, '[]', '161.49.149.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 16:45:25'),
(24, 3, 'login_success', 'users', 3, '[]', '161.49.149.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 16:54:53'),
(25, 3, 'login_success', 'users', 3, '[]', '124.104.172.164', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-25 01:56:48'),
(26, 3, 'login_success', 'users', 3, '{\"page\":\"product.php\"}', '111.125.107.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-25 02:03:59'),
(27, 2, 'login_success', 'users', 2, '[]', '111.125.107.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-25 02:06:39'),
(28, 3, 'login_success', 'users', 3, '[]', '111.125.107.100', 'Mozilla/5.0 (Linux; Android 10; JNY-LX2; HMSCore 6.15.4.322) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.196 HuaweiBrowser/16.0.6.300 Mobile Safari/537.36', '2025-11-25 02:13:07'),
(29, 3, 'login_success', 'users', 3, '[]', '111.125.107.82', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36 Edg/142.0.0.0', '2025-11-25 02:13:45'),
(30, 3, 'login_success', 'users', 3, '{\"page\":\"product.php\"}', '111.125.107.100', 'Mozilla/5.0 (Linux; Android 10; JNY-LX2; HMSCore 6.15.4.322) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.196 HuaweiBrowser/16.0.6.300 Mobile Safari/537.36', '2025-11-25 13:59:44'),
(31, 3, 'login_success', 'users', 3, '[]', '124.104.172.164', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-25 14:14:35'),
(32, 2, 'login_success', 'users', 2, '[]', '124.104.172.164', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-25 14:16:38'),
(33, 3, 'login_success', 'users', 3, '[]', '111.125.107.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-25 14:16:44'),
(34, 2, 'login_success', 'users', 2, '[]', '111.125.107.82', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', '2025-11-25 14:56:03'),
(35, 3, 'login_success', 'users', 3, '[]', '111.125.107.100', 'Mozilla/5.0 (Linux; Android 10; JNY-LX2; HMSCore 6.15.4.322) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.196 HuaweiBrowser/16.0.6.300 Mobile Safari/537.36', '2025-11-25 14:59:41'),
(36, 3, 'login_success', 'users', 3, '{\"page\":\"product.php\"}', '111.125.107.100', 'Mozilla/5.0 (Linux; Android 10; JNY-LX2; HMSCore 6.15.4.322) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.196 HuaweiBrowser/16.0.6.300 Mobile Safari/537.36', '2025-11-26 04:19:48'),
(37, 3, 'login_success', 'users', 3, '[]', '111.125.107.100', 'Mozilla/5.0 (Linux; Android 10; JNY-LX2; HMSCore 6.15.4.322) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.196 HuaweiBrowser/16.0.6.300 Mobile Safari/537.36', '2025-11-26 10:09:24'),
(38, 3, 'login_success', 'users', 3, '[]', '111.125.107.100', 'Mozilla/5.0 (Linux; Android 10; JNY-LX2; HMSCore 6.15.4.322) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.196 HuaweiBrowser/16.0.6.300 Mobile Safari/537.36', '2025-11-26 10:10:10'),
(39, 3, 'login_success', 'users', 3, '[]', '120.29.86.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 11:57:11'),
(40, 3, 'login_success', 'users', 3, '[]', '120.29.86.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-26 13:30:10'),
(41, 3, 'login_success', 'users', 3, '[]', '120.29.86.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 14:39:00'),
(42, 2, 'login_success', 'users', 2, '[]', '111.125.107.100', 'Mozilla/5.0 (Linux; Android 10; JNY-LX2; HMSCore 6.15.4.322) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.196 HuaweiBrowser/16.0.6.300 Mobile Safari/537.36', '2025-11-26 15:20:01'),
(43, 2, 'login_success', 'users', 2, '[]', '120.29.86.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 15:20:46'),
(44, 2, 'login_success', 'users', 2, '{\"page\":\"product.php\"}', '120.29.86.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-26 15:24:51'),
(45, 2, 'profile_picture_updated', 'users', 2, '{\"filename\":\"user_2_1764173208.jpg\"}', '120.29.86.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-26 16:06:48'),
(46, 3, 'login_success', 'users', 3, '[]', '120.29.86.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 16:18:31'),
(47, 3, 'login_success', 'users', 3, '[]', '120.29.86.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 16:28:03'),
(48, 2, 'login_success', 'users', 2, '[]', '120.29.86.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 19:04:43'),
(49, 2, 'login_success', 'users', 2, '[]', '120.29.86.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-26 19:50:27'),
(50, 3, 'login_success', 'users', 3, '[]', '120.29.86.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 19:59:26'),
(51, 2, 'login_success', 'users', 2, '[]', '111.125.107.100', 'Mozilla/5.0 (Linux; Android 10; JNY-LX2; HMSCore 6.15.4.322) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.196 HuaweiBrowser/16.0.6.300 Mobile Safari/537.36', '2025-11-26 22:37:43'),
(52, 3, 'login_success', 'users', 3, '[]', '111.125.107.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-27 00:52:59'),
(53, 2, 'login_success', 'users', 2, '[]', '120.29.86.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-27 03:31:23'),
(54, 3, 'login_success', 'users', 3, '[]', '120.29.86.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-27 03:47:22'),
(55, 3, 'login_success', 'users', 3, '[]', '124.104.205.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-27 07:00:08'),
(56, 8, 'login_success', 'users', 8, '{\"page\":\"product.php\"}', '175.176.11.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-27 08:58:28'),
(57, 8, 'login_success', 'users', 8, '[]', '175.176.11.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-27 09:07:25'),
(58, 3, 'login_success', 'users', 3, '[]', '175.176.11.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-27 09:31:06'),
(59, 3, 'login_success', 'users', 3, '[]', '175.176.11.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-27 09:31:26'),
(60, 8, 'login_success', 'users', 8, '[]', '175.176.11.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-27 09:34:39'),
(61, 3, 'login_success', 'users', 3, '[]', '175.176.11.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-27 09:38:26'),
(62, 8, 'login_success', 'users', 8, '[]', '175.176.11.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-27 09:45:32'),
(63, 3, 'login_success', 'users', 3, '[]', '161.49.149.106', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-29 03:51:04'),
(64, 2, 'login_success', 'users', 2, '[]', '111.125.107.132', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-29 09:53:31'),
(65, 2, 'login_success', 'users', 2, '{\"page\":\"product.php\"}', '120.29.69.73', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-30 12:13:06'),
(66, 3, 'login_success', 'users', 3, '[]', '124.104.205.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-03 03:37:19'),
(67, 3, 'login_success', 'users', 3, '[]', '124.104.205.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-08 04:12:58'),
(68, 3, 'login_success', 'users', 3, '[]', '124.104.205.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-13 07:15:08'),
(69, 3, 'login_success', 'users', 3, '{\"page\":\"product.php\"}', '124.104.205.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-29 06:37:27'),
(70, 3, 'login_success', 'users', 3, '[]', '124.104.205.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-29 06:41:26'),
(71, 3, 'login_success', 'users', 3, '[]', '2a09:bac1:5ac0:58::246:1c', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-25 06:13:12'),
(72, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 02:45:44'),
(73, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 05:13:49'),
(74, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:01:28'),
(75, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:07:52'),
(76, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:09:16'),
(77, 4, 'password_reset_requested', 'users', 4, '{\"method\":\"email\"}', '124.105.39.138', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', '2026-02-03 08:13:40'),
(78, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', '2026-02-03 08:34:18'),
(79, 10, 'otp_resent_success', 'otp_codes', 10, '{\"email\":\"kylerefrado@gmail.com\"}', '124.105.39.138', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:36:52'),
(80, 11, 'otp_resent_success', 'otp_codes', 12, '{\"email\":\"gnc.isaacjedm@gmail.com\"}', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 09:07:47'),
(81, 11, 'otp_resent_success', 'otp_codes', 13, '{\"email\":\"gnc.isaacjedm@gmail.com\"}', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 09:10:20'),
(82, 11, 'login_success', 'users', 11, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 09:11:20'),
(83, 10, 'login_success', 'users', 10, '{\"page\":\"product.php\"}', '124.105.39.138', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 09:11:54'),
(84, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 11:25:58'),
(85, 10, 'login_success', 'users', 10, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 12:38:41'),
(86, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 12:40:30'),
(87, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 12:45:07'),
(88, 10, 'login_success', 'users', 10, '{\"page\":\"product.php\"}', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 12:50:21'),
(89, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 12:52:31'),
(90, 3, 'login_success', 'users', 3, '{\"page\":\"product.php\"}', '124.104.183.123', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 15:12:50'),
(91, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 02:48:03'),
(92, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 02:56:26'),
(93, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 03:01:03'),
(94, 15, 'otp_resent_success', 'otp_codes', 17, '{\"email\":\"gnc.isaacjedm@gmail.com\"}', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 03:03:32'),
(95, 15, 'otp_resent_success', 'otp_codes', 18, '{\"email\":\"gnc.isaacjedm@gmail.com\"}', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 03:11:45'),
(96, 15, 'otp_resent_success', 'otp_codes', 19, '{\"email\":\"gnc.isaacjedm@gmail.com\"}', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 03:15:35'),
(97, 15, 'otp_resent_success', 'otp_codes', 20, '{\"email\":\"gnc.isaacjedm@gmail.com\"}', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 03:21:10'),
(98, 15, 'otp_resent_success', 'otp_codes', 21, '{\"email\":\"gnc.isaacjedm@gmail.com\"}', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 03:23:24'),
(99, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 03:42:23'),
(100, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 03:46:16'),
(101, 3, 'login_success', 'users', 3, '[]', '161.49.193.176', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-04 13:25:38'),
(102, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-05 03:14:59'),
(103, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-06 06:24:51'),
(104, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-06 09:46:42'),
(105, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-06 09:50:49'),
(106, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-06 10:15:20'),
(107, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-08 03:24:04'),
(108, 3, 'login_success', 'users', 3, '[]', '124.105.39.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-08 11:57:22'),
(109, 3, 'login_success', 'users', 3, '{\"page\":\"product.php\"}', '2a09:bac5:4fab:dc::16:211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-08 17:34:30'),
(110, 3, 'login_success', 'users', 3, '[]', '119.92.236.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-11 03:48:40'),
(111, 3, 'login_success', 'users', 3, '[]', '119.92.236.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-11 05:42:32'),
(112, 3, 'login_success', 'users', 3, '[]', '119.92.236.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-11 06:39:11'),
(113, 3, 'login_success', 'users', 3, '[]', '119.92.236.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-11 07:04:37');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `action` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `admin_id`, `admin_name`, `action`, `description`, `table_name`, `record_id`, `created_at`) VALUES
(1, 3, 'Isaac Jed Macaraeg', 'order_confirm', 'Confirmed order #1 for customer: Franz Luis Beltran', 'cart', 1, '2025-11-20 03:05:24'),
(2, 3, 'Isaac Jed Macaraeg', 'order_processing', 'Order #1 is now being prepared for customer: Franz Luis Beltran', 'cart', 1, '2025-11-20 03:05:36'),
(3, 3, 'Isaac Jed Macaraeg', 'order_out_for_delivery', 'Order #1 is out for delivery for customer: Franz Luis Beltran', 'cart', 1, '2025-11-20 03:05:41'),
(4, 3, 'Isaac Jed Macaraeg', 'order_complete', 'Marked order #1 as completed for customer: Franz Luis Beltran - Amount: ₱700.00', 'cart', 1, '2025-11-20 03:05:45'),
(5, 3, 'Isaac Jed Macaraeg', 'order_confirm', 'Confirmed order #2 for customer: Franz Luis Beltran', 'cart', 2, '2025-11-20 05:23:38'),
(6, 3, 'Isaac Jed Macaraeg', 'order_processing', 'Order #2 is now being prepared for customer: Franz Luis Beltran', 'cart', 2, '2025-11-20 05:24:26'),
(7, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Hibiscus Iced Tea (ID: 47)', 'products', 47, '2025-11-20 05:35:57'),
(8, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Matcha (ID: 50)', 'products', 50, '2025-11-21 09:23:30'),
(9, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Espresso (ID: 49)', 'products', 49, '2025-11-21 09:23:33'),
(10, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Brewed (ID: 48)', 'products', 48, '2025-11-21 09:23:38'),
(11, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Hibiscus Iced Tea (ID: 47)', 'products', 47, '2025-11-21 09:23:41'),
(12, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Tonkatsu (ID: 46)', 'products', 46, '2025-11-21 09:23:43'),
(13, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Carbonara (ID: 45)', 'products', 45, '2025-11-21 09:23:44'),
(14, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Kani Salad (ID: 44)', 'products', 44, '2025-11-21 09:23:46'),
(15, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Nachos (ID: 43)', 'products', 43, '2025-11-21 09:23:53'),
(16, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Iced Americano', 'products', 51, '2025-11-21 09:26:10'),
(17, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Iced Americano', 'products', 52, '2025-11-21 09:26:15'),
(18, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Iced Americano (ID: 52)', 'products', 52, '2025-11-21 09:26:30'),
(19, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Vietnamese Coffee', 'products', 53, '2025-11-21 09:27:51'),
(20, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Carbonara', 'products', 54, '2025-11-21 09:28:46'),
(21, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Waffle', 'products', 55, '2025-11-21 09:29:38'),
(22, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Charlie Chan', 'products', 56, '2025-11-21 09:30:33'),
(23, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pizza', 'products', 57, '2025-11-21 09:31:19'),
(24, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Muffins', 'products', 58, '2025-11-21 09:32:02'),
(25, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Italian Pasta Puttanesca', 'products', 59, '2025-11-21 09:32:56'),
(26, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Brownie a La Mode', 'products', 60, '2025-11-21 09:33:55'),
(27, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Cookies', 'products', 61, '2025-11-21 09:34:55'),
(28, 3, 'Isaac Jed Macaraeg', 'order_out_for_delivery', 'Order #2 is out for delivery for customer: Franz Luis Beltran', 'cart', 2, '2025-11-23 09:49:38'),
(29, 3, 'Isaac Jed Macaraeg', 'user_role_change', 'Changed role of user \'franzkhun\' from user to admin', 'users', 2, '2025-11-24 17:12:24'),
(30, 3, 'Isaac Jed Macaraeg', 'user_role_change', 'Changed role of user \'franzkhun\' from admin to user', 'users', 2, '2025-11-24 17:12:28'),
(31, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Brownie a La Mode (ID: 60)', 'products', 60, '2025-11-25 14:30:37'),
(32, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Brownie a La Mode (ID: 60)', 'products', 60, '2025-11-25 14:30:46'),
(33, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Brownie a La Mode (ID: 60)', 'products', 60, '2025-11-25 14:30:54'),
(34, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Brownie a La Mode (ID: 60)', 'products', 60, '2025-11-25 14:31:00'),
(35, 3, 'Isaac Jed Macaraeg', 'inquiry_response', 'Replied to inquiry #1 from Jeremiah Valencia. Status: responded', 'inquiries', 1, '2025-11-26 14:33:56'),
(36, 3, 'Isaac Jed Macaraeg', 'inquiry_response', 'Replied to inquiry #1 from Jeremiah Valencia. Status: responded', 'inquiries', 1, '2025-11-26 14:36:46'),
(37, 3, 'Isaac Jed Macaraeg', 'inquiry_response', 'Replied to inquiry #1 from Jeremiah Valencia. Status: responded', 'inquiries', 1, '2025-11-26 14:38:44'),
(38, 3, 'Isaac Jed Macaraeg', 'inquiry_response', 'Replied to inquiry #2 from Vincent paul Pena. Status: responded', 'inquiries', 2, '2025-11-26 15:26:15'),
(39, 3, 'Isaac Jed Macaraeg', 'inquiry_response', 'Replied to inquiry #3 from Franz Beltran. Status: responded', 'inquiries', 3, '2025-11-26 15:27:21'),
(40, 3, 'Isaac Jed Macaraeg', 'inquiry_response', 'Replied to inquiry #5 from Jann Kyle Refrado. Status: responded', 'inquiries', 5, '2025-11-26 15:50:59'),
(41, 3, 'Isaac Jed Macaraeg', 'order_confirm', 'Confirmed order #8 for customer: Franz Luis Beltran', 'cart', 8, '2025-11-26 19:48:02'),
(42, 3, 'Isaac Jed Macaraeg', 'order_processing', 'Order #8 is now being prepared for customer: Franz Luis Beltran', 'cart', 8, '2025-11-26 19:48:08'),
(43, 3, 'Isaac Jed Macaraeg', 'order_out_for_delivery', 'Order #8 is out for delivery for customer: Franz Luis Beltran', 'cart', 8, '2025-11-26 19:48:12'),
(44, 3, 'Isaac Jed Macaraeg', 'order_complete', 'Marked order #8 as completed for customer: Franz Luis Beltran - Amount: ₱1,040.00', 'cart', 8, '2025-11-26 19:48:17'),
(45, 3, 'Isaac Jed Macaraeg', 'user_delete', 'Moved user to recycle bin: Keycm (ID: 6)', 'users', 6, '2025-11-27 04:04:31'),
(46, 3, 'Isaac Jed Macaraeg', 'user_delete', 'Moved user to recycle bin: Vincent21 (ID: 1)', 'users', 1, '2025-11-27 04:04:36'),
(47, 3, 'Isaac Jed Macaraeg', 'user_restore', 'Restored user: Keycm (ID: 0). User must reset password.', 'users', 0, '2025-11-27 04:04:48'),
(48, 3, 'Isaac Jed Macaraeg', 'product_restore', 'Restored product: Hibiscus Iced Tea (New ID: 62)', 'products', 62, '2025-11-27 04:11:09'),
(49, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Iced Americano', 'recently_deleted_products', 9, '2025-12-03 03:38:02'),
(50, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Nachos', 'recently_deleted_products', 8, '2025-12-03 03:38:05'),
(51, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Kani Salad', 'recently_deleted_products', 7, '2025-12-03 03:38:08'),
(52, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Carbonara', 'recently_deleted_products', 6, '2025-12-03 03:38:10'),
(53, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Tonkatsu', 'recently_deleted_products', 5, '2025-12-03 03:38:11'),
(54, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Brewed', 'recently_deleted_products', 3, '2025-12-03 03:38:13'),
(55, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Espresso', 'recently_deleted_products', 2, '2025-12-03 03:38:15'),
(56, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Matcha', 'recently_deleted_products', 1, '2025-12-03 03:38:16'),
(57, 3, 'Isaac Jed Macaraeg', 'user_delete_permanent', 'Permanently deleted user: Vincent21 (Original ID: 0)', 'recently_deleted_users', 2, '2025-12-03 03:38:21'),
(58, 3, 'Isaac Jed Macaraeg', 'inquiry_response', 'Replied to inquiry #13 from Sample Name. Status: responded', 'inquiries', 13, '2025-12-29 06:45:02'),
(59, 3, 'Isaac Jed Macaraeg', 'inquiry_response', 'Replied to inquiry #5 from Jann Kyle Refrado. Status: responded', 'inquiries', 5, '2026-02-03 02:47:52'),
(60, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Hibiscus Iced Tea (ID: 62)', 'products', 62, '2026-02-03 05:24:43'),
(61, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Cookies (ID: 61)', 'products', 61, '2026-02-03 05:24:45'),
(62, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Brownie a La Mode (ID: 60)', 'products', 60, '2026-02-03 05:24:47'),
(63, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Italian Pasta Puttanesca (ID: 59)', 'products', 59, '2026-02-03 05:24:51'),
(64, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Muffins (ID: 58)', 'products', 58, '2026-02-03 05:24:54'),
(65, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Pizza (ID: 57)', 'products', 57, '2026-02-03 05:25:09'),
(66, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Charlie Chan (ID: 56)', 'products', 56, '2026-02-03 05:25:14'),
(67, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Waffle (ID: 55)', 'products', 55, '2026-02-03 05:25:15'),
(68, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Carbonara (ID: 54)', 'products', 54, '2026-02-03 05:25:18'),
(69, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Vietnamese Coffee (ID: 53)', 'products', 53, '2026-02-03 05:25:19'),
(70, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Iced Americano (ID: 51)', 'products', 51, '2026-02-03 05:25:19'),
(71, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Corned Beef', 'products', 63, '2026-02-03 05:27:07'),
(72, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Bacon And Egg', 'products', 64, '2026-02-03 05:27:51'),
(73, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Chicken Nuggets', 'products', 65, '2026-02-03 05:28:09'),
(74, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Spam', 'products', 66, '2026-02-03 05:28:24'),
(75, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Longganisa', 'products', 67, '2026-02-03 05:28:42'),
(76, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Tocino', 'products', 68, '2026-02-03 05:29:02'),
(77, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Beef tapa', 'products', 69, '2026-02-03 05:29:21'),
(78, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Hungarian Sausage', 'products', 70, '2026-02-03 05:29:43'),
(79, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pork Chop', 'products', 71, '2026-02-03 05:30:02'),
(80, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Bagnet', 'products', 72, '2026-02-03 05:30:17'),
(81, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: All Cheese Pizza', 'products', 73, '2026-02-03 05:30:54'),
(82, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pepperoni', 'products', 74, '2026-02-03 05:31:13'),
(83, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Pepperoni (ID: 74)', 'products', 74, '2026-02-03 05:31:38'),
(84, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pepperoni Pizza', 'products', 75, '2026-02-03 05:32:02'),
(85, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pepperoni Pizza', 'products', 76, '2026-02-03 05:32:42'),
(86, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Pepperoni Pizza (ID: 76)', 'products', 76, '2026-02-03 05:32:55'),
(87, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Supreme Pizza', 'products', 77, '2026-02-03 05:33:26'),
(88, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Supreme Pizza', 'products', 78, '2026-02-03 05:33:40'),
(89, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Supreme Pizza (ID: 78)', 'products', 78, '2026-02-03 05:33:50'),
(90, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Supreme Pizza (ID: 77)', 'products', 77, '2026-02-03 05:34:24'),
(91, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Pepperoni Pizza (ID: 75)', 'products', 75, '2026-02-03 05:34:28'),
(92, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: All Cheese Pizza (ID: 73)', 'products', 73, '2026-02-03 05:34:31'),
(93, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Bagnet (ID: 72)', 'products', 72, '2026-02-03 05:36:29'),
(94, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Pork Chop (ID: 71)', 'products', 71, '2026-02-03 06:09:27'),
(95, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Hungarian Sausage (ID: 70)', 'products', 70, '2026-02-03 06:09:52'),
(96, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Beef tapa (ID: 69)', 'products', 69, '2026-02-03 06:10:16'),
(97, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Tocino (ID: 68)', 'products', 68, '2026-02-03 06:10:20'),
(98, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Longganisa (ID: 67)', 'products', 67, '2026-02-03 06:10:25'),
(99, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Spam (ID: 66)', 'products', 66, '2026-02-03 06:10:33'),
(100, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Chicken Nuggets (ID: 65)', 'products', 65, '2026-02-03 06:10:41'),
(101, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Bacon And Egg (ID: 64)', 'products', 64, '2026-02-03 06:10:46'),
(102, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Corned Beef (ID: 63)', 'products', 63, '2026-02-03 06:10:50'),
(103, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Corned Beef', 'recently_deleted_products', 36, '2026-02-03 06:11:03'),
(104, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Bacon And Egg', 'recently_deleted_products', 35, '2026-02-03 06:11:12'),
(105, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Chicken Nuggets', 'recently_deleted_products', 34, '2026-02-03 06:11:21'),
(106, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Spam', 'recently_deleted_products', 33, '2026-02-03 06:11:32'),
(107, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Longganisa', 'recently_deleted_products', 32, '2026-02-03 06:11:50'),
(108, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Tocino', 'recently_deleted_products', 31, '2026-02-03 06:11:57'),
(109, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Beef tapa', 'recently_deleted_products', 30, '2026-02-03 06:12:06'),
(110, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Hungarian Sausage', 'recently_deleted_products', 29, '2026-02-03 06:12:14'),
(111, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Pork Chop', 'recently_deleted_products', 28, '2026-02-03 06:12:22'),
(112, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Bagnet', 'recently_deleted_products', 27, '2026-02-03 06:12:27'),
(113, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: All Cheese Pizza', 'recently_deleted_products', 26, '2026-02-03 06:12:30'),
(114, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Pepperoni Pizza', 'recently_deleted_products', 25, '2026-02-03 06:12:34'),
(115, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Supreme Pizza', 'recently_deleted_products', 24, '2026-02-03 06:12:38'),
(116, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Supreme Pizza', 'recently_deleted_products', 23, '2026-02-03 06:12:42'),
(117, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Pepperoni Pizza', 'recently_deleted_products', 22, '2026-02-03 06:12:51'),
(118, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Pepperoni', 'recently_deleted_products', 21, '2026-02-03 06:12:58'),
(119, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Vietnamese Coffee', 'recently_deleted_products', 19, '2026-02-03 06:13:05'),
(120, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Iced Americano', 'recently_deleted_products', 20, '2026-02-03 06:13:10'),
(121, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Carbonara', 'recently_deleted_products', 18, '2026-02-03 06:13:16'),
(122, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Waffle', 'recently_deleted_products', 17, '2026-02-03 06:13:25'),
(123, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Charlie Chan', 'recently_deleted_products', 16, '2026-02-03 06:13:32'),
(124, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Pizza', 'recently_deleted_products', 15, '2026-02-03 06:13:37'),
(125, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Muffins', 'recently_deleted_products', 14, '2026-02-03 06:13:43'),
(126, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Italian Pasta Puttanesca', 'recently_deleted_products', 13, '2026-02-03 06:13:51'),
(127, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Brownie a La Mode', 'recently_deleted_products', 12, '2026-02-03 06:13:57'),
(128, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Cookies', 'recently_deleted_products', 11, '2026-02-03 06:14:02'),
(129, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Hibiscus Iced Tea', 'recently_deleted_products', 10, '2026-02-03 06:14:07'),
(130, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Corned Beef', 'products', 79, '2026-02-03 06:28:17'),
(131, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Bacon and Egg', 'products', 80, '2026-02-03 06:28:44'),
(132, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Chicken Nuggets', 'products', 81, '2026-02-03 06:29:11'),
(133, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Spam', 'products', 82, '2026-02-03 06:29:29'),
(134, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Longganisa', 'products', 83, '2026-02-03 06:29:50'),
(135, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Tocino', 'products', 84, '2026-02-03 06:30:06'),
(136, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Beef Tapa', 'products', 85, '2026-02-03 06:30:30'),
(137, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Hungarian Sausage', 'products', 86, '2026-02-03 06:30:58'),
(138, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pork Chop', 'products', 87, '2026-02-03 06:31:13'),
(139, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Bagnet', 'products', 88, '2026-02-03 06:31:27'),
(140, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: All Cheese Pizza', 'products', 89, '2026-02-03 06:31:55'),
(141, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pepperoni Pizza', 'products', 90, '2026-02-03 06:32:21'),
(142, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Supreme Pizza', 'products', 91, '2026-02-03 06:32:47'),
(143, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Aligue Pizza', 'products', 92, '2026-02-03 06:33:09'),
(144, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Seafood Pesto Pizza', 'products', 93, '2026-02-03 06:33:36'),
(145, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Brewed', 'products', 94, '2026-02-03 06:35:02'),
(146, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Americano', 'products', 95, '2026-02-03 06:35:32'),
(147, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Cafe Latte', 'products', 96, '2026-02-03 06:35:54'),
(148, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Cafe Mocha', 'products', 97, '2026-02-03 06:36:46'),
(149, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Cappucino', 'products', 98, '2026-02-03 06:37:24'),
(150, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Macchiato', 'products', 99, '2026-02-03 06:37:42'),
(151, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Espresso', 'products', 100, '2026-02-03 06:38:00'),
(152, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Ristretto', 'products', 101, '2026-02-03 06:38:15'),
(153, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Iced Americano', 'products', 102, '2026-02-03 06:38:47'),
(154, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Iced Matcha', 'products', 103, '2026-02-03 06:39:12'),
(155, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Iced Latte', 'products', 104, '2026-02-03 06:39:26'),
(156, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Iced White Mocha', 'products', 105, '2026-02-03 06:39:46'),
(157, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Iced Caramel', 'products', 106, '2026-02-03 06:40:02'),
(158, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Iced Mocha', 'products', 107, '2026-02-03 06:40:28'),
(159, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Iced Spanish Latte', 'products', 108, '2026-02-03 06:41:11'),
(160, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Caramel Latte', 'products', 109, '2026-02-03 06:42:01'),
(161, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pecan Praline Latte', 'products', 110, '2026-02-03 06:42:30'),
(162, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Roasted Almond Matcha', 'products', 111, '2026-02-03 06:42:51'),
(163, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Vanilla Latte', 'products', 112, '2026-02-03 06:43:11'),
(164, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Choco Macadamia Latte', 'products', 113, '2026-02-03 06:43:32'),
(165, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: White Choco Mocha', 'products', 114, '2026-02-03 06:43:54'),
(166, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Vietnamese Coffee', 'products', 115, '2026-02-03 06:44:31'),
(167, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Java Chip', 'products', 116, '2026-02-03 06:45:37'),
(168, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Dark Mocha', 'products', 117, '2026-02-03 06:45:54'),
(169, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Oreo N\' Cream', 'products', 118, '2026-02-03 06:46:51'),
(170, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Roasted Almond Matcha', 'products', 119, '2026-02-03 06:47:18'),
(171, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Strawberry Matcha', 'products', 120, '2026-02-03 06:47:55'),
(172, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Biscoff Freddo', 'products', 121, '2026-02-03 06:48:12'),
(173, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Nutella Freddo', 'products', 122, '2026-02-03 06:48:28'),
(174, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Coffee Jelly', 'products', 123, '2026-02-03 06:48:44'),
(175, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: English Breakfast', 'products', 124, '2026-02-03 06:50:06'),
(176, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Earl Grey Tea', 'products', 125, '2026-02-03 06:50:38'),
(177, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Green Tea & Mint', 'products', 126, '2026-02-03 06:51:17'),
(178, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pure Pepper Mint', 'products', 127, '2026-02-03 06:51:35'),
(179, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Hibiscus', 'products', 128, '2026-02-03 06:51:55'),
(180, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Butterfly Pea', 'products', 129, '2026-02-03 06:52:57'),
(181, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Passion Fruit', 'products', 130, '2026-02-03 06:53:24'),
(182, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Blueberry', 'products', 131, '2026-02-03 06:53:41'),
(183, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Strawberry', 'products', 132, '2026-02-03 06:53:55'),
(184, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pomegranate', 'products', 133, '2026-02-03 06:54:17'),
(185, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pomegranate', 'products', 134, '2026-02-03 06:57:38'),
(186, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Espresso (ID: 100)', 'products', 100, '2026-02-03 06:57:56'),
(187, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Espresso (ID: 100)', 'products', 100, '2026-02-03 06:58:12'),
(188, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Brewed (ID: 94)', 'products', 94, '2026-02-03 06:58:24'),
(189, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Brewed (ID: 94)', 'products', 94, '2026-02-03 06:58:36'),
(190, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Brewed', 'products', 135, '2026-02-03 06:59:47'),
(191, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Brewed', 'products', 136, '2026-02-03 07:00:05'),
(192, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Brewed (ID: 136)', 'products', 136, '2026-02-03 07:00:08'),
(193, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Pomegranate (ID: 134)', 'products', 134, '2026-02-03 07:00:22'),
(194, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Pomegranate (ID: 134)', 'products', 134, '2026-02-03 07:01:06'),
(195, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Pomegranate (ID: 134)', 'products', 134, '2026-02-03 07:01:12'),
(196, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Brewed (ID: 135)', 'products', 135, '2026-02-03 07:01:46'),
(197, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Brewed (ID: 135)', 'products', 135, '2026-02-03 07:01:57'),
(198, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Brewed (ID: 94)', 'products', 94, '2026-02-03 07:02:01'),
(199, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Espresso (ID: 100)', 'products', 100, '2026-02-03 07:02:19'),
(200, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Americano (ID: 95)', 'products', 95, '2026-02-03 07:02:32'),
(201, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Cafe Latte (ID: 96)', 'products', 96, '2026-02-03 07:02:40'),
(202, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Cafe Mocha (ID: 97)', 'products', 97, '2026-02-03 07:02:48'),
(203, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Cappucino (ID: 98)', 'products', 98, '2026-02-03 07:02:53'),
(204, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Ristretto (ID: 101)', 'products', 101, '2026-02-03 07:03:00'),
(205, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Macchiato (ID: 99)', 'products', 99, '2026-02-03 07:03:07'),
(206, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Brewed', 'products', 137, '2026-02-03 07:03:36'),
(207, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Brewed (ID: 137)', 'products', 137, '2026-02-03 07:03:45'),
(208, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Pomegranate (ID: 133)', 'products', 133, '2026-02-03 07:04:35'),
(209, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Brewed', 'products', 138, '2026-02-03 07:04:56'),
(210, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Brewed', 'products', 139, '2026-02-03 07:05:04'),
(211, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Brewed', 'products', 140, '2026-02-03 07:05:07'),
(212, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Brewed (ID: 140)', 'products', 140, '2026-02-03 07:05:11'),
(213, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Brewed (ID: 139)', 'products', 139, '2026-02-03 07:05:12'),
(214, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Brewed (ID: 138)', 'products', 138, '2026-02-03 07:05:15'),
(215, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Brewed', 'recently_deleted_products', 52, '2026-02-03 07:05:31'),
(216, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Brewed', 'recently_deleted_products', 51, '2026-02-03 07:05:35'),
(217, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Brewed', 'recently_deleted_products', 50, '2026-02-03 07:05:39'),
(218, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Pomegranate', 'recently_deleted_products', 49, '2026-02-03 07:05:41'),
(219, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Brewed', 'recently_deleted_products', 48, '2026-02-03 07:05:44'),
(220, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Macchiato', 'recently_deleted_products', 47, '2026-02-03 07:05:48'),
(221, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Ristretto', 'recently_deleted_products', 46, '2026-02-03 07:05:51'),
(222, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Cappucino', 'recently_deleted_products', 45, '2026-02-03 07:05:54'),
(223, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Cafe Mocha', 'recently_deleted_products', 44, '2026-02-03 07:05:56'),
(224, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Cafe Latte', 'recently_deleted_products', 43, '2026-02-03 07:05:58'),
(225, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Americano', 'recently_deleted_products', 42, '2026-02-03 07:06:01'),
(226, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Espresso', 'recently_deleted_products', 41, '2026-02-03 07:06:03'),
(227, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Brewed', 'recently_deleted_products', 40, '2026-02-03 07:06:07'),
(228, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Brewed', 'recently_deleted_products', 39, '2026-02-03 07:06:09'),
(229, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Pomegranate', 'recently_deleted_products', 38, '2026-02-03 07:06:12'),
(230, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Brewed', 'recently_deleted_products', 37, '2026-02-03 07:06:14'),
(231, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Brewed', 'products', 141, '2026-02-03 07:07:00'),
(232, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Brewed (ID: 141)', 'products', 141, '2026-02-03 07:07:08'),
(233, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Brewed', 'products', 142, '2026-02-03 07:07:26'),
(234, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Brewed (ID: 142)', 'products', 142, '2026-02-03 07:07:42'),
(235, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Brewed', 'products', 143, '2026-02-03 07:08:11'),
(236, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: qweq', 'products', 144, '2026-02-03 07:08:28'),
(237, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: qweq (ID: 144)', 'products', 144, '2026-02-03 07:08:32'),
(238, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Brewed (ID: 143)', 'products', 143, '2026-02-03 07:08:36'),
(239, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Brewed', 'products', 145, '2026-02-03 07:09:50'),
(240, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Brewed (ID: 145)', 'products', 145, '2026-02-03 07:10:00'),
(241, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pomegranate', 'products', 146, '2026-02-03 07:12:58'),
(242, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pomegranate', 'products', 147, '2026-02-03 07:13:19'),
(243, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Pomegranate (ID: 147)', 'products', 147, '2026-02-03 07:13:29'),
(244, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Black Tea', 'products', 148, '2026-02-03 07:15:37'),
(245, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Hibiscus', 'products', 149, '2026-02-03 07:17:10'),
(246, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Butterfly Pea', 'products', 150, '2026-02-03 07:17:27'),
(247, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Hot Chocolate', 'products', 151, '2026-02-03 07:18:07'),
(248, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Hot White Chocolate ', 'products', 152, '2026-02-03 07:18:28'),
(249, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Hot Matcha', 'products', 153, '2026-02-03 07:18:47'),
(250, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Hot Batirol', 'products', 154, '2026-02-03 07:19:17'),
(251, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Brewed', 'products', 155, '2026-02-03 07:20:03'),
(252, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Americano', 'products', 156, '2026-02-03 07:20:21'),
(253, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Americano', 'products', 157, '2026-02-03 07:20:22'),
(254, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Cafe Latte ', 'products', 158, '2026-02-03 07:20:38'),
(255, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Cafe Mocha', 'products', 159, '2026-02-03 07:20:56'),
(256, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Cappucino', 'products', 160, '2026-02-03 07:21:28'),
(257, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Macchiato', 'products', 161, '2026-02-03 07:21:48'),
(258, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Espresso', 'products', 162, '2026-02-03 07:22:03'),
(259, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Ristretto', 'products', 163, '2026-02-03 07:22:14'),
(260, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Strawberry Yogurt', 'products', 164, '2026-02-03 07:24:46'),
(261, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Blueberry Yogurt', 'products', 165, '2026-02-03 07:25:09'),
(262, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Mango Yogurt', 'products', 166, '2026-02-03 07:25:32'),
(263, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Banana Smoothie', 'products', 167, '2026-02-03 07:25:50'),
(264, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Avocado Smoothie', 'products', 168, '2026-02-03 07:26:29'),
(265, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Carbonara', 'products', 169, '2026-02-03 07:27:27'),
(266, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pesto Seafood', 'products', 170, '2026-02-03 07:27:46'),
(267, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Aligue Pasta', 'products', 171, '2026-02-03 07:28:07'),
(268, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Aglio Olio', 'products', 172, '2026-02-03 07:28:24'),
(269, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Pasta Al Comino', 'products', 173, '2026-02-03 07:28:55'),
(270, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Puttanesca', 'products', 174, '2026-02-03 07:29:17'),
(271, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Charlie Chan', 'products', 175, '2026-02-03 07:29:32'),
(272, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Lasagna', 'products', 176, '2026-02-03 07:29:48'),
(273, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Tuna Sandwich', 'products', 177, '2026-02-03 07:30:07'),
(274, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: TLC Burger', 'products', 178, '2026-02-03 07:30:25'),
(275, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: TLC Burger', 'products', 179, '2026-02-03 07:30:26'),
(276, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Clubhouse', 'products', 180, '2026-02-03 07:30:43'),
(277, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Philly Cheese Steak', 'products', 181, '2026-02-03 07:31:10'),
(278, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Quesadillas', 'products', 182, '2026-02-03 07:31:35'),
(279, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Chicken Wrap', 'products', 183, '2026-02-03 07:31:53'),
(280, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Nachos', 'products', 184, '2026-02-03 07:32:20'),
(281, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Buffalo', 'products', 185, '2026-02-03 07:32:46'),
(282, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Garlic Parmesan', 'products', 186, '2026-02-03 07:33:10'),
(283, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Salted Egg', 'products', 187, '2026-02-03 07:33:26'),
(284, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Teriyaki', 'products', 188, '2026-02-03 07:33:38'),
(285, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Katsucurry', 'products', 189, '2026-02-03 07:33:56'),
(286, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Tonkatsu', 'products', 190, '2026-02-03 07:34:08'),
(287, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Strawberry Waffle', 'products', 191, '2026-02-03 07:34:41'),
(288, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Classic Honey Waffle', 'products', 192, '2026-02-03 07:34:56'),
(289, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Vanilla Caramel Waffle', 'products', 193, '2026-02-03 07:35:14'),
(290, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Biscoff Waffle', 'products', 194, '2026-02-03 07:35:34'),
(291, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Banana Nutella', 'products', 195, '2026-02-03 07:35:52'),
(292, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Bacon & Egg Waffle', 'products', 196, '2026-02-03 07:36:05'),
(293, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Churros ', 'products', 197, '2026-02-03 07:36:25'),
(294, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Mojos', 'products', 198, '2026-02-03 07:36:42'),
(295, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Twisted Fries', 'products', 199, '2026-02-03 07:37:04'),
(296, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Shoestring Fries', 'products', 200, '2026-02-03 07:37:36'),
(297, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Onion Rings', 'products', 201, '2026-02-03 07:37:52'),
(298, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Chicken Nuggets', 'products', 202, '2026-02-03 07:38:12'),
(299, 3, 'Isaac Jed Macaraeg', 'product_create', 'Created new product: Mozzarella Cheese Stick', 'products', 203, '2026-02-03 07:38:30'),
(300, 3, 'Isaac Jed Macaraeg', 'user_delete', 'Moved user to recycle bin: Dendi (ID: 4)', 'users', 4, '2026-02-03 08:34:40'),
(301, 3, 'Isaac Jed Macaraeg', 'user_delete', 'Moved user to recycle bin: kylerefrado@gmail.com (ID: 9)', 'users', 9, '2026-02-03 08:34:44'),
(302, 3, 'Isaac Jed Macaraeg', 'user_delete_permanent', 'Permanently deleted user: kylerefrado@gmail.com (Original ID: 0)', 'recently_deleted_users', 4, '2026-02-03 08:34:55'),
(303, 3, 'Isaac Jed Macaraeg', 'user_delete_permanent', 'Permanently deleted user: Dendi (Original ID: 0)', 'recently_deleted_users', 3, '2026-02-03 08:34:59'),
(304, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Beef Tapa (ID: 85)', 'products', 85, '2026-02-03 09:23:27'),
(305, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Beef Tapa (ID: 85)', 'products', 85, '2026-02-03 09:24:00'),
(306, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Beef Tapa (ID: 85)', 'products', 85, '2026-02-03 09:24:08'),
(307, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Corned Beef (ID: 79)', 'products', 79, '2026-02-03 09:24:20'),
(308, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Bagnet (ID: 88)', 'products', 88, '2026-02-03 09:24:36'),
(309, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Pork Chop (ID: 87)', 'products', 87, '2026-02-03 09:24:44'),
(310, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Hungarian Sausage (ID: 86)', 'products', 86, '2026-02-03 09:24:58'),
(311, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Bacon and Egg (ID: 80)', 'products', 80, '2026-02-03 09:25:09'),
(312, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Chicken Nuggets (ID: 81)', 'products', 81, '2026-02-03 09:25:23'),
(313, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Spam (ID: 82)', 'products', 82, '2026-02-03 09:25:30'),
(314, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Longganisa (ID: 83)', 'products', 83, '2026-02-03 09:25:38'),
(315, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Tocino (ID: 84)', 'products', 84, '2026-02-03 09:25:50'),
(316, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Corned Beef (ID: 79)', 'products', 79, '2026-02-03 11:59:17'),
(317, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Spam (ID: 82)', 'products', 82, '2026-02-03 11:59:27'),
(318, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Longganisa (ID: 83)', 'products', 83, '2026-02-03 12:02:55'),
(319, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Tocino (ID: 84)', 'products', 84, '2026-02-03 12:03:02'),
(320, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Philly Cheese Steak (ID: 181)', 'products', 181, '2026-02-03 12:05:10'),
(321, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Chicken Wrap (ID: 183)', 'products', 183, '2026-02-03 12:05:21'),
(322, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Pasta Al Comino (ID: 173)', 'products', 173, '2026-02-03 12:05:37'),
(323, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Churros  (ID: 197)', 'products', 197, '2026-02-03 12:07:19'),
(324, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Pesto Seafood (ID: 170)', 'products', 170, '2026-02-03 12:30:10'),
(325, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Seafood Pesto Pizza (ID: 93)', 'products', 93, '2026-02-03 12:31:05'),
(326, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Banana Smoothie (ID: 167)', 'products', 167, '2026-02-03 12:31:34'),
(327, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Banana Nutella (ID: 195)', 'products', 195, '2026-02-03 12:31:53'),
(328, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: TLC Burger (ID: 179)', 'products', 179, '2026-02-03 12:36:41'),
(329, 3, 'Isaac Jed Macaraeg', 'order_confirm', 'Confirmed order #22 for customer: Jann Kyle Refrado', 'cart', 22, '2026-02-03 12:41:04'),
(330, 3, 'Isaac Jed Macaraeg', 'order_processing', 'Order #22 is now being prepared for customer: Jann Kyle Refrado', 'cart', 22, '2026-02-03 12:41:17'),
(331, 3, 'Isaac Jed Macaraeg', 'order_out_for_delivery', 'Order #22 is out for delivery for customer: Jann Kyle Refrado', 'cart', 22, '2026-02-03 12:42:06'),
(332, 3, 'Isaac Jed Macaraeg', 'order_complete', 'Marked order #22 as completed for customer: Jann Kyle Refrado - Amount: ₱450.00', 'cart', 22, '2026-02-03 12:42:13'),
(333, 3, 'Isaac Jed Macaraeg', 'user_delete', 'Moved user to recycle bin: Josephlintag (ID: 5)', 'users', 5, '2026-02-03 12:43:38'),
(334, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: TLC Burger', 'recently_deleted_products', 67, '2026-02-03 12:44:44'),
(335, 3, 'Isaac Jed Macaraeg', 'product_delete_permanent', 'Permanently deleted product: Churros ', 'recently_deleted_products', 66, '2026-02-03 12:44:50'),
(336, 3, 'Isaac Jed Macaraeg', 'inquiry_response', 'Replied to inquiry #3 from Franz Beltran. Status: responded', 'inquiries', 3, '2026-02-03 12:54:24'),
(337, 3, 'Isaac Jed Macaraeg', 'admin_created', 'Created new admin account: ms_gzelle (Role: super_admin)', 'users', 12, '2026-02-03 12:58:20'),
(338, 3, 'Isaac Jed Macaraeg', 'user_delete', 'Moved user to recycle bin: nagumooo (ID: 11)', 'users', 11, '2026-02-04 02:48:24'),
(339, 3, 'Isaac Jed Macaraeg', 'user_delete_permanent', 'Permanently deleted user: Josephlintag (Original ID: 0)', 'recently_deleted_users', 5, '2026-02-04 02:48:32'),
(340, 3, 'Isaac Jed Macaraeg', 'user_delete_permanent', 'Permanently deleted user: nagumooo (Original ID: 0)', 'recently_deleted_users', 6, '2026-02-04 02:48:36'),
(341, 3, 'Isaac Jed Macaraeg', 'user_delete', 'Moved user to recycle bin: nagumo000 (ID: 13)', 'users', 13, '2026-02-04 02:56:35'),
(342, 3, 'Isaac Jed Macaraeg', 'user_delete', 'Moved user to recycle bin: nagumo000 (ID: 14)', 'users', 14, '2026-02-04 03:01:11'),
(343, 3, 'Isaac Jed Macaraeg', 'user_delete', 'Moved user to recycle bin: Dendi (ID: 10)', 'users', 10, '2026-02-04 03:42:31'),
(344, 3, 'Isaac Jed Macaraeg', 'user_delete_permanent', 'Permanently deleted user: Dendi (Original ID: 0)', 'recently_deleted_users', 9, '2026-02-04 03:42:39'),
(345, 3, 'Isaac Jed Macaraeg', 'user_delete_permanent', 'Permanently deleted user: nagumo000 (Original ID: 0)', 'recently_deleted_users', 8, '2026-02-04 04:01:53'),
(346, 3, 'Isaac Jed Macaraeg', 'user_delete', 'Moved user to recycle bin: Dendi (ID: 16)', 'users', 16, '2026-02-04 04:42:10'),
(347, 3, 'Isaac Jed Macaraeg', 'update_hero', 'Updated Hero Section content', 'hero_section', 0, '2026-02-04 13:41:50'),
(348, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 1', 'hero_slides', 1, '2026-02-04 13:53:28'),
(349, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 2', 'hero_slides', 2, '2026-02-04 13:53:30'),
(350, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 3', 'hero_slides', 3, '2026-02-04 13:53:33'),
(351, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 4', 'hero_slides', 4, '2026-02-04 13:53:35'),
(352, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 4', 'hero_slides', 4, '2026-02-04 13:53:51'),
(353, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Added New Slide', 'hero_slides', 0, '2026-02-04 13:53:51'),
(354, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 4', 'hero_slides', 4, '2026-02-04 13:54:07'),
(355, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Added New Slide', 'hero_slides', 0, '2026-02-04 13:54:07'),
(356, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 4', 'hero_slides', 4, '2026-02-04 13:54:33'),
(357, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Added New Slide', 'hero_slides', 0, '2026-02-04 13:54:33'),
(358, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Carbonara (ID: 169)', 'products', 169, '2026-02-05 06:20:55'),
(359, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Avocado Smoothie (ID: 168)', 'products', 168, '2026-02-05 06:23:17'),
(360, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Carbonara (ID: 169)', 'products', 169, '2026-02-05 08:02:04'),
(361, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Carbonara (ID: 169)', 'products', 169, '2026-02-05 08:03:45'),
(362, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Carbonara (ID: 169)', 'products', 169, '2026-02-05 08:04:43'),
(363, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Carbonara (ID: 169)', 'products', 169, '2026-02-05 08:37:09'),
(364, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Carbonara (ID: 169)', 'products', 169, '2026-02-05 09:25:20'),
(365, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Buffalo (ID: 185)', 'products', 185, '2026-02-05 09:37:56'),
(366, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Quesadillas (ID: 182)', 'products', 182, '2026-02-05 09:38:55'),
(367, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Onion Rings (ID: 201)', 'products', 201, '2026-02-05 09:39:28'),
(368, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Supreme Pizza (ID: 91)', 'products', 91, '2026-02-05 09:40:04'),
(369, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Bagnet (ID: 88)', 'products', 88, '2026-02-05 09:42:11'),
(370, 3, 'Isaac Jed Macaraeg', 'product_update', 'Updated product: Avocado Smoothie (ID: 168)', 'products', 168, '2026-02-05 09:45:07'),
(371, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Added New Slide', 'hero_slides', 0, '2026-02-05 10:07:36'),
(372, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Added New Slide', 'hero_slides', 0, '2026-02-05 10:16:12'),
(373, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 8', 'hero_slides', 8, '2026-02-05 10:16:27'),
(374, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 8', 'hero_slides', 8, '2026-02-05 10:16:36'),
(375, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Updated Slide', 'hero_slides', 9, '2026-02-05 10:17:03'),
(376, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Updated Slide', 'hero_slides', 5, '2026-02-05 10:17:10'),
(377, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Updated Slide', 'hero_slides', 6, '2026-02-05 10:17:36'),
(378, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Updated Slide', 'hero_slides', 6, '2026-02-05 10:17:40');
INSERT INTO `audit_logs` (`id`, `admin_id`, `admin_name`, `action`, `description`, `table_name`, `record_id`, `created_at`) VALUES
(379, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Updated Slide', 'hero_slides', 9, '2026-02-05 10:18:13'),
(380, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 6', 'hero_slides', 6, '2026-02-05 11:30:37'),
(381, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 6', 'hero_slides', 6, '2026-02-05 11:30:48'),
(382, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Updated Slide', 'hero_slides', 5, '2026-02-05 11:30:48'),
(383, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 6', 'hero_slides', 6, '2026-02-05 11:31:07'),
(384, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Updated Slide', 'hero_slides', 9, '2026-02-05 11:31:07'),
(385, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 6', 'hero_slides', 6, '2026-02-05 11:31:22'),
(386, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Updated Slide', 'hero_slides', 9, '2026-02-05 11:31:22'),
(387, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Updated Slide', 'hero_slides', 9, '2026-02-05 11:31:28'),
(388, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Updated Slide', 'hero_slides', 9, '2026-02-05 11:32:26'),
(389, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Updated Slide', 'hero_slides', 9, '2026-02-05 11:33:07'),
(390, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 9', 'hero_slides', 9, '2026-02-05 11:33:13'),
(391, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 9', 'hero_slides', 9, '2026-02-05 11:33:36'),
(392, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Added New Slide', 'hero_slides', 0, '2026-02-05 11:33:36'),
(393, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 9', 'hero_slides', 9, '2026-02-05 11:33:50'),
(394, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Updated Slide', 'hero_slides', 7, '2026-02-05 11:33:50'),
(395, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 9', 'hero_slides', 9, '2026-02-05 11:33:57'),
(396, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Updated Slide', 'hero_slides', 5, '2026-02-05 11:33:57'),
(397, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 9', 'hero_slides', 9, '2026-02-05 11:35:19'),
(398, 3, 'Isaac Jed Macaraeg', 'manage_slide', 'Updated Slide', 'hero_slides', 7, '2026-02-05 11:35:19'),
(399, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 7', 'hero_slides', 7, '2026-02-06 06:25:05'),
(400, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 5', 'hero_slides', 5, '2026-02-06 06:25:08'),
(401, 3, 'Isaac Jed Macaraeg', 'delete_slide', 'Deleted hero slide ID: 10', 'hero_slides', 10, '2026-02-06 06:25:12'),
(402, 3, 'Isaac Jed Macaraeg', 'product_delete', 'Deleted product: Mozzarella Cheese Stick (ID: 203)', 'products', 203, '2026-02-06 10:46:47');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `cart` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`cart`)),
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `cancel_reason` varchar(255) DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hero_section`
--

CREATE TABLE `hero_section` (
  `id` int(11) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `subtext` text NOT NULL,
  `button_text` varchar(50) NOT NULL,
  `button_link` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hero_section`
--

INSERT INTO `hero_section` (`id`, `heading`, `subtext`, `button_text`, `button_link`, `image_path`) VALUES
(1, 'Helllo', 'Welcome to Cafe Emmanuel. Enjoy our premium coffee and pastries.', 'Order Now', 'Orders.php', 'uploads/1770212510_img3.jpg'),
(2, 'Welcome to Cafe Emmanuel', 'Roasting with Art. Taste the difference.', 'View Menu', 'product.php', 'CSS/images/hero_default.png');

-- --------------------------------------------------------

--
-- Table structure for table `hero_slides`
--

CREATE TABLE `hero_slides` (
  `id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `type` enum('image','video') NOT NULL DEFAULT 'image',
  `heading` varchar(255) DEFAULT NULL,
  `subtext` text DEFAULT NULL,
  `button_text` varchar(50) DEFAULT 'View Menu',
  `button_link` varchar(255) DEFAULT 'product.php',
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','in_progress','responded','closed') DEFAULT 'new',
  `received_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_response` text DEFAULT NULL,
  `responded_by` int(11) DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `internal_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `first_name`, `last_name`, `email`, `message`, `status`, `received_at`, `admin_response`, `responded_by`, `responded_at`, `internal_notes`) VALUES
(2, 'Vincent paul', 'Pena', 'keycm109@gmail.com', 'dfgh', 'responded', '2025-11-26 15:22:29', 'Kiss muna', 3, '2025-11-26 15:26:12', ''),
(3, 'Franz', 'Beltran', 'gnc.isaacjedm@gmail.com', 'I want to reserve', 'responded', '2025-11-26 15:25:33', 'We have confirmed your reservation. We look forward to seeing you!', 3, '2026-02-03 12:54:21', 'Visit us'),
(14, 'Mike Diego Moreau\r\n', 'Mike Diego Moreau\r\n', 'mike@monkeydigital.co', 'Dear Webmaster, \r\n \r\nI wanted to reach out with something that could seriously improve your website’s visitor count. We work with a trusted ad network that allows us to deliver real, geo-targeted social ads traffic for just $10 per 10,000 visits. \r\n \r\nThis isn\'t junk clicks—it’s engaged traffic, tailored to your target country and niche. \r\n \r\nWhat you get: \r\n \r\n10,000+ genuine visitors for just $10 \r\nGeo-targeted traffic for your chosen location \r\nScalability available based on your needs \r\nProven to work—we even use this for our SEO clients! \r\n \r\nWant to give it a try? Check out the details here: \r\nhttps://www.monkeydigital.co/product/country-targeted-traffic/ \r\n \r\nOr connect instantly on WhatsApp: \r\nhttps://monkeydigital.co/whatsapp-us/ \r\n \r\nLooking forward to working with you! \r\n \r\nBest, \r\nMike Diego Moreau\r\n \r\nPhone/whatsapp: +1 (775) 314-7914', 'new', '2026-01-04 15:00:33', NULL, NULL, NULL, NULL),
(15, 'Mike Ralf Dubois\r\n', 'Mike Ralf Dubois\r\n', 'info@professionalseocleanup.com', 'Hi, \r\nWhile reviewing cafeemmanuel.com, we spotted toxic backlinks that could put your site at risk of a Google penalty. Especially that this Google SPAM update had a high impact in ranks. This is an easy and quick fix for you. Totally free of charge. No obligations. \r\n \r\nFix it now: \r\nhttps://www.professionalseocleanup.com/ \r\n \r\nNeed help or questions? Chat here: \r\nhttps://www.professionalseocleanup.com/whatsapp/ \r\n \r\nBest, \r\nMike Ralf Dubois\r\n \r\n+1 (855) 221-7591 \r\ninfo@professionalseocleanup.com', 'new', '2026-01-11 06:32:18', NULL, NULL, NULL, NULL),
(16, 'Olivier Gabriel Balzac', 'Olivier Gabriel Balzac', 'duckmenoffice11@gmail.com', 'Good day, \r\n \r\nMy name is Olivier Gabriel Balzac, a practicing lawyer from France. I previously contacted you regarding a transaction involving 13.5 million Euros, which was left by my late client before his unexpected demise. \r\n \r\nI am reaching out to you once more because, after examining your profile, I am thoroughly convinced that you are capable of managing this transaction effectively alongside me. \r\nIf you are interested, I would like to emphasize that after the transaction, 5% of the funds will be allocated to charitable organizations, while the remaining 95% will be divided equally between us, resulting in 47.5% for each party. \r\nThis transaction is entirely risk-free. Please respond to me at your earliest convenience to receive further details regarding the transaction. \r\nMy email: info@balzacavocate.com Sincerely, I look forward to your prompt response. \r\nBest regards. \r\nOlivier Gabriel Balzac, \r\nAttorney. \r\nPhone: +33 756 850 084 \r\nEmail: info@balzacavocate.com', 'new', '2026-01-23 09:10:39', NULL, NULL, NULL, NULL),
(17, 'Mike Gustavo Wilson\r\n', 'Mike Gustavo Wilson\r\n', 'info@strictlydigital.net', 'Greetings, \r\n \r\nHaving some collection of links redirecting to cafeemmanuel.com may result in zero worth or negative impact for your website. \r\n \r\nIt really makes no difference the number of inbound links you have, what matters is the amount of ranking terms those websites are optimized for. \r\n \r\nThat is the most important element. \r\nNot the overrated Domain Authority or Domain Rating. \r\nThat anyone can do these days. \r\nBUT the number of Google-ranked terms the websites that point to your site rank for. \r\nThat’s it. \r\n \r\nHave such links point to your website and you will ROCK! \r\n \r\nWe are offering this special offer here: \r\nhttps://www.strictlydigital.net/product/semrush-backlinks/ \r\n \r\nNeed more details, or want clarification, message us here: \r\nhttps://www.strictlydigital.net/whatsapp-us/ \r\n \r\nKind regards, \r\nMike Gustavo Wilson\r\n \r\nstrictlydigital.net \r\nPhone/WhatsApp: +1 (877) 566-3738', 'new', '2026-01-23 20:00:03', NULL, NULL, NULL, NULL),
(18, 'Ankit', 'S', 'info@bestaiseocompany.com', 'Hey team cafeemmanuel.com,\r\n\r\nHope your doing well!\r\n\r\nI just following your website and realized that despite having a good design; but it was not ranking high on any of the Search Engines (Google, Yahoo & Bing) for most of the keywords related to your business.\r\n\r\nWe can place your website on Google\'s 1st page.\r\n\r\n*  Top ranking on Google search!\r\n*  Improve website clicks and views!\r\n*  Increase Your Leads, clients & Revenue!\r\n\r\nInterested? Please provide your name, contact information, and email.\r\n\r\nBests Regards,\r\nAnkit\r\nBest AI SEO Company\r\nAccounts Manager\r\nwww.bestaiseocompany.com\r\nPhone No: +1 (949) 508-0277', 'new', '2026-01-27 07:20:09', NULL, NULL, NULL, NULL),
(19, 'Mike Rodrigo Van Dijk\r\n', 'Mike Rodrigo Van Dijk\r\n', 'mike@monkeydigital.co', 'Hi there, \r\n \r\nI wanted to check in with something that could seriously boost your website’s visitor count. We work with a trusted ad network that allows us to deliver authentic, geo-targeted social ads traffic for just $10 per 10,000 visits. \r\n \r\nThis isn\'t bot traffic—it’s actual users, tailored to your preferred location and niche. \r\n \r\nWhat you get: \r\n \r\n10,000+ genuine visitors for just $10 \r\nGeo-targeted traffic for your chosen location \r\nScalability available based on your needs \r\nTrusted by SEO experts—we even use this for our SEO clients! \r\n \r\nWant to give it a try? Check out the details here: \r\nhttps://www.monkeydigital.co/product/country-targeted-traffic/ \r\n \r\nOr chat with us on WhatsApp: \r\nhttps://monkeydigital.co/whatsapp-us/ \r\n \r\nLooking forward to helping you grow! \r\n \r\nBest, \r\nMike Rodrigo Van Dijk\r\n \r\nPhone/whatsapp: +1 (775) 314-7914', 'new', '2026-01-28 11:07:05', NULL, NULL, NULL, NULL),
(20, 'Mike Johan Dubois\r\n', 'Mike Johan Dubois\r\n', 'info@professionalseocleanup.com', 'Hi, \r\nWhile reviewing cafeemmanuel.com, we spotted toxic backlinks that could put your site at risk of a Google penalty. Especially that this Google SPAM update had a high impact in ranks. This is an easy and quick fix for you. Totally free of charge. No obligations. \r\n \r\nFix it now: \r\nhttps://www.professionalseocleanup.com/ \r\n \r\nNeed help or questions? Chat here: \r\nhttps://www.professionalseocleanup.com/whatsapp/ \r\n \r\nBest, \r\nMike Johan Dubois\r\n \r\n+1 (855) 221-7591 \r\ninfo@professionalseocleanup.com', 'new', '2026-02-04 13:14:48', NULL, NULL, NULL, NULL),
(21, 'Michalak Aleksandra', 'Michalak Aleksandra', 'aleksandramichalakalek51@gmail.com', 'Good day. \r\nMy name is Michalak Aleksandra, a Poland based business consultant. \r\nRunning a business means juggling a million things, and getting the funding you need shouldn\'t be another hurdle. We\'ve helped businesses to secure debt financing for growth, inventory, or operations, without the typical bank delays. \r\nTogether with our partners (investors), we offer a straightforward, transparent process with clear terms, designed to get you funded quickly so you can focus on your business. \r\nReady to explore our services? Please feel free to contact me directly by michalak.aleksandra@mail.com Let\'s make your business goals a reality, together. \r\nRegards, \r\nMichalak Aleksandra. \r\nEmail: michalak.aleksandra@mail.com', 'new', '2026-02-04 14:30:10', NULL, NULL, NULL, NULL),
(22, 'Allie', 'Shippee', 'shippee.allie@gmail.com', 'Hi,\r\n\r\nI am a senior web developer, highly skilled and with 10+ years of collective web design and development experience, I work in one of the best web development company.\r\n\r\nMy hourly rate is $8\r\n\r\nMy expertise includes:\r\n\r\nWebsite design - custom mockups and template designs\r\nWebsite design and development - theme development, backend customisation\r\nResponsive website - on all screen sizes and devices\r\nPlugins and Extensions Development\r\nWebsite speed optimisation and SEO on-page optimisation\r\nWebsite security\r\nWebsite migration, support and maintenance\r\nIf you have a question or requirement to discuss, I would love to help and further discuss it. Please email me at e.solus@gmail.com\r\n\r\nRegards,\r\nSachin\r\ne.solus@gmail.com', 'new', '2026-02-09 21:08:55', NULL, NULL, NULL, NULL),
(23, 'Ab', 'y', 'info@bestaiseocompany.com', 'Hey team cafeemmanuel.com,\r\n\r\nHope your doing well!\r\n\r\nI just following your website and realized that despite having a good design; but it’s not achieving competitive rankings for your target keywords.\r\n\r\nWe can place your website on Google\'s 1st page.\r\n\r\n*  Top ranking on Google search!\r\n*  Improve website clicks and views!\r\n*  Increase Your Leads, clients & Revenue!\r\n\r\nInterested? Please provide your name, contact information, and email.\r\n\r\nBests Regards,\r\nAby\r\nBest AI SEO Company\r\nAccounts Manager\r\nwww.bestaiseocompany.com\r\nPhone No: +1 (949) 508-0277', 'new', '2026-02-10 12:36:42', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `order_id`, `type`, `title`, `message`, `is_read`, `created_at`) VALUES
(1, 2, 1, 'order_confirmed', 'Order Confirmed', 'Your order #1 has been confirmed and will be prepared soon.', 1, '2025-11-20 03:05:24'),
(2, 2, 1, 'order_processing', 'Order is being prepared', 'Your order #1 is now being prepared.', 1, '2025-11-20 03:05:36'),
(3, 2, 1, 'order_out_for_delivery', 'Out for delivery', 'Your order #1 is now out for delivery.', 1, '2025-11-20 03:05:41'),
(4, 2, 1, 'order_completed', 'Order Completed!', 'Your order #1 has been completed. Thank you for your purchase!', 1, '2025-11-20 03:05:45'),
(5, 2, 2, 'order_confirmed', 'Order Confirmed', 'Your order #2 has been confirmed and will be prepared soon.', 1, '2025-11-20 05:23:38'),
(6, 2, 2, 'order_processing', 'Order is being prepared', 'Your order #2 is now being prepared.', 1, '2025-11-20 05:24:26'),
(7, 2, 2, 'order_out_for_delivery', 'Out for delivery', 'Your order #2 is now out for delivery.', 1, '2025-11-23 09:49:38'),
(8, 2, 8, 'order_confirmed', 'Order Confirmed', 'Your order #8 has been confirmed and will be prepared soon.', 1, '2025-11-26 19:48:02'),
(9, 2, 8, 'order_processing', 'Order is being prepared', 'Your order #8 is now being prepared.', 1, '2025-11-26 19:48:08'),
(10, 2, 8, 'order_out_for_delivery', 'Out for delivery', 'Your order #8 is now out for delivery.', 1, '2025-11-26 19:48:12'),
(11, 2, 8, 'order_completed', 'Order Completed!', 'Your order #8 has been completed. Thank you for your purchase!', 1, '2025-11-26 19:48:17'),
(12, 10, 22, 'order_confirmed', 'Order Confirmed', 'Your order #22 has been confirmed and will be prepared soon.', 0, '2026-02-03 12:41:04'),
(13, 10, 22, 'order_processing', 'Order is being prepared', 'Your order #22 is now being prepared.', 0, '2026-02-03 12:41:17'),
(14, 10, 22, 'order_out_for_delivery', 'Out for delivery', 'Your order #22 is now out for delivery.', 0, '2026-02-03 12:42:06'),
(15, 10, 22, 'order_completed', 'Order Completed!', 'Your order #22 has been completed. Thank you for your purchase!', 0, '2026-02-03 12:42:13');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Completed','Cancelled') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `fullname`, `contact`, `address`, `payment_method`, `total`, `created_at`, `status`) VALUES
(1, 2, 'Franz Luis Beltran', '09123456789', 'Sta.Catalina Lubao Pampanga, Lubao', 'COD', 1530.00, '2025-11-26 18:48:02', 'Pending'),
(2, 2, 'Franz Luis Beltran', '09123456789', 'Sta Catalina Lubao Pampanga, Lubao', 'COD', 405.00, '2025-11-26 18:59:35', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `attempts` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `user_id`, `code`, `expires_at`, `used_at`, `attempts`, `created_at`) VALUES
(1, 1, '194967', '2025-11-19 19:03:50', '2025-11-19 18:54:29', 0, '2025-11-19 18:53:50'),
(2, 2, '162727', '2025-11-19 19:46:05', '2025-11-19 19:36:51', 0, '2025-11-19 19:36:05'),
(3, 3, '322692', '2025-11-19 19:59:24', '2025-11-19 19:50:00', 0, '2025-11-19 19:49:24'),
(4, 2, '117254', '2025-11-20 02:27:08', '2025-11-20 02:18:14', 0, '2025-11-20 02:17:08'),
(5, 3, '868800', '2025-11-20 02:37:03', '2025-11-20 02:27:41', 0, '2025-11-20 02:27:03'),
(6, 5, '006384', '2025-11-21 05:17:40', '2025-11-21 05:08:00', 0, '2025-11-21 05:07:40'),
(7, 6, '140285', '2025-11-24 15:41:06', '2025-11-24 15:31:26', 0, '2025-11-24 15:31:06'),
(8, 9, '961958', '2026-02-03 08:32:03', NULL, 0, '2026-02-03 08:22:03'),
(9, 10, '696818', '2026-02-03 08:45:39', '2026-02-03 08:36:12', 0, '2026-02-03 08:35:39'),
(10, 10, '605800', '2026-02-03 08:46:50', '2026-02-03 08:37:18', 0, '2026-02-03 08:36:50'),
(11, 11, '358019', '2026-02-03 09:10:46', '2026-02-03 09:01:22', 0, '2026-02-03 09:00:46'),
(12, 11, '899628', '2026-02-03 09:17:44', '2026-02-03 09:08:24', 0, '2026-02-03 09:07:44'),
(13, 11, '360700', '2026-02-03 09:20:17', '2026-02-03 09:10:33', 0, '2026-02-03 09:10:17'),
(14, 13, '845425', '2026-02-04 03:00:14', '2026-02-04 02:50:39', 0, '2026-02-04 02:50:14'),
(15, 14, '587085', '2026-02-04 03:07:19', '2026-02-04 02:57:36', 0, '2026-02-04 02:57:19'),
(16, 15, '209977', '2026-02-04 03:11:56', '2026-02-04 03:02:19', 0, '2026-02-04 03:01:56'),
(17, 15, '196968', '2026-02-04 03:13:29', '2026-02-04 03:04:00', 0, '2026-02-04 03:03:29'),
(18, 15, '603334', '2026-02-04 03:21:42', '2026-02-04 03:12:01', 0, '2026-02-04 03:11:42'),
(19, 15, '644491', '2026-02-04 03:25:32', '2026-02-04 03:15:52', 0, '2026-02-04 03:15:32'),
(20, 15, '094234', '2026-02-04 03:31:07', '2026-02-04 03:21:28', 0, '2026-02-04 03:21:07'),
(21, 15, '368351', '2026-02-04 03:33:21', '2026-02-04 03:23:43', 0, '2026-02-04 03:23:21'),
(22, 16, '001414', '2026-02-04 03:53:17', '2026-02-04 03:44:00', 0, '2026-02-04 03:43:17');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reset_code` varchar(10) NOT NULL,
  `reset_method` enum('email','phone') NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `attempts` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `reset_code`, `reset_method`, `expires_at`, `used_at`, `attempts`, `created_at`) VALUES
(1, 3, '155279', 'phone', '2025-11-19 20:50:08', NULL, 0, '2025-11-19 20:40:08'),
(2, 3, '948926', 'email', '2025-11-19 20:50:33', NULL, 0, '2025-11-19 20:40:33'),
(3, 4, '394416', 'email', '2026-02-03 08:23:37', NULL, 0, '2026-02-03 08:13:37');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `rating`, `stock`, `category`) VALUES
(80, 'Bacon and Egg', 160.00, 'uploads/6981959cc13ba_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'All Day Breakfast'),
(81, 'Chicken Nuggets', 160.00, 'uploads/698195b7422a9_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'All Day Breakfast'),
(85, 'Beef Tapa', 170.00, 'uploads/69819606d619c_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'All Day Breakfast'),
(86, 'Hungarian Sausage', 170.00, 'uploads/6981962294591_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'All Day Breakfast'),
(87, 'Pork Chop', 190.00, 'uploads/69819631dc5ff_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'All Day Breakfast'),
(88, 'Bagnet', 190.00, 'uploads/698465f3eee12_bagnet.png', 5, 10, 'All Day Breakfast'),
(89, 'All Cheese Pizza', 320.00, 'uploads/6981965b2d520_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'pizza'),
(90, 'Pepperoni Pizza', 360.00, 'uploads/69819675ad597_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'pizza'),
(91, 'Supreme Pizza', 360.00, 'uploads/698465746ab44_supreme pizza.png', 5, 10, 'pizza'),
(92, 'Aligue Pizza', 340.00, 'uploads/698196a593042_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'pizza'),
(93, 'Seafood Pesto Pizza', 330.00, 'uploads/698196c01a582_553580834_1182486273793919_1185018289292646288_n.png', 5, 0, 'pizza'),
(102, 'Iced Americano', 130.00, 'uploads/698197f71c88f_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'over iced'),
(103, 'Iced Matcha', 150.00, 'uploads/698198106a703_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'over iced'),
(104, 'Iced Latte', 140.00, 'uploads/6981981e7ea05_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'over iced'),
(105, 'Iced White Mocha', 160.00, 'uploads/69819832cfdd2_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'over iced'),
(106, 'Iced Caramel', 160.00, 'uploads/698198426459e_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'over iced'),
(107, 'Iced Mocha', 160.00, 'uploads/6981985cf0085_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'over iced'),
(108, 'Iced Spanish Latte', 150.00, 'uploads/6981988778ba4_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'over iced'),
(109, 'Caramel Latte', 150.00, 'uploads/698198b9b15fb_553580834_1182486273793919_1185018289292646288_n.png', 5, 9, 'cafe artistry'),
(110, 'Pecan Praline Latte', 150.00, 'uploads/698198d6d0782_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'cafe artistry'),
(111, 'Roasted Almond Matcha', 150.00, 'uploads/698198eb33431_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'cafe artistry'),
(112, 'Vanilla Latte', 160.00, 'uploads/698198ff1a3e4_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'cafe artistry'),
(113, 'Choco Macadamia Latte', 170.00, 'uploads/69819914dcfbd_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'cafe artistry'),
(114, 'White Choco Mocha', 160.00, 'uploads/6981992ac7d28_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'cafe artistry'),
(115, 'Vietnamese Coffee', 160.00, 'uploads/6981994ff3deb_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'cafe artistry'),
(116, 'Java Chip', 185.00, 'uploads/698199916c8dd_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'freddo'),
(117, 'Dark Mocha', 170.00, 'uploads/698199a22c842_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'freddo'),
(118, 'Oreo N\' Cream', 175.00, 'uploads/698199db7c927_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'freddo'),
(119, 'Roasted Almond Matcha', 185.00, 'uploads/698199f6b3378_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'freddo'),
(120, 'Strawberry Matcha', 185.00, 'uploads/69819a1b11571_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'freddo'),
(121, 'Biscoff Freddo', 185.00, 'uploads/69819a2c2aaa8_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'freddo'),
(122, 'Nutella Freddo', 185.00, 'uploads/69819a3c8aa2d_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'freddo'),
(123, 'Coffee Jelly', 185.00, 'uploads/69819a4c54685_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'freddo'),
(124, 'English Breakfast', 130.00, 'uploads/69819a9e94f41_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'hot tea'),
(125, 'Earl Grey Tea', 130.00, 'uploads/69819abec5a04_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'hot tea'),
(126, 'Green Tea & Mint', 130.00, 'uploads/69819ae509ca9_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'hot tea'),
(127, 'Pure Pepper Mint', 130.00, 'uploads/69819af7247f8_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'hot tea'),
(128, 'Hibiscus', 140.00, 'uploads/69819b0bd9c96_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'hot tea'),
(129, 'Butterfly Pea', 140.00, 'uploads/69819b494b9da_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'hot tea'),
(130, 'Passion Fruit', 150.00, 'uploads/69819b64af796_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'refresher'),
(131, 'Blueberry', 150.00, 'uploads/69819b7553627_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'refresher'),
(132, 'Strawberry', 150.00, 'uploads/69819b838eb83_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'refresher'),
(146, 'Pomegranate', 150.00, 'uploads/69819ffae5369_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'refresher'),
(148, 'Black Tea', 140.00, 'uploads/6981a0997c3eb_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'iced tea'),
(149, 'Hibiscus', 140.00, 'uploads/6981a0f640c28_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'iced tea'),
(150, 'Butterfly Pea', 150.00, 'uploads/6981a107117c1_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'iced tea'),
(151, 'Hot Chocolate', 160.00, 'uploads/6981a12fc973f_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'hot choco'),
(152, 'Hot White Chocolate ', 160.00, 'uploads/6981a14481755_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'hot choco'),
(153, 'Hot Matcha', 160.00, 'uploads/6981a157c4f0b_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'hot choco'),
(154, 'Hot Batirol', 170.00, 'uploads/6981a1754d82e_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'hot choco'),
(155, 'Brewed', 120.00, 'uploads/6981a1a318acf_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'classico'),
(156, 'Americano', 140.00, 'uploads/6981a1b5b607b_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'classico'),
(157, 'Americano', 140.00, 'uploads/6981a1b652e2a_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'classico'),
(158, 'Cafe Latte ', 150.00, 'uploads/6981a1c67782b_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'classico'),
(159, 'Cafe Mocha', 160.00, 'uploads/6981a1d8a595c_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'classico'),
(160, 'Cappucino', 150.00, 'uploads/6981a1f826b8c_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'classico'),
(161, 'Macchiato', 120.00, 'uploads/6981a20ce6c0e_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'classico'),
(162, 'Espresso', 120.00, 'uploads/6981a21b16152_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'classico'),
(163, 'Ristretto', 120.00, 'uploads/6981a226e05ae_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'classico'),
(164, 'Strawberry Yogurt', 180.00, 'uploads/6981a2bede5e4_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'smoothies'),
(165, 'Blueberry Yogurt', 180.00, 'uploads/6981a2d509fb7_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'smoothies'),
(166, 'Mango Yogurt', 180.00, 'uploads/6981a2ec563a0_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'smoothies'),
(167, 'Banana Smoothie', 180.00, 'uploads/6981a2fe8293b_553580834_1182486273793919_1185018289292646288_n.png', 5, 0, 'smoothies'),
(168, 'Avocado Smoothie', 180.00, 'uploads/698466a3c81c1_avocado smoothie.png', 5, 10, 'smoothies'),
(169, 'Carbonara', 260.00, 'uploads/69846200f0cc5_carbonara.png', 5, 10, 'pasta'),
(170, 'Pesto Seafood', 250.00, 'uploads/6981a372ccff7_553580834_1182486273793919_1185018289292646288_n.png', 5, 0, 'pasta'),
(171, 'Aligue Pasta', 280.00, 'uploads/6981a387c272e_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'pasta'),
(172, 'Aglio Olio', 290.00, 'uploads/6981a398cd8f4_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'pasta'),
(174, 'Puttanesca', 260.00, 'uploads/6981a3cd52d68_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'pasta'),
(175, 'Charlie Chan', 290.00, 'uploads/6981a3dc4ff94_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'pasta'),
(176, 'Lasagna', 205.00, 'uploads/6981a3ecb48e4_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'pasta'),
(177, 'Tuna Sandwich', 200.00, 'uploads/6981a3ffe9e6e_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'sandwich'),
(178, 'TLC Burger', 260.00, 'uploads/6981a41123755_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'sandwich'),
(180, 'Clubhouse', 280.00, 'uploads/6981a423c1b9f_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'sandwich'),
(182, 'Quesadillas', 250.00, 'uploads/6984652f3ff85_quesadillas.png', 5, 10, 'mexican food'),
(184, 'Nachos', 260.00, 'uploads/6981a484d482b_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'mexican food'),
(185, 'Buffalo', 260.00, 'uploads/698464f4260a7_bufallo.png', 5, 9, 'ali di pollo'),
(186, 'Garlic Parmesan', 260.00, 'uploads/6981a4b6c3708_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'ali di pollo'),
(187, 'Salted Egg', 260.00, 'uploads/6981a4c61b391_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'ali di pollo'),
(188, 'Teriyaki', 260.00, 'uploads/6981a4d242e3f_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'ali di pollo'),
(189, 'Katsucurry', 280.00, 'uploads/6981a4e434371_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'katsu'),
(190, 'Tonkatsu', 250.00, 'uploads/6981a4f040e53_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'katsu'),
(191, 'Strawberry Waffle', 220.00, 'uploads/6981a5110b20e_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'waffle'),
(192, 'Classic Honey Waffle', 180.00, 'uploads/6981a52073c73_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'waffle'),
(193, 'Vanilla Caramel Waffle', 280.00, 'uploads/6981a532d5305_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'waffle'),
(194, 'Biscoff Waffle', 270.00, 'uploads/6981a546c205f_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'waffle'),
(195, 'Banana Nutella', 280.00, 'uploads/6981a55817301_553580834_1182486273793919_1185018289292646288_n.png', 5, 0, 'waffle'),
(196, 'Bacon & Egg Waffle', 280.00, 'uploads/6981a565c14a6_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'waffle'),
(198, 'Mojos', 170.00, 'uploads/6981a58a3d0de_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'antipasto'),
(199, 'Twisted Fries', 170.00, 'uploads/6981a5a0941ab_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'antipasto'),
(200, 'Shoestring Fries', 160.00, 'uploads/6981a5c01c375_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'antipasto'),
(201, 'Onion Rings', 160.00, 'uploads/69846550abf96_onion rings.png', 5, 10, 'antipasto'),
(202, 'Chicken Nuggets', 170.00, 'uploads/6981a5e3f40e9_553580834_1182486273793919_1185018289292646288_n.png', 5, 10, 'antipasto'),
(206, 'trizone', 160.00, 'uploads/1770793115_6916a311bfa4c_Iced Americano.jpg', 5, 7, 'antipasto');

-- --------------------------------------------------------

--
-- Table structure for table `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size_name` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recently_deleted`
--

CREATE TABLE `recently_deleted` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recently_deleted`
--

INSERT INTO `recently_deleted` (`id`, `name`, `price`, `image`, `rating`, `stock`, `category`) VALUES
(207, 'trizone', 160.00, 'logo.png', 5, 7, 'antipasto');

-- --------------------------------------------------------

--
-- Table structure for table `recently_deleted_products`
--

CREATE TABLE `recently_deleted_products` (
  `id` int(11) NOT NULL,
  `original_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `rating` int(11) DEFAULT 5,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recently_deleted_products`
--

INSERT INTO `recently_deleted_products` (`id`, `original_id`, `name`, `price`, `stock`, `image`, `category`, `rating`, `deleted_at`) VALUES
(68, 203, 'Mozzarella Cheese Stick', 220.00, 10, 'uploads/6981a5f6e600d_553580834_1182486273793919_1185018289292646288_n.png', 'antipasto', 5, '2026-02-06 10:46:47');

-- --------------------------------------------------------

--
-- Table structure for table `recently_deleted_users`
--

CREATE TABLE `recently_deleted_users` (
  `id` int(11) NOT NULL,
  `original_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revenue`
--

CREATE TABLE `revenue` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revenue`
--

INSERT INTO `revenue` (`id`, `order_id`, `amount`, `date_created`) VALUES
(1, 1, 700.00, '2025-11-20 03:05:45'),
(2, 8, 1040.00, '2025-11-26 19:48:17'),
(3, 22, 450.00, '2026-02-03 12:42:13');

-- --------------------------------------------------------

--
-- Table structure for table `site_content`
--

CREATE TABLE `site_content` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `media_type` enum('image','video') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stockalert`
--

CREATE TABLE `stockalert` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `stock_remaining` int(11) DEFAULT NULL,
  `alert_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `role` enum('user','admin','super_admin') NOT NULL DEFAULT 'user',
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `email`, `contact`, `gender`, `is_verified`, `role`, `profile_picture`, `created_at`) VALUES
(2, 'franzkhun', '$2y$10$UbFAMY1/WZdsZQIUQXHB3ee/Zj6q1X/oqiMrGpJ1/o4yzNZgL3uO2', 'Franz Luis Beltran', 'franzbeltran32@gmail.com', '09076969899', 'Prefer not to say', 1, 'user', 'uploads/profiles/user_2_1764173208.jpg', '2025-11-19 19:36:05'),
(3, 'Isaacjed06', '$2y$10$Z97Lubxi/8NrGy4B/yFnqueaVUS1ZIeM9TjC/y9HboDTo604d95oa', 'Isaac Jed Macaraeg', 'isaacjedm@gmail.com', '09942170085', 'Male', 1, 'super_admin', NULL, '2025-11-19 19:49:24'),
(7, 'Keycm', '$2y$10$pBiGFoS7P.ycxdEbw.hwGu1n1mgAnkJxE8drt9SiI2YGR7tqjdubO', 'Vincent paul D Pena', 'penapaul858@gmail.com', NULL, NULL, 0, 'user', NULL, '2025-11-27 04:04:48'),
(8, 'jecelann', '$2y$10$EuFe9sUJnvhbJ8RbRAKY..8zYMASsFGlY0YWm6xrL3Vv4iCrRp/uq', 'Jecel Ann Averion', 'jecelaverion@gmail.com', '09916835722', 'Female', 1, 'user', NULL, '2025-11-27 08:56:14'),
(12, 'ms_gzelle', '$2y$10$vC88AhZRXbiIxS7MycLRNObNOvWsj8CEoqqJ8IFU8kRFRwqLATthO', 'Gzelle Abrenica Velasco', 'gzelleabrenicavelasco@gmail.com', '09452640598', NULL, 0, 'super_admin', NULL, '2026-02-03 12:58:20'),
(15, 'nagumo000', '$2y$10$.ISb9GHTsEnSLXPhAb8TkuLDW0QiWOfwjnSVEux2GPvgouqcAwpDe', 'isaac jed', 'gnc.isaacjedm@gmail.com', '09942170085', 'Male', 1, 'user', NULL, '2026-02-04 03:01:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `entity_type` (`entity_type`),
  ADD KEY `entity_id` (`entity_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin_id` (`admin_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_section`
--
ALTER TABLE `hero_section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_user_read` (`user_id`,`is_read`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orders_user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `expires_at` (`expires_at`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `reset_code` (`reset_code`),
  ADD KEY `expires_at` (`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recently_deleted`
--
ALTER TABLE `recently_deleted`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recently_deleted_products`
--
ALTER TABLE `recently_deleted_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recently_deleted_users`
--
ALTER TABLE `recently_deleted_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `revenue`
--
ALTER TABLE `revenue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_content`
--
ALTER TABLE `site_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stockalert`
--
ALTER TABLE `stockalert`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=403;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `hero_section`
--
ALTER TABLE `hero_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hero_slides`
--
ALTER TABLE `hero_slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recently_deleted`
--
ALTER TABLE `recently_deleted`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `recently_deleted_products`
--
ALTER TABLE `recently_deleted_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `recently_deleted_users`
--
ALTER TABLE `recently_deleted_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `revenue`
--
ALTER TABLE `revenue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `site_content`
--
ALTER TABLE `site_content`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stockalert`
--
ALTER TABLE `stockalert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
