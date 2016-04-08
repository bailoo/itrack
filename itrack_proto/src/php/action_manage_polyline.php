<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
        include('coreDb.php');
	$DEBUG=0;
	
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);

	$old_value= Array();		$new_value=Array();			$field_name=Array();		$table_name="polyline"; 
  
	if($action_type1=="add") 
	{ 
		$polyline_name1 = trim($_POST['polyline_name']);
		
		$polyline_coord1 = $_POST['polyline_coord'];
		$polyline_coord1=trim($polyline_coord1);    
		$polyline_coord1 = base64_encode($polyline_coord1); 

		$max_no= getMaxSerialPolyline($DbConnection);
		if($max_no=="")
		{
			$max_no=1;
		}   
		$result=insertPolyline($account_size,$local_account_ids,$max_no,$polyline_name1,$polyline_coord1,$account_id,$date,$DbConnection);          	  
		if($result)
		{
			$flag=1;
			$action_perform="Added";
		}    

	}
        else if($action_type1=="register")
        {
            //phase 1 check polyline if registered in polyline_register
            //if yes then skip
            //else insert
            $account_id_to=$_POST['account_id_to'];
            $polyline_id=$_POST['polyline_id'];
            
            $sno=getSnoPolyline_register($polyline_id,$account_id_to,$DbConnection);
            /*
            $query_if="SELECT sno FROM polyline_register where polyline_id='$polyline_id' AND status='1' AND user_account_id=$account_id_to";
            $result_if=mysql_query($query_if,$DbConnection);
            $row_if=mysql_fetch_object($result_if);
            $sno=$row_if->sno;*/
            if($sno=="" && $polyline_id!='0')
            {
                //get polyline name
                $polyline_name=getPolylineNameByID($polyline_id,$DbConnection);
                /*$query_name="SELECT polyline_name FROM polyline where polyline_id='$polyline_id' AND status='1' ";
                $result_name=mysql_query($query_name,$DbConnection);
                $row_name=mysql_fetch_object($result_name);
                $polyline_name=$row_name->polyline_name;*/
                $result_insert=insertPolylineRegister($account_id_to,$polyline_id,$polyline_name,$account_id,$date,$DbConnection);
                /*$query_insert="INSERT INTO polyline_register(user_account_id,polyline_id,polyline_name,status,create_id,create_date) VALUES('$account_id_to','$polyline_id','$polyline_name','1','$account_id','$date')";
                //echo $query_insert;
                $result_insert=mysql_query($query_insert,$DbConnection); 
                */
                $flag=1;
		$action_perform="Register";
            }
            else
            {
                $flag=2;
                $action_perform="Already Register";
            }
            
        }
	else if($action_type1=="edit")
	{
		//$type="edit_delete";
		$polyline_id1 = $_POST['polyline_id'];    
		$polyline_name1 =trim($_POST['polyline_name']);
		$new_value[]=$polyline_name1;
		 
		$polyline_coord1 =base64_encode(trim($_POST['polyline_coord']));     
		$new_value[]=$polyline_coord1;           

		/*$query="SELECT * FROM polyline where polyline_id='$polyline_id1' AND status='1'";
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$polyline_name2=$row->polyline_name;
		$old_value[] =$polyline_name2;
		$field_name[]="polyline_name";
		$polyline_coord2=$row->polyline_coord;         
		$old_value[] = $polyline_coord2;
		$field_name[]="polyline_coord";*/ 

	
		$result=editPolyline($polyline_name1,$polyline_coord1,$account_id,$date,$polyline_id1,$DbConnection); 

		//$ret_result=track_table($polyline_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($result)
		{
			$flag=1;
			$action_perform="Updated";
		} 
		   
	}
	else if ($action_type1=="delete")
	{
		// $type="edit_delete";
		$polyline_id1 = $_POST['polyline_id']; 
		$cnt= getMaxCountPolylineAssign($polyline_id1,$DbConnection);
		if($cnt==0)
		{			
			$result=updatePolyline($account_id,$date,$polyline_id1,$DbConnection);    
			
			if($result)
			{
				$flag=1;
				$action_perform="Deleted";
			}
		}
		else
		{
			$flag=2;
			$action_perform="Not able to delete because this polyline is assigned to vehicle. Deassigned it First!";
		}
	}
	else if($action_type1=="assign")
	{
		$local_vehicle_ids = $_POST['vehicle_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);
		//echo "vehicle_size=".$vehicle_size."<br>";
		$local_polyline_id = $_POST['polyline_id'];				    
		$result=insertPolylineAssign($vehicle_size,$local_polyline_id,$local_vehicle_ids,$account_id,$date,$DbConnection);          	  
		if($result)
		{
			$flag=1;
			$action_perform="Assigned";
		} 		
	}
	else if($action_type1=="deassign")
	{
		$local_vehicle_ids = $_POST['vehicle_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);
		
		//$old_value= Array();   $new_value=Array();   $field_name=Array(); $table_name="polyline_assignment";

		for($i=0;$i<$vehicle_size;$i++)
		{				
			$result=deletePolylineAssign($account_id,$date,$local_vehicle_ids[$i],$DbConnection); 
			//$geo_id1= $veh_geo_id2[1];
			//$old_value[]= "1";
			//$new_value[]="0";
			//$field_name[]= "status";         
			//$ret_result=track_table($local_vehicle_ids[$i],$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		}
		
		if($result)
		{
			$flag=1;
			$action_perform="De-Assigned";
		} 	
	}
 
	if($flag==1)
	{
		$msg = "Polyline ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else if($flag==2)
	{
		$msg = $action_perform;
		$msg_color = "red";				
	}
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
  
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'polyline\');" class="back_css">&nbsp;<b>Back</b></a></center>';                 
  
?>
        