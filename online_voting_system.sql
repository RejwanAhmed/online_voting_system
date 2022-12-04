-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2021 at 04:01 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online_voting_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_email_sending_confirmation`
--

CREATE TABLE `admin_email_sending_confirmation` (
  `id` int(11) NOT NULL,
  `email` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_email_sending_confirmation`
--

INSERT INTO `admin_email_sending_confirmation` (`id`, `email`, `password`) VALUES
(2, 'amtrbml1ZWxlY3Rpb24yQGdtYWlsLmNvbQ==', 'amtrbml1XzIwMjFfZWxlY3Rpb25fMg==');

-- --------------------------------------------------------

--
-- Table structure for table `admin_email_sending_id`
--

CREATE TABLE `admin_email_sending_id` (
  `id` int(11) NOT NULL,
  `email` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_email_sending_id`
--

INSERT INTO `admin_email_sending_id` (`id`, `email`, `password`) VALUES
(2, 'amtrbml1ZWxlY3Rpb25AZ21haWwuY29t', 'amtrbml1XzIwMjFfZWxlY3Rpb24=');

-- --------------------------------------------------------

--
-- Table structure for table `admin_info`
--

CREATE TABLE `admin_info` (
  `id` int(50) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_info`
--

INSERT INTO `admin_info` (`id`, `username`, `password`) VALUES
(1, 'a9f54ce7ad9534e4d3379a394a210563', '827ccb0eea8a706c4c34a16891f84e7b');

-- --------------------------------------------------------

--
-- Table structure for table `ballot`
--

CREATE TABLE `ballot` (
  `id` int(11) NOT NULL,
  `election_id` int(255) NOT NULL,
  `panel_id` int(255) NOT NULL,
  `election_name` varchar(200) NOT NULL,
  `election_year` int(50) NOT NULL,
  `1_i` varchar(1000) NOT NULL,
  `2_i` varchar(1000) NOT NULL,
  `3_i` varchar(1000) NOT NULL,
  `4_i` varchar(1000) NOT NULL,
  `5_i` varchar(1000) NOT NULL,
  `6_i` varchar(1000) NOT NULL,
  `7_i` varchar(1000) NOT NULL,
  `8_i` varchar(1000) NOT NULL,
  `9_i` varchar(1000) NOT NULL,
  `10_i` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `create_election`
--

CREATE TABLE `create_election` (
  `id` int(255) NOT NULL,
  `panel_id` int(255) NOT NULL,
  `election_name` varchar(200) NOT NULL,
  `election_year` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `1_i` varchar(1000) NOT NULL,
  `2_i` varchar(1000) NOT NULL,
  `3_i` varchar(1000) NOT NULL,
  `4_i` varchar(1000) NOT NULL,
  `5_i` varchar(1000) NOT NULL,
  `6_i` varchar(1000) NOT NULL,
  `7_i` varchar(1000) NOT NULL,
  `8_i` varchar(1000) NOT NULL,
  `9_i` varchar(1000) NOT NULL,
  `10_i` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(50) NOT NULL,
  `department_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `department_name`) VALUES
(1, 'Computer Science & Engineering'),
(2, 'Environmental Science & Engineering'),
(3, 'Accounting & Information System'),
(4, 'Anthropology'),
(5, 'Economics'),
(6, 'Bangla Language & Literature'),
(7, 'Electrical and Electronic Engineering'),
(8, 'English Language & Literature'),
(9, 'Film & Media'),
(10, 'Finance & Banking'),
(11, 'Fine Arts'),
(12, 'Folklore'),
(13, 'Human Resource Management'),
(14, 'Law & Justice'),
(15, 'Local Goverment & Urban Development'),
(16, 'Management'),
(17, 'Music'),
(18, 'Philosophy'),
(19, 'Population Science'),
(20, 'Public Administration & Governance Studies'),
(21, 'Sociology'),
(22, 'Statistics'),
(24, 'Theatre & Performance Studies');

-- --------------------------------------------------------

--
-- Table structure for table `election_designation`
--

CREATE TABLE `election_designation` (
  `id` int(50) NOT NULL,
  `sequence_number` int(50) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `option` varchar(10) NOT NULL,
  `restriction` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `election_designation`
--

INSERT INTO `election_designation` (`id`, `sequence_number`, `designation`, `option`, `restriction`) VALUES
(77, 2, 'Vice President', 'single', 0),
(78, 1, 'President', 'single', 0),
(79, 3, 'General Secretary', 'single', 0),
(82, 5, 'Organizational Secretary', 'single', 0),
(83, 6, 'Treasurer', 'single', 0),
(84, 8, 'Sports and Culture Secretary', 'single', 0),
(85, 7, 'Education and Research Secretary', 'single', 0),
(86, 9, 'Bureau and Publicity Secretary', 'single', 0),
(87, 10, 'Member', 'multiple', 6),
(88, 4, 'Joint Secretary', 'single', 0);

-- --------------------------------------------------------

--
-- Table structure for table `election_panel`
--

CREATE TABLE `election_panel` (
  `id` int(255) NOT NULL,
  `panel_id` int(255) NOT NULL,
  `panel_name` varchar(1000) NOT NULL,
  `status` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `opponents_login_info`
--

CREATE TABLE `opponents_login_info` (
  `id` int(220) NOT NULL,
  `election_id` int(220) NOT NULL,
  `panel_id` int(255) NOT NULL,
  `opponent_id` int(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_information`
--

CREATE TABLE `teacher_information` (
  `id` int(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_no` varchar(11) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teacher_information`
--

INSERT INTO `teacher_information` (`id`, `name`, `designation`, `department`, `email`, `contact_no`, `status`) VALUES
(1, 'AHM Kamal', 'Professor', '1', 'ahmkctg@yahoo.com', '01732226402', 0),
(2, 'Md Mijanur Rahman', 'Professor', '1', 'mijanjkkniu@gmail.com', '01712594569', 0),
(3, 'Md Saiful Islam', 'Professor', '1', 'saifulmath@yahoo.com', '01552637446', 0),
(4, 'Sheikh Sujan Ali', 'Professor', '1', 'msujanali@gmail.com', '01732155234', 0),
(5, 'Mst Jannatul Ferdous', 'Professor', '1', 'mjannatul@gmail.com', '01710695925', 0),
(6, 'Uzzal Kumar Prodhan', 'Associate Professor', '1', 'uzzal_bagerhat@yahoo.com', '01718045304', 0),
(7, 'Md Selim Al Mamun', 'Associate Professor', '1', 'mamun0013@yahoo.com', '01705575778', 0),
(8, 'Subrata Kumar Das', 'Associate Professor', '1', 'sdas_ce@yahoo.com', '01721006007', 0),
(9, 'Tushar Kanti Saha', 'Associate Professor', '1', 'tusharcsebd@gmail.com', '01711028510', 0),
(10, 'Indrani Mandal', 'Associate Professor', '1', 'indranicsedu@yahoo.com', '01718333474', 0),
(11, 'Pronab Kumar Mondal', 'Associate Professor', '1', 'bappycseru@gmail.com', '01716589875', 0),
(12, 'Rubya Shaharin', 'Assistant Professor', '1', 'sunshinerr1@gmail.com', '01911261976', 0),
(13, 'Habiba Sultana', 'Assistant Professor', '1', 'srity.cse@gmail.com', '01920296771', 0),
(14, 'Kazi Mahmudul Hassan', 'Assistant Professor', '1', 'munnakazi92@gmail.com', '01676019438', 0),
(15, 'Mahbubun Nahar', 'Assistant Professor', '1', 'mahbuba.knu@gmail.com', '01770393499', 0),
(22, 'fahim', 'Associate Professor', '1', 'dipto99@gmail.com', '01681091172', 0),
(23, 'Abu Bakar', 'Associate Professor', '3', 'absiddique.cse@gmail.com', '01516175614', 1),
(24, 'Shuvongkor', 'Assistant Professor', '4', 'shuvongkorvodro@gmail.com', '01521649671', 0),
(25, 'Shamin Yeasar Apon', 'Professor', '6', 'apon10@gmail.com', '01756568927', 1),
(26, 'Piyas Biswas', 'Lecturer', '17', 'piyasbiswas.cse@gmail.com', '01695632175', 1),
(27, 'Md Rejwan Ahmed', 'Assistant Professor', '3', 'rejwancse10@gmail.com', '01681091173', 1),
(28, 'Reja', 'Lecturer', '7', 'rejwanahmed143342@gmail.com', '01641177890', 1),
(29, 'Sagor', 'Lecturer', '9', 'biswassagar4516@gmail.com', '01762508551', 1),
(30, 'Komal', 'Lecturer', '12', 'komalbanik066@gmail.com', '01719320850', 1),
(31, 'Md Arifur Rahman', 'Associate Professor', '17', 'arifur.jkkniu@gmail.com', '01712841365', 0),
(32, 'Ismat Ara Bhuiya Ila', 'Associate Professor', '24', 'ila.kotha@gmail.com', '01717451241', 0),
(33, 'Shanjay Kumar Mukharjee', 'Assistant Professor', '20', 'shanjay.jkkniu@gmail.com', '01715635319', 0),
(34, 'Md Asaduzzaman', 'Assistant Professor', '14', 'asaduzzamanlaw@gmail.com', '01735235168', 0),
(35, 'Dr Mohammad Emdadur Rashed', 'Associate Professor', '11', 'sukhon.fa@gmail.com', '01716487686', 0),
(36, 'Al Zabir', 'Assistant Professor', '24', 'zabirknu@gmail.com', '01716253627', 0);

-- --------------------------------------------------------

--
-- Table structure for table `voter_list`
--

CREATE TABLE `voter_list` (
  `id` int(255) NOT NULL,
  `election_id` int(255) NOT NULL,
  `voter_id` varchar(200) NOT NULL,
  `voter_id_status` int(50) NOT NULL,
  `teacher_mail` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_email_sending_confirmation`
--
ALTER TABLE `admin_email_sending_confirmation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_email_sending_id`
--
ALTER TABLE `admin_email_sending_id`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_info`
--
ALTER TABLE `admin_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ballot`
--
ALTER TABLE `ballot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `create_election`
--
ALTER TABLE `create_election`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `election_designation`
--
ALTER TABLE `election_designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `election_panel`
--
ALTER TABLE `election_panel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `opponents_login_info`
--
ALTER TABLE `opponents_login_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_information`
--
ALTER TABLE `teacher_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voter_list`
--
ALTER TABLE `voter_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_email_sending_confirmation`
--
ALTER TABLE `admin_email_sending_confirmation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_email_sending_id`
--
ALTER TABLE `admin_email_sending_id`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `admin_info`
--
ALTER TABLE `admin_info`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ballot`
--
ALTER TABLE `ballot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `create_election`
--
ALTER TABLE `create_election`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `election_designation`
--
ALTER TABLE `election_designation`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `election_panel`
--
ALTER TABLE `election_panel`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `opponents_login_info`
--
ALTER TABLE `opponents_login_info`
  MODIFY `id` int(220) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `teacher_information`
--
ALTER TABLE `teacher_information`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `voter_list`
--
ALTER TABLE `voter_list`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=620;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
