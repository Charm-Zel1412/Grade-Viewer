-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306:3306
-- Generation Time: Mar 17, 2025 at 10:34 AM
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
-- Database: `grade_viewer`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblaccounts`
--

CREATE TABLE `tblaccounts` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `secondname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `usertype` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblaccounts`
--

INSERT INTO `tblaccounts` (`username`, `password`, `firstname`, `secondname`, `middlename`, `lastname`, `email`, `usertype`, `status`, `createdby`, `datecreated`) VALUES
('10-00079', '10-00079Clemente', 'Gina', '', 'R.', 'Clemente', '', 'PROFESSOR', 'ACTIVE', 'admin', '18/11/2024'),
('22-00014', '22-00014Marcelo', 'Ma', 'Angelica', 'Aquino', 'Marcelo', '', 'STUDENT', 'ACTIVE', 'admin', '13/11/2024'),
('22-00021', '22-00021Reginaldo', 'Hazel', 'Charm', 'Dy', 'Reginaldo', '', 'STUDENT', 'ACTIVE', 'admin', '13/11/2024'),
('admin', '123456', '', '', '', '', '', 'ADMINISTRATOR', 'ACTIVE', 'admin', '10/21/2024');

-- --------------------------------------------------------

--
-- Table structure for table `tblgrades`
--

CREATE TABLE `tblgrades` (
  `studentnumber` varchar(20) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `instructor` varchar(50) NOT NULL,
  `ID_number` varchar(50) NOT NULL,
  `grade` varchar(20) NOT NULL,
  `gradedby` varchar(50) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `datecreated` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblgrades`
--

INSERT INTO `tblgrades` (`studentnumber`, `subject_code`, `instructor`, `ID_number`, `grade`, `gradedby`, `createdby`, `datecreated`) VALUES
('22-00014', 'CS_313LAB', 'Clemente, Gina', '10-00079', '2.25', '10-00079', 'admin', '20/11/2024'),
('22-00021', 'CS_313LAB', 'Clemente, Gina', '10-00079', '1.25', '10-00079', 'admin', '20/11/2024'),
('22-00021', 'CS_221LAB', '', '', '', '', 'admin', '22/11/2024');

-- --------------------------------------------------------

--
-- Table structure for table `tbllogs`
--

