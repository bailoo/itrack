<?php
include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  include_once('lib/VTSFuel.php'); 
  $postPars = array('calibration_id' , 'action_type' , 'local_account_ids' , 'calibration_name' , 'calibration_data', 'vehicle_ids');
  include_once('action_post_data.php');
  
    include_once('coreDb.php');
  $pd = new PostData();   
  $DEBUG=0;   
 // $setgetpostvalue_obj=new setgetpostvalue();    		
  $local_account_ids=$setgetpostvalue_obj->localAccoutIDS;
	$account_size=sizeof($local_account_ids);
  $old_value= Array();
  $new_value=Array();
  $field_name=Array();
  $table_name="calibration"; 
  
	if($pd->data[action_type]=="add") 
	{
		$action_type="add";  
		// $calibration_data2 = VTSFuel::checkCalibration($pd->data[calibration_data]);    
			$final_calibration_data = VTSFuel::checkCalibration($pd->data[calibration_data]);
		// $final_calibration_data = VTSFuel::calibrationDbToDisplay($calibration_data2); 
	  // echo "final_value=".$final_calibration_data;
		if($final_calibration_data!="")
		{
			  $calibration_name1=$pd->data[calibration_name]; 
			  $local_account_ids=explode(",",$pd->data[local_account_ids]);
			  $account_size=sizeof($local_account_ids);     	
			
			  $max_no= getCalibrationMaxSerial($DbConnection);
			  if($max_no=="")
			  {
				$max_no=1;
			  }		
			
			$result=insertCalibration($max_no,$calibration_name1,$final_calibration_data,1,$account_id,$date,$DbConnection);
			if($result)
			{
			   $result=insertCalibrationGrouping($account_size,$max_no,$local_account_ids,$account_id,$date,$DbConnection);  		             	  
				if($result){$flag=1;$action_perform="Added";} 
			}
		}
		else
		{
		  $flag=2;
		} 
	}   
	else if($pd->data[action_type]=="edit")
	{	
		$file_name="src/php/manage_edit_delete_calibration.php"; ///////for previous page
		$action_type="edit_delete";
		$calibration_id=$pd->data[calibration_id]; 
		$calibration_name=$pd->data[calibration_name];
		$calibration_data=$pd->data[calibration_data];
		//echo "calibration_data=".$calibration_data."<br>calibration_name=".$calibration_name."<br>";
		// $calibration_data2 = VTSFuel::checkCalibration($calibration_data);    
			$final_calibration_data = VTSFuel::checkCalibration($calibration_data);
		// $final_calibration_data = VTSFuel::calibrationDbToDisplay($calibration_data2);
		//echo "cal=".$final_calibration_data."<br>";
		if($final_calibration_data!="")
		{		
			$result=updateCalibration($calibration_name,$final_calibration_data,$account_id,$date,$calibration_id,$DbConnection);
			
			if($result)
			{
				$flag=1;
				$action_perform="Updated";
			} 
		} 
		else
		{
		  $flag=2;
		}    
	}
	else if ($pd->data[action_type]=="delete")
	{
		$file_name="src/php/manage_edit_delete_calibration.php"; ///////for previous page
		$action_type="edit_delete"; 
		$calibration_id=$pd->data[calibration_id];
				
		$numrows=getCalibrationIdCalibrationVehicleAssignment($calibration_id,1,$DbConnection);		
		if($numrows>0)
		{
		  $delete_flag=1; 
		}
		else
		{
			$file_name="src/php/manage_edit_delete_calibration.php"; ///////for previous page  			 	    
			$result=updateCalibration1($account_id,$date,0,$calibration_id,1,$DbConnection);
			if($result)
			{
				$flag=1;$action_perform="Deleted";
			}
		}
	}
	else if($pd->data[action_type]=="assign")
	{
		$file_name="src/php/manage_calibration_vehicle_assignment.php"; ///////for previous page
		$action_type="assign";
		$local_vehicle_ids=explode(",",$pd->data[vehicle_ids]);
		$vehicle_size=sizeof($local_vehicle_ids);
		$local_calibration_id = $pd->data[calibration_id];		
        $result=insertCalibrationVehicleAssignment($vehicle_size,$local_calibration_id,$local_vehicle_ids,$account_id,$date,1,$DbConnection);
		if($result)
		{
			$flag=1;
			$action_perform="Assigned";
		} 		
	}
	else if($pd->data[action_type]=="deassign")
	{
	  $file_name="src/php/manage_calibration_vehicle_deassignment.php"; ///////for previous page
      $action_type="de-assign";
		$local_vehicle_ids=explode(",",$pd->data[vehicle_ids]);
		$vehicle_size=sizeof($local_vehicle_ids); 	 		
 
		for($i=0;$i<$vehicle_size;$i++)
		{	
		    $result=updateCalibrationVehicleAssignment(0,$account_id,$date,$local_vehicle_ids[$i],1,$DbConnection);
		}		
		if($result)
		{
			$flag=1;
			$action_perform="De-Assigned";
		} 	
	}
     
	if($flag==1)
	{
		$msg = "Calibration ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else if($flag==2)
	{	  
		$msg = "Calibration input is not correct.Plesae enter correct input.";
		$msg_color = "green";				
	}
	else if($delete_flag==1)
	{
    $msg = "This calibration has been Assigned to Vehicle! Deassign First!</strong></font></center>";
    $msg_color = "red";	
  }
	else
	{ 	 
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
	
	
	if($flag==2)
	{	      
    $common_str="calibartion_id=".$pd->data[calibration_id]."&action_type=".$action_type."&local_account_ids=".$pd->data[local_account_ids]."&common_id=".$pd->data[local_account_ids]."&calibration_name=".$pd->data[calibration_name]."&calibration_data=".$pd->data[calibration_data];
	  //echo"common_str=".$common_str;
    echo"<input type='hidden' id='common_id' value='".$common_str."'>";
  }
    
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  if($pd->data[action_type]=="add")
  {
    $common_str="action_type=".$pd->data[action_type]; 
    echo"<input type='hidden' id='common_id' value='".$common_str."'>";
    echo'<center><a href="javascript:show_option_with_value(\'manage\',\'add_calibration\');" class="back_css">&nbsp;<b>Back</b></a></center>';
  }
  else
  {
    $common_str="action_type=".$action_type."&common_id=".$pd->data[local_account_ids]; 
    echo"<input type='hidden' id='common_id' value='".$common_str."'>";
    echo'<center><a href="javascript:manage_action_edit_prev(\''.$file_name.'\');" class="back_css">&nbsp;<b>Back</b></a></center>';
  }
   
?>
        
