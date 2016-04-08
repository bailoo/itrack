-- phpMyAdmin SQL Dump
-- version 2.8.2.4
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Dec 23, 2010 at 04:54 PM
-- Server version: 5.0.24
-- PHP Version: 5.2.0RC4-dev
-- 
-- Database: `iespl_vts`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `account`
-- 

CREATE TABLE `account` (
  `account_id` int(11) NOT NULL auto_increment,
  `superuser` varchar(100) NOT NULL,
  `user` varchar(100) NOT NULL,
  `grp` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `hint_question` varchar(100) default NULL,
  `answer` varchar(100) default NULL,
  `date_of_birth` varchar(50) default NULL,
  `email` varchar(100) default NULL,
  `status` int(1) NOT NULL,
  `create_id` int(10) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`account_id`)
);

-- 
-- Dumping data for table `account`
-- 

INSERT INTO `account` (`account_id`, `superuser`, `user`, `grp`, `password`, `hint_question`, `answer`, `date_of_birth`, `email`, `status`, `create_id`, `create_date`, `edit_id`, `edit_date`, `remark`) VALUES (1, 'admin', 'admin', 'admin', 'fe01ce2a7fbac8fafaed7c982a04e229', NULL, NULL, NULL, 'root@iembsys.com', 1, 1, '2010-12-15 17:27:19', NULL, NULL, 'Root Account');

-- --------------------------------------------------------

-- 
-- Table structure for table `account_detail`
-- 

CREATE TABLE `account_detail` (
  `serial` int(10) NOT NULL auto_increment,
  `account_id` int(10) NOT NULL,
  `name` varchar(100) default NULL,
  `address1` varchar(100) default NULL,
  `address2` varchar(100) default NULL,
  `city` varchar(100) default NULL,
  `state` varchar(100) default NULL,
  `country` varchar(100) default NULL,
  `zip` varchar(50) default NULL,
  `phone` varchar(50) default NULL,
  `admin_id` int(10) NOT NULL,
  `account_admin_id` int(11) NOT NULL,
  `vehicle_group_id` int(10) NOT NULL,
  `company_id` int(10) NOT NULL,
  `permission` int(1) NOT NULL default '0',
  `create_id` int(10) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`serial`)
);

-- 
-- Dumping data for table `account_detail`
-- 

