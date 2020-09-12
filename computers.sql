-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 25, 2018 at 01:08 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `computers`
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `name`) VALUES
(1, 'Segate'),
(2, 'UV'),
(3, 'Google'),
(4, NULL),
(5, NULL),
(6, 'Intel'),
(7, 'CISCO'),
(8, 'mu');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_book`
--

CREATE TABLE `complaint_book` (
  `complaint_book_id` int(11) NOT NULL,
  `Date_of_complaint` date DEFAULT NULL,
  `machine_id` int(11) DEFAULT NULL,
  `complaint_details` varchar(512) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `work_for` int(11) DEFAULT NULL,
  `remarks` varchar(512) DEFAULT NULL,
  `complaint_by` varchar(40) DEFAULT NULL,
  `processor` int(11) DEFAULT NULL,
  `ram` int(11) DEFAULT NULL,
  `harddisk` int(11) DEFAULT NULL,
  `mouse` int(11) DEFAULT NULL,
  `keyboard` int(11) DEFAULT NULL,
  `monitor` int(11) DEFAULT NULL,
  `DOPR` date DEFAULT NULL,
  `completed` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `complaint_book`
--

INSERT INTO `complaint_book` (`complaint_book_id`, `Date_of_complaint`, `machine_id`, `complaint_details`, `priority`, `work_for`, `remarks`, `complaint_by`, `processor`, `ram`, `harddisk`, `mouse`, `keyboard`, `monitor`, `DOPR`, `completed`) VALUES
(6, '2018-07-28', 4, 'Blast in CPU', 1, 1, 'No Blast was there', 'Vaishali Chourey', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(7, '2018-07-28', 4, 'Not wokring', 1, 1, 'Format', 'Vaishali Chourey', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(8, '2018-07-28', 4, 'Not wokring', 1, 1, 'OS Reinstalled', 'Vaishali Chourey', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(9, '2018-07-25', 6, 'NEW TEEST', 7, 1, 'DONE', 'Vaishali Chourey', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(10, '2018-07-25', 6, 'NEW NEW TEEST', 3, 1, 'FIXED', 'Vaishali Chourey', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(11, '2018-07-25', 6, 'NEW NEW', 2, 1, 'FIXED', 'Vaishali Chourey', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(12, '2018-07-25', 9, 'RAM breakdown', 2, 1, NULL, 'AC DR', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(13, '2018-07-25', 13, 'RAM', 2, 1, 'ok', 'Anurag Phadnis', NULL, NULL, NULL, 1, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `device_repair_history`
--

CREATE TABLE `device_repair_history` (
  `repair_history_id` int(11) NOT NULL,
  `hardware_id` int(11) DEFAULT NULL,
  `initial_date` date DEFAULT NULL,
  `final_date` date DEFAULT NULL,
  `fault` varchar(500) DEFAULT NULL,
  `cost` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `device_repair_history`
--

INSERT INTO `device_repair_history` (`repair_history_id`, `hardware_id`, `initial_date`, `final_date`, `fault`, `cost`) VALUES
(1, 5, '2018-07-27', '0000-00-00', NULL, NULL),
(2, 20, '2018-07-28', '0000-00-00', NULL, NULL),
(3, 21, '2018-07-25', '2018-07-25', 'Replaced wire', 100),
(4, 3, '2018-07-25', '2018-07-25', 'Minor', 20);

-- --------------------------------------------------------

--
-- Table structure for table `hardware`
--

CREATE TABLE `hardware` (
  `hardware_id` int(11) NOT NULL,
  `company` int(11) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `grn` int(11) DEFAULT NULL,
  `name` int(11) DEFAULT NULL,
  `state` int(11) NOT NULL,
  `supplier` int(20) DEFAULT NULL,
  `DOP` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hardware`
--

