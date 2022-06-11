-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2022 at 12:22 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pujmonsys2`
--

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `email_add` varchar(100) NOT NULL,
  `dri_username` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `contactno` int(11) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `bday` date DEFAULT NULL,
  `ope_email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`email_add`, `dri_username`, `password`, `fname`, `mname`, `lname`, `contactno`, `address`, `bday`, `ope_email`) VALUES
('charlie', 'john', 'helen', 'dinausor', 'fgsdf', 'gfgsdf', 456345, 'far', '2000-10-07', 'celtics'),
('e', 'e', 'e', 'e', 'e', 'e', 123, 'e', '2001-02-02', 'lava'),
('lom', 'h', 'h', 'h', 'h', 'h', 456, 'h', '2005-01-01', 'nana'),
('r', 'r', 'r', 'r', 'r', 'r', 564, 'r', '2001-02-02', 'lava'),
('waley', 'waley', 'waley', 'a', 'adoy', 'a', 5, '345', '2002-01-01', 'lava');

-- --------------------------------------------------------

--
-- Table structure for table `operators`
--

CREATE TABLE `operators` (
  `email_add` varchar(100) NOT NULL,
  `ope_username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `contactno` int(11) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `bday` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `operators`
--

INSERT INTO `operators` (`email_add`, `ope_username`, `password`, `fname`, `mname`, `lname`, `contactno`, `address`, `bday`) VALUES
('akoay', 'ddfassa', '123', 'sada', 'asds', 'fgds', 123454, 'dfgds', '1997-08-12'),
('celtics', 'win', 'yehey', 'john', 'wall', 'tatum', 145612385, 'celtics man', '2001-02-02'),
('lava', 'ice', 'metal', 'chord', 'simon', 'stacy', 789456, 'seven', '2002-01-01'),
('nana', 'f', 'f', 'jghfgf', 'hjgfg', 'ghgfgh', 4895312, 'gfhyydfh', '2019-08-08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`email_add`),
  ADD UNIQUE KEY `dri_username` (`dri_username`) USING BTREE,
  ADD KEY `ope_email` (`ope_email`);

--
-- Indexes for table `operators`
--
ALTER TABLE `operators`
  ADD PRIMARY KEY (`email_add`),
  ADD UNIQUE KEY `ope_username` (`ope_username`) USING BTREE;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_ibfk_1` FOREIGN KEY (`ope_email`) REFERENCES `operators` (`email_add`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