CREATE TABLE `tbllogs` (
  `datelog` varchar(20) NOT NULL,
  `timelog` varchar(20) NOT NULL,
  `action` varchar(20) NOT NULL,
  `module` varchar(20) NOT NULL,
  `ID` varchar(50) NOT NULL,
  `performedby` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbllogs`
--

INSERT INTO `tbllogs` (`datelog`, `timelog`, `action`, `module`, `ID`, `performedby`) VALUES
('11/20/2024', '03:58:07am', 'Create', 'Subjects', 'CS_221LEC', 'admin'),
('11/20/2024', '03:58:37am', 'Update', 'Subjects', 'CS_221LEC', 'admin'),
('11/20/2024', '03:59:21am', 'Create', 'Subjects', 'CS_222LAB', 'admin'),
('11/20/2024', '03:59:31am', 'Update', 'Subjects', 'CS_222LAB', 'admin'),
('11/20/2024', '04:00:01am', 'Update', 'Subjects', 'CS_313LAB', 'admin'),
('11/20/2024', '04:00:12am', 'Update', 'Subjects', 'CS_224LEC', 'admin'),
('11/20/2024', '04:03:30am', 'Update', 'Subjects', 'CS_222LEC', 'admin'),
('11/20/2024', '04:14:21am', 'Update', 'Subjects', 'CS_221LAB', 'admin'),
('11/20/2024', '04:29:03am', 'Update', 'Grades', '22-00014', 'admin'),
('11/20/2024', '04:30:39am', 'Update', 'Grades', '22-00014', '10-00079'),
('11/20/2024', '04:30:45am', 'Update', 'Grades', '22-00014', '10-00079'),
('11/20/2024', '04:33:38am', 'Update', 'Grades', '22-00021', '10-00079'),
('11/20/2024', '04:35:11am', 'Update', 'Grades', '22-00021', 'admin'),
('11/20/2024', '04:35:16am', 'Update', 'Grades', '22-00021', 'admin'),
('11/20/2024', '04:35:20am', 'Update', 'Grades', '22-00021', 'admin'),
('11/20/2024', '04:36:38am', 'Update', 'Grades', '22-00021', 'admin'),
('11/20/2024', '04:37:45am', 'Create', 'Subject List', '22-00014', 'admin'),
('11/20/2024', '04:37:58am', 'Create', 'Subject List', '22-00021', 'admin'),
('11/20/2024', '04:38:36am', 'Update', 'Grades', '22-00014', 'admin'),
('11/20/2024', '04:38:41am', 'Update', 'Grades', '22-00014', 'admin'),
('11/20/2024', '04:39:21am', 'Update', 'Grades', '22-00021', '10-00079'),
('11/20/2024', '04:39:52am', 'Update', 'Grades', '22-00014', '10-00079'),
('11/20/2024', '04:45:21am', 'Update', 'Grades', '22-00014', '10-00079'),
('11/20/2024', '04:45:27am', 'Update', 'Grades', '22-00021', '10-00079'),
('11/20/2024', '04:50:44am', 'Create', 'Professors', 'w', 'admin'),
('11/20/2024', '04:54:24am', 'Update', 'Accounts', '22-00014', 'admin'),
('11/20/2024', '04:54:30am', 'Update', 'Accounts', '22-00014', 'admin'),
('11/20/2024', '04:59:05am', 'Update', 'Accounts', '10-00079', 'admin'),
('11/20/2024', '04:59:11am', 'Update', 'Accounts', '10-00079', 'admin'),
('11/20/2024', '05:03:21am', 'Update', 'Grades', '22-00014', 'admin'),
('11/20/2024', '05:03:28am', 'Update', 'Grades', '22-00014', 'admin'),
('11/20/2024', '05:04:04am', 'Update', 'Grades', '22-00014', '10-00079'),
('11/20/2024', '05:04:09am', 'Update', 'Grades', '22-00014', '10-00079'),
('11/21/2024', '03:53:26pm', 'Create', 'Accounts', 'clemente', 'admin'),
('11/21/2024', '03:54:10pm', 'Update', 'Accounts', 'clemente', 'admin'),
('11/21/2024', '03:55:39pm', 'Create', 'Students', '22-00022', 'admin'),
('11/21/2024', '03:56:45pm', 'Create', 'Subject List', '22-00022', 'admin'),
('11/21/2024', '03:56:53pm', 'Delete', 'Subject List', '22-00022', 'admin'),
('11/21/2024', '03:57:30pm', 'Create', 'Professors', 'ID1223', 'admin'),
('11/21/2024', '03:59:31pm', 'Assign Subject', 'Professors', '10-00079', 'admin'),
('11/21/2024', '04:01:16pm', 'Create', 'Subjects', 'ITC127LEC', 'admin'),
('11/21/2024', '04:02:36pm', 'Update', 'Grades', '22-00014', 'admin'),
('11/21/2024', '04:04:35pm', 'Update', 'Grades', '22-00014', '10-00079'),
('11/22/2024', '08:37:16pm', 'Assign Subject', 'Professors', '10-00079', 'admin'),
('11/22/2024', '08:59:29pm', 'Create', 'Subject List', '22-00021', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tblprofessors`
--

CREATE TABLE `tblprofessors` (
  `ID_number` varchar(20) NOT NULL,
  `Pfirstname` varchar(50) NOT NULL,
  `Psecondname` varchar(50) NOT NULL,
  `Pmiddlename` varchar(50) NOT NULL,
  `Plastname` varchar(50) NOT NULL,
  `faculty` varchar(150) NOT NULL,
  `subject_1` varchar(50) DEFAULT NULL,
  `subject_2` varchar(50) DEFAULT NULL,
  `subject_3` varchar(50) DEFAULT NULL,
  `subject_4` varchar(50) DEFAULT NULL,
  `subject_5` varchar(50) DEFAULT NULL,
  `subject_6` varchar(50) DEFAULT NULL,
  `subject_7` varchar(50) DEFAULT NULL,
  `subject_8` varchar(50) DEFAULT NULL,
  `createdby` varchar(50) NOT NULL,
  `datecreated` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblprofessors`
--

INSERT INTO `tblprofessors` (`ID_number`, `Pfirstname`, `Psecondname`, `Pmiddlename`, `Plastname`, `faculty`, `subject_1`, `subject_2`, `subject_3`, `subject_4`, `subject_5`, `subject_6`, `subject_7`, `subject_8`, `createdby`, `datecreated`) VALUES
('10-00079', 'Gina', '', 'R.', 'Clemente', 'Computer Science', 'CS_313LAB', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'admin', '18/11/2024');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `studentnumber` varchar(20) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `secondname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `course` varchar(100) NOT NULL,
  `yearlevel` varchar(20) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `datecreated` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudents`
--

INSERT INTO `tblstudents` (`studentnumber`, `firstname`, `secondname`, `middlename`, `lastname`, `course`, `yearlevel`, `createdby`, `datecreated`) VALUES
('22-00014', 'Ma', 'Angelica', 'Aquino', 'Marcelo', 'Bachelor of Science in Computer Science', '3RD', 'admin', '13/11/2024'),
('22-00021', 'Hazel', 'Charm', 'Dy', 'Reginaldo', 'Bachelor of Science in Computer Science', '3RD', 'admin', '13/11/2024');

-- --------------------------------------------------------

--
-- Table structure for table `tblsubjects`
--

CREATE TABLE `tblsubjects` (
  `subject_code` varchar(50) NOT NULL,
  `subject_description` varchar(1000) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `course_1` varchar(200) DEFAULT NULL,
  `course_2` varchar(200) DEFAULT NULL,
  `course_3` varchar(200) DEFAULT NULL,
  `course_4` varchar(200) DEFAULT NULL,
  `course_5` varchar(200) DEFAULT NULL,
  `course_6` varchar(200) DEFAULT NULL,
  `course_7` varchar(200) DEFAULT NULL,
  `course_8` varchar(200) DEFAULT NULL,
  `createdby` varchar(50) NOT NULL,
  `datecreated` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsubjects`
--

INSERT INTO `tblsubjects` (`subject_code`, `subject_description`, `unit`, `course_1`, `course_2`, `course_3`, `course_4`, `course_5`, `course_6`, `course_7`, `course_8`, `createdby`, `datecreated`) VALUES
('CS_221LAB', 'Object Oriented Programming (LAB)', '1', 'Bachelor of Science in Computer Science', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'admin', '10/11/2024'),
('CS_221LEC', 'Object Oriented Programming (LEC)', '2', 'Bachelor of Science in Computer Science', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'admin', '20/11/2024'),
('CS_222LAB', 'Computer Architecture (LAB)', '1', 'Bachelor of Science in Computer Science', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'admin', '20/11/2024'),
('CS_222LEC', 'Computer Architecture (LEC)', '2', 'Bachelor of Science in Computer Science', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'admin', '10/11/2024'),
('CS_224LEC', 'Networks and Comminucation (LEC)', '2', 'Bachelor of Science in Computer Science', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'admin', '19/11/2024'),
('CS_313LAB', 'Elective 1 (LAB)', '1', 'N/A', 'Bachelor of Science in Computer Science', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'admin', '19/11/2024'),
('ITC127LEC', 'Advance Database Systems (LEC)', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin', '21/11/2024');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblaccounts`
--
ALTER TABLE `tblaccounts`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `tblprofessors`
--
ALTER TABLE `tblprofessors`
  ADD PRIMARY KEY (`ID_number`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`studentnumber`);

--
-- Indexes for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  ADD PRIMARY KEY (`subject_code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
