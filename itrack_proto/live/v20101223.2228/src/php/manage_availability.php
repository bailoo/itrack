<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  	
	$query = "SELECT account_id from account WHERE superuser='$superuser' and user='$user' and grp='admin'";
	$result = @mysql_query($query, $DbConnection);
	$row = mysql_fetch_object($result);     	
	$user_account_id = $row->account_id;	
	
  $DEBUG=0;
  $imei_no_1=$_POST['imei_no'];
  $super_user_1=$_POST['super_user'];
  $user_1=$_POST['user'];
  $qos_1=$_POST['qos'];
  $vname_1=$_POST['vname'];
  
  $add_route_name_1 =$_POST['add_route_name']; 
  $edit_route_name_1 = $_POST['edit_route_name']; 
  
  $add_geo_name_1 =$_POST['add_geo_name']; 
  $edit_geo_name_1 = $_POST['edit_geo_name'];
  
  $add_landmark_name_1 =$_POST['add_landmark_name']; 
  $edit_landmark_name_1 = $_POST['edit_landmark_name'];
 
  $geo_name_1=$_POST['geo_name']; 
  $route_name_1=$_POST['route_name'];
   
  $type = $_POST['type'];
    
  if($imei_no_1 !=null)
  {
      if($type == "new")
    	{
    		$query = "SELECT device_imei_no FROM device_manufacturing_info WHERE device_imei_no='$imei_no_1'";
    		 //echo "Q=".$query;    		  
      	$result = @mysql_query($query, $DbConnection);
      	
        if(@mysql_num_rows($result)) 
      	{
      	  $message="failure##<font color='red'>!!Not Available</font>";
      	}
      	else
      	{
      	 $message="success##<font color='green'>!! Available</font>";
        }
      	echo $message;  			
      }  
  
      else if($type == "existing")
    	{
    		$query = "SELECT device_imei_no FROM device_manufacturing_info WHERE device_imei_no='$imei_no_1'";
    		
      	$result = @mysql_query($query, $DbConnection);
      	
        if(@mysql_num_rows($result)) 
      	{
      	  $message="success##<font color='green'>!!Correct</font>";
      	}
      	else
      	{
      	 $message="failure##<font color='red'>!! Incorrect</font>";
        }
      	echo $message;  			
      } 
      
      else if($type="existing_in_user")
      {
      	
        $query = "SELECT device_imei_no FROM device_lookup WHERE ".
        "device_imei_no IN(SELECT device_imei_no FROM device_sales_info ".
        "WHERE user_account_id='$user_account_id') AND ".
        "device_imei_no NOT IN(SELECT device_imei_no FROM vehicletable WHERE status='1') and ".
        "device_imei_no='$imei_no_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	  $message="success##<font color='green'>!!Available</font>";
      	}
      	else
      	{
      	 $message="failure##<font color='red'>!! Not Available</font>";
        }
      	echo $message;  		
      }  
  } 
  
  else if($super_user_1 !=null)
	{
		$query = "SELECT superuser FROM account WHERE superuser='$super_user_1'";
	}	
  	 
  else if($user_1 !=null)
	{
		$query = "SELECT user FROM account WHERE user='$user_1'";
	}
		
  if( ($super_user_1 !=null	|| $user_1!=null))
  { 
    //echo $query;
    $result = @mysql_query($query, $DbConnection);
    	
    if(@mysql_num_rows($result)) 
  	{
  	  $message="success##<font color='green'>!!Correct</font>";
  	}
  	else
  	{
  	 $message="failure##<font color='red'>!! Incorrect</font>";
    }
  	echo $message;  			
  }  
  
  else if($qos_1 !=null)
	{
		$query = "SELECT MaxVehicle FROM qos WHERE QOS='$qos_1'";
		
    //echo $query;
    $result = @mysql_query($query, $DbConnection);
    	
    if(@mysql_num_rows($result)) 
  	{
  	  $row = @mysql_fetch_object($result);
  	  $max_vehicle = $row->MaxVehicle;
  	  
      $message = "success##<font color='green'>!!Correct [ Max-Vehicle - ".$max_vehicle."] </font>";
  	}
  	else
  	{
  	 $message="failure##<font color='red'>!! Incorrect</font>";
    }
  	echo $message;  					
	}	
  
  if($vname_1 !=null)
  {
      if($type == "existing_not_assigned")
    	{
      	
        $query = "SELECT VehicleID,VehicleName FROM vehicle WHERE ".
        "VehicleID IN(SELECT vehicle_id FROM vehicle_grouping WHERE ".
        "vehicle_group_id =(SELECT vehicle_group_id FROM ".
        "account_detail WHERE account_id='$account_id') AND ".
        "vehicle_id IN(SELECT VehicleID from vehicle WHERE VehicleSerial IS NULL)) AND ".
        "VehicleName='$vname_1'";
  
      	if($DEBUG==1)
          print_query($query);
 
      	$result = @mysql_query($query, $DbConnection);
      	
        if(@mysql_num_rows($result)) 
      	{
      	  $message="success##<font color='green'>!! Available</font>";          
      	}
      	else
      	{
      	  $message="failure##<font color='red'>!!Not Available</font>";
        }
      	echo $message;  			
      }  
  } 
  
  if($geo_name_1 !=null)
  {
      if($type="existing_in_user")
      {      	
        $query = "SELECT geo_id,geo_name FROM geofence WHERE user_account_id='$user_account_id' AND geo_name='$geo_name_1'";  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	  $message="success##<font color='green'>!!Available</font>";
      	}
      	else
      	{
      	 $message="failure##<font color='red'>!! Not Available</font>";
        }
      	echo $message;  		
      }  
  }
  if($route_name_1 !=null)
  {
      if($type="existing_in_user")
      {       	
        $query = "SELECT route_id,route_name FROM route WHERE user_account_id='$user_account_id' AND  route_name='$route_name_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	  $message="success##<font color='green'>!!Available</font>";
      	}
      	else
      	{
      	 $message="failure##<font color='red'>!! Not Available</font>";
        }
      	echo $message;  		
      }  
  } 
  
  if($add_route_name_1 !=null)
  {
      if($type="existing_in_user")
      {       	
        $query = "SELECT route_id,route_name FROM route WHERE user_account_id='$user_account_id' AND  route_name='$add_route_name_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	 $message="failure##<font color='red'>!! Not Available</font>";      	  
      	}
      	else
      	{ 
          $message="success##<font color='green'>!!Available</font>";      	 
        }
      	echo $message;  		
      }  
  }
  if($edit_route_name_1 !=null)
  {
      if($type="existing_in_user")
      {       	
        $query = "SELECT route_id,route_name FROM route WHERE user_account_id='$user_account_id' AND  route_name='$edit_route_name_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	  $message="failure##<font color='red'>!! Not Available</font>";
      	  
      	}
      	else
      	{
      	  $message="success##<font color='green'>!!Available</font>";
        }
      	echo $message;  		
      }  
  }
  
  if($add_geo_name_1 !=null)
  {
      if($type="existing_in_user")
      {       	
        $query = "SELECT geo_id,geo_name FROM geofence WHERE user_account_id='$user_account_id' AND  geo_name='$add_geo_name_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	 $message="failure##<font color='red'>!! Not Available</font>";      	  
      	}
      	else
      	{ 
          $message="success##<font color='green'>!!Available</font>";      	 
        }
      	echo $message;  		
      }  
  }
  if($edit_geo_name_1 !=null)
  {
      if($type="existing_in_user")
      {       	
        $query = "SELECT geo_id,geo_name FROM geofence WHERE user_account_id='$user_account_id' AND  geo_name='$edit_geo_name_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	  $message="failure##<font color='red'>!! Not Available</font>";
      	  
      	}
      	else
      	{
      	  $message="success##<font color='green'>!!Available</font>";
        }
      	echo $message;  		
      }  
  }
  
  if($add_landmark_name_1 !=null)
  {
      if($type="existing_in_user")
      {       	
        $query = "SELECT landmark_id,landmark_name FROM landmark WHERE user_account_id='$user_account_id' AND  landmark_name='$add_landmark_name_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	 $message="failure##<font color='red'>!! Not Available</font>";      	  
      	}
      	else
      	{ 
          $message="success##<font color='green'>!!Available</font>";      	 
        }
      	echo $message;  		
      }  
  }
  if($edit_landmark_name_1 !=null)
  {
      if($type="existing_in_user")
      {       	
        $query = "SELECT landmark_id,landmark_name FROM landmark WHERE user_account_id='$user_account_id' AND  landmark_name='$edit_landmark_name_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	  $message="failure##<font color='red'>!! Not Available</font>";
      	  
      	}
      	else
      	{
      	  $message="success##<font color='green'>!!Available</font>";
        }
      	echo $message;  		
      }  
  }
 
  	
	
?>