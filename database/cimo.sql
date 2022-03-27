-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2022 at 10:48 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cimo`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `AccountID` varchar(60) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `AccountStatus` enum('Pending','Approved','Violation','Denied') NOT NULL,
  `AccountTypeID` varchar(60) NOT NULL,
  `TokenID` varchar(60) NOT NULL,
  `DateCreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`AccountID`, `Password`, `Username`, `Email`, `AccountStatus`, `AccountTypeID`, `TokenID`, `DateCreated`) VALUES
('IDacc=61de51491a675DjQYTNsJZ2C', '$2y$10$Sgc8bJ0ef750/vzckiJfyOkRmV180pRqEZWBhuC4uSFNEA4SPBpFO', 'brgy12', 'brgy12@gmail.com', 'Approved', 'AcctTyID02', 'IDtkn=61de51491a672UdcK6tCgS1e', '2022-01-12 04:55:53'),
('IDacc=623d8c8bbee07xTdC8aiPLNV', '$2y$10$d6ZRhGa3AyMHJrIBsTjbaOB3jbxLL.UzYGWlHzlZMNn8Rh9SOb9W.', 'Balanar2022', 'jivangdiazchmsc3b@gmail.com', 'Approved', 'AcctTyID02', 'IDtkn=623d8c8bbee04k3AoTqZrX2d', '2022-03-25 10:34:03'),
('JIDacc=61f77b73def2a64CXuFEU9l', '$2y$10$xZ3FtXJ1lb.rsAp/fRZz2.no7hk11WtaeMthqjQ0hAmNWWbizc8HG', '123123123123', 'jivanchari1s143@gmail.com', 'Pending', 'AcctTyID01', 'JIDtkn=61f77b73def2b0Ge3wiQ25F', '2022-01-31 02:02:27'),
('LIDacc=61de4f2229e782CutdQTg4G', '$2y$10$n3..dX/x7QrfmRjDBqJY6.kKpW6UsfD52iDIjEwK.dPLaIxZVT9ua', 'ljor1234567', 'jivancharis143@gmail.com', 'Approved', 'AcctTyID01', 'LIDtkn=61de4f2229e7agtXbyUd5Fm', '2022-01-12 11:46:42');

-- --------------------------------------------------------

--
-- Table structure for table `account_type`
--

