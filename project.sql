-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2021 at 11:09 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `annID` int(20) NOT NULL,
  `id` int(20) NOT NULL,
  `nako` varchar(50) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `mokwalo` varchar(300) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`annID`, `id`, `nako`, `author`, `title`, `mokwalo`, `type`) VALUES
(1, 5, '2021-03-11 14:34:59', 'admin@school.com', '', 'An example of an announcement made by a user, comments can be added to the announcement by other users, the colors of the announcements vary by the type of announcement posted.', '0'),
(12, 0, '2021-April-27 01:19:43', 'lesd@school.com', '', 'Hello', '1');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `studentid` int(20) NOT NULL,
  `teacher` int(10) DEFAULT NULL,
  `grade` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`studentid`, `teacher`, `grade`) VALUES
(28, 30, 2),
(28, 30, 1),
(34, 30, 1),
(35, 30, 3),
(37, 30, 7),
(40, 31, 0),
(41, 31, 0),
(43, 30, 7);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) NOT NULL,
  `author` varchar(50) NOT NULL,
  `mokwalo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `author`, `mokwalo`) VALUES
(1, 'lesd@school.com', 'Hello.. When can we start using the system'),
(1, 'thebe@school.com', 'Hello... Can you upload the yearly financial report');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation`
--

CREATE TABLE `evaluation` (
  `resID` int(11) NOT NULL,
  `behaviour` int(10) NOT NULL,
  `activities` int(10) NOT NULL,
  `punctuality` int(10) NOT NULL,
  `neatness` int(10) NOT NULL,
  `assignments` int(10) NOT NULL,
  `health` int(10) NOT NULL,
  `remarks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation`
--

INSERT INTO `evaluation` (`resID`, `behaviour`, `activities`, `punctuality`, `neatness`, `assignments`, `health`, `remarks`) VALUES
(25, 4, 5, 5, 4, 4, 4, 'We have uploaded more Science homework, please help the Thabiso get more marks, overall, he is a well mannerd kid.'),
(26, 1, 1, 1, 1, 1, 1, 'Overall A Well Mannered Student, please use agriculture files uploaded to help this student');

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE `instructor` (
  `id` int(20) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `grade` varchar(50) DEFAULT NULL,
  `class` text NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `phone` int(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `instructor`
--

INSERT INTO `instructor` (`id`, `name`, `surname`, `grade`, `class`, `role`, `phone`, `email`) VALUES
(30, 'Lesedi', 'Fane', '7', '', 'HOD', 451354, 'lesd@school.com'),
(31, 'hp', 'notepad', '0', '', 'Teacher', 71777777, 'hp@school.com'),
(42, 'Batsile', 'Tumo', '0', '', 'Temp', 7888222, 'tumo@school.com');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `id` int(20) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `phone` int(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `occupation` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`id`, `name`, `surname`, `phone`, `email`, `occupation`) VALUES
(28, 'Keffie', 'Kgosimore', 70122333, 'keffie@school.com', 'Self Employed'),
(34, 'Jane', 'Doe', 73744433, 'jane@school.com', 'Web Developer'),
(35, 'Gorata', 'Fane', 73334421, 'kuda@school.com', 'Driver'),
(37, 'Rre', 'Thebe', 75648269, 'thebe@school.com', 'Web Developer'),
(40, 'Gorata', 'Fane', 71234567, 'k@school.com', 'Unemployed'),
(41, 'Calvin', 'Thako', 755485524, 'thako@gmail.com', 'Shoemaker'),
(43, 'Dobe', 'Dobena', 71112221, 'thapi@mail.com', 'Dumila Dithapi');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(20) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `parent` varchar(20) DEFAULT NULL,
  `grade` varchar(50) DEFAULT NULL,
  `class` text NOT NULL,
  `instructor` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `surname`, `parent`, `grade`, `class`, `instructor`) VALUES
(28, 'Thabiso', 'Kgosimore', 'keffi@school.com', '2', '', 30),
(34, 'Test', 'Subject', '0', '1', '', 30),
(35, 'Kago', 'Fane', 'kuda@school.com', '3', '', 30),
(37, 'Leano', 'Thebe', 'thebe@school.com', '7', '', 30),
(40, 'Thapelo', 'Fane', 'k@school.com', '0', '', 31),
(41, 'Kagiso', 'Thako', 'thako@gmail.com', '0', '', 31),
(43, 'Winnie', 'Dobena', 'thapi@mail.com', '7', '', 30);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `stuID` int(20) DEFAULT NULL,
  `English` int(20) DEFAULT NULL,
  `Setswana` int(20) DEFAULT NULL,
  `Mathematics` int(20) DEFAULT NULL,
  `Science` int(20) DEFAULT NULL,
  `CAPA` int(20) DEFAULT NULL,
  `Social_Studies` int(20) DEFAULT NULL,
  `Agriculture` int(20) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `month` varchar(30) NOT NULL,
  `resID` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`stuID`, `English`, `Setswana`, `Mathematics`, `Science`, `CAPA`, `Social_Studies`, `Agriculture`, `type`, `month`, `resID`) VALUES
(37, 67, 89, 83, 44, 23, 65, 11, 'March', '14/April/2021', 9),
(37, 25, 11, 35, 41, 23, 12, 22, 'Term 1', '14/April/2021', 10),
(37, 50, 60, 45, 55, 62, 64, 51, 'Term 2', '14/April/2021', 11),
(28, 79, 46, 56, 22, 33, 78, 45, 'June', '14/April/2021', 13),
(34, 14, 99, 77, 56, 34, 77, 22, 'February', '19/April/2021', 14),
(37, 99, 89, 83, 80, 62, 76, 11, 'Term 1', '01/May/2021', 15),
(37, 67, 89, 83, 80, 23, 65, 11, 'March', '01/May/2021', 16),
(28, 67, 89, 56, 45, 78, 56, 75, 'Term 3', '02/May/2021', 25),
(37, 76, 85, 78, 57, 55, 87, 46, 'Term 3', '03/May/2021', 26);

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(20) NOT NULL,
  `grade` int(20) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `term` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `grade`, `subject`, `term`, `filename`, `created`) VALUES