INSERT INTO `account_detail` (`serial`, `account_id`, `name`, `address1`, `address2`, `city`, `state`, `country`, `zip`, `phone`, `admin_id`, `account_admin_id`, `vehicle_group_id`, `company_id`, `permission`, `create_id`, `create_date`, `edit_id`, `edit_date`, `remark`) VALUES (1, 1, 'Root', 'SIDBI', 'IITK', 'Kanpur', 'UP', 'India', '208016', NULL, 0, 0, 1, 1, 1, 1, '2010-12-15 17:59:50', NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `account_feature`
-- 

CREATE TABLE `account_feature` (
  `serial` int(10) NOT NULL auto_increment,
  `account_id` int(10) NOT NULL,
  `create_id` int(10) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  `geofencing` int(1) default '0',
  `landmark` int(1) default '0',
  `route` int(1) default '0',
  `fuel` int(1) default '0',
  `trip` int(1) default '0',
  `test1` int(1) default '0',
  `test2` int(1) default '0',
  `test3` int(1) default '0',
  `test4` int(1) default '0',
  `test5` int(1) default '0',
  `test6` int(1) default '0',
  `test7` int(1) default '0',
  `test8` int(1) default '0',
  PRIMARY KEY  (`serial`)
);

-- 
-- Dumping data for table `account_feature`
-- 

INSERT INTO `account_feature` (`serial`, `account_id`, `create_id`, `create_date`, `edit_id`, `edit_date`, `remark`, `geofencing`, `landmark`, `route`, `fuel`, `trip`, `test1`, `test2`, `test3`, `test4`, `test5`, `test6`, `test7`, `test8`) VALUES (1, 1, 1, '2010-12-15 18:02:21', NULL, NULL, NULL, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `account_preference`
-- 

CREATE TABLE `account_preference` (
  `serial` int(10) NOT NULL auto_increment,
  `account_id` int(10) NOT NULL,
  `time_zone` varchar(50) NOT NULL,
  `default_map_view` varchar(15) default NULL,
  `latlng` int(1) default '1',
  `refresh_rate` int(1) default '1',
  `create_id` int(10) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`serial`)
);

-- 
-- Dumping data for table `account_preference`
-- 

INSERT INTO `account_preference` (`serial`, `account_id`, `time_zone`, `default_map_view`, `latlng`, `refresh_rate`, `create_id`, `create_date`, `edit_id`, `edit_date`, `remark`) VALUES (1, 1, 'GMT+5.5', NULL, 1, 1, 1, '2010-12-15 18:06:34', NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `company_info`
-- 

CREATE TABLE `company_info` (
  `company_id` int(10) NOT NULL auto_increment,
  `logo_id` int(10) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `copyright_name` varchar(150) NOT NULL,
  `status` int(1) NOT NULL,
  `create_id` int(10) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`company_id`)
);

-- 
-- Dumping data for table `company_info`
-- 

INSERT INTO `company_info` (`company_id`, `logo_id`, `company_name`, `copyright_name`, `status`, `create_id`, `create_date`, `edit_id`, `edit_date`, `remark`) VALUES (1, 1, 'Embedding Intelligence Everywhere', '@ Copyright IESPL All Right Reserved', 1, 1, '2010-12-15 17:56:19', NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `company_logo`
-- 

CREATE TABLE `company_logo` (
  `logo_id` int(10) NOT NULL auto_increment,
  `logo_file` varchar(200) NOT NULL,
  `status` int(1) NOT NULL,
  `create_id` int(10) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`logo_id`)
);

-- 
-- Dumping data for table `company_logo`
-- 

INSERT INTO `company_logo` (`logo_id`, `logo_file`, `status`, `create_id`, `create_date`, `edit_id`, `edit_date`, `remark`) VALUES (1, 'images/logo/iespl.jpg', 1, 1, '2010-12-15 17:30:48', NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `device_info`
-- 

CREATE TABLE `device_info` (
  `serial` int(10) NOT NULL auto_increment,
  `device_imei_no` varchar(100) NOT NULL,
  `sim` varchar(50) default NULL,
  `cellular_network` varchar(200) default NULL,
  `create_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `status` int(1) NOT NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`serial`)
);

-- 
-- Dumping data for table `device_info`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `device_lookup`
-- 

CREATE TABLE `device_lookup` (
  `sno` int(11) NOT NULL auto_increment,
  `device_imei_no` varchar(100) NOT NULL,
  `track_table_id` int(11) NOT NULL,
  PRIMARY KEY  (`sno`)
);

-- 
-- Dumping data for table `device_lookup`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `device_manufacturing_info`
-- 

CREATE TABLE `device_manufacturing_info` (
  `device_id` int(10) NOT NULL auto_increment,
  `device_imei_no` varchar(100) NOT NULL,
  `manufacture_date` datetime NOT NULL,
  `make` varchar(100) NOT NULL,
  `status` int(1) NOT NULL,
  `create_id` int(10) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`device_id`)
);

-- 
-- Dumping data for table `device_manufacturing_info`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `device_sales_info`
-- 

CREATE TABLE `device_sales_info` (
  `serial` int(10) NOT NULL auto_increment,
  `device_imei_no` varchar(100) NOT NULL,
  `user_account_id` int(10) NOT NULL,
  `status` int(1) NOT NULL,
  `create_id` int(10) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`serial`)
);

-- 
-- Dumping data for table `device_sales_info`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `geofence`
-- 

CREATE TABLE `geofence` (
  `sno` int(11) NOT NULL auto_increment,
  `geo_id` int(11) NOT NULL,
  `geo_name` varchar(100) NOT NULL,
  `geo_coord` blob NOT NULL,
  `user_account_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `create_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`sno`)
);

-- 
-- Dumping data for table `geofence`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `landmark`
-- 

CREATE TABLE `landmark` (
  `sno` int(11) NOT NULL auto_increment,
  `landmark_id` int(11) NOT NULL,
  `landmark_name` varchar(100) NOT NULL,
  `landmark_point` varchar(50) NOT NULL,
  `user_account_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `create_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`sno`)
);

-- 
-- Dumping data for table `landmark`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `log_login`
-- 

CREATE TABLE `log_login` (
  `log_id` int(12) NOT NULL auto_increment,
  `superuser` varchar(100) NOT NULL,
  `user` varchar(100) NOT NULL,
  `grp` varchar(100) NOT NULL,
  `account_id` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `flag` int(1) NOT NULL,
  `datetime_in` datetime NOT NULL,
  `datetime_out` datetime default NULL,
  `count` int(2) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `browser_number` varchar(50) NOT NULL,
  `browser_working` varchar(50) NOT NULL,
  `browser_name` varchar(50) NOT NULL,
  `browser` varchar(50) NOT NULL,
  `browser_v` varchar(50) NOT NULL,
  `os_name` varchar(50) NOT NULL,
  `os_number` varchar(50) NOT NULL,
  `os` varchar(50) NOT NULL,
  `width` int(8) NOT NULL,
  `height` int(8) NOT NULL,
  `resolution` varchar(25) NOT NULL,
  PRIMARY KEY  (`log_id`)
);

-- 
-- Dumping data for table `log_login`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `qos`
-- 

CREATE TABLE `qos` (
  `SNO` int(15) unsigned NOT NULL auto_increment,
  `QOS` int(5) NOT NULL,
  `Duration` int(7) NOT NULL,
  `MaxVehicle` int(15) NOT NULL,
  PRIMARY KEY  (`SNO`)
);

-- 
-- Dumping data for table `qos`
-- 

INSERT INTO `qos` (`SNO`, `QOS`, `Duration`, `MaxVehicle`) VALUES (1, 1, 1, 100);

-- --------------------------------------------------------

-- 
-- Table structure for table `report_type`
-- 

CREATE TABLE `report_type` (
  `sno` int(11) NOT NULL auto_increment,
  `report_name` varchar(100) NOT NULL,
  `related_feature` varchar(100) NOT NULL,
  PRIMARY KEY  (`sno`)
);

-- 
-- Dumping data for table `report_type`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `request`
-- 

CREATE TABLE `request` (
  `sno` int(11) NOT NULL auto_increment,
  `req_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `body` varchar(2000) NOT NULL,
  `status` int(1) NOT NULL,
  `create_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`sno`)
);

-- 
-- Dumping data for table `request`
-- 

INSERT INTO `request` (`sno`, `req_id`, `name`, `subject`, `body`, `status`, `create_id`, `create_date`, `edit_id`, `edit_date`, `remark`) VALUES (1, 1, 'Root', 'Setup', 'Initiating System', 0, 1, '2010-12-23 22:22:05', NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `route`
-- 

CREATE TABLE `route` (
  `sno` int(11) NOT NULL auto_increment,
  `route_id` int(11) NOT NULL,
  `route_name` varchar(100) NOT NULL,
  `route_coord` blob NOT NULL,
  `user_account_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `create_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`sno`)
);

-- 
-- Dumping data for table `route`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `t1`
-- 

CREATE TABLE `t1` (
  `Serial` int(10) NOT NULL auto_increment,
  `ServerTS` datetime default NULL,
  `DateTime` datetime default NULL,
  `VehicleID` varchar(25) default NULL,
  `DeviceIMEINo` varchar(25) default NULL,
  `MsgType` varchar(10) default NULL,
  `Version` varchar(20) default NULL,
  `SendMode` varchar(10) default NULL,
  `Latitude` varchar(20) default NULL,
  `Longitude` varchar(20) default NULL,
  `Altitude` varchar(20) default NULL,
  `Speed` double default NULL,
  `Fix` varchar(8) default NULL,
  `Signal_Strength` varchar(3) default NULL,
  `No_Of_Satellites` varchar(3) default NULL,
  `CBC` varchar(6) default NULL,
  `CellName` varchar(50) default NULL,
  `min_speed` varchar(7) default NULL,
  `max_speed` varchar(7) default NULL,
  `distance` varchar(10) default NULL,
  `IO_Value1` varchar(20) default NULL,
  `IO_Value2` varchar(20) default NULL,
  `IO_Value3` varchar(20) default NULL,
  `IO_Value4` varchar(20) default NULL,
  `IO_Value5` varchar(20) default NULL,
  `IO_Value6` varchar(20) default NULL,
  `IO_Value7` varchar(20) default NULL,
  `IO_Value8` varchar(20) default NULL,
  `BatteryDisconnect` varchar(10) default NULL,
  `Temperature` varchar(20) default NULL,
  `FrontDoorOpen` varchar(10) default NULL,
  `RearDoorOpen` varchar(10) default NULL,
  `Ignition` varchar(10) default NULL,
  `CoverOpen` varchar(10) default NULL,
  `Fuel` varchar(20) default NULL,
  `DoorStatus` varchar(5) default NULL,
  `ACStatus` varchar(5) default NULL,
  `SupplyVoltage` varchar(10) default NULL,
  `SpeedAlert` int(2) default NULL,
  `GeofenceInAlert` int(2) default NULL,
  `GeofenceOutAlert` int(2) default NULL,
  `StopAlert` int(2) default NULL,
  `MoveAlert` int(2) default NULL,
  `LowVoltageAlert` int(2) default NULL,
  `Last_Data` varchar(10) default NULL,
  PRIMARY KEY  (`Serial`)
);

-- 
-- Dumping data for table `t1`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `table`
-- 

CREATE TABLE `table` (
  `TableID` int(15) unsigned NOT NULL auto_increment,
  `TableName` varchar(20) default NULL,
  `QOS` int(15) default NULL,
  `VehicleCount` int(5) default NULL,
  `Status` varchar(5) default 'NF',
  PRIMARY KEY  (`TableID`)
);

-- 
-- Dumping data for table `table`
-- 

INSERT INTO `table` (`TableID`, `TableName`, `QOS`, `VehicleCount`, `Status`) VALUES (1, 't1', 1, 2, 'NF');

-- --------------------------------------------------------

-- 
-- Table structure for table `track_log`
-- 

CREATE TABLE `track_log` (
  `sno` int(11) NOT NULL auto_increment,
  `id` int(11) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `old_value` blob NOT NULL,
  `new_value` blob NOT NULL,
  `edit_id` int(11) NOT NULL,
  `edit_date` datetime NOT NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`sno`)
);

-- 
-- Dumping data for table `track_log`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `vehicle`
-- 

CREATE TABLE `vehicle` (
  `VehicleID` int(10) NOT NULL auto_increment,
  `VehicleSerial` varchar(100) default NULL,
  `VehicleName` varchar(100) NOT NULL,
  `VehicleType` varchar(50) NOT NULL,
  `tag` varchar(100) default NULL,
  `vehicle_number` varchar(100) NOT NULL,
  `max_speed` int(5) NOT NULL,
  `geo_id` int(11) default NULL,
  `route_id` int(11) default NULL,
  `status` int(1) NOT NULL,
  `create_id` int(10) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(200) default NULL,
  PRIMARY KEY  (`VehicleID`)
);

-- 
-- Dumping data for table `vehicle`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `vehicle_grouping`
-- 

CREATE TABLE `vehicle_grouping` (
  `serial` int(10) NOT NULL auto_increment,
  `vehicle_group_id` int(10) NOT NULL,
  `vehicle_id` int(10) NOT NULL,
  `status` int(1) NOT NULL,
  `create_id` int(10) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`serial`)
);

-- 
-- Dumping data for table `vehicle_grouping`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `vehicletable`
-- 

CREATE TABLE `vehicletable` (
  `sno` int(10) NOT NULL auto_increment,
  `VehicleID` int(11) NOT NULL,
  `TableID` int(11) NOT NULL,
  `device_imei_no` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `create_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`sno`)
);

-- 
-- Dumping data for table `vehicletable`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `vehicletable_track`
-- 

CREATE TABLE `vehicletable_track` (
  `sno` int(10) NOT NULL auto_increment,
  `VehicleID` int(11) NOT NULL,
  `TableID` int(11) NOT NULL,
  `device_imei_no` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `edit_id` int(11) default NULL,
  `edit_date` datetime default NULL,
  `remark` varchar(100) default NULL,
  PRIMARY KEY  (`sno`)
);

-- 
-- Dumping data for table `vehicletable_track`
-- 

