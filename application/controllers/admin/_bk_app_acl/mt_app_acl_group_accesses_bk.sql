-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 28, 2016 at 02:57 PM
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
-- Table structure for table `mt_app_acl_group_accesses_bk`
--

CREATE TABLE `mt_app_acl_group_accesses_bk` (
  `aga_id` int(20) NOT NULL,
  `aga_access_id` int(11) NOT NULL,
  `aga_group_id` int(11) NOT NULL,
  `app_id` int(5) DEFAULT NULL,
  `aga_action_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mt_app_acl_group_accesses_bk`
--
ALTER TABLE `mt_app_acl_group_accesses_bk`
  ADD PRIMARY KEY (`aga_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mt_app_acl_group_accesses_bk`
--
ALTER TABLE `mt_app_acl_group_accesses_bk`
  MODIFY `aga_id` int(20) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
