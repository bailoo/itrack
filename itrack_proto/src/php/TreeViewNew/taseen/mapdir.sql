-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 24, 2011 at 07:13 AM
-- Server version: 5.1.30
-- PHP Version: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mapdir`
--

-- --------------------------------------------------------

--
-- Table structure for table `mapdir`
--

CREATE TABLE IF NOT EXISTS `mapdir` (
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mapdir`
--

INSERT INTO `mapdir` (`value`) VALUES
('{"start":{"lat":26.10488,"lng":-80.39231999999998},"end":{"lat":25.94161,"lng":-80.16158000000001},"waypoints":[[26.0675,-80.29781000000003],[26.0105666,-80.24428790000002]]}');
