-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2025 at 06:43 AM
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
-- Database: `php_ims`
--

-- --------------------------------------------------------

--
-- Table structure for table `adobe`
--

CREATE TABLE `adobe` (
  `adobe_id` int(11) NOT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `adobe_assettag` varchar(50) DEFAULT NULL,
  `adobe_brand` varchar(50) DEFAULT NULL,
  `adobe_modelnumber` varchar(50) DEFAULT NULL,
  `adobe_dateacquired` timestamp NOT NULL DEFAULT current_timestamp(),
  `adobe_dateedited` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `adobe_adobeversion` varchar(50) DEFAULT NULL,
  `adobe_assigneduser` varchar(50) DEFAULT NULL,
  `adobe_licensekey` varchar(100) DEFAULT NULL,
  `adobe_remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `avr`
--

CREATE TABLE `avr` (
  `avr_id` int(11) NOT NULL,
  `avr_name` varchar(50) DEFAULT NULL,
  `avr_assettag` varchar(50) NOT NULL,
  `avr_brand` varchar(50) NOT NULL,
  `avr_modelnumber` varchar(50) NOT NULL,
  `avr_dateacquired` date NOT NULL,
  `avr_deviceage` varchar(50) NOT NULL,
  `avr_assigneduser` varchar(50) DEFAULT NULL,
  `avr_remarks` text DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `equipment_id` int(11) NOT NULL,
  `pcname` varchar(50) NOT NULL,
  `assigneduser` varchar(50) NOT NULL,
  `processor` varchar(50) NOT NULL,
  `motherboard` varchar(50) NOT NULL,
  `ram` varchar(50) NOT NULL,
  `hdd` varchar(50) NOT NULL,
  `ssd` varchar(50) NOT NULL,
  `gpu` varchar(50) NOT NULL,
  `psu` varchar(50) NOT NULL,
  `pccase` varchar(50) NOT NULL,
  `monitor` varchar(50) NOT NULL,
  `lancard` varchar(50) NOT NULL,
  `wificard` varchar(50) NOT NULL,
  `macaddress` varchar(50) NOT NULL,
  `osversion` varchar(50) NOT NULL,
  `msversion` varchar(50) NOT NULL,
  `windows_key` varchar(50) NOT NULL,
  `ms_key` varchar(50) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_edited` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `equipment_remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`equipment_id`, `pcname`, `assigneduser`, `processor`, `motherboard`, `ram`, `hdd`, `ssd`, `gpu`, `psu`, `pccase`, `monitor`, `lancard`, `wificard`, `macaddress`, `osversion`, `msversion`, `windows_key`, `ms_key`, `date_added`, `date_edited`, `equipment_remarks`) VALUES
(8, 'EDT01', 'EDT01', 'Intel Core i5-12400F', 'ASUSTeK COMPUTER INC.', '32.0 GB', 'ST500DM002-1BD142', 'Samsung SSD 860 EVO 500GB', 'NVIDIA GeForce 210', 'PSU', 'PC Case', 'NVision 19 inches', 'LANCARD123', 'WIFICARD123', '3C-84-6A-44-E7-D2', 'Microsoft Windows 10 Pro', 'Microsoft 365', '1234567890XXX', '0987654321XXXx', '2025-03-05 04:10:05', '2025-03-10 01:37:25', '123asd'),
(9, 'EDT02', 'EDT02', 'Intel Core i5-12400F', 'ASUSTeK COMPUTER INC.', '32.0 GB', 'ST500DM002-1BD142', 'Samsung SSD 860 EVO 500GB', 'NVIDIA GeForce 210', 'PSU', 'PC Case', 'NVision 19 inches', 'LANCARD123', 'WIFICARD123', '3C-84-6A-44-E7-D2', 'Microsoft Windows 10 Pro', 'Microsoft 365', '1234567890XXX', '0987654321XXXx', '2025-03-06 02:27:51', '2025-03-06 04:42:38', 'jgjs231'),
(12, 'EDT01', 'EDT01', 'Intel Core i5-12400F', 'ASUSTeK COMPUTER INC.', '32.0 GB', 'ST500DM002-1BD142', 'Samsung SSD 860 EVO 500GB', 'NVIDIA GeForce 210', 'PSU', 'PC Case', 'NVision 19 inches', 'LANCARD123', 'WIFICARD123', '3C-84-6A-44-E7-D2', 'Microsoft Windows 10 Pro', 'Microsoft 365', '1234567890XXX', '0987654321XXXx', '2025-03-10 01:30:42', '2025-03-10 01:37:17', 'asd123'),
(14, 'EDT04', '123asd', 'Intel Core i5-12400F', 'ASUSTeK COMPUTER INC.', '32.0 GB', 'ST500DM002-1BD142', 'Samsung SSD 860 EVO 500GB', 'NVIDIA GeForce 210', 'PSU', 'PC Case', 'NVision 19 inches', 'LANCARD123', 'WIFICARD123', 'EDT01', 'Microsoft Windows 10 Pro', 'Microsoft 365', '1234567890XXX', '0987654321XXXx', '2025-03-10 02:16:33', '2025-03-10 02:16:33', '');

-- --------------------------------------------------------

--
-- Table structure for table `gpu`
--

CREATE TABLE `gpu` (
  `gpu_id` int(11) NOT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `gpu_assettag` varchar(255) DEFAULT NULL,
  `gpu_brand` varchar(255) DEFAULT NULL,
  `gpu_modelnumber` varchar(255) DEFAULT NULL,
  `gpu_size` varchar(255) DEFAULT NULL,
  `gpu_dateacquired` date DEFAULT NULL,
  `gpu_deviceage` varchar(50) DEFAULT NULL,
  `gpu_assigneduser` varchar(255) DEFAULT NULL,
  `gpu_remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gpu`
--

INSERT INTO `gpu` (`gpu_id`, `equipment_id`, `gpu_assettag`, `gpu_brand`, `gpu_modelnumber`, `gpu_size`, `gpu_dateacquired`, `gpu_deviceage`, `gpu_assigneduser`, `gpu_remarks`) VALUES
(11, 12, 'asd123', 'asd123', 'asd123', 'asd123', '2025-03-01', 'asd123', 'asd123', 'asd123');

-- --------------------------------------------------------

--
-- Table structure for table `hdd`
--

CREATE TABLE `hdd` (
  `hdd_id` int(11) NOT NULL,
  `hdd_name` varchar(50) DEFAULT NULL,
  `hdd_assettag` varchar(50) DEFAULT NULL,
  `hdd_brand` varchar(50) DEFAULT NULL,
  `hdd_modelnumber` varchar(50) DEFAULT NULL,
  `hdd_size` varchar(50) DEFAULT NULL,
  `hdd_dateacquired` date DEFAULT NULL,
  `hdd_deviceage` varchar(50) DEFAULT NULL,
  `hdd_assigneduser` varchar(50) DEFAULT NULL,
  `hdd_remarks` text DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hdd`
--

INSERT INTO `hdd` (`hdd_id`, `hdd_name`, `hdd_assettag`, `hdd_brand`, `hdd_modelnumber`, `hdd_size`, `hdd_dateacquired`, `hdd_deviceage`, `hdd_assigneduser`, `hdd_remarks`, `equipment_id`) VALUES
(13, NULL, 'asd123', 'asd123', 'asd123', 'asd123', '2025-03-02', 'asd123', 'asd123', 'asd123', 12);

-- --------------------------------------------------------

--
-- Table structure for table `keyboard`
--

CREATE TABLE `keyboard` (
  `keyboard_id` int(11) NOT NULL,
  `keyboard_name` varchar(50) DEFAULT NULL,
  `keyboard_assettag` varchar(50) DEFAULT NULL,
  `keyboard_brand` varchar(50) DEFAULT NULL,
  `keyboard_modelnumber` varchar(50) DEFAULT NULL,
  `keyboard_dateacquired` date DEFAULT NULL,
  `keyboard_deviceage` varchar(50) DEFAULT NULL,
  `keyboard_assigneduser` varchar(50) DEFAULT NULL,
  `keyboard_remarks` text DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keyboard`
--

INSERT INTO `keyboard` (`keyboard_id`, `keyboard_name`, `keyboard_assettag`, `keyboard_brand`, `keyboard_modelnumber`, `keyboard_dateacquired`, `keyboard_deviceage`, `keyboard_assigneduser`, `keyboard_remarks`, `equipment_id`) VALUES
(21, NULL, 'asd123', 'asd123', 'asd123', '2025-03-15', 'asd123', 'asd123', 'asd123', 8);

-- --------------------------------------------------------

--
-- Table structure for table `lancard`
--

CREATE TABLE `lancard` (
  `lancard_id` int(11) NOT NULL,
  `lancard_name` varchar(255) NOT NULL,
  `lancard_assettag` varchar(100) DEFAULT NULL,
  `lancard_brand` varchar(100) DEFAULT NULL,
  `lancard_modelnumber` varchar(100) DEFAULT NULL,
  `lancard_dateacquired` date DEFAULT NULL,
  `lancard_deviceage` varchar(50) DEFAULT NULL,
  `lancard_assigneduser` varchar(255) DEFAULT NULL,
  `lancard_macaddress` varchar(50) DEFAULT NULL,
  `lancard_remarks` text DEFAULT NULL,
  `equipment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lancard`
--

INSERT INTO `lancard` (`lancard_id`, `lancard_name`, `lancard_assettag`, `lancard_brand`, `lancard_modelnumber`, `lancard_dateacquired`, `lancard_deviceage`, `lancard_assigneduser`, `lancard_macaddress`, `lancard_remarks`, `equipment_id`) VALUES
(11, '', 'asd123', 'asd123', 'asd123', '2025-03-02', 'asd123', 'asd123', 'asd123', 'asd123', 12);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `pcname` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `date_edited` datetime NOT NULL DEFAULT current_timestamp(),
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`log_id`, `pcname`, `action`, `date_edited`, `date_added`, `user_id`) VALUES
(1, '', 'Updated user details: First Name: rio → rio123', '2025-03-10 10:25:22', '2025-03-10 02:25:22', 1),
(2, '', 'Updated user details: First Name: abiel321 → abiel321321', '2025-03-10 10:25:42', '2025-03-10 02:25:42', 1),
(3, '', 'Updated user details: First Name: abiel321321 → abiel321', '2025-03-10 10:25:54', '2025-03-10 02:25:54', 21);

-- --------------------------------------------------------

--
-- Table structure for table `monitor`
--

CREATE TABLE `monitor` (
  `monitor_id` int(11) NOT NULL,
  `monitor_name` varchar(50) DEFAULT NULL,
  `monitor_assettag` varchar(50) DEFAULT NULL,
  `monitor_brand` varchar(50) DEFAULT NULL,
  `monitor_modelnumber` varchar(50) DEFAULT NULL,
  `monitor_size` varchar(50) DEFAULT NULL,
  `monitor_dateacquired` date DEFAULT NULL,
  `monitor_deviceage` varchar(50) DEFAULT NULL,
  `monitor_assigneduser` varchar(50) DEFAULT NULL,
  `monitor_remarks` text DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monitor`
--

INSERT INTO `monitor` (`monitor_id`, `monitor_name`, `monitor_assettag`, `monitor_brand`, `monitor_modelnumber`, `monitor_size`, `monitor_dateacquired`, `monitor_deviceage`, `monitor_assigneduser`, `monitor_remarks`, `equipment_id`) VALUES
(13, NULL, '123asd', '123asd', '123asd', '123asd', '2025-03-01', '123asd', '123asd', '123asd', 12);

-- --------------------------------------------------------

--
-- Table structure for table `motherboard`
--

CREATE TABLE `motherboard` (
  `mobo_id` int(11) NOT NULL,
  `mobo_name` varchar(50) DEFAULT NULL,
  `mobo_assettag` varchar(50) DEFAULT NULL,
  `mobo_brand` varchar(50) DEFAULT NULL,
  `mobo_modelnumber` varchar(50) DEFAULT NULL,
  `mobo_ramslot` int(11) DEFAULT NULL,
  `mobo_dateacquired` date DEFAULT NULL,
  `mobo_deviceage` varchar(50) DEFAULT NULL,
  `mobo_assigneduser` varchar(50) DEFAULT NULL,
  `mobo_computername` varchar(50) DEFAULT NULL,
  `mobo_macaddress` varchar(50) DEFAULT NULL,
  `mobo_remarks` text DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `motherboard`
--

INSERT INTO `motherboard` (`mobo_id`, `mobo_name`, `mobo_assettag`, `mobo_brand`, `mobo_modelnumber`, `mobo_ramslot`, `mobo_dateacquired`, `mobo_deviceage`, `mobo_assigneduser`, `mobo_computername`, `mobo_macaddress`, `mobo_remarks`, `equipment_id`) VALUES
(32, NULL, '123asd', '123asd', '123asd', 2, '2025-03-01', '123asd', '123asd', '123asd', '123asd', '123asd', 12);

-- --------------------------------------------------------

--
-- Table structure for table `mouse`
--

CREATE TABLE `mouse` (
  `mouse_id` int(11) NOT NULL,
  `mouse_name` varchar(50) NOT NULL,
  `mouse_assettag` varchar(50) NOT NULL,
  `mouse_brand` varchar(50) NOT NULL,
  `mouse_modelnumber` varchar(50) NOT NULL,
  `mouse_dateacquired` date NOT NULL,
  `mouse_deviceage` varchar(50) NOT NULL,
  `mouse_assigneduser` varchar(50) DEFAULT NULL,
  `mouse_remarks` text DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `msoffice`
--

CREATE TABLE `msoffice` (
  `msoffice_id` int(11) NOT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `msoffice_assettag` varchar(50) DEFAULT NULL,
  `msoffice_brand` varchar(50) DEFAULT NULL,
  `msoffice_modelnumber` varchar(50) DEFAULT NULL,
  `msoffice_dateacquired` timestamp NOT NULL DEFAULT current_timestamp(),
  `msoffice_dateedited` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `msoffice_officeversion` varchar(50) DEFAULT NULL,
  `msoffice_assigneduser` varchar(50) DEFAULT NULL,
  `msoffice_licensekey` varchar(100) DEFAULT NULL,
  `msoffice_remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `msos`
--

CREATE TABLE `msos` (
  `msos_id` int(11) NOT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `msos_assettag` varchar(50) DEFAULT NULL,
  `msos_brand` varchar(50) DEFAULT NULL,
  `msos_modelnumber` varchar(50) DEFAULT NULL,
  `msos_dateacquired` timestamp NOT NULL DEFAULT current_timestamp(),
  `msos_dateedited` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `msos_windowsversion` varchar(50) DEFAULT NULL,
  `msos_assigneduser` varchar(50) DEFAULT NULL,
  `msos_licensekey` varchar(100) DEFAULT NULL,
  `msos_remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otherdevices`
--

CREATE TABLE `otherdevices` (
  `device_id` int(11) NOT NULL,
  `device_type` varchar(50) NOT NULL,
  `device_name` varchar(50) DEFAULT NULL,
  `device_assettag` varchar(50) DEFAULT NULL,
  `device_brand` varchar(50) DEFAULT NULL,
  `device_modelnumber` varchar(50) DEFAULT NULL,
  `device_deviceage` varchar(50) DEFAULT NULL,
  `device_pcname` varchar(50) DEFAULT NULL,
  `device_macaddress` varchar(50) DEFAULT NULL,
  `device_dateacquired` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `device_dateedited` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `device_remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otherdevices`
--

INSERT INTO `otherdevices` (`device_id`, `device_type`, `device_name`, `device_assettag`, `device_brand`, `device_modelnumber`, `device_deviceage`, `device_pcname`, `device_macaddress`, `device_dateacquired`, `device_dateedited`, `device_remarks`) VALUES
(1, 'Server', 'Server123', 'Server123', 'Server123', 'Server123', 'Server123', 'Server123', 'Server123', '2025-02-19 08:07:08', '2025-02-19 08:07:08', 'Remarks1'),
(2, 'Router', 'Router123', 'Router123', 'Router123', 'Router123', 'Router123', 'Router123', 'Router123', '2025-02-19 08:07:27', '2025-02-19 08:07:27', 'Remarks2'),
(3, 'NAS', 'NAS123', 'NAS123', 'NAS123', 'NAS123', 'NAS123', 'NAS123', 'NAS123', '2025-02-19 08:07:40', '2025-02-19 08:07:40', 'NAS123');

-- --------------------------------------------------------

--
-- Table structure for table `pccase`
--

CREATE TABLE `pccase` (
  `pccase_id` int(11) NOT NULL,
  `pccase_name` varchar(50) NOT NULL,
  `pccase_assettag` varchar(50) DEFAULT NULL,
  `pccase_brand` varchar(50) DEFAULT NULL,
  `pccase_modelnumber` varchar(50) DEFAULT NULL,
  `pccase_dateacquired` date DEFAULT NULL,
  `pccase_deviceage` varchar(50) DEFAULT NULL,
  `pccase_assigneduser` varchar(50) DEFAULT NULL,
  `pccase_remarks` text DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pccase`
--

INSERT INTO `pccase` (`pccase_id`, `pccase_name`, `pccase_assettag`, `pccase_brand`, `pccase_modelnumber`, `pccase_dateacquired`, `pccase_deviceage`, `pccase_assigneduser`, `pccase_remarks`, `equipment_id`) VALUES
(11, '', 'asd123', 'asd123', 'asd123', '2025-03-22', 'asd123', 'asd123', 'asd123', 12);

-- --------------------------------------------------------

--
-- Table structure for table `peripherals`
--

CREATE TABLE `peripherals` (
  `peripheral_id` int(11) NOT NULL,
  `keyboard` varchar(50) DEFAULT NULL,
  `mouse` varchar(50) DEFAULT NULL,
  `printer` varchar(50) DEFAULT NULL,
  `avr` varchar(50) DEFAULT NULL,
  `peripheral_dateadded` timestamp NOT NULL DEFAULT current_timestamp(),
  `peripheral_dateaedited` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `equipment_id` int(11) DEFAULT NULL,
  `peripheral_remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peripherals`
--

INSERT INTO `peripherals` (`peripheral_id`, `keyboard`, `mouse`, `printer`, `avr`, `peripheral_dateadded`, `peripheral_dateaedited`, `equipment_id`, `peripheral_remarks`) VALUES
(8, 'sd', '213a', 'dads', 'q2e', '2025-03-06 04:47:46', '0000-00-00 00:00:00', 9, 'asdas111'),
(9, '2131saad', 'hwrsd', 'asd', 'vasda', '2025-03-06 04:48:26', '0000-00-00 00:00:00', 8, 'gadads2'),
(10, 'hg', 'ar3qd', 'ssasdzxc', 'aseq2', '2025-03-06 05:00:08', '0000-00-00 00:00:00', 8, 'e2222222');

-- --------------------------------------------------------

--
-- Table structure for table `printer`
--

CREATE TABLE `printer` (
  `printer_id` int(11) NOT NULL,
  `printer_name` varchar(50) DEFAULT NULL,
  `printer_assettag` varchar(50) NOT NULL,
  `printer_brand` varchar(50) NOT NULL,
  `printer_modelnumber` varchar(50) NOT NULL,
  `printer_dateacquired` date NOT NULL,
  `printer_deviceage` varchar(50) NOT NULL,
  `printer_assigneduser` varchar(50) DEFAULT NULL,
  `printer_remarks` text DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `processor`
--

CREATE TABLE `processor` (
  `processor_id` int(11) NOT NULL,
  `processor_name` varchar(255) NOT NULL,
  `processor_assettag` varchar(255) DEFAULT NULL,
  `processor_brand` varchar(255) DEFAULT NULL,
  `processor_modelnumber` varchar(255) DEFAULT NULL,
  `processor_dateacquired` date DEFAULT NULL,
  `processor_deviceage` varchar(50) DEFAULT NULL,
  `processor_assigneduser` varchar(50) DEFAULT NULL,
  `processor_remarks` text DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `processor`
--

INSERT INTO `processor` (`processor_id`, `processor_name`, `processor_assettag`, `processor_brand`, `processor_modelnumber`, `processor_dateacquired`, `processor_deviceage`, `processor_assigneduser`, `processor_remarks`, `equipment_id`) VALUES
(24, '', 'asd', 'asd', 'asd', '2025-03-28', 'asd', 'asd', 'ads', 8),
(25, '', '123asd', '123asd', '123asd', '2025-03-27', '123asd', '123asd', '123asd', 12);

-- --------------------------------------------------------

--
-- Table structure for table `psu`
--

CREATE TABLE `psu` (
  `psu_id` int(11) NOT NULL,
  `psu_name` varchar(50) NOT NULL,
  `psu_assettag` varchar(50) DEFAULT NULL,
  `psu_brand` varchar(50) DEFAULT NULL,
  `psu_modelnumber` varchar(50) DEFAULT NULL,
  `psu_dateacquired` date DEFAULT NULL,
  `psu_deviceage` varchar(50) DEFAULT NULL,
  `psu_assigneduser` varchar(50) DEFAULT NULL,
  `psu_remarks` text DEFAULT NULL,
  `equipment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `psu`
--

INSERT INTO `psu` (`psu_id`, `psu_name`, `psu_assettag`, `psu_brand`, `psu_modelnumber`, `psu_dateacquired`, `psu_deviceage`, `psu_assigneduser`, `psu_remarks`, `equipment_id`) VALUES
(10, '', '123asd', '123asd', '123asd', '2025-03-01', '123asd', '123asd', '123asd', 12);

-- --------------------------------------------------------

--
-- Table structure for table `ram`
--

CREATE TABLE `ram` (
  `ram_id` int(11) NOT NULL,
  `ram_name` varchar(255) DEFAULT NULL,
  `ram_assettag` varchar(255) DEFAULT NULL,
  `ram_brand` varchar(255) DEFAULT NULL,
  `ram_modelnumber` varchar(255) DEFAULT NULL,
  `ram_size` varchar(50) DEFAULT NULL,
  `ram_dateacquired` date DEFAULT NULL,
  `ram_deviceage` varchar(50) DEFAULT NULL,
  `ram_assigneduser` varchar(50) DEFAULT NULL,
  `ram_remarks` text DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ram`
--

INSERT INTO `ram` (`ram_id`, `ram_name`, `ram_assettag`, `ram_brand`, `ram_modelnumber`, `ram_size`, `ram_dateacquired`, `ram_deviceage`, `ram_assigneduser`, `ram_remarks`, `equipment_id`) VALUES
(8, NULL, '123asd', '123asd', '123asd', '123asd', '2025-03-15', '123asd', '123asd', '123asd', 12);

-- --------------------------------------------------------

--
-- Table structure for table `software`
--

CREATE TABLE `software` (
  `software_id` int(11) NOT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `software_msos` varchar(50) DEFAULT NULL,
  `software_msoffice` varchar(50) DEFAULT NULL,
  `software_adobe` varchar(50) DEFAULT NULL,
  `software_dateadded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `software_dateedited` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `software_remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `software`
--

INSERT INTO `software` (`software_id`, `equipment_id`, `software_msos`, `software_msoffice`, `software_adobe`, `software_dateadded`, `software_dateedited`, `software_remarks`) VALUES
(4, 8, 'MS OS 123', 'MS Office 123', 'Adobe123', '2025-03-10 01:57:32', '2025-03-10 01:57:32', 'aasdasda'),
(5, 9, 'MS OS 123', 'MS Office 123', 'Adobe123', '2025-03-10 01:57:56', '2025-03-10 01:57:56', 'asdasda'),
(6, 9, 'MS OS 123', 'MS Office 123', 'Adobe123', '2025-03-10 01:58:00', '2025-03-10 01:58:00', 'asdasda');

-- --------------------------------------------------------

--
-- Table structure for table `ssd`
--

CREATE TABLE `ssd` (
  `ssd_id` int(11) NOT NULL,
  `ssd_name` varchar(50) DEFAULT NULL,
  `ssd_assettag` varchar(50) DEFAULT NULL,
  `ssd_brand` varchar(50) DEFAULT NULL,
  `ssd_modelnumber` varchar(50) DEFAULT NULL,
  `ssd_size` varchar(50) DEFAULT NULL,
  `ssd_dateacquired` date DEFAULT NULL,
  `ssd_deviceage` varchar(50) DEFAULT NULL,
  `ssd_assigneduser` varchar(50) DEFAULT NULL,
  `ssd_remarks` text DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ssd`
--

INSERT INTO `ssd` (`ssd_id`, `ssd_name`, `ssd_assettag`, `ssd_brand`, `ssd_modelnumber`, `ssd_size`, `ssd_dateacquired`, `ssd_deviceage`, `ssd_assigneduser`, `ssd_remarks`, `equipment_id`) VALUES
(11, NULL, '123asd', '123asd', '123asd', '123asd', '2025-03-02', '123asd', '123asd', '123asd', 12);

-- --------------------------------------------------------

--
-- Table structure for table `user_registration`
--

CREATE TABLE `user_registration` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `role` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_registration`
--

INSERT INTO `user_registration` (`user_id`, `firstname`, `lastname`, `username`, `password`, `department`, `role`, `status`) VALUES
(1, 'admin', 'admin', 'admin', 'admin', 'Management Information System', 'admin', 'active'),
(20, 'admin', 'admin', 'admin123', 'admin', 'Management Information System', 'admin', 'active'),
(21, 'rio123', 'balbuena', 'rio', '123', 'Advertising', 'admin', 'active'),
(24, 'abiel321', 'balbuena', 'abiel321', 'admin', 'Management Information System', 'admin', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `wificard`
--

CREATE TABLE `wificard` (
  `wificard_id` int(11) NOT NULL,
  `wificard_name` varchar(50) DEFAULT NULL,
  `wificard_assettag` varchar(50) DEFAULT NULL,
  `wificard_brand` varchar(50) DEFAULT NULL,
  `wificard_modelnumber` varchar(50) DEFAULT NULL,
  `wificard_dateacquired` date DEFAULT NULL,
  `wificard_deviceage` varchar(50) DEFAULT NULL,
  `wificard_assigneduser` varchar(50) DEFAULT NULL,
  `wificard_remarks` text DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wificard`
--

INSERT INTO `wificard` (`wificard_id`, `wificard_name`, `wificard_assettag`, `wificard_brand`, `wificard_modelnumber`, `wificard_dateacquired`, `wificard_deviceage`, `wificard_assigneduser`, `wificard_remarks`, `equipment_id`) VALUES
(12, NULL, '123asd', '123asd', '123asd', '2025-03-01', '123asd', '123asd', '123asd', 12);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adobe`
--
ALTER TABLE `adobe`
  ADD PRIMARY KEY (`adobe_id`),
  ADD KEY `adobe_ibfk_1` (`equipment_id`);

--
-- Indexes for table `avr`
--
ALTER TABLE `avr`
  ADD PRIMARY KEY (`avr_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`equipment_id`);

--
-- Indexes for table `gpu`
--
ALTER TABLE `gpu`
  ADD PRIMARY KEY (`gpu_id`),
  ADD KEY `gpu_ibfk_1` (`equipment_id`);

--
-- Indexes for table `hdd`
--
ALTER TABLE `hdd`
  ADD PRIMARY KEY (`hdd_id`),
  ADD KEY `fk_equipment_id` (`equipment_id`);

--
-- Indexes for table `keyboard`
--
ALTER TABLE `keyboard`
  ADD PRIMARY KEY (`keyboard_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `lancard`
--
ALTER TABLE `lancard`
  ADD PRIMARY KEY (`lancard_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_logs_user` (`user_id`);

--
-- Indexes for table `monitor`
--
ALTER TABLE `monitor`
  ADD PRIMARY KEY (`monitor_id`),
  ADD KEY `monitor_ibfk_1` (`equipment_id`);

--
-- Indexes for table `motherboard`
--
ALTER TABLE `motherboard`
  ADD PRIMARY KEY (`mobo_id`),
  ADD KEY `fk_new_equipment` (`equipment_id`);

--
-- Indexes for table `mouse`
--
ALTER TABLE `mouse`
  ADD PRIMARY KEY (`mouse_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `msoffice`
--
ALTER TABLE `msoffice`
  ADD PRIMARY KEY (`msoffice_id`),
  ADD KEY `msoffice_ibfk_1` (`equipment_id`);

--
-- Indexes for table `msos`
--
ALTER TABLE `msos`
  ADD PRIMARY KEY (`msos_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `otherdevices`
--
ALTER TABLE `otherdevices`
  ADD PRIMARY KEY (`device_id`);

--
-- Indexes for table `pccase`
--
ALTER TABLE `pccase`
  ADD PRIMARY KEY (`pccase_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `peripherals`
--
ALTER TABLE `peripherals`
  ADD PRIMARY KEY (`peripheral_id`),
  ADD KEY `peripherals_ibfk_1` (`equipment_id`);

--
-- Indexes for table `printer`
--
ALTER TABLE `printer`
  ADD PRIMARY KEY (`printer_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `processor`
--
ALTER TABLE `processor`
  ADD PRIMARY KEY (`processor_id`),
  ADD KEY `processor_ibfk_1` (`equipment_id`);

--
-- Indexes for table `psu`
--
ALTER TABLE `psu`
  ADD PRIMARY KEY (`psu_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `ram`
--
ALTER TABLE `ram`
  ADD PRIMARY KEY (`ram_id`),
  ADD KEY `ram_ibfk_1` (`equipment_id`);

--
-- Indexes for table `software`
--
ALTER TABLE `software`
  ADD PRIMARY KEY (`software_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `ssd`
--
ALTER TABLE `ssd`
  ADD PRIMARY KEY (`ssd_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `user_registration`
--
ALTER TABLE `user_registration`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `wificard`
--
ALTER TABLE `wificard`
  ADD PRIMARY KEY (`wificard_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adobe`
--
ALTER TABLE `adobe`
  MODIFY `adobe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `avr`
--
ALTER TABLE `avr`
  MODIFY `avr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `equipment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `gpu`
--
ALTER TABLE `gpu`
  MODIFY `gpu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `hdd`
--
ALTER TABLE `hdd`
  MODIFY `hdd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `keyboard`
--
ALTER TABLE `keyboard`
  MODIFY `keyboard_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `lancard`
--
ALTER TABLE `lancard`
  MODIFY `lancard_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `monitor`
--
ALTER TABLE `monitor`
  MODIFY `monitor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `motherboard`
--
ALTER TABLE `motherboard`
  MODIFY `mobo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `mouse`
--
ALTER TABLE `mouse`
  MODIFY `mouse_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `msoffice`
--
ALTER TABLE `msoffice`
  MODIFY `msoffice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `msos`
--
ALTER TABLE `msos`
  MODIFY `msos_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `otherdevices`
--
ALTER TABLE `otherdevices`
  MODIFY `device_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pccase`
--
ALTER TABLE `pccase`
  MODIFY `pccase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `peripherals`
--
ALTER TABLE `peripherals`
  MODIFY `peripheral_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `printer`
--
ALTER TABLE `printer`
  MODIFY `printer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `processor`
--
ALTER TABLE `processor`
  MODIFY `processor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `psu`
--
ALTER TABLE `psu`
  MODIFY `psu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ram`
--
ALTER TABLE `ram`
  MODIFY `ram_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `software`
--
ALTER TABLE `software`
  MODIFY `software_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ssd`
--
ALTER TABLE `ssd`
  MODIFY `ssd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_registration`
--
ALTER TABLE `user_registration`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `wificard`
--
ALTER TABLE `wificard`
  MODIFY `wificard_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adobe`
--
ALTER TABLE `adobe`
  ADD CONSTRAINT `adobe_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `avr`
--
ALTER TABLE `avr`
  ADD CONSTRAINT `avr_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `gpu`
--
ALTER TABLE `gpu`
  ADD CONSTRAINT `gpu_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `hdd`
--
ALTER TABLE `hdd`
  ADD CONSTRAINT `fk_equipment_id` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `keyboard`
--
ALTER TABLE `keyboard`
  ADD CONSTRAINT `keyboard_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `lancard`
--
ALTER TABLE `lancard`
  ADD CONSTRAINT `lancard_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `fk_logs_user` FOREIGN KEY (`user_id`) REFERENCES `user_registration` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_registration` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `monitor`
--
ALTER TABLE `monitor`
  ADD CONSTRAINT `monitor_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `motherboard`
--
ALTER TABLE `motherboard`
  ADD CONSTRAINT `fk_new_equipment` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mouse`
--
ALTER TABLE `mouse`
  ADD CONSTRAINT `mouse_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `msoffice`
--
ALTER TABLE `msoffice`
  ADD CONSTRAINT `msoffice_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `msos`
--
ALTER TABLE `msos`
  ADD CONSTRAINT `msos_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `pccase`
--
ALTER TABLE `pccase`
  ADD CONSTRAINT `pccase_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `peripherals`
--
ALTER TABLE `peripherals`
  ADD CONSTRAINT `peripherals_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `printer`
--
ALTER TABLE `printer`
  ADD CONSTRAINT `printer_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `processor`
--
ALTER TABLE `processor`
  ADD CONSTRAINT `processor_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `psu`
--
ALTER TABLE `psu`
  ADD CONSTRAINT `psu_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `ram`
--
ALTER TABLE `ram`
  ADD CONSTRAINT `ram_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `software`
--
ALTER TABLE `software`
  ADD CONSTRAINT `software_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ssd`
--
ALTER TABLE `ssd`
  ADD CONSTRAINT `ssd_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wificard`
--
ALTER TABLE `wificard`
  ADD CONSTRAINT `wificard_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