(8, 7, 'Setswana', 'Term 1', '1-4-null.docx', '2021-03-29 21:59:51'),
(10, 7, 'Mathematics', 'Term 1', '9-K.A. Stroud - Engineering Mathematics-Red Globe Press (2013).pdf', '2021-04-01 00:24:16'),
(11, 7, 'English', 'Term 1', '11-Lecture1.pdf', '2021-04-12 13:07:03'),
(12, 7, 'content', 'Term 2', '12-Asignment1-2021.pdf', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `designation` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `designation`) VALUES
(1, 'admin', 'admin@school.com', '1a1dc91c907325c69271ddf0c944bc72', 'admin'),
(28, 'Thabiso', 'kef@school.com', '1a1dc91c907325c69271ddf0c944bc72', 'student'),
(30, 'Lesedi', 'lesd@school.com', '1a1dc91c907325c69271ddf0c944bc72', 'instructor'),
(31, 'hp', 'hp@school.com', '1a1dc91c907325c69271ddf0c944bc72', 'instructor'),
(34, 'Test', 'jane@school.com', '1a1dc91c907325c69271ddf0c944bc72', 'student'),
(35, 'Kago', 'kuda@school.com', '1a1dc91c907325c69271ddf0c944bc72', 'student'),
(37, 'Leano', 'thebe@school.com', '1a1dc91c907325c69271ddf0c944bc72', 'student'),
(40, 'Thapelo', 'k@school.com', '1a1dc91c907325c69271ddf0c944bc72', 'student'),
(41, 'Kagiso', 'thako@gmail.com', '1a1dc91c907325c69271ddf0c944bc72', 'student'),
(42, 'Batsile', 'tumo@school.com', '1a1dc91c907325c69271ddf0c944bc72', 'instructor'),
(43, 'Winnie', 'thapi@mail.com', 'af3b5afea68feb99e19147bccb73da90', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`annID`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD KEY `class_ibfk_1` (`studentid`),
  ADD KEY `ins_id` (`teacher`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD KEY `ann_id` (`id`);

--
-- Indexes for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD KEY `results_id` (`resID`);

--
-- Indexes for table `instructor`
--
ALTER TABLE `instructor`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD KEY `parents_ibfk_1` (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD KEY `students_ibfk_1` (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`resID`),
  ADD KEY `subjects_ibfk_1` (`stuID`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `annID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `resID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`studentid`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ins_id` FOREIGN KEY (`teacher`) REFERENCES `instructor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `ann_id` FOREIGN KEY (`id`) REFERENCES `announcements` (`annID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `results_id` FOREIGN KEY (`resID`) REFERENCES `subjects` (`resID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `instructor`
--
ALTER TABLE `instructor`
  ADD CONSTRAINT `instructor_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `parents_ibfk_1` FOREIGN KEY (`id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`stuID`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
