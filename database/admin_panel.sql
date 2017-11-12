-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2017 at 06:10 PM
-- Server version: 10.1.24-MariaDB
-- PHP Version: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin_panel`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_login`
--

CREATE TABLE `admin_login` (
  `AL` int(11) NOT NULL,
  `Username` varchar(120) NOT NULL,
  `Password` text NOT NULL,
  `TextPassword` varchar(120) NOT NULL,
  `Name` varchar(120) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Address` text NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '1',
  `Txndate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_login`
--

INSERT INTO `admin_login` (`AL`, `Username`, `Password`, `TextPassword`, `Name`, `Phone`, `Address`, `Status`, `Txndate`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'Admin', '9494822568', '', 1, '2017-11-01 19:10:10');

-- --------------------------------------------------------

--
-- Table structure for table `changeinput`
--

CREATE TABLE `changeinput` (
  `CI` int(11) NOT NULL,
  `TableName` varchar(120) NOT NULL,
  `ColumName` varchar(120) NOT NULL,
  `ChangeTo` varchar(120) NOT NULL,
  `CountOf` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `changeinputnamevalue`
--

CREATE TABLE `changeinputnamevalue` (
  `CINV` int(11) NOT NULL,
  `CI` int(11) NOT NULL,
  `Name` varchar(120) NOT NULL,
  `Value` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `column_settings`
--

CREATE TABLE `column_settings` (
  `CUS` int(11) NOT NULL,
  `TableName` varchar(120) NOT NULL,
  `ColumName` varchar(120) NOT NULL,
  `Status` int(11) NOT NULL,
  `Txnuser` int(11) NOT NULL,
  `Txndate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `form_settings`
--

CREATE TABLE `form_settings` (
  `FUS` int(11) NOT NULL,
  `TableName` varchar(120) NOT NULL,
  `ColumName` varchar(120) NOT NULL,
  `Status` int(11) NOT NULL,
  `Txnuser` int(11) NOT NULL,
  `Txndate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `form_settings`
--

INSERT INTO `form_settings` (`FUS`, `TableName`, `ColumName`, `Status`, `Txnuser`, `Txndate`) VALUES
(1, 'district_master', 'TxnDate', 1, 1, '2017-11-01 19:28:19'),
(2, 'category_master', 'TxnDate', 1, 1, '2017-11-01 19:30:29'),
(3, 'add_users', 'TxnDate', 1, 1, '2017-11-01 19:31:48'),
(4, 'constituency_master', 'TxnDate', 1, 1, '2017-11-01 19:48:44');

-- --------------------------------------------------------

--
-- Table structure for table `mappingforeignkey`
--

CREATE TABLE `mappingforeignkey` (
  `MFK_slno` int(11) NOT NULL,
  `TableName` varchar(120) NOT NULL,
  `ColumnName` varchar(120) NOT NULL,
  `ColumnID` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mappingforeignkey`
--

INSERT INTO `mappingforeignkey` (`MFK_slno`, `TableName`, `ColumnName`, `ColumnID`) VALUES
(1, 'category_master', 'Name', 'CM_ID'),
(2, 'district_master', 'Name', 'DM_ID'),
(3, 'constituency_master', 'Name', 'COM_ID'),
(4, 'village_master', 'Name', 'VM_ID');

-- --------------------------------------------------------

--
-- Table structure for table `onchange`
--

CREATE TABLE `onchange` (
  `OC` int(11) NOT NULL,
  `ParentTable` varchar(120) NOT NULL,
  `ChildTable` varchar(120) NOT NULL,
  `ChildColumnMapp` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `onchange`
--

INSERT INTO `onchange` (`OC`, `ParentTable`, `ChildTable`, `ChildColumnMapp`) VALUES
(1, 'constituency_master', 'village_master', 'COM_ID');

-- --------------------------------------------------------

--
-- Table structure for table `table_settings`
--

CREATE TABLE `table_settings` (
  `TUS` int(11) NOT NULL,
  `TableName` varchar(120) NOT NULL,
  `Status` int(11) NOT NULL,
  `Txnuser` int(11) NOT NULL,
  `Txndate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `validations`
--

CREATE TABLE `validations` (
  `VD` int(11) NOT NULL,
  `TableName` varchar(120) NOT NULL,
  `ColumName` varchar(120) NOT NULL,
  `Status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `validations`
--

INSERT INTO `validations` (`VD`, `TableName`, `ColumName`, `Status`) VALUES
(1, 'district_master', 'Name', 1),
(2, 'category_master', 'Name', 1),
(3, 'constituency_master', 'Name', 1),
(4, 'village_master', 'Name', 1),
(5, 'add_users', 'Name', 1),
(6, 'add_users', 'Phone', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_login`
--
ALTER TABLE `admin_login`
  ADD PRIMARY KEY (`AL`);

--
-- Indexes for table `changeinput`
--
ALTER TABLE `changeinput`
  ADD PRIMARY KEY (`CI`);

--
-- Indexes for table `changeinputnamevalue`
--
ALTER TABLE `changeinputnamevalue`
  ADD PRIMARY KEY (`CINV`),
  ADD KEY `CI` (`CI`);

--
-- Indexes for table `column_settings`
--
ALTER TABLE `column_settings`
  ADD PRIMARY KEY (`CUS`);

--
-- Indexes for table `form_settings`
--
ALTER TABLE `form_settings`
  ADD PRIMARY KEY (`FUS`);

--
-- Indexes for table `mappingforeignkey`
--
ALTER TABLE `mappingforeignkey`
  ADD PRIMARY KEY (`MFK_slno`);

--
-- Indexes for table `onchange`
--
ALTER TABLE `onchange`
  ADD PRIMARY KEY (`OC`);

--
-- Indexes for table `table_settings`
--
ALTER TABLE `table_settings`
  ADD PRIMARY KEY (`TUS`);

--
-- Indexes for table `validations`
--
ALTER TABLE `validations`
  ADD PRIMARY KEY (`VD`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_login`
--
ALTER TABLE `admin_login`
  MODIFY `AL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `changeinput`
--
ALTER TABLE `changeinput`
  MODIFY `CI` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `changeinputnamevalue`
--
ALTER TABLE `changeinputnamevalue`
  MODIFY `CINV` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `column_settings`
--
ALTER TABLE `column_settings`
  MODIFY `CUS` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `form_settings`
--
ALTER TABLE `form_settings`
  MODIFY `FUS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `mappingforeignkey`
--
ALTER TABLE `mappingforeignkey`
  MODIFY `MFK_slno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `onchange`
--
ALTER TABLE `onchange`
  MODIFY `OC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `table_settings`
--
ALTER TABLE `table_settings`
  MODIFY `TUS` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `validations`
--
ALTER TABLE `validations`
  MODIFY `VD` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `changeinputnamevalue`
--
ALTER TABLE `changeinputnamevalue`
  ADD CONSTRAINT `changeinputnamevalue_ibfk_1` FOREIGN KEY (`CI`) REFERENCES `changeinput` (`CI`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
