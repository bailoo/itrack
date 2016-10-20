<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');   
	include_once('coreDb.php'); 
	$account_id_local1 = $_POST['account_id_local'];

	$DEBUG=0;
	$FIELD_DEBUG=0;

	$field_value = $_POST['field_value'];   /////coming name or user_id
	$file_type = $_POST['file_type'];
	$local_account_id = $_POST['local_account_id1'];
	$vendor_group_id = $_POST['vendor_group_id1'];
	
		//// sales /////
	$expense_group_id = $_POST['expense_group_id1'];
	$call_group_id = $_POST['call_group_id1'];  
    ///// sales stop /////
	if($FIELD_DEBUG==1){echo "field_value=".$field_value." file_type=".$file_type." acccount_id_local=".$local_account_id."<br>";}
	
	function failure_successesor($row_result_param)
	{
		//echo "row_result_param=".$row_result_param;
		if($row_result_param>0)
		{
			$message="failure##<font color='red'>!! Not Available</font>";
		}
		else
		{
			$message="success##<font color='green'>!!Available</font>";
		}
		return $message;	
	}

	if($FIELD_DEBUG==1){echo "field_value=".$field_value." file_type=".$file_type." acccount_id_local=".$local_account_id."<br>";}

	if($file_type=="vehicle")
	{
    	$row_result=getVehicleNumRow($field_value,1,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}   	
	if($file_type=="device")
	{
		$row_result=getNumRowDeviceManfInfo($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}
	if($file_type=="vehicle_assignment")
	{
		$result = getNumRowVehicleByNameAccount($field_value,$local_account_id,$DbConnection);
		if($result)
		{
			$message="success##<font color='green'>!!Available</font>";
		}
		else
		{
			$message="failure##<font color='red'>!!Not Available</font>";
		}
		echo $message;
	}	
	if($file_type=="device_assignment")
	{
		$result = getNumRowDeviceAssign($field_value,$local_account_id,$DbConnection);
		if($result)
		{
			$message="success##<font color='green'>!!Available</font>";
		}
		else
		{
			$message="failure##<font color='red'>!!Not Available</font>";
		}
		echo $message;
	}	
	if($file_type=="calibration")
	{
		$row_result=getNumRowCallibration($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}
	
	if($file_type=="consignment")
	{
		$row_result=getNumRowConsignment($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}
	
	if($file_type=="vendor_type")
	{
		
		$row_result=getNumRowVendorType($vendor_group_id,$field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;	
	}
	if($file_type=="vendor_account")
	{
		
		$row_result=getNumRowVendor($field_value,$DbConnection);		
		$message=failure_successesor($row_result);
		echo $message;	
	}
  
  if($file_type=="landmark")
	{             	     	                       
        $row_result=getNumRowLandmark($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}
	if($file_type=="location")
	{     	
	   	     	                       
		$row_result=getNumRowScheduleLocationSingle($field_value,$DbConnection);
		//echo "row_result=".$row_result."<br>";
		$message=failure_successesor($row_result);
		echo $message;
	}
	if($file_type=="geofence")
	{     	
            	     	                       
        $row_result=getNumRowGeofence($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}
	if($file_type=="polyline")
	{     	
            	     	                       
		$row_result=getNumRowPolyline($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}
        if($file_type=="polyline_name_edit")
	{     	
            	$polyline_id = $_POST['actual_id']; 	                       
		$row_result=getNumRowPolylineExclude($field_value,$polyline_id,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}
	if($file_type=="visit_area")
	{     	
          	     	                       
		$row_result=getNumRowVisitArea($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}	
	

	if($file_type=="station_no")
	{

		$row_result=getCustomerNoStation($field_value,$account_id,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}


	if($file_type=="station")
	{     	
		   	     	                       
		$row_result=getNumRowStation($field_value,$local_account_id,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}	

	
	if($file_type=="sector")
	{     	
           	     	                       
		$row_result=getNumRowSectorName($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}	
	if($file_type=="route")
	{     	
           	     	                       
		$row_result=getNumRowRoute($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}
	
	if($file_type=="transporter")
	{     	
            	     	                       
		$row_result=getNumRowTranspoter($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}	

	if($file_type=="school")
	{     	
             	     	                       
		$row_result=getNumRowSchool($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}
	
	if($file_type=="student")
	{     	
         	     	                       
		$row_result=getNumRowStudent($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}
	
	if($file_type=="studentcard")
	{     	
           	     	                       
		$row_result=getNumRowStudentCard($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}
	
	if($file_type=="busstop")
	{     	
           	     	                       
		$row_result=getNumRowBusStop($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}
	
	if($file_type=="busroute")
	{     	
				   
		$row_result=getNumRowBusRoute($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}

	if($file_type=="shift")
	{     	
             	     	                       
		$row_result=getNumRowShift($field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}
		////////// sales ///////////
	if($file_type=="expense_type")
	{
		$row_result=getNumRowExpenceType($expense_group_id,$field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;	
	}

	if($file_type=="call_type")
	{
		$row_result=getNumRowCallType($call_group_id,$field_value,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;	
	}
	/////////stop sales /////////
         if($file_type=="invoice_rawmilk" )
	{
		$rowcnt=getRawMilkInvoiceMaterial($field_value,$DbConnection);
		//$message=failure_successesor($row_result);
                if($rowcnt>0)
                {
                    echo "addMaterial##<font color=red>Not Available</font>";	
                }
                else
                {
                    echo "addMaterial##<font color=green>Available</font>"; 
                }
	}
          //=========person
        if($file_type=="station_no_person")
	{

		$row_result=getCustomerNoStationPerson($field_value,$account_id,$DbConnection);
		//echo $row_result;
                //exit();
                $message=failure_successesor($row_result);
		echo $message;
	}
        if($file_type=="station_person")
	{     	
		   	     	                       
		$row_result=getNumRowStationPerson($field_value,$local_account_id,$DbConnection);
		$message=failure_successesor($row_result);
		echo $message;
	}
	?>
