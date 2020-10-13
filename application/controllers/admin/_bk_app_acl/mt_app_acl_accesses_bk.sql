-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 28, 2016 at 02:56 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `butiksasha`
--

-- --------------------------------------------------------

--
-- Table structure for table `mt_app_acl_accesses_bk`
--

CREATE TABLE `mt_app_acl_accesses_bk` (
  `acc_id` int(11) NOT NULL,
  `acc_group` varchar(255) DEFAULT NULL,
  `acc_menu` varchar(255) NOT NULL,
  `acc_group_controller` varchar(255) NOT NULL,
  `acc_controller_name` varchar(255) NOT NULL,
  `acc_access_name` varchar(255) DEFAULT NULL,
  `acc_description` varchar(255) NOT NULL,
  `acc_by_order` int(11) DEFAULT NULL,
  `app_id` int(5) NOT NULL,
  `acc_css_class` varchar(50) DEFAULT NULL,
  `acc_isshow` char(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mt_app_acl_accesses_bk`
--
ALTER TABLE `mt_app_acl_accesses_bk`
  ADD PRIMARY KEY (`acc_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mt_app_acl_accesses_bk`
--
ALTER TABLE `mt_app_acl_accesses_bk`
  MODIFY `acc_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
