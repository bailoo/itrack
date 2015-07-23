<?php

function read_pending_invoice_db()
{
	global $DbConnection;
	global $account_id;
	global $sdatetime;
	global $sdatetime_open;
	global $pdatetime;
	global $cdatetime;
	global $cdatetime_db;

	global $transporter_id;
	global $transporter_name;
	global $vehicle_imei;
	global $customer_no;
	global $station_name;
	global $station_coord;
	global $distance_variable;
	
	global $customer_no_total;			//##### PLANTS TOTAL
	global $station_coord_total;
	global $distance_variable_total;
		
	global $chilling_plant_no;			//##### CHILLING PLANT TOTAL
	global $chilling_plant_coord_total;
	global $chilling_plant_distvar_total;

	global $transporter_child_account;	
	
	global $sno_db;
	global $lorry_no;
	global $vehicle_no;
	global $tanker_type;
	global $email;
	global $mobile;
	global $qty_kg;
	global $fat_percentage;
	global $snf_percentage;
	global $fat_kg;
	global $snf_kg;
	global $milk_age;
	global $docket_no;
	global $dispatch_time;
	global $target_time;
	global $validity_time;
	global $plant_acceptance_time;
	global $plant;
	global $chilling_plant;
	global $chilling_plant_coord;
	global $chilling_plant_distvar;
	global $driver_name;
	global $driver_mobile;
	global $unload_estimated_time;
	global $unload_accept_time;
	global $parent_account_id;
	global $create_id;
	global $create_date;
	global $close_type;
	global $close_time;
	global $system_time;	
	global $plant_outtime_db;;
	global $plant_intime_db;
	global $chilling_plant_outtime_db;
	global $gprs_chilling_plant_db;
	global $gprs_plant_db;
	
    //$query = "SELECT * FROM `invoice_mdrm` WHERE parent_account_id = '$account_id' AND ( (create_date BETWEEN '$pdatetime' and '$cdatetime') OR ( (system_time IS NULL)&&(invoice_status='1'))) AND status=1";
	//READ ALL -UNCLOSED / OPENED / PENDING INVOICES
	$query0 = "SELECT admin_id FROM account_detail WHERE account_id ='$account_id'";
	$result0 = mysql_query($query0,$DbConnection);
	$row0 = mysql_fetch_object($result0);
	$admin_id = $row0->admin_id;

/*$query ="SELECT DISTINCT sno,lorry_no,vehicle_no,tanker_type,email,mobile,qty_kg,fat_percentage,snf_percentage,fat_kg,snf_kg,milk_age,docket_no,dispatch_time,target_time,validity_time,plant_acceptance_time,plant,chilling_plant,driver_name,driver_mobile,unload_estimated_time,unload_accept_time,parent_account_id,transporter_account_id,create_date,invoice_status,close_type,close_time,system_time,plant_outtime,plant_intime,chilling_plant_outtime,gprs_chilling_plant,gprs_plant FROM `invoice_mdrm` WHERE ( ((parent_account_id = '$account_id') OR (parent_account_id IN (SELECT account_id FROM account_detail WHERE account_admin_id='$admin_id'))) AND ( ((create_date BETWEEN '$sdatetime_open' AND '$cdatetime_db') OR (edit_date BETWEEN '$sdatetime' and '$cdatetime_db')) AND (invoice_status=1) ) OR (edit_date between '$pdatetime' AND '$cdatetime_db')) AND (dispatch_time > '2015-01-01 00:00:00' AND status=1) AND (plant!='' AND chilling_plant!='') AND transporter_account_id!=0 GROUP BY (vehicle_no) ORDER BY vehicle_no DESC";*/

$query ="SELECT DISTINCT sno,lorry_no,vehicle_no,tanker_type,email,mobile,qty_kg,fat_percentage,snf_percentage,fat_kg,snf_kg,milk_age,docket_no,dispatch_time,target_time,validity_time,plant_acceptance_time,plant,chilling_plant,driver_name,driver_mobile,unload_estimated_time,unload_accept_time,parent_account_id,transporter_account_id,create_date,invoice_status,close_type,close_time,system_time,plant_outtime,plant_intime,chilling_plant_outtime,gprs_chilling_plant,gprs_plant FROM `invoice_mdrm` WHERE ( ((parent_account_id = '$account_id') OR (parent_account_id IN (SELECT account_id FROM account_detail WHERE account_admin_id='$admin_id'))) AND ( ((create_date BETWEEN '$sdatetime_open' AND '$cdatetime_db') OR (edit_date BETWEEN '$sdatetime' and '$cdatetime_db')) AND (invoice_status=1) ) OR (edit_date between '$pdatetime' AND '$cdatetime_db')) AND (dispatch_time > '2015-01-01 00:00:00' AND status=1) AND (plant!='' AND chilling_plant!='') AND transporter_account_id!=0 GROUP BY (vehicle_no) ORDER BY vehicle_no DESC,edit_date DESC";


    //echo "\n".$query;
    $result = mysql_query($query,$DbConnection);
	
    while($row = mysql_fetch_object($result))
    {
		//$remark_rdb[] = $row->remark_ev;
		$query2 = "SELECT DISTINCT vehicle_assignment.device_imei_no FROM vehicle_assignment,vehicle,vehicle_grouping WHERE vehicle_assignment.vehicle_id = vehicle.vehicle_id AND ".
		" vehicle.vehicle_name = '$row->vehicle_no' AND vehicle_assignment.status=1 AND vehicle.status=1 AND vehicle_grouping.vehicle_id=vehicle_assignment.vehicle_id AND vehicle_grouping.status=1 AND vehicle_grouping.account_id='$account_id'";				
		//echo "\nQ2=".$query2;
		$result2 = mysql_query($query2,$DbConnection); 			
		$numrows2 = mysql_num_rows($result2);
		//echo "\nNUM=".$numrows;
		$flag1 = false;
		$flag2 = false;
		
		if($numrows2>0)
		{				
			$flag1 = true;
		}
		
		$query3 = "SELECT customer_no,station_name,station_coord,distance_variable FROM station WHERE user_account_id='$account_id' AND customer_no='$row->plant' AND type=1 AND status=1";            
		//echo "\nQ3=".$query3;
		$result3 = mysql_query($query3,$DbConnection);
		$numrows3 = mysql_num_rows($result3);
		
		if($numrows3>0)
		{            
			$flag2 = true;
		}
		//###################           
		if($flag1 && $flag2)
		{			
			$query4 = "SELECT station_coord,distance_variable FROM station WHERE user_account_id='$account_id' AND customer_no='$row->chilling_plant' AND type=2 AND status=1";            
			//echo "\nQ4=".$query4;
			$result4 = mysql_query($query4,$DbConnection);
			$numrows4 = mysql_num_rows($result4);
			
			if($numrows4>0)
			{
				$row2 = mysql_fetch_object($result2);
				//echo "<br>IMEI=".$row2->device_imei_no;
				$vehicle_imei[] = $row2->device_imei_no;
				
				$row3 = mysql_fetch_object($result3);
				$customer_no[] = $row3->customer_no;
				$station_name[] = $row3->station_name; 
				$station_coord[] = $row3->station_coord;
				$distance_variable[] = $row3->distance_variable;
				
				$paccount_id = $row->parent_account_id;
				$tpt_id = $row->transporter_account_id;

				$query_name = "SELECT name FROM account_detail WHERE account_id='$tpt_id'";
				//echo "\nQname=".$query_name;
				$result_name = mysql_query($query_name,$DbConnection);
				$row_name = mysql_fetch_object($result_name);
				$transporter_id[] = $tpt_id;
				$transporter_name[] = $row_name->name;
				
				$sno_db[] = $row->sno;
				$lorry_no[] = $row->lorry_no;              
				$vehicle_no[] = $row->vehicle_no;
				$tanker_type[] = $row->tanker_type;
				$email[] = $row->email;                 
				$mobile[] = $row->mobile;                
				$qty_kg[] = $row->qty_kg;                
				$fat_percentage[] = $row->fat_percentage;        
				$snf_percentage[] = $row->snf_percentage;        
				$fat_kg[] = $row->fat_kg;                
				$snf_kg[] = $row->snf_kg;                
				$milk_age[] = $row->milk_age;              
				$docket_no[] = $row->docket_no;
				$dispatch_time[] = $row->dispatch_time;         
				$target_time[] = $row->target_time;           
				$validity_time[] = $row->validity_time;          
				$plant_acceptance_time[] = $row->plant_acceptance_time; 
				$plant[] = $row->plant;               
				
				$row4 = mysql_fetch_object($result4);
				
				$chilling_plant_tmp = $row->chilling_plant;
				$chilling_plant[] = $row->chilling_plant;
				$chilling_plant_coord[] = $row4->station_coord;
				$chilling_plant_distvar[] = $row4->distance_variable;
		
				$driver_name[] = $row->driver_name;           
				$driver_mobile[] = $row->driver_mobile;         
				$unload_estimated_time[] = $row->unload_estimated_time; 
				$unload_accept_time[] = $row->unload_accept_time;    
				$parent_account_id[] = $row->parent_account_id;     
				//$create_id[] = $row->create_id;             
				$create_date[] = $row->create_date;
				
				$tmp_out = $row->plant_outtime;
				if($tmp_out =='0000-00-00 00:00:00')
				{		
					$plant_outtime_db[] = '';
				}
				else
				{
					$plant_outtime_db[] = $tmp_out;
				}
				
				$tmp_in = $row->plant_intime;
				if($tmp_in == '0000-00-00 00:00:00')
				{
					$plant_intime_db[] = '';
				}
				else
				{
					$plant_intime_db[] = $tmp_in;
				}
				
				$tmp_cp = $row->chilling_plant_outtime;
				if($tmp_cp == '0000-00-00 00:00:00')
				{
					$chilling_plant_outtime_db[] = '';
				}
				else
				{
					$chilling_plant_outtime_db[] = $tmp_cp;
				}

				$gprs_chilling_plant_db[] = $row->gprs_chilling_plant;
				$gprs_plant_db[] = $row->gprs_plant;
				//$edit_id[] = $row->edit_id;               
				//$edit_date[] = $row->edit_date;            
				//$invoice_status[] = $row->invoice_status;        
				/*if($row->unload_estimated_time!='')
				{
					$close_time[] = $row->close_time;
				}
				else
				{
					$close_time[] = "";
				}*/

				//echo "\nSystemTime=".$row->system_time;
				if(($row->close_time > $pdatetime) && ($row->close_time < $cdatetime))
				{
					//echo "\nCloseTime=".$row->close_time;
					$close_type[] = $row->close_type;
					$close_time[] = $row->close_time;
					$system_time[] = $row->system_time;           
					//$status[] = $row->status;
					//################
				}
				else
				{
					$close_type[] = "";
					$close_time[] = "";
					$system_time[] = "";					
				}								
			}
			//#########  GET SEPARATE CODE FOR GETTING ALL CHILLING PLANT OF TRANSPORTER
			//$transporter_child_account[$paccount_id][] = $paccount_id;
			
			$query_admin = "SELECT account_detail.admin_id FROM account_detail,account WHERE account.account_id='$tpt_id' AND account.status=1 AND account.account_id=account_detail.account_id";
			//echo "\nP_ID=".$query_admin;
			$result_admin = mysql_query($query_admin,$DbConnection);
			if($row_admin = mysql_fetch_object($result_admin))
			{
				$admin_id = $row_admin->admin_id;
			}
			// paayas =>$admin_id = "1268"; 
			//echo "\nPaccount=".$paccount_id." ,admin_id=".$admin_id;
			$child_chilling_plant[$tpt_id][] = $chilling_plant_tmp; 
			get_child_transporter($tpt_id,$admin_id);
			
			//echo "\nSizeTPT_ChildAcc=".sizeof($transporter_child_account[$paccount_id]);
			for($t=0;$t<sizeof($transporter_child_account[$tpt_id]);$t++)
			{
				$tpt_acc = $transporter_child_account[$tpt_id][$t];
				$query_t = "SELECT customer_no FROM transporter_chilling_plant_assignment WHERE account_id='$tpt_acc' AND status=1";
				//echo "\nQT=".$query_t;
				$result_t = mysql_query($query_t,$DbConnection);
				if($row_t = mysql_fetch_object($result_t))
				{
					$child_chilling_plant[$tpt_id][] = $row_t->customer_no;
				}
			}
			
			//echo "<br>SizeCP=".sizeof($child_chilling_plant[$paccount_id]);
			for($c=0;$c<sizeof($child_chilling_plant[$tpt_id]);$c++)
			{
				$cno = $child_chilling_plant[$tpt_id][$c];
				$query5 = "SELECT customer_no,station_coord,distance_variable FROM station WHERE user_account_id='$account_id' AND customer_no='$cno' AND type=2 AND status=1";
				
				$result5 = mysql_query($query5,$DbConnection);
				//echo "\nQ5=".$query5;
				while($row5 = mysql_fetch_object($result5))
				{					
					//echo "<br>CP Stored";
					$chilling_plant_no[$tpt_id][] = $row5->customer_no;
					$chilling_plant_coord_total[$tpt_id][] = $row5->station_coord;
					$chilling_plant_distvar_total[$tpt_id][] = $row5->distance_variable;
				}
			}			
		}
    }
	
	$query6 = "SELECT customer_no,station_name,station_coord,distance_variable FROM station WHERE user_account_id='$account_id' AND type=1 AND status=1";            
	//echo "\nQ6=".$query6;
	$result6 = mysql_query($query6,$DbConnection);
	while($row6 = mysql_fetch_object($result6))
	{
		$customer_no_total[] = $row6->customer_no;
		//$station_name_total[] = $row6->station_name;
		$station_coord_total[] = $row6->station_coord;
		$distance_variable_total[] = $row6->distance_variable;
	}	
}


function get_child_transporter($parent,$admin_id)
{	
	global $DbConnection;
	global $transporter_child_account;
	$query = "SELECT account_id FROM account_detail where account_admin_id='$admin_id'";
	//echo "\nCHILD_ACC=".$query;
	$result = mysql_query($query,$DbConnection);
	$numrows = mysql_num_rows($result);
	if($numrows==0)
	{
		return false;
	}
	else
	{
		while($row = mysql_fetch_object($result))
		{
			$child_acc = $row->account_id;
			//echo "\nChildACC=".$child_acc;
			$transporter_child_account[$parent][] = $child_acc;
			
			$query2 = "SELECT account_detail.admin_id FROM account_detail,account WHERE account.account_id='$child_acc' AND account.status=1 AND account.account_id=account_detail.account_id";
			$result2 = mysql_query($query2,$DbConnection);
			if($row2 = mysql_fetch_object($result2))
			{
				$admin_id_child = $row2->admin_id;
				get_child_transporter($parent,$admin_id_child);
			}
		}
	}	
}
?>  
