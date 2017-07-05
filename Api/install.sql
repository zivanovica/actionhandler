-- phpMyAdmin SQL Dump
-- version 4.6.5.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 22, 2017 at 02:58 PM
-- Server version: 5.6.34
-- PHP Version: 7.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `idea`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id`         INT(11) NOT NULL,
  `user_id`    INT(11)                 DEFAULT NULL,
  `first_name` VARCHAR(64)
               COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name`  VARCHAR(64)
               COLLATE utf8_unicode_ci DEFAULT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ideas`
--

CREATE TABLE `ideas` (
  `id`            INT(11)                      NOT NULL,
  `idea_category` INT(11)                      NOT NULL,
  `description`   TEXT COLLATE utf8_unicode_ci NOT NULL,
  `status`        ENUM ('OPEN', 'CONFIRMED', 'DECLINED')
                  COLLATE utf8_unicode_ci      NOT NULL DEFAULT 'OPEN'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `idea_categories`
--

CREATE TABLE `idea_categories` (
  `id`         INT(11)                 NOT NULL,
  `name`       VARCHAR(255)
               COLLATE utf8_unicode_ci NOT NULL,
  `active`     TINYINT(1)              NOT NULL DEFAULT '0',
  `updated_at` INT(11)                 NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id`       INT(11) NOT NULL,
  `email`    VARCHAR(255)
             COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` VARCHAR(60)
             COLLATE utf8_unicode_ci DEFAULT NULL,
  `status`   ENUM ('INACTIVE', 'ACTIVE', 'BANNED')
             COLLATE utf8_unicode_ci DEFAULT 'INACTIVE',
  `code`     VARCHAR(64)
             COLLATE utf8_unicode_ci DEFAULT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;


CREATE  TABLE `user_roles` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NULL ,
  `permission` INT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC) );


CREATE  TABLE `unique` (
  `id` INT NOT NULL ,
  `entity_model` VARCHAR(45) NULL ,
  `entity_id` INT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `entity_UNIQUE` (`entity_model` ASC, `entity_id` ASC) );


--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  ADD KEY `full_name` (`first_name`, `last_name`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD KEY `code_IDX` (`code`);