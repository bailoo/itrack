<?php
	  // DECIDE TABLE ID
    $DEBUG = 0;
    $new_table_flag = 0;  
    $track_table_id = 0;
    
    $query ="SELECT MAX(track_table_id) as mtid FROM device_lookup";
    $result = @mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $max_table_id = $row->tid;
    
    $query = "SELECT COUNT(track_table_id) as ctid, track_table_id FROM device_lookup WHERE track_table_id='$max_table_id'".
    " GROUP BY track_table_id";
    $result = @mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $count_table_id = $row->ctid;
    $track_table_id = $row->track_table_id;
       
    if($DEBUG) echo "<br>count_table_id=".$count_table_id." , track_table_id=".$track_table_id;
    
    if(!$count_table_id || $count_table_id == 100)
    {
      $new_table_flag = 1; 
      $track_table_id = $track_table_id + 1;   
    }    
    
    // CREATE NEW TABLE
    if($DEBUG) echo "<br>new_table_flag=".$new_table_flag;
    
    if($new_table_flag && $track_table_id)
    {
        $query = "Create Table t".$track_table_id."  (
        `Serial` int(10) NOT NULL auto_increment,
        `ServerTS` datetime default NULL,
        `DateTime` datetime default NULL,
        `VehicleID` varchar(25) default NULL,
        `MsgType` varchar(10) default NULL,
        `Version` varchar(30) default NULL,
        `DataValid` int(2) ,
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
        `BatteryDisconnect` varchar(10) default NULL,
        `Temperature` varchar(20) default NULL,
        `FrontDoorOpen` varchar(10) default NULL,
        `RearDoorOpen` varchar(10) default NULL,
        `Ignition` varchar(10) default NULL,
        `CoverOpen` varchar(10) default NULL,					  
        `Fuel` varchar(10) default NULL,
        `DoorStatus` varchar(5) default NULL,
        `ACStatus` varchar(5) default NULL,        
        `SupplyVoltage` varchar(10) default NULL,
        `RFID` varchar(30) default NULL,
        `SpeedAlert` int(2) default 0,
        `GeofenceInAlert` int(2)  default 0,
        `GeofenceOutAlert` int(2)  default 0,
        `StopAlert` int(2)  default 0,
        `MoveAlert` int(2)  default 0,
        `LowVoltageAlert` int(2)  default 0,
        
        `Last_Data` varchar(10) default NULL,
        PRIMARY KEY  (`Serial`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        
        $result = mysql_query($query, $DbConnection);    
    }             
    
    if($track_table_id)
    {
      $query="INSERT INTO device_lookup (device_imei_no, track_table_id)VALUES('$imei_no_1','$track_table_id')";
      $result=mysql_query($query,$DbConnection);
    }
    
?>