-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2021 at 12:05 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinema`
--
CREATE DATABASE IF NOT EXISTS `cinema` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cinema`;

-- --------------------------------------------------------

--
-- Table structure for table `actors`
--

DROP TABLE IF EXISTS `actors`;
CREATE TABLE IF NOT EXISTS `actors`
(
    `id`   int(11)     NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`),
    KEY `name_2` (`name`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3598
  DEFAULT CHARSET = utf8;

-- --------------------------------------------------------

--
-- Table structure for table `films`
--

DROP TABLE IF EXISTS `films`;
CREATE TABLE IF NOT EXISTS `films`
(
    `id`      int(11)      NOT NULL AUTO_INCREMENT,
    `title`   varchar(255) NOT NULL,
    `release` smallint(4)  NOT NULL,
    `format`  varchar(10)  NOT NULL,
    PRIMARY KEY (`id`),
    KEY `title` (`title`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 1027
  DEFAULT CHARSET = utf8;

-- --------------------------------------------------------

--
-- Table structure for table `films_actors`
--

DROP TABLE IF EXISTS `films_actors`;
CREATE TABLE IF NOT EXISTS `films_actors`
(
    `id_film`  int(11) NOT NULL,
    `id_actor` int(11) NOT NULL,
    KEY `FK_films_actors_films` (`id_film`),
    KEY `FK_films_actors_actors` (`id_actor`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `films_actors`
--
ALTER TABLE `films_actors`
    ADD CONSTRAINT `FK_films_actors_actors` FOREIGN KEY (`id_actor`) REFERENCES `actors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `FK_films_actors_films` FOREIGN KEY (`id_film`) REFERENCES `films` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
