-- phpMyAdmin SQL Dump
-- version 4.6.5.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 21, 2017 at 06:11 PM
-- Server version: 5.6.34
-- PHP Version: 7.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `idea`
--

-- --------------------------------------------------------

--
-- Table structure for table `ideas`
--

CREATE TABLE `ideas` (
  `id` int(11) NOT NULL,
  `idea_category` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('OPEN','CONFIRMED','DECLINED') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'OPEN'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `idea_categories`
--

CREATE TABLE `idea_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `idea_categories`
--

INSERT INTO `idea_categories` (`id`, `name`, `active`, `updated_at`) VALUES
(1, 'User Profile', 1, 0),
(2, 'User Images', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ideas`
--
ALTER TABLE `ideas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `idea_categories`
--
ALTER TABLE `idea_categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ideas`
--
ALTER TABLE `ideas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `idea_categories`
--
ALTER TABLE `idea_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;