<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');	

	$DEBUG = 0;
	
  $post_action_type = $_POST['action_type'];
  
  $imei_no_1=$_POST['imei_no'];
	$superuser_1=$_POST['super_user'];
	$user_1 = $_POST['user'];
	$qos_1 = $_POST['qos'];
      
  if($post_action_type =="add")
  {	    	
  	$query="SELECT account_id FROM account WHERE superuser='$superuser_1' AND user='$user_1' AND grp='admin'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $user_account_id=$row->account_id;
   
    date_default_timezone_set('Asia/Calcutta');
  	$date=date("Y-m-d h:i:s");
  		
    // DECIDE TABLE ID
    // GET CURRENT VEHICLE COUNT FROM `table`
    $query = "SELECT VehicleCount,TableID FROM `table` WHERE QOS='$qos_1' AND Status='NF'";
    $result = @mysql_query($query, $DbConnection);
    
    $numrows = @mysql_num_rows($result);
    
    $new_table_flag = 0;
    $track_table_id =0;
    
    
    if(!$numrows)  // IF COUNT EMPTY
    {
        $query = "INSERT INTO `table`(QOS,VehicleCount,Status) values('$qos_1','1','NF')";
        $result = mysql_query($query,$DbConnection);
        
        //print_query($query);
        // FETCH AND UPDATE TABLE AGAIN   - TO SUPPORT JAVA CODE
        $query = "SELECT TableID FROM `table` where QOS='$qos_1'";
        $result = @mysql_query($query,$DbConnection);
        $row = mysql_fetch_object($result);
        $tableid = $row->TableID;
        
        $tablename = "t".$tableid;
        $query = "UPDATE `table` SET TableName='$tablename' where QOS='$qos_1'";
        
        //print_query($query);
        $result = @mysql_query($query, $DbConnection);   
        
        $new_table_flag  = 1;       
    }
    else          // IF COUNT FOUND
    {
       $row = mysql_fetch_object($result);
       $current_vcount = $row->VehicleCount;     // GET CURRENT COUNT
       $tableid = $row->TableID;
       
       $current_vcount = $current_vcount + 1;     // INCREMENT COUNT BY 1
       
       //  SELECT MAX VEHICLE COUNT FROM qos 
       $query = "SELECT MaxVehicle FROM qos where QOS='$qos_1'"; 
       $result = @mysql_query($query,$DbConnection);
       $row = mysql_fetch_object($result);
       $max_vcount = $row->MaxVehicle;       // GET MAX VEHICLE COUNT
       
       // 
       if($current_vcount < $max_vcount)   
       {
         // UPDATE QUERY 
          $query = "UPDATE `table` SET VehicleCount='$current_vcount' WHERE TableID='$tableid'";
          $result = @mysql_query($query, $DbConnection);             
          //print_query($query);
       }  
       else if($current_vcount == $max_vcount)
       {
          // INSERT QUERY             
          $query = "UPDATE `table` SET VehicleCount='$current_vcount', Status='F' WHERE TableID='$tableid'";
          $result = @mysql_query($query, $DbConnection);    
          
          //print_query($query);         
          
          $query = "INSERT into `table`(QOS,VehicleCount,Status) Values ('$qos_1','1','NF')";                       
          $result = mysql_query($query, $DbConnection);
          
          //AGAIN UPDATE `table` - SUPPORT TO JAVA CODE
          $query = "SELECT TableID FROM `table` WHERE QOS='$qos_1' AND Status='NF'"; 
          
  				$result = mysql_query($query, $DbConnection);
  				$row = mysql_fetch_object($result);
  				$tableid=$row->TableID;		//get TableID
  				//echo "TableID=".$tableid."<br>";
  
  				$tablename='t'.$tableid;
  				//echo "tablename=".$tablename."<br>";
  			
  				$query = "UPDATE `table` SET TableName='$tablename' WHERE TableID='$tableid'";
  				$result = mysql_query($query, $DbConnection); 
          
          $new_table_flag  = 1;                      
       }     
    }
    
    // CREATE NEW TABLE
    if($new_table_flag == 1)
    {
        $query = "Create Table ".$tablename."  (
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
    
      
    $track_table_id = $tableid;
    
    if($track_table_id>=1)
    {
      $query="INSERT INTO device_lookup (device_imei_no, track_table_id)VALUES('$imei_no_1','$track_table_id')";
      $result=mysql_query($query,$DbConnection);
    }
    
    $query="INSERT INTO device_sales_info(device_imei_no, user_account_id, create_id, create_date, status)VALUES('$imei_no_1','$user_account_id','$account_id','$date','1')";
    $result=mysql_query($query,$DbConnection);  
      
    // PRINT MESSAGE
    if($result)
  	{
  	  $message="Device Sold Successfully";
    }
    else
    {
      $message="Unable to sell";
    }  
  }  
  
  else if($post_action_type == "edit")
  {   	
    $new_value[] = $qos_1;
         
    $query = "SELECT QOS FROM `table` WHERE TableID = (SELECT track_table_id FROM device_lookup WHERE ".
    "device_imei_no='$imei_no_1')";
    
    if($DEBUG == 1) print_query($query);
    
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $qos_old = $row->QOS; 
    	
    $old_value[] = $qos_2;
     
    
    //$msg = track_table($id,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);

    if($DEBUG == 1) print_query($query);
    
    if($result && $msg=="success")
      $message = "<center><br><br><FONT color=\"green\"><strong>Device Sale Detail Updated Successfully</strong></font></center>";
    else 
      $message = "<center><br><br><FONT color=\"green\"><strong>Unable to Update Device Sale Detail</strong></font></center>";  
  }
  
  else if($post_action_type == "delete")
  {       
    $query = "SELECT serial FROM device_sales_info WHERE device_imei_no='$imei_no_1'";
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $serial = $row->serial;
    
    $table = "device_sales_info";
    $old_value[]="1";$new_value[]="0";$field_name[]="status";
    
    $msg = track_table($serial,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);

    if($DEBUG == 1) echo "msg=".$msg;

    $query = "UPDATE device_sales_info SET status='0',edit_id='$account_id',edit_date='$date' WHERE device_imei_no='$imei_no_1'";
    
    if($DEBUG == 1) print_query($query);
    $result = mysql_query($query, $DbConnection);   
    if($result)
      $message = "<center><br><br><FONT color=\"red\"><strong>Selected Device Sale Detail Deleted Successfully</strong></font></center>";
    else
      $message = "<center><br><br><FONT color=\"red\"><strong>Unable to Delete Device Sale Detail Detail</strong></font></center>";    
  }
	
  echo' <br> 
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
    <td colspan="3" align="center"><b>'.$message.'</b></td>    
  </tr>
  </table>';
  
  include_once("manage_device_sale.php");
        	
?> 
	

