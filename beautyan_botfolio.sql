-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 15, 2020 at 05:41 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beautyan_botfolio`
--

-- --------------------------------------------------------

--
-- Table structure for table `approval_instance`
--

DROP TABLE IF EXISTS `approval_instance`;
CREATE TABLE IF NOT EXISTS `approval_instance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `approval_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `approval_instance_result`
--

DROP TABLE IF EXISTS `approval_instance_result`;
CREATE TABLE IF NOT EXISTS `approval_instance_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `approval_instance_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `approval_type`
--

DROP TABLE IF EXISTS `approval_type`;
CREATE TABLE IF NOT EXISTS `approval_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `approval_type`
--

INSERT INTO `approval_type` (`id`, `name`) VALUES
(1, 'ขออนุมัติเข้าร่วมอบรม');

-- --------------------------------------------------------

--
-- Table structure for table `approval_type_step`
--

DROP TABLE IF EXISTS `approval_type_step`;
CREATE TABLE IF NOT EXISTS `approval_type_step` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `approval_type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `approval_type_step`
--

INSERT INTO `approval_type_step` (`id`, `approval_type_id`, `name`) VALUES
(1, 1, 'H4 approve \"ขออนุมัติเข้าร่วมอบรม\"'),
(2, 1, 'H3 approve \"ขออนุมัติเข้าร่วมอบรม\"'),
(3, 1, 'H2 approve \"ขออนุมัติเข้าร่วมอบรม\"'),
(4, 1, 'H1 approve \"ขออนุมัติเข้าร่วมอบรม\"');

-- --------------------------------------------------------

--
-- Table structure for table `approver_type`
--

DROP TABLE IF EXISTS `approver_type`;
CREATE TABLE IF NOT EXISTS `approver_type` (
  `approval_step_type_id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  PRIMARY KEY (`approval_step_type_id`,`user_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attachment`
--

DROP TABLE IF EXISTS `attachment`;
CREATE TABLE IF NOT EXISTS `attachment` (
  `file_id` int(11) NOT NULL,
  `approval_instance_id` int(11) NOT NULL,
  PRIMARY KEY (`file_id`,`approval_instance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `files_info`
--

DROP TABLE IF EXISTS `files_info`;
CREATE TABLE IF NOT EXISTS `files_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `filename_original` varchar(255) NOT NULL,
  `filetype` varchar(16) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `files_info`
--

INSERT INTO `files_info` (`id`, `filename`, `filename_original`, `filetype`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '20200111-164500.jpg', 'img1.jpg', 'jpg', 1, '2020-01-11 16:45:00', '2020-01-11 16:45:00'),
(5, '20200209-123609.pdf', 'กำหนดการสอบ วันอาทิตย์ที่ 26 มกราคม 2563 update.pdf', 'pdf', 1, '2020-02-09 12:36:09', '2020-02-09 12:36:09'),
(6, '20200209-143932.', 'test22.pdf', '', 1, '2020-02-09 14:08:30', '2020-02-09 14:39:32'),
(10, '20200211-121238.jpg', '20200211-121238.jpg', 'jpg', 2, '2020-02-11 12:12:38', '2020-02-11 12:12:38'),
(11, '20200211-121357.pdf', '156405_PHP MySQL.pdf', 'pdf', 2, '2020-02-11 12:13:57', '2020-02-11 12:13:57'),
(12, '20200211-122237.pdf', 'กำหนดการสอบ วันอาทิตย์ที่ 26 มกราคม 2563 update.pdf', 'pdf', 1, '2020-02-11 12:22:37', '2020-02-11 12:22:37'),
(13, '20200211-122835.jpg', '20200211-122835.jpg', 'jpg', 5, '2020-02-11 12:28:35', '2020-02-11 12:28:35'),
(14, '20200212-050919.jpg', '20200212-050919.jpg', 'jpg', 1, '2020-02-12 05:09:19', '2020-02-12 05:09:19');

-- --------------------------------------------------------

--
-- Table structure for table `form_instance`
--

DROP TABLE IF EXISTS `form_instance`;
CREATE TABLE IF NOT EXISTS `form_instance` (
  `form_type_id` int(11) NOT NULL,
  `approval_instance_id` int(11) NOT NULL,
  PRIMARY KEY (`form_type_id`,`approval_instance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `form_type`
--

DROP TABLE IF EXISTS `form_type`;
CREATE TABLE IF NOT EXISTS `form_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `approval_type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `form_type`
--

INSERT INTO `form_type` (`id`, `approval_type_id`, `name`) VALUES
(1, 1, 'A1'),
(2, 1, 'A1');

-- --------------------------------------------------------

--
-- Table structure for table `line_user`
--

DROP TABLE IF EXISTS `line_user`;
CREATE TABLE IF NOT EXISTS `line_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lineUserId` varchar(40) NOT NULL,
  `token` varchar(64) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userId` (`lineUserId`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `line_user`
--

INSERT INTO `line_user` (`id`, `lineUserId`, `token`, `user_type_id`, `created_at`, `updated_at`) VALUES
(1, 'U8d979fc1b1a2b976ec6fc65c54e288f1', 'aaa', 0, '2020-01-05 13:39:11', '2020-02-12 16:29:34'),
(2, 'U26e4e6446cc1063b57b722805a357518', 'bbb', 0, '2020-01-05 14:54:32', '2020-02-16 17:14:08'),
(3, 'U97e096f45780adbbb0c7e2318495a39b', 'ccc', 0, '2020-01-05 15:01:45', '2020-01-05 15:02:10'),
(4, 'U0bab9ea54be2493d172cd2cc56dba7e8', 'ddd', 0, '2020-02-11 12:17:38', '2020-02-11 12:17:49'),
(5, 'Ud1a29e82a5cb37602619e95d26ff03e8', 'eee', 0, '2020-02-11 12:27:50', '2020-02-11 12:28:55'),
(6, 'H1', 'H1', 1, '2020-03-08 13:19:18', '2020-03-08 13:19:18'),
(7, 'H2', 'H2', 2, '2020-03-08 13:19:38', '2020-03-08 13:19:38'),
(8, 'H3', 'H3', 3, '2020-03-08 13:20:04', '2020-03-08 13:20:04'),
(9, 'H4', 'H4', 4, '2020-03-08 13:20:23', '2020-03-08 13:20:23');

-- --------------------------------------------------------

--
-- Table structure for table `sub_instance_result`
--

DROP TABLE IF EXISTS `sub_instance_result`;
CREATE TABLE IF NOT EXISTS `sub_instance_result` (
  `approval_type_step_id` int(11) NOT NULL,
  `approver_type_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `approval_instance_result_id` int(11) NOT NULL,
  PRIMARY KEY (`approval_type_step_id`,`approver_type_id`,`user_id`,`approval_instance_result_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

DROP TABLE IF EXISTS `user_type`;
CREATE TABLE IF NOT EXISTS `user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `name`) VALUES
(1, 'H1'),
(2, 'H2'),
(3, 'H3'),
(4, 'H4');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