INSERT INTO `hardware` (`hardware_id`, `company`, `description`, `price`, `grn`, `name`, `state`, `supplier`, `DOP`) VALUES
(1, 1, 1, 5000, 123456, 7, 0, 1, '2018-07-24'),
(2, 1, 1, 5000, 123456, 7, 0, 1, '2018-07-24'),
(3, 1, 1, 5000, 123456, 7, 0, 1, '2018-07-24'),
(4, 1, 1, 5000, 123456, 7, 0, 1, '2018-07-24'),
(5, 1, 1, 5000, 123456, 7, 3, 1, '2018-07-24'),
(8, 1, 15, NULL, 121, 4, 0, 1, '2018-07-24'),
(12, 1, 16, NULL, 121, 6, 1, 1, '2018-07-24'),
(13, 1, 21, NULL, 121, 5, 1, 1, '2018-07-24'),
(14, 1, 15, NULL, 121, 4, 0, 1, '2018-07-24'),
(15, 1, 5, NULL, 121, 1, 1, 1, '2018-07-24'),
(16, 1, 22, NULL, 121, 2, 1, 1, '2018-07-24'),
(17, 1, 23, NULL, 121, 3, 1, 1, '2018-07-24'),
(18, 2, 13, 2000, 1144, 4, 1, 1, '2018-07-24'),
(20, 2, 4, 1000, 1123, 1, 3, 1, '2018-07-24'),
(21, 2, 4, 1000, 1123, 1, 0, 1, '2018-07-24'),
(22, 2, 16, NULL, 12132, 6, 1, 1, '2018-07-24'),
(23, 2, 18, NULL, 12132, 5, 1, 1, '2018-07-24'),
(24, 2, 12, NULL, 12132, 4, 1, 1, '2018-07-24'),
(25, 2, 3, NULL, 12132, 1, 1, 1, '2018-07-24'),
(26, 2, 7, NULL, 12132, 2, 1, 1, '2018-07-24'),
(27, 2, 10, NULL, 12132, 3, 1, 1, '2018-07-24'),
(28, 2, 16, NULL, 12132, 6, 1, 1, '2018-07-24'),
(29, 2, 18, NULL, 12132, 5, 1, 1, '2018-07-24'),
(30, 2, 12, NULL, 12132, 4, 1, 1, '2018-07-24'),
(31, 2, 3, NULL, 12132, 1, 1, 1, '2018-07-24'),
(32, 2, 7, NULL, 12132, 2, 1, 1, '2018-07-24'),
(33, 2, 10, NULL, 12132, 3, 1, 1, '2018-07-24'),
(34, 2, 16, NULL, 12132, 6, 1, 1, '2018-07-24'),
(35, 2, 18, NULL, 12132, 5, 1, 1, '2018-07-24'),
(36, 2, 12, NULL, 12132, 4, 1, 1, '2018-07-24'),
(37, 2, 3, NULL, 12132, 1, 1, 1, '2018-07-24'),
(38, 2, 7, NULL, 12132, 2, 1, 1, '2018-07-24'),
(39, 2, 10, NULL, 12132, 3, 1, 1, '2018-07-24'),
(40, 2, 16, NULL, 12132, 6, 1, 1, '2018-07-24'),
(41, 2, 18, NULL, 12132, 5, -1, 1, '2018-07-24'),
(42, 2, 12, NULL, 12132, 4, 1, 1, '2018-07-24'),
(43, 2, 3, NULL, 12132, 1, 1, 1, '2018-07-24'),
(44, 2, 7, NULL, 12132, 2, 1, 1, '2018-07-24'),
(45, 2, 10, NULL, 12132, 3, 1, 1, '2018-07-24'),
(46, 1, 25, NULL, 12345, 6, 0, 1, '2018-07-24'),
(58, 1, 25, NULL, 12345, 6, 1, 1, '2018-07-24'),
(59, 1, 18, NULL, 12345, 5, 1, 1, '2018-07-24'),
(60, 1, 14, NULL, 12345, 4, 1, 1, '2018-07-24'),
(61, 1, 3, NULL, 12345, 1, 1, 1, '2018-07-24'),
(62, 1, 8, NULL, 12345, 2, 1, 1, '2018-07-24'),
(63, 1, 11, NULL, 12345, 3, 1, 1, '2018-07-24'),
(64, 1, 25, NULL, 12345, 6, 1, 1, '2018-07-24'),
(65, 1, 18, NULL, 12345, 5, 1, 1, '2018-07-24'),
(66, 1, 14, NULL, 12345, 4, 1, 1, '2018-07-24'),
(67, 1, 3, NULL, 12345, 1, 1, 1, '2018-07-24'),
(68, 1, 8, NULL, 12345, 2, 1, 1, '2018-07-24'),
(69, 1, 11, NULL, 12345, 3, 1, 1, '2018-07-24'),
(76, 1, 16, NULL, 12345, 6, 1, 1, '2018-07-24'),
(77, 1, 18, NULL, 12345, 5, 1, 1, '2018-07-24'),
(78, 1, 12, NULL, 12345, 4, 1, 1, '2018-07-24'),
(79, 1, 3, NULL, 12345, 1, 1, 1, '2018-07-24'),
(80, 1, 7, NULL, 12345, 2, 1, 1, '2018-07-24'),
(81, 1, 10, NULL, 12345, 3, 1, 1, '2018-07-24'),
(82, 1, 17, 12, 123456, 6, 0, 1, '2018-07-24'),
(83, 1, 17, 12, 123456, 6, 0, 1, '2018-07-24'),
(85, 1, 17, 12, 123456, 6, 0, 1, '2018-07-24'),
(86, 1, 17, 12, 123456, 6, 0, 1, '2018-07-24'),
(87, 3, 26, 500, 124585, 10, 0, 1, '2018-07-28'),
(88, 6, 18, 500, 45210, 5, 0, 1, '2018-07-25'),
(89, 6, 18, 500, 45210, 5, 0, 1, '2018-07-25'),
(90, 6, 18, 500, 45210, 5, 0, 1, '2018-07-25'),
(91, 6, 18, 500, 45210, 5, 0, 1, '2018-07-25'),
(92, 6, 18, 500, 45210, 5, 0, 1, '2018-07-25'),
(93, 6, 21, 5000, 45110, 5, 0, 1, '2018-07-25'),
(94, 6, 21, 5000, 45110, 5, 0, 1, '2018-07-25'),
(95, 6, 21, 5000, 45110, 5, 2, 1, '2018-07-25'),
(96, 7, 27, 0, 12345, 11, 1, 3, '2018-07-25'),
(97, 8, 16, NULL, 12345, 6, 1, 1, '2018-07-25'),
(98, 8, 18, NULL, 12345, 5, 1, 1, '2018-07-25'),
(99, 8, 12, NULL, 12345, 4, 1, 1, '2018-07-25'),
(100, 8, 3, NULL, 12345, 1, 1, 1, '2018-07-25'),
(101, 8, 7, NULL, 12345, 2, 1, 1, '2018-07-25'),
(102, 8, 10, NULL, 12345, 3, 1, 1, '2018-07-25'),
(103, 8, 16, NULL, 12345, 6, 1, 1, '2018-07-25'),
(104, 8, 18, NULL, 12345, 5, 1, 1, '2018-07-25'),
(105, 8, 12, NULL, 12345, 4, 1, 1, '2018-07-25'),
(106, 8, 3, NULL, 12345, 1, 1, 1, '2018-07-25'),
(107, 8, 7, NULL, 12345, 2, 1, 1, '2018-07-25'),
(108, 8, 10, NULL, 12345, 3, 1, 1, '2018-07-25'),
(109, 8, 16, NULL, 12345, 6, 1, 1, '2018-07-25'),
(110, 8, 18, NULL, 12345, 5, 1, 1, '2018-07-25'),
(111, 8, 12, NULL, 12345, 4, 1, 1, '2018-07-25'),
(112, 8, 3, NULL, 12345, 1, 1, 1, '2018-07-25'),
(113, 8, 7, NULL, 12345, 2, 1, 1, '2018-07-25'),
(114, 8, 10, NULL, 12345, 3, 1, 1, '2018-07-25');

