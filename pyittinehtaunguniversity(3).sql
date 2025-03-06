-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2025 at 07:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pyittinehtaunguniversity`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `activity_id` varchar(255) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `timestamp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

CREATE TABLE `assignment` (
  `AID` varchar(255) NOT NULL,
  `Code` varchar(255) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `deadline` varchar(255) NOT NULL,
  `full_marks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignment`
--

INSERT INTO `assignment` (`AID`, `Code`, `Title`, `deadline`, `full_marks`) VALUES
('A001', '600CS', 'Assignment 1 CS', '2025-12-25', '100'),
('A002', '6006CEM', 'CEM Assigment 1', '2025-02-25', '50'),
('A003', '6006CEM', 'CEM Assigment 2', '2025-03-14', '70');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('new','read','responded') DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `full_name`, `email`, `subject`, `message`, `submission_date`, `status`) VALUES
(1, 'Su San', 'susan@gmail.com', 'Academic Question', 'Hello, I wanna contact the univeristy', '2025-03-06 11:21:33', 'responded');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `Code` varchar(255) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Credits` varchar(255) NOT NULL,
  `FID` varchar(255) NOT NULL,
  `semester` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`Code`, `Title`, `Credits`, `FID`, `semester`) VALUES
('6006CEM', 'Information Systems', '20', 'F001', 'Semester 1'),
('600CS', 'Computer Sciences', '20', 'F003', 'Semester 1');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `SID` varchar(255) NOT NULL,
  `Code` varchar(255) NOT NULL,
  `date_enrolled` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`SID`, `Code`, `date_enrolled`) VALUES
('S001', '6006CEM', '02/12/2005'),
('S009', '600CS', '2025-03-06'),
('S011', '600CS', '2025-03-06'),
('S006', '600CS', '2025-03-06'),
('S007', '600CS', '2025-03-06'),
('S002', '600CS', '2025-03-06'),
('S013', '600CS', '2025-03-06');

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `SID` varchar(255) NOT NULL,
  `AID` varchar(255) NOT NULL,
  `graded_mark` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`SID`, `AID`, `graded_mark`) VALUES
('S001', 'A001', '70'),
('S001', 'A003', '50'),
('S009', 'A001', '60'),
('S011', 'A001', '12'),
('S013', 'A001', '100'),
('S001', 'A002', '40');

-- --------------------------------------------------------

--
-- Table structure for table `login_details`
--

CREATE TABLE `login_details` (
  `ID` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_details`
--

INSERT INTO `login_details` (`ID`, `username`, `password`) VALUES
('A001', 'admin123', 'admin12345678'),
('F001', 'dune@32', 'dune3455'),
('F002', 'sam', 'sam12345'),
('F003', 'john', 'john12345'),
('F004', 'thom', 'thom12345'),
('S001', 'hay123', 'hay12345678'),
('S002', 'man123', 'man12345678'),
('S003', 'naing3432', 'naing12345678'),
('S006', 'kyawkyaw', 'password123'),
('S007', 'mama', 'password123'),
('S008', 'zawzaw', 'password123'),
('S009', 'ayeaye', 'password123'),
('S010', 'tuntun', 'password123'),
('S011', 'hlahla', 'password123'),
('S012', 'winwin', 'password123'),
('S013', 'myomyo', 'password123'),
('S014', 'soesoe', 'password123');

-- --------------------------------------------------------

--
-- Table structure for table `personal_details`
--

CREATE TABLE `personal_details` (
  `ID` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_of_birth` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `Role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal_details`
--

INSERT INTO `personal_details` (`ID`, `full_name`, `email`, `date_of_birth`, `address`, `Role`) VALUES
('A001', 'Admin123', 'admin@gmail.com', '29/05/2003', 'Yangon', 'Admin'),
('F001', 'Dune Alterman Sr', 'dune@gmail.com', '13/02/1992', 'Yangon', 'Faculty'),
('F002', 'Sam Thomson Jame', 'sam@gmail.com', '', '', 'Faculty'),
('F003', 'Jhon', 'john@gmail.com', '1991-02-02', 'Mandalay', 'Faculty'),
('F004', 'Thomsom', 'thom@gmail.com', '1994-03-06', 'Yangon', 'Faculty'),
('S001', 'Hay Man Su Naing', 'hay@gmail.com', '29/05/2004', 'Yangon', 'Student'),
('S002', 'Mann Pyae', 'mann@gmail.com', '14/02/2001', 'Mandalay', 'Student'),
('S006', 'Kyaw Kyaw', 'kyawkyaw@gmail.com', '', '', 'Student'),
('S007', 'Ma Ma', 'mama@gmail.com', '', '', 'Student'),
('S008', 'Zaw Zaw', 'zawzaw@gmail.com', '', '', 'Student'),
('S009', 'Aye Aye', 'ayeaye@gmail.com', '', '', 'Student'),
('S010', 'Tun Tun', 'tuntun@gmail.com', '', '', 'Student'),
('S011', 'Hla Hla', 'hlahla@gmail.com', '', '', 'Student'),
('S012', 'Win Win', 'winwin@gmail.com', '', '', 'Student'),
('S013', 'Myo Myo', 'myomyo@gmail.com', '', '', 'Student'),
('S014', 'Soe Soe', 'soesoe@gmail.com', '', '', 'Student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`AID`),
  ADD KEY `Code` (`Code`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`Code`),
  ADD KEY `FID` (`FID`);

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD KEY `Code` (`Code`),
  ADD KEY `SID` (`SID`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD KEY `AID` (`AID`),
  ADD KEY `SID` (`SID`);

--
-- Indexes for table `login_details`
--
ALTER TABLE `login_details`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `personal_details`
--
ALTER TABLE `personal_details`
  ADD UNIQUE KEY `ID` (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignment`
--
ALTER TABLE `assignment`
  ADD CONSTRAINT `assignment_ibfk_1` FOREIGN KEY (`Code`) REFERENCES `courses` (`Code`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`FID`) REFERENCES `login_details` (`ID`);

--
-- Constraints for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD CONSTRAINT `enrollment_ibfk_1` FOREIGN KEY (`Code`) REFERENCES `courses` (`Code`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollment_ibfk_2` FOREIGN KEY (`SID`) REFERENCES `login_details` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `grade`
--
ALTER TABLE `grade`
  ADD CONSTRAINT `grade_ibfk_1` FOREIGN KEY (`AID`) REFERENCES `assignment` (`AID`) ON DELETE CASCADE,
  ADD CONSTRAINT `grade_ibfk_2` FOREIGN KEY (`SID`) REFERENCES `login_details` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `personal_details`
--
ALTER TABLE `personal_details`
  ADD CONSTRAINT `personal_details_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `login_details` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