CREATE TABLE `account_type` (
  `AccountTypeID` varchar(60) NOT NULL,
  `AccountType` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account_type`
--

INSERT INTO `account_type` (`AccountTypeID`, `AccountType`) VALUES
('AcctTyID01', 'establishment'),
('AcctTyID02', 'barangay');

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `AddressID` varchar(60) NOT NULL,
  `Region` varchar(255) NOT NULL,
  `Province` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL,
  `Barangay` varchar(255) NOT NULL,
  `Street` varchar(255) NOT NULL,
  `Branch` varchar(255) NOT NULL,
  `CoordinateID` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`AddressID`, `Region`, `Province`, `City`, `Barangay`, `Street`, `Branch`, `CoordinateID`) VALUES
('IDadrs=61de51491ac97MHvx8RyS3mp', 'REGION VI', 'NEGROS OCCIDENTAL', 'BACOLOD CITY', 'BARANGAY 12 (POB.)', 'None', 'None', 'IDcds=61de51491ac8fU4xLZDiIurJ'),
('IDadrs=623d8c8bbee13YuJhVBbvXLK', 'REGION VI', 'NEGROS OCCIDENTAL', 'BACOLOD CITY', 'TACULING', 'None', 'None', 'IDcds=623d8c8bbee10VehF627vDyt'),
('JIDadrs=61f77b73def2d4nrR57kfhP', 'REGION II', 'BATANES', 'ITBAYAT', 'SAN RAFAEL (IDIANG)', '123', '123', 'JIDcds=61f77b73def2eGFW3eXYzob'),
('LIDadrs=61de4f2229e7bTlmqabF9IS', 'REGION VI', 'NEGROS OCCIDENTAL', 'BACOLOD CITY', 'BARANGAY 12 (POB.)', 'ARANETA', 'CINEPLEX', 'LIDcds=61de4f2229e7dSqFQ1tLzx2');

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `AreaID` varchar(60) NOT NULL,
  `SquareMeters` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`AreaID`, `SquareMeters`) VALUES
('JIDasq=61f77b73def32rKUJbYiB3m', '1234'),
('LIDasq=61de4f2229e813Lx87IPbDj', '300');

-- --------------------------------------------------------

--
-- Table structure for table `authentication_token`
--

CREATE TABLE `authentication_token` (
  `TokenID` varchar(60) NOT NULL,
  `Token` varchar(1024) NOT NULL,
  `TokenStatus` enum('offline','online') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `authentication_token`
--

INSERT INTO `authentication_token` (`TokenID`, `Token`, `TokenStatus`) VALUES
('IDtkn=61de51491a672UdcK6tCgS1e', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIxOTIuMTY4LjI1NC4xMDciLCJleHAiOjI3NTk2NjIzMzUzNiwiaWF0IjoxNjQyNjU2MTUyLCJkYXRhIjp7InRva19pZCI6IklEdGtuPTYxZGU1MTQ5MWE2NzJVZGNLNnRDZ1MxZSIsImFjY2lkIjoiSURhY2M9NjFkZTUxNDkxYTY3NURqUVlUTnNKWjJDIiwiYWRkcmVzc2lkIjoiSURhZHJzPTYxZGU1MTQ5MWFjOTdNSHZ4OFJ5UzNtcCIsInJlZ2lvbiI6IlJFR0lPTiBWSSIsInByb3ZpbmNlIjoiTkVHUk9TIE9DQ0lERU5UQUwiLCJjaXR5IjoiQkFDT0xPRCBDSVRZIiwiYnJneSI6IkJBUkFOR0FZIDEyIChQT0IuKSJ9fQ.oM66ZVmGHTy841bCpN2X9swX8CxTtzzrHcttvYqprZM', 'offline'),
('IDtkn=61de59829db23Rw2fB671jkT', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIxOTIuMTY4LjQzLjg5IiwiZXhwIjoyNzU4NDk1OTIxNDQsImlhdCI6MTY0MTk2MTg1OCwiZGF0YSI6eyJkZXZfaWQiOiJJRGR2Yz02MWRlNTk4MjlkYjE5RnhmdUFzSVJyMXYiLCJ0b2tfaWQiOiJJRHRrbj02MWRlNTk4MjlkYjIzUncyZkI2NzFqa1QifX0.YS-iWXwq2gVg0E_V0JkelParZ9oNliZ19aOHbvNTuCA', 'offline'),
('IDtkn=623d8c0483911GVsnoIBSeW4', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIxOTIuMTY4LjI1NC4xMDMiLCJleHAiOjI3Njg5NzcxODk0NCwiaWF0IjoxNjQ4MjAwNzA4LCJkYXRhIjp7ImRldl9pZCI6IklEZHZjPTYyM2Q4YzA0ODM5MDIycHVxTnRoSU95ZyIsInRva19pZCI6IklEdGtuPTYyM2Q4YzA0ODM5MTFHVnNub0lCU2VXNCJ9fQ.6L31wU4BnuIVYAsP_tQxnXy8nie7OazBHgvqEEL-KcI', 'online'),
('IDtkn=623d8c8bbee04k3AoTqZrX2d', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIxOTIuMTY4LjI1NC4xMDMiLCJleHAiOjI3Njg5Nzc0MzQ3MiwiaWF0IjoxNjQ4MjAwODU0LCJkYXRhIjp7InRva19pZCI6IklEdGtuPTYyM2Q4YzhiYmVlMDRrM0FvVHFaclgyZCIsImFjY2lkIjoiSURhY2M9NjIzZDhjOGJiZWUwN3hUZEM4YWlQTE5WIiwiYWRkcmVzc2lkIjoiSURhZHJzPTYyM2Q4YzhiYmVlMTNZdUpoVkJidlhMSyIsInJlZ2lvbiI6IlJFR0lPTiBWSSIsInByb3ZpbmNlIjoiTkVHUk9TIE9DQ0lERU5UQUwiLCJjaXR5IjoiQkFDT0xPRCBDSVRZIiwiYnJneSI6IlRBQ1VMSU5HIn19.WLVwTZT3FZOhG0QTXhMPCyrWj8rBvPnOEzGlmuxsSkM', 'offline'),
('JIDtkn=61f77b73def2b0Ge3wiQ25F', '$2y$10$cwRSZlSDFPhiALYKR3sMl.refd6WBJbjTyiisa5bwxSKZpjYm2zE.', 'offline'),
('LIDtkn=61de4f2229e7agtXbyUd5Fm', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJleHAiOjI3Njg5Nzg2OTgwOCwiaWF0IjoxNjQ4MjAxNjA2LCJkYXRhIjp7ImVzdF9pZCI6IkxJRGVzdD02MWRlNGYyMjI5ZTgyZElORzFxdzZRTyIsImVzdF9uYW1lIjoiTEpPUiIsImVzdF9sb2dvIjoiNjFkZTViOWYxNjYwYzUuODU2MTA2MzYuanBnIiwiYWNjX2lkIjoiTElEYWNjPTYxZGU0ZjIyMjllNzgyQ3V0ZFFUZzRHIiwidHlwZV9pZCI6IkxJRHR5cD02MWQzY2E1M2MwMGVmVmJHVGc3eHpGSyIsImVzdF90eXBlIjoiUmVsaWdpb3VzIEdhdGhlcmluZyBWZW51ZSIsImFkZF9pZCI6IkxJRGFkcnM9NjFkZTRmMjIyOWU3YlRsbXFhYkY5SVMiLCJ0b2tfaWQiOiJMSUR0a249NjFkZTRmMjIyOWU3YWd0WGJ5VWQ1Rm0iLCJyZWdpb24iOiJSRUdJT04gVkkiLCJwcm92aW5jZSI6Ik5FR1JPUyBPQ0NJREVOVEFMIiwiY2l0eSI6IkJBQ09MT0QgQ0lUWSIsImJhcmFuZ2F5IjoiQkFSQU5HQVkgMTIgKFBPQi4pIiwic3RyZWV0IjoiQVJBTkVUQSIsImJyYW5jaCI6IkNJTkVQTEVYIiwiY2FwX2lkIjoiTElEY3R5PTYxZGU0ZjIyMjllN2VNRm1walFvOTB4Iiwibm9ybWFsX2NhcCI6NCwibGltaXRlZF9jYXAiOjQsImNudF9pZCI6IkxJRGNudD02MWRlNGYyMjI5ZTdmbUtianpQMEx3YyIsImFyZWFfaWQiOiJMSURhc3E9NjFkZTRmMjIyOWU4MTNMeDg3SVBiRGoiLCJjb29yX2lkIjoiTElEY2RzPTYxZGU0ZjIyMjllN2RTcUZRMXRMengyIiwibGF0aXR1ZGUiOiIxMC42NjUxMzI', 'offline');

-- --------------------------------------------------------

--
-- Table structure for table `capacity`
--

CREATE TABLE `capacity` (
  `CapacityID` varchar(60) NOT NULL,
  `NormalCapacity` int(11) NOT NULL,
  `LimitedCapacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `capacity`
--

INSERT INTO `capacity` (`CapacityID`, `NormalCapacity`, `LimitedCapacity`) VALUES
('JIDcty=61f77b73def2fSoP2iW5kuE', 123, 4),
('LIDcty=61de4f2229e7eMFmpjQo90x', 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `coordinate`
--

CREATE TABLE `coordinate` (
  `CoordinateID` varchar(60) NOT NULL,
  `Latitude` varchar(60) NOT NULL,
  `Longitude` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `coordinate`
--

INSERT INTO `coordinate` (`CoordinateID`, `Latitude`, `Longitude`) VALUES
('IDcds=61de51491ac8fU4xLZDiIurJ', '0', '0'),
('IDcds=623d8c8bbee10VehF627vDyt', '0', '0'),
('JIDcds=61f77b73def2eGFW3eXYzob', '11.275386692600028', '123.64013671875001'),
('LIDcds=61de4f2229e7dSqFQ1tLzx2', '10.66513217522505', '122.94405238902365');

-- --------------------------------------------------------

--
-- Table structure for table `count`
--

CREATE TABLE `count` (
  `CountID` varchar(60) NOT NULL,
  `CurrentCount` int(11) NOT NULL,
  `Counter` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `count`
--

INSERT INTO `count` (`CountID`, `CurrentCount`, `Counter`) VALUES
('JIDcnt=61f77b73def31Xh3WlibQOK', 0, 0),
('LIDcnt=61de4f2229e7fmKbjzP0Lwc', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `designated_barangay`
--

CREATE TABLE `designated_barangay` (
  `DesignatedBarangayID` varchar(60) NOT NULL,
  `AccountID` varchar(60) NOT NULL,
  `AddressID` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `designated_barangay`
--

INSERT INTO `designated_barangay` (`DesignatedBarangayID`, `AccountID`, `AddressID`) VALUES
('IDdsg=61de51491ac95qg7zhyBX6Ik', 'IDacc=61de51491a675DjQYTNsJZ2C', 'IDadrs=61de51491ac97MHvx8RyS3mp'),
('IDdsg=623d8c8bbee12lwcBkMb1a3T', 'IDacc=623d8c8bbee07xTdC8aiPLNV', 'IDadrs=623d8c8bbee13YuJhVBbvXLK');

-- --------------------------------------------------------

--
-- Table structure for table `establishment`
--

CREATE TABLE `establishment` (
  `EstablishmentID` varchar(60) NOT NULL,
  `EstablishmentName` varchar(255) NOT NULL,
  `EstablishmentLogo` varchar(255) NOT NULL,
  `TypeID` varchar(60) NOT NULL,
  `AccountID` varchar(60) NOT NULL,
  `AddressID` varchar(60) NOT NULL,
  `CapacityID` varchar(60) NOT NULL,
  `CountID` varchar(60) NOT NULL,
  `AreaID` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `establishment`
--

INSERT INTO `establishment` (`EstablishmentID`, `EstablishmentName`, `EstablishmentLogo`, `TypeID`, `AccountID`, `AddressID`, `CapacityID`, `CountID`, `AreaID`) VALUES
('JIDest=61f77b73def33BIyOAeVwQ3', 'Jol', 'none', 'JIDtyp=61f77b73def28F5jXk6Pbo1', 'JIDacc=61f77b73def2a64CXuFEU9l', 'JIDadrs=61f77b73def2d4nrR57kfhP', 'JIDcty=61f77b73def2fSoP2iW5kuE', 'JIDcnt=61f77b73def31Xh3WlibQOK', 'JIDasq=61f77b73def32rKUJbYiB3m'),
('LIDest=61de4f2229e82dING1qw6QO', 'LJOR', '61de5b9f1660c5.85610636.jpg', 'LIDtyp=61d3ca53c00efVbGTg7xzFK', 'LIDacc=61de4f2229e782CutdQTg4G', 'LIDadrs=61de4f2229e7bTlmqabF9IS', 'LIDcty=61de4f2229e7eMFmpjQo90x', 'LIDcnt=61de4f2229e7fmKbjzP0Lwc', 'LIDasq=61de4f2229e813Lx87IPbDj');

-- --------------------------------------------------------

--
-- Table structure for table `establishment_type`
--

CREATE TABLE `establishment_type` (
  `TypeID` varchar(60) NOT NULL,
  `EstablishmentType` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `establishment_type`
--

INSERT INTO `establishment_type` (`TypeID`, `EstablishmentType`) VALUES
('JIDtyp=61c552cb5efdepSQztNgbLh', 'Eatery'),
('JIDtyp=61f77b73def28F5jXk6Pbo1', 'Internet Cafe'),
('LIDtyp=61d3ca53c00efVbGTg7xzFK', 'Religious Gathering Venue');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `LogID` varchar(60) NOT NULL,
  `LogVideoUrl` varchar(1024) NOT NULL,
  `EstablishmentID` varchar(60) NOT NULL,
  `LogScreenCapture` varchar(120) NOT NULL,
  `DateAndTime` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`LogID`, `LogVideoUrl`, `EstablishmentID`, `LogScreenCapture`, `DateAndTime`) VALUES
('IDlog=61de5847ed63bhq4WbE7J6rf', 'LIDest=61de4f2229e82dING1qw6QOlgs16419615432620242.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de5847ed67aZ735fAO4pmv.png', '2022-01-12 05:25:43.000000'),
('IDlog=61de59e2e0bbeKmRTXgq5kxs', 'LIDest=61de4f2229e82dING1qw6QOlgs1641961954123025.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de59e2e0e15Y0TmfdBuFR7.png', '2022-01-12 05:32:34.000000'),
('IDlog=61de5ec4852a2Xj9mkLpYxql', 'LIDest=61de4f2229e82dING1qw6QOlgs1641963203776317.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de5ec4852efjQ6fqOAbPFm.png', '2022-01-12 05:53:24.000000'),
('IDlog=61de6043ba572u26SdDRrNWQ', 'LIDest=61de4f2229e82dING1qw6QOlgs1641963586844191.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de6043bac89TIcst9D4JhU.png', '2022-01-12 05:59:47.000000'),
('IDlog=61de62262f7f30XQqTWDy9pw', 'LIDest=61de4f2229e82dING1qw6QOlgs16419640692845964.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de62262f9b8mxYSoRvTO76.png', '2022-01-12 06:07:50.000000'),
('IDlog=61de63842c81fvC9edsgG3O7', 'LIDest=61de4f2229e82dING1qw6QOlgs16419644182058344.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de63842c875yT8eq0FNjcl.png', '2022-01-12 06:13:40.000000'),
('IDlog=61de648d9339bGUwC0s5bNdi', 'LIDest=61de4f2229e82dING1qw6QOlgs16419646846171014.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de648d935a2nrXUWg4mYRN.png', '2022-01-12 06:18:05.000000'),
('IDlog=61de65d33d3bfu8aXOiAl3Ly', 'LIDest=61de4f2229e82dING1qw6QOlgs16419650095717857.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de65d33d3eesIO2fvr3095.png', '2022-01-12 06:23:31.000000'),
('IDlog=61de6605098bcfK5tXvksVGH', 'LIDest=61de4f2229e82dING1qw6QOlgs16419650596098542.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de6605098f03STjmWxiBZR.png', '2022-01-12 06:24:21.000000'),
('IDlog=61de66e0d428d1xcw3T9LA8n', 'LIDest=61de4f2229e82dING1qw6QOlgs16419652794775107.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de66e0d473ekPDOmUxMYVr.png', '2022-01-12 06:28:00.000000'),
('IDlog=61de6714264505PnSE2wUxah', 'LIDest=61de4f2229e82dING1qw6QOlgs1641965329634587.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de671426a75pwiKFv2EcPx.png', '2022-01-12 06:28:52.000000'),
('IDlog=61de67467309epwYubhmQ5iJ', 'LIDest=61de4f2229e82dING1qw6QOlgs16419653797229445.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de6746737d1vKYfugDhCa6.png', '2022-01-12 06:29:42.000000'),
('IDlog=61de6777ba6d1layh2T7B4wm', 'LIDest=61de4f2229e82dING1qw6QOlgs16419654297436683.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de6777ba739TCdhUxYOEgs.png', '2022-01-12 06:30:31.000000'),
('IDlog=61de67aa5bc55Mj0WfpyXw7B', 'LIDest=61de4f2229e82dING1qw6QOlgs16419654798970096.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de67aa5bcddgSfzOQYPvoU.png', '2022-01-12 06:31:22.000000'),
('IDlog=61de67dbdcdcfgqzc0e8TFrB', 'LIDest=61de4f2229e82dING1qw6QOlgs16419655299435775.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de67dbdce30QsmZ5tTG7E3.png', '2022-01-12 06:32:11.000000'),
('IDlog=61de680e59645dnWx5sMZV8J', 'LIDest=61de4f2229e82dING1qw6QOlgs16419655799809368.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61de680e598f5OTB2pLRYqNI.png', '2022-01-12 06:33:02.000000'),
('IDlog=61e131c7b63a1Ql1arvSiL6x', 'LIDest=61de4f2229e82dING1qw6QOlgs16421482886932256.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61e131c7b63d0S0jp3KmQ7AL.png', '2022-01-14 09:18:15.000000'),
('IDlog=61e13518c3ee8VGq9dK1Jtni', 'LIDest=61de4f2229e82dING1qw6QOlgs1642149143876003.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61e13518c4213MRpKdmv80B5.png', '2022-01-14 09:32:24.000000'),
('IDlog=61e1354ad4be6pw62eTnFuvb', 'LIDest=61de4f2229e82dING1qw6QOlgs16421491939545798.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=61e1354ad4e3bqLlcEBAkD2s.png', '2022-01-14 09:33:14.000000'),
('IDlog=6214be33ef310IEvDq0CNHeM', 'LIDest=61de4f2229e82dING1qw6QOlgs16455265728346245.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=6214be33ef34brAM1HzsRWKN.png', '2022-02-22 11:42:59.000000'),
('IDlog=622093fe58627JZ4adVU0sEb', 'LIDest=61de4f2229e82dING1qw6QOlgs16463021999152205.mp4', 'LIDest=61de4f2229e82dING1qw6QO', 'lgimg=622093fe586524gJyUPERIDa.png', '2022-03-03 11:10:06.000000');

-- --------------------------------------------------------

--
-- Table structure for table `mobile_device`
--

CREATE TABLE `mobile_device` (
  `DeviceID` varchar(60) NOT NULL,
  `TokenID` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mobile_device`
--

INSERT INTO `mobile_device` (`DeviceID`, `TokenID`) VALUES
('IDdvc=61de59829db19FxfuAsIRr1v', 'IDtkn=61de59829db23Rw2fB671jkT'),
('IDdvc=623d8c04839022puqNthIOyg', 'IDtkn=623d8c0483911GVsnoIBSeW4');

-- --------------------------------------------------------

--
-- Table structure for table `violation`
--

CREATE TABLE `violation` (
  `ViolationID` varchar(60) NOT NULL,
  `ViolationVideoUrl` varchar(1024) NOT NULL,
  `ViolationScreenCapture` varchar(120) NOT NULL,
  `EstablishmentID` varchar(60) NOT NULL,
  `ViolationStatus` varchar(255) NOT NULL,
  `DateAndTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `violation`
--

INSERT INTO `violation` (`ViolationID`, `ViolationVideoUrl`, `ViolationScreenCapture`, `EstablishmentID`, `ViolationStatus`, `DateAndTime`) VALUES
('IDvls=61e8f4b815e69dJ5hlmKsogH', 'LIDest=61de4f2229e82dING1qw6QOvls16426569052011902.mp4', 'vlimg=61e8f4b8160abC1bTkdB4rcI.png', 'LIDest=61de4f2229e82dING1qw6QO', 'Dismissed', '2022-01-20 06:35:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`AccountID`),
  ADD UNIQUE KEY `TokenID_2` (`TokenID`),
  ADD KEY `AccountTypeID` (`AccountTypeID`),
  ADD KEY `TokenID` (`TokenID`);

--
-- Indexes for table `account_type`
--
ALTER TABLE `account_type`
  ADD PRIMARY KEY (`AccountTypeID`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`AddressID`),
  ADD UNIQUE KEY `CoordinateID_2` (`CoordinateID`),
  ADD KEY `CoordinateID` (`CoordinateID`);

--
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`AreaID`);

--
-- Indexes for table `authentication_token`
--
ALTER TABLE `authentication_token`
  ADD PRIMARY KEY (`TokenID`);

--
-- Indexes for table `capacity`
--
ALTER TABLE `capacity`
  ADD PRIMARY KEY (`CapacityID`);

--
-- Indexes for table `coordinate`
--
ALTER TABLE `coordinate`
  ADD PRIMARY KEY (`CoordinateID`);

--
-- Indexes for table `count`
--
ALTER TABLE `count`
  ADD PRIMARY KEY (`CountID`);

--
-- Indexes for table `designated_barangay`
--
ALTER TABLE `designated_barangay`
  ADD PRIMARY KEY (`DesignatedBarangayID`),
  ADD KEY `AccountID` (`AccountID`),
  ADD KEY `AddressID` (`AddressID`);

--
-- Indexes for table `establishment`
--
ALTER TABLE `establishment`
  ADD PRIMARY KEY (`EstablishmentID`),
  ADD UNIQUE KEY `AccountID_2` (`AccountID`),
  ADD UNIQUE KEY `AddressID_2` (`AddressID`),
  ADD UNIQUE KEY `CapacityID_2` (`CapacityID`),
  ADD UNIQUE KEY `CountID_2` (`CountID`),
  ADD UNIQUE KEY `AreaID_2` (`AreaID`),
  ADD KEY `TypeID` (`TypeID`),
  ADD KEY `AccountID` (`AccountID`),
  ADD KEY `AddressID` (`AddressID`),
  ADD KEY `CapacityID` (`CapacityID`),
  ADD KEY `CountID` (`CountID`),
  ADD KEY `AreaID` (`AreaID`);

--
-- Indexes for table `establishment_type`
--
ALTER TABLE `establishment_type`
  ADD PRIMARY KEY (`TypeID`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`LogID`),
  ADD KEY `EstablishmentID` (`EstablishmentID`);

--
-- Indexes for table `mobile_device`
--
ALTER TABLE `mobile_device`
  ADD PRIMARY KEY (`DeviceID`),
  ADD KEY `TokenID` (`TokenID`);

--
-- Indexes for table `violation`
--
ALTER TABLE `violation`
  ADD PRIMARY KEY (`ViolationID`),
  ADD KEY `EstablishmentID` (`EstablishmentID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`AccountTypeID`) REFERENCES `account_type` (`AccountTypeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `account_ibfk_3` FOREIGN KEY (`TokenID`) REFERENCES `authentication_token` (`TokenID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`CoordinateID`) REFERENCES `coordinate` (`CoordinateID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `designated_barangay`
--
ALTER TABLE `designated_barangay`
  ADD CONSTRAINT `designated_barangay_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `account` (`AccountID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `designated_barangay_ibfk_2` FOREIGN KEY (`AddressID`) REFERENCES `address` (`AddressID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `establishment`
--
ALTER TABLE `establishment`
  ADD CONSTRAINT `establishment_ibfk_1` FOREIGN KEY (`TypeID`) REFERENCES `establishment_type` (`TypeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `establishment_ibfk_2` FOREIGN KEY (`CountID`) REFERENCES `count` (`CountID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `establishment_ibfk_3` FOREIGN KEY (`AreaID`) REFERENCES `area` (`AreaID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `establishment_ibfk_5` FOREIGN KEY (`AddressID`) REFERENCES `address` (`AddressID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `establishment_ibfk_6` FOREIGN KEY (`AccountID`) REFERENCES `account` (`AccountID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `establishment_ibfk_7` FOREIGN KEY (`CapacityID`) REFERENCES `capacity` (`CapacityID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `log_ibfk_1` FOREIGN KEY (`EstablishmentID`) REFERENCES `establishment` (`EstablishmentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mobile_device`
--
ALTER TABLE `mobile_device`
  ADD CONSTRAINT `mobile_device_ibfk_1` FOREIGN KEY (`TokenID`) REFERENCES `authentication_token` (`TokenID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `violation`
--
ALTER TABLE `violation`
  ADD CONSTRAINT `violation_ibfk_1` FOREIGN KEY (`EstablishmentID`) REFERENCES `establishment` (`EstablishmentID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