-- --------------------------------------------------------

--
-- Table structure for table `hardware_complaint_book`
--

CREATE TABLE `hardware_complaint_book` (
  `hardware_complaint_book_id` int(11) NOT NULL COMMENT 'AUTO_INCREMENT',
  `date_of_complaint` date DEFAULT NULL,
  `hardware_id` int(11) DEFAULT NULL,
  `complaint_details` varchar(512) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `work_for` int(11) DEFAULT NULL,
  `remarks` varchar(512) DEFAULT NULL,
  `complaint_by` varchar(40) DEFAULT NULL,
  `completed` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hardware_complaint_book`
--

INSERT INTO `hardware_complaint_book` (`hardware_complaint_book_id`, `date_of_complaint`, `hardware_id`, `complaint_details`, `priority`, `work_for`, `remarks`, `complaint_by`, `completed`) VALUES
(1, '2018-07-27', 5, 'hd', 2, 1, NULL, 'Vaishali Chourey', NULL),
(2, '2018-07-28', 20, 'Wire Cut by real mouse', 1, 1, NULL, 'Vaishali Chourey', NULL),
(3, '2018-07-25', 21, 'Wire Breakdown', 3, 1, 'Repaired Successfully', 'Vaishali Chourey', 1),
(4, '2018-07-25', 3, 'Burst', 10, 1, 'Done', 'Vaishali Chourey', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hardware_position`
--

CREATE TABLE `hardware_position` (
  `hardware_position_id` int(11) NOT NULL,
  `hardware_id` int(11) DEFAULT NULL,
  `lab_id` int(11) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `initial_date` date DEFAULT NULL,
  `final_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hardware_position`
--

INSERT INTO `hardware_position` (`hardware_position_id`, `hardware_id`, `lab_id`, `member_id`, `initial_date`, `final_date`) VALUES
(3, 1, NULL, 1, '2018-07-24', NULL),
(4, 3, NULL, 1, '2018-07-24', '2018-07-25'),
(5, 1, NULL, 1, '2018-07-24', '2018-07-24'),
(6, 5, NULL, 1, '2018-07-24', '2018-07-27'),
(7, 20, 1, NULL, '2018-07-24', '2018-07-28'),
(8, 21, 1, NULL, '2018-07-28', '2018-07-25'),
(9, 95, NULL, 0, '2018-07-25', '0000-00-00'),
(10, 96, 2, NULL, '2018-07-25', '2018-07-25'),
(11, 96, 2, NULL, '2018-07-25', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `issue_request`
--

CREATE TABLE `issue_request` (
  `issue_report_id` int(11) NOT NULL,
  `department` varchar(30) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  `purpose` text,
  `date_of_request` date DEFAULT NULL,
  `name_of_hardware` int(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lab`
--

CREATE TABLE `lab` (
  `lab_id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `department` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lab`
--

INSERT INTO `lab` (`lab_id`, `name`, `department`) VALUES
(1, 'C4', 'IT'),
(2, 'first', 'IT');

-- --------------------------------------------------------

--
-- Table structure for table `machine`
--

CREATE TABLE `machine` (
  `machine_id` int(11) NOT NULL,
  `MAC_ADDR` varchar(30) DEFAULT NULL,
  `processor` int(11) NOT NULL,
  `ram` int(11) NOT NULL,
  `memory` int(11) NOT NULL,
  `DOP` date DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `state` varchar(10) DEFAULT NULL,
  `os` varchar(30) DEFAULT NULL,
  `monitor` int(11) NOT NULL,
  `keyboard` int(11) NOT NULL,
  `mouse` int(11) NOT NULL,
  `grn` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `machine`
--

INSERT INTO `machine` (`machine_id`, `MAC_ADDR`, `processor`, `ram`, `memory`, `DOP`, `price`, `state`, `os`, `monitor`, `keyboard`, `mouse`, `grn`) VALUES
(3, '93', 23, 22, 24, '2018-07-24', 1233, 'ACTIVE', 'windows', 27, 26, 25, 12132),
(4, '94', 29, 28, 30, '2018-07-24', 1233, 'ACTIVE', 'windows', 33, 32, 31, 12132),
(5, '95', 35, 34, 36, '2018-07-24', 1233, 'ACTIVE', 'windows', 39, 38, 37, 12132),
(6, '96', 88, 40, 42, '2018-07-24', 1233, 'ACTIVE', 'windows', 45, 44, 43, 12132),
(9, '33', 59, 58, 60, '2018-07-24', 0, 'INACTIVE', 'windows', 63, 62, 61, 12345),
(10, '34', 65, 64, 66, '2018-07-24', 0, 'ACTIVE', 'windows', 69, 68, 67, 12345),
(12, '92', 77, 76, 78, '2018-07-24', 0, 'ACTIVE', 'windows', 81, 80, 79, 12345),
(13, '50', 98, 97, 99, '2018-07-25', 0, 'ACTIVE', 'windows', 102, 101, 100, 12345),
(14, '51', 104, 103, 105, '2018-07-25', 0, 'ACTIVE', 'windows', 108, 107, 106, 12345),
(15, '52', 110, 109, 111, '2018-07-25', 0, 'ACTIVE', 'windows', 114, 113, 112, 12345);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `member_id` int(11) NOT NULL,
  `id` varchar(30) DEFAULT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `pass_word` varchar(512) DEFAULT NULL,
  `role` int(5) DEFAULT NULL,
  `contact_no` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `id`, `first_name`, `last_name`, `email`, `pass_word`, `role`, `contact_no`) VALUES
(0, '0', 'Vaishali', 'Chourey', 'vaishali@medicaps.ac.in', '4f2a91d6913739834ec9c3d4f9203534', 0, NULL),
(1, '25', 'Anurag', 'Phadnis', 'anurag@phadnis.com', '4f2a91d6913739834ec9c3d4f9203534', 2, '1234567890'),
(2, '10', 'AC', 'DR', 'acdr@mm.com', '4f2a91d6913739834ec9c3d4f9203534', 1, '1234567891');

-- --------------------------------------------------------

--
-- Table structure for table `name`
--

CREATE TABLE `name` (
  `name_id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `name`
--

INSERT INTO `name` (`name_id`, `name`) VALUES
(1, 'mouse'),
(2, 'keyboard'),
(3, 'monitor'),
(4, 'harddisk'),
(5, 'processor'),
(6, 'ram'),
(7, 'External Harddisk'),
(9, 'Projector'),
(10, 'Smart Board'),
(11, 'light pen');

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `position_id` int(11) NOT NULL,
  `machine_id` int(11) DEFAULT NULL,
  `lab_id` int(11) DEFAULT NULL,
  `initial_date` date DEFAULT NULL,
  `final_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`position_id`, `machine_id`, `lab_id`, `initial_date`, `final_date`) VALUES
(3, 9, 2, '2018-07-27', '2018-07-25'),
(4, 10, 2, '2018-07-27', '1970-01-01'),
(5, 4, NULL, '1970-01-01', '2018-07-28'),
(6, 4, NULL, '1970-01-01', '2018-07-28'),
(7, 4, NULL, '1970-01-01', '1970-01-01'),
(8, 6, NULL, '1970-01-01', '2018-07-25'),
(9, 6, NULL, '1970-01-01', '2018-07-25'),
(10, 6, NULL, '1970-01-01', '1970-01-01'),
(11, 3, 1, '2018-07-25', '1970-01-01'),
(12, 13, NULL, '1970-01-01', '1970-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `repair_history`
--

CREATE TABLE `repair_history` (
  `repair_history_id` int(11) NOT NULL,
  `machine_id` int(11) DEFAULT NULL,
  `initial_date` date DEFAULT NULL,
  `final_date` date DEFAULT NULL,
  `fault` varchar(500) DEFAULT NULL,
  `cost` int(11) DEFAULT NULL,
  `complaint_book_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `repair_history`
--

INSERT INTO `repair_history` (`repair_history_id`, `machine_id`, `initial_date`, `final_date`, `fault`, `cost`, `complaint_book_id`) VALUES
(4, 4, '2018-07-28', '1970-01-01', 'Nothing', 0, NULL),
(5, 4, '2018-07-28', '1970-01-01', 'OS corrupted', 0, NULL),
(6, 4, '2018-07-28', '1970-01-01', 'OS corrupted', 0, NULL),
(8, 6, '2018-07-25', '2018-07-25', 'NEW', 333, 9),
(9, 6, '2018-07-25', '2018-07-25', 'TESTED', 222, 10),
(10, 6, '2018-07-25', '2018-07-25', 'Minor', 1000, 11),
(11, 9, '2018-07-25', '0000-00-00', NULL, NULL, 12),
(12, 13, '2018-07-25', '2018-07-25', 'RAM', 50, 13);

-- --------------------------------------------------------

--
-- Table structure for table `software`
--

CREATE TABLE `software` (
  `software_id` int(11) NOT NULL,
  `company` int(11) DEFAULT NULL,
  `description` text,
  `price` int(11) DEFAULT NULL,
  `grn` int(11) DEFAULT NULL,
  `name` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `specification`
--

CREATE TABLE `specification` (
  `spec_id` int(11) NOT NULL,
  `spec` varchar(50) DEFAULT NULL,
  `name_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `specification`
--

INSERT INTO `specification` (`spec_id`, `spec`, `name_id`) VALUES
(1, '2TB', 7),
(2, '2TB', 7),
(3, 'Wireless Mouse', 1),
(4, 'Trackball Mouse', 1),
(5, 'Optical Mouse', 1),
(6, 'Laser Mouse', 1),
(7, 'Wired Membrane', 2),
(8, 'Wireless Membrane', 2),
(9, 'Wired Mechanical', 2),
(10, 'LCD', 3),
(11, 'LED', 3),
(12, '128 GB', 4),
(13, '256 GB', 4),
(14, '1 TB', 4),
(15, '2 TB', 4),
(16, '2 GB', 6),
(17, '1 GB', 6),
(18, 'Intel Pentium Dual Core', 5),
(19, 'Intel i3 Processors (Ivy Bridge)', 5),
(20, 'Intel i5 Processors', 5),
(21, 'Intel i7 Processors', 5),
(22, 'Wireless Mechanical', 2),
(23, 'CRT Monitor', 3),
(25, '8 GB', 6),
(26, '19''', 10),
(27, 'Digital Writing', 11);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supname` varchar(30) DEFAULT NULL,
  `sup_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supname`, `sup_id`) VALUES
('Alphastar hardwares', 1),
('Alphastar hardwares', 2),
('MU', 3);

-- --------------------------------------------------------

--
-- Table structure for table `system_transfer_report`
--

CREATE TABLE `system_transfer_report` (
  `system_transfer_report_id` int(11) NOT NULL,
  `department` varchar(30) DEFAULT NULL,
  `lab_id` int(11) DEFAULT NULL,
  `purpose` text,
  `date_of_assignment` date DEFAULT NULL,
  `trid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `system_transfer_report`
--

INSERT INTO `system_transfer_report` (`system_transfer_report_id`, `department`, `lab_id`, `purpose`, `date_of_assignment`, `trid`) VALUES
(1, 'EC', 1, 'Presentation', '2018-07-25', 2);

-- --------------------------------------------------------

--
-- Table structure for table `system_transfer_report_history`
--

CREATE TABLE `system_transfer_report_history` (
  `system_transfer_report_history_id` int(11) NOT NULL,
  `system_transfer_report_id` int(11) DEFAULT NULL,
  `machine_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `system_transfer_report_history`
--

INSERT INTO `system_transfer_report_history` (`system_transfer_report_history_id`, `system_transfer_report_id`, `machine_id`) VALUES
(1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE `temp` (
  `temp_id` int(11) NOT NULL,
  `machine_id` int(11) DEFAULT NULL,
  `processor` int(11) DEFAULT NULL,
  `ram` int(11) DEFAULT NULL,
  `mouse` int(11) DEFAULT NULL,
  `harddisk` int(11) DEFAULT NULL,
  `keyboard` int(11) DEFAULT NULL,
  `monitor` int(11) DEFAULT NULL,
  `completed` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `temp`
--

INSERT INTO `temp` (`temp_id`, `machine_id`, `processor`, `ram`, `mouse`, `harddisk`, `keyboard`, `monitor`, `completed`) VALUES
(2, 9, NULL, 83, NULL, NULL, NULL, NULL, 0),
(3, 9, NULL, 46, NULL, NULL, NULL, NULL, 0),
(4, 9, NULL, 46, NULL, NULL, NULL, NULL, 0),
(5, 9, NULL, 46, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `transfer_request`
--

CREATE TABLE `transfer_request` (
  `transfer_request_id` int(11) NOT NULL,
  `date_of_request` date DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `department` varchar(30) DEFAULT NULL,
  `purpose` text,
  `processor` varchar(100) DEFAULT NULL,
  `ram` varchar(10) DEFAULT NULL,
  `hdd` varchar(10) DEFAULT NULL,
  `os` varchar(20) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transfer_request`
--

INSERT INTO `transfer_request` (`transfer_request_id`, `date_of_request`, `name`, `department`, `purpose`, `processor`, `ram`, `hdd`, `os`, `quantity`) VALUES
(1, '2018-07-28', 'Vaishali Chourey', 'CS', 'Gaming', 'Intel i7 Processors', '8 GB', '2 TB', 'windows', 1),
(2, '2018-07-25', 'Vaishali Chourey', 'EC', 'Presentation', 'Intel Pentium Dual Core', 'NULL', 'NULL', 'windows', 2);

-- --------------------------------------------------------

--
-- Table structure for table `upgrade_history`
--

CREATE TABLE `upgrade_history` (
  `upgrade_history_id` int(11) NOT NULL,
  `machine_id` int(11) DEFAULT NULL,
  `processori` int(11) DEFAULT NULL,
  `rami` int(11) DEFAULT NULL,
  `memoryi` int(11) DEFAULT NULL,
  `processorf` int(11) DEFAULT NULL,
  `ramf` int(11) DEFAULT NULL,
  `memoryf` int(11) DEFAULT NULL,
  `dateofupgrade` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `upgrade_history`
--

INSERT INTO `upgrade_history` (`upgrade_history_id`, `machine_id`, `processori`, `rami`, `memoryi`, `processorf`, `ramf`, `memoryf`, `dateofupgrade`) VALUES
(5, 6, 41, 40, 42, 88, 40, 42, '2018-07-25'),
(6, 13, 98, 97, 99, 98, 97, 99, '2018-07-25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `complaint_book`
--
ALTER TABLE `complaint_book`
  ADD PRIMARY KEY (`complaint_book_id`),
  ADD KEY `fk_1` (`machine_id`),
  ADD KEY `FK1` (`work_for`);

--
-- Indexes for table `device_repair_history`
--
ALTER TABLE `device_repair_history`
  ADD PRIMARY KEY (`repair_history_id`),
  ADD KEY `fk_devicehistory_hardwareid` (`hardware_id`);

--
-- Indexes for table `hardware`
--
ALTER TABLE `hardware`
  ADD PRIMARY KEY (`hardware_id`),
  ADD KEY `FK_company` (`company`),
  ADD KEY `FK_name` (`name`),
  ADD KEY `supplier` (`supplier`),
  ADD KEY `fk_description` (`description`);

--
-- Indexes for table `hardware_complaint_book`
--
ALTER TABLE `hardware_complaint_book`
  ADD PRIMARY KEY (`hardware_complaint_book_id`),
  ADD KEY `fk_hardwarecomplaint_hardwareid` (`hardware_id`),
  ADD KEY `fk_hardware_workfor` (`work_for`);

--
-- Indexes for table `hardware_position`
--
ALTER TABLE `hardware_position`
  ADD PRIMARY KEY (`hardware_position_id`),
  ADD KEY `FK_HW` (`hardware_id`),
  ADD KEY `FK_LAB` (`lab_id`),
  ADD KEY `FK_MEM` (`member_id`);

--
-- Indexes for table `issue_request`
--
ALTER TABLE `issue_request`
  ADD PRIMARY KEY (`issue_report_id`),
  ADD KEY `fk_issue` (`name_of_hardware`),
  ADD KEY `fk_member_id` (`id`);

--
-- Indexes for table `lab`
--
ALTER TABLE `lab`
  ADD PRIMARY KEY (`lab_id`);

--
-- Indexes for table `machine`
--
ALTER TABLE `machine`
  ADD PRIMARY KEY (`machine_id`),
  ADD KEY `fk_processor` (`processor`),
  ADD KEY `fk_ram` (`ram`),
  ADD KEY `fk_memory` (`memory`),
  ADD KEY `fk_monitor` (`monitor`),
  ADD KEY `fk_keyboard` (`keyboard`),
  ADD KEY `fk_mouse` (`mouse`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `name`
--
ALTER TABLE `name`
  ADD PRIMARY KEY (`name_id`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`position_id`),
  ADD KEY `machine_id` (`machine_id`),
  ADD KEY `lab_id` (`lab_id`);

--
-- Indexes for table `repair_history`
--
ALTER TABLE `repair_history`
  ADD PRIMARY KEY (`repair_history_id`),
  ADD KEY `machine_id` (`machine_id`),
  ADD KEY `cid_fk` (`complaint_book_id`);

--
-- Indexes for table `software`
--
ALTER TABLE `software`
  ADD PRIMARY KEY (`software_id`),
  ADD KEY `FK_company2` (`company`),
  ADD KEY `FK_name2` (`name`);

--
-- Indexes for table `specification`
--
ALTER TABLE `specification`
  ADD PRIMARY KEY (`spec_id`),
  ADD KEY `name_id` (`name_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`sup_id`);

--
-- Indexes for table `system_transfer_report`
--
ALTER TABLE `system_transfer_report`
  ADD PRIMARY KEY (`system_transfer_report_id`),
  ADD KEY `fk_str` (`lab_id`),
  ADD KEY `fk_streport` (`trid`);

--
-- Indexes for table `system_transfer_report_history`
--
ALTER TABLE `system_transfer_report_history`
  ADD PRIMARY KEY (`system_transfer_report_history_id`),
  ADD KEY `FKK_SYSTRAN` (`system_transfer_report_id`),
  ADD KEY `FKK_MID` (`machine_id`);

--
-- Indexes for table `temp`
--
ALTER TABLE `temp`
  ADD PRIMARY KEY (`temp_id`),
  ADD KEY `fk_machine_id` (`machine_id`),
  ADD KEY `fk_harddisk` (`harddisk`),
  ADD KEY `fk_keyboardd` (`keyboard`),
  ADD KEY `fk_mousee` (`mouse`),
  ADD KEY `fk_monitorr` (`monitor`),
  ADD KEY `fk_processorr` (`processor`),
  ADD KEY `fk_ramm` (`ram`);

--
-- Indexes for table `transfer_request`
--
ALTER TABLE `transfer_request`
  ADD PRIMARY KEY (`transfer_request_id`);

--
-- Indexes for table `upgrade_history`
--
ALTER TABLE `upgrade_history`
  ADD PRIMARY KEY (`upgrade_history_id`),
  ADD KEY `FK_Mid` (`machine_id`),
  ADD KEY `FK_proi` (`processori`),
  ADD KEY `FK_rami` (`rami`),
  ADD KEY `FK_memi` (`memoryi`),
  ADD KEY `FK_prof` (`processorf`),
  ADD KEY `FK_ramf` (`ramf`),
  ADD KEY `FK_memf` (`memoryf`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `complaint_book`
--
ALTER TABLE `complaint_book`
  MODIFY `complaint_book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `device_repair_history`
--
ALTER TABLE `device_repair_history`
  MODIFY `repair_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `hardware`
--
ALTER TABLE `hardware`
  MODIFY `hardware_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;
--
-- AUTO_INCREMENT for table `hardware_complaint_book`
--
ALTER TABLE `hardware_complaint_book`
  MODIFY `hardware_complaint_book_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'AUTO_INCREMENT', AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `hardware_position`
--
ALTER TABLE `hardware_position`
  MODIFY `hardware_position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `issue_request`
--
ALTER TABLE `issue_request`
  MODIFY `issue_report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `lab`
--
ALTER TABLE `lab`
  MODIFY `lab_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `machine`
--
ALTER TABLE `machine`
  MODIFY `machine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `name`
--
ALTER TABLE `name`
  MODIFY `name_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `repair_history`
--
ALTER TABLE `repair_history`
  MODIFY `repair_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `software`
--
ALTER TABLE `software`
  MODIFY `software_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `specification`
--
ALTER TABLE `specification`
  MODIFY `spec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `sup_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `system_transfer_report`
--
ALTER TABLE `system_transfer_report`
  MODIFY `system_transfer_report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `system_transfer_report_history`
--
ALTER TABLE `system_transfer_report_history`
  MODIFY `system_transfer_report_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `temp`
--
ALTER TABLE `temp`
  MODIFY `temp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `transfer_request`
--
ALTER TABLE `transfer_request`
  MODIFY `transfer_request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `upgrade_history`
--
ALTER TABLE `upgrade_history`
  MODIFY `upgrade_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaint_book`
--
ALTER TABLE `complaint_book`
  ADD CONSTRAINT `FK1` FOREIGN KEY (`work_for`) REFERENCES `member` (`member_id`),
  ADD CONSTRAINT `fk_1` FOREIGN KEY (`machine_id`) REFERENCES `machine` (`machine_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `device_repair_history`
--
ALTER TABLE `device_repair_history`
  ADD CONSTRAINT `fk_devicehistory_hardwareid` FOREIGN KEY (`hardware_id`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hardware`
--
ALTER TABLE `hardware`
  ADD CONSTRAINT `FK_company` FOREIGN KEY (`company`) REFERENCES `company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_name` FOREIGN KEY (`name`) REFERENCES `name` (`name_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_description` FOREIGN KEY (`description`) REFERENCES `specification` (`spec_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hardware_ibfk_1` FOREIGN KEY (`supplier`) REFERENCES `supplier` (`sup_id`);

--
-- Constraints for table `hardware_complaint_book`
--
ALTER TABLE `hardware_complaint_book`
  ADD CONSTRAINT `fk_hardwarecomplaint_hardwareid` FOREIGN KEY (`hardware_id`) REFERENCES `hardware` (`hardware_id`);

--
-- Constraints for table `hardware_position`
--
ALTER TABLE `hardware_position`
  ADD CONSTRAINT `FK_HW` FOREIGN KEY (`hardware_id`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_LAB` FOREIGN KEY (`lab_id`) REFERENCES `lab` (`lab_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MEM` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `issue_request`
--
ALTER TABLE `issue_request`
  ADD CONSTRAINT `fk_issue` FOREIGN KEY (`name_of_hardware`) REFERENCES `name` (`name_id`),
  ADD CONSTRAINT `fk_member_id` FOREIGN KEY (`id`) REFERENCES `member` (`member_id`);

--
-- Constraints for table `machine`
--
ALTER TABLE `machine`
  ADD CONSTRAINT `fk_keyboard` FOREIGN KEY (`keyboard`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_memory` FOREIGN KEY (`memory`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_monitor` FOREIGN KEY (`monitor`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mouse` FOREIGN KEY (`mouse`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_processor` FOREIGN KEY (`processor`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ram` FOREIGN KEY (`ram`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `position`
--
ALTER TABLE `position`
  ADD CONSTRAINT `position_ibfk_1` FOREIGN KEY (`machine_id`) REFERENCES `machine` (`machine_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `position_ibfk_2` FOREIGN KEY (`lab_id`) REFERENCES `lab` (`lab_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `repair_history`
--
ALTER TABLE `repair_history`
  ADD CONSTRAINT `cid_fk` FOREIGN KEY (`complaint_book_id`) REFERENCES `complaint_book` (`complaint_book_id`),
  ADD CONSTRAINT `repair_history_ibfk_1` FOREIGN KEY (`machine_id`) REFERENCES `machine` (`machine_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `software`
--
ALTER TABLE `software`
  ADD CONSTRAINT `FK_company2` FOREIGN KEY (`company`) REFERENCES `company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_name2` FOREIGN KEY (`name`) REFERENCES `name` (`name_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `specification`
--
ALTER TABLE `specification`
  ADD CONSTRAINT `specification_ibfk_1` FOREIGN KEY (`name_id`) REFERENCES `name` (`name_id`);

--
-- Constraints for table `system_transfer_report`
--
ALTER TABLE `system_transfer_report`
  ADD CONSTRAINT `fk_str` FOREIGN KEY (`lab_id`) REFERENCES `lab` (`lab_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_streport` FOREIGN KEY (`trid`) REFERENCES `transfer_request` (`transfer_request_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `system_transfer_report_history`
--
ALTER TABLE `system_transfer_report_history`
  ADD CONSTRAINT `FKK_MID` FOREIGN KEY (`machine_id`) REFERENCES `machine` (`machine_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKK_SYSTRAN` FOREIGN KEY (`system_transfer_report_id`) REFERENCES `system_transfer_report` (`system_transfer_report_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `temp`
--
ALTER TABLE `temp`
  ADD CONSTRAINT `fk_harddisk` FOREIGN KEY (`harddisk`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_keyboardd` FOREIGN KEY (`keyboard`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_machine_id` FOREIGN KEY (`machine_id`) REFERENCES `machine` (`machine_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_monitorr` FOREIGN KEY (`monitor`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mousee` FOREIGN KEY (`mouse`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_processorr` FOREIGN KEY (`processor`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ramm` FOREIGN KEY (`ram`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `upgrade_history`
--
ALTER TABLE `upgrade_history`
  ADD CONSTRAINT `FK_Mid` FOREIGN KEY (`machine_id`) REFERENCES `machine` (`machine_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_memf` FOREIGN KEY (`memoryf`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_memi` FOREIGN KEY (`memoryi`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_prof` FOREIGN KEY (`processorf`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_proi` FOREIGN KEY (`processori`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_ramf` FOREIGN KEY (`ramf`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_rami` FOREIGN KEY (`rami`) REFERENCES `hardware` (`hardware_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
