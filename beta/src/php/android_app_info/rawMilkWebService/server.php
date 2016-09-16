<?php
include_once('util_session_variable.php');  	   //util_session_variable.php sets values in session
include_once('util_php_mysql_connectivity.php');   
require_once "lib/nusoap.php"; 

//========login
function getLogin($user_id,$pass,$group_id)
{
     
	$post_group_id = $group_id;		
	$post_user_id = $user_id;	
	$post_password = md5($pass);	
	global $DbConnection;
	
	$query="SELECT account.account_id,account.user_type,account.group_id FROM account,account_detail WHERE (account.group_id='$post_group_id' OR account.group_id IS NULL) AND account.account_id=account_detail.account_id AND account.user_id='$post_user_id' AND account.password='$post_password' AND (account.status=1 OR account.status=4)";	
	$result = mysql_query($query, $DbConnection);
	$count = mysql_num_rows($result);	
	if($count > 0)
	{
		$row =mysql_fetch_object($result);
		$account_id=$row->account_id;
		$userTypeLogin=$row->user_type;
		if($userTypeLogin=="raw_milk")//supplier or transporter
		{
			$success='1'; //success
		
			//ok
		}
		else
		{
			$success='0';//fail
		}
		
	}
	else
	{
		$success='0';//fail
	}
	return $success;
}


function getPendingInvoice($user_id,$group_id)
{
	$post_group_id = $group_id;		
	$post_user_id = $user_id;	
	global $DbConnection;
	
	$pending_string=array();
	
	$query="SELECT account.account_id,account.user_type,account.group_id FROM account,account_detail WHERE (account.group_id='$post_group_id' OR account.group_id IS NULL) AND account.account_id=account_detail.account_id AND account.user_id='$post_user_id'  AND (account.status=1 OR account.status=4)";	
	$result = mysql_query($query, $DbConnection);
	$count = mysql_num_rows($result);	
	if($count > 0)
	{
		$row =mysql_fetch_object($result);
		$account_id=$row->account_id;
		$userTypeLogin=$row->user_type;
		if($userTypeLogin=="raw_milk")//supplier or transporter
		{
		
			$raw_milk_account = assign_to_till_root($account_id);
		
			$LRNO="";  $Vehicle = ""; $Transporter = "";$transporter_name=""; $mobileno=""; $emailid=""; $drivername=""; $drivermobile=""; $qty=""; $snf_per=""; $snf_kg="";
			$fat_kg=""; $fat_per=""; $milk_age=""; $disp_time=""; $target_time=""; $plant="";$final_plant_name=""; $chillplant=""; $chillplant_name="";$tankertype="";
			$sno_id="";
			
		
			$queryPending = "SELECT * FROM invoice_mdrm WHERE status=1 AND invoice_status=5 AND (vehicle_no is NULL OR vehicle_no='') AND transporter_account_id IN ($account_id) ";
			//echo $queryPending;
			$resultPending = mysql_query($queryPending,$DbConnection);
			while($rowPending = mysql_fetch_object($resultPending))
			{
				$LRNO = $rowPending->lorry_no;
				$Vehicle = $rowPending->vehicle_no;
				$Transporter =  $rowPending->transporter_account_id;
				$queryType="SELECT user_type,user_id from account WHERE account_id='$Transporter'";
				//echo "<br>".$queryType;
				$resultType=mysql_query($queryType,$DbConnection);
				$rowType=mysql_fetch_row($resultType);
				$function_account_type=$rowType[0];
				$transporter_name=$rowType[1];
				
				$emailid = $rowPending->email;
				$mobileno= $rowPending->mobile;
				$drivername = $rowPending->driver_name;
				$drivermobile = $rowPending->driver_mobile;
				$qty = $rowPending->qty_kg;
				$fat_per = $rowPending->fat_percentage;
				$snf_per = $rowPending->snf_percentage;
				$fat_kg = $rowPending->fat_kg;
				$snf_kg = $rowPending->snf_kg;
				$milk_age = $rowPending->milk_age;
				$disp_time = $rowPending->dispatch_time;
				$target_time = $rowPending->target_time;
				
				$plant = $rowPending->plant;
				$query_plant = "SELECT station_name FROM station WHERE type=1 AND customer_no='$plant' AND  user_account_id='$raw_milk_account[0]' AND status=1";
				$result_query = mysql_query($query_plant,$DbConnection);
				$row=mysql_fetch_object($result_query);				
				$final_plant_name=$row->station_name;
				
				
				
				$chillplant = $rowPending->chilling_plant;
				
				$query_chillplant = "SELECT station_name FROM station WHERE type=2 AND  customer_no='$chillplant' AND status=1";
				//echo $query_chillplant;
				$result_chillquery = mysql_query($query_chillplant,$DbConnection);
				$rowchill=mysql_fetch_object($result_chillquery);					
				$chillplant_name=$rowchill->station_name;
				
				
				$tankertype =  $rowPending->tanker_type;
				$sno_id = $rowPending->sno;
				
				$pending_string[]=array("lrno"=>$LRNO , "vehicle"=>$Vehicle, "transport_id"=>$Transporter,"transport_name"=>$transporter_name,"emailid"=>$emailid , "mobileno"=>$mobileno ,"drivername"=>$drivername,"drivermobile"=>$drivermobile,"qty"=>$qty,"fat_per"=>$fat_per,"fat_kg"=>$fat_kg ,"snf_per" => $snf_per ,"snf_kg"=>$snf_kg,"milk_age"=>$milk_age,"disp_time"=>$disp_time,"target_time"=>$target_time,"plant"=>$plant,"final_plant_name"=>$final_plant_name,"chillplant"=>$chillplant,"chillplant_name"=>$chillplant_name,"tankertype"=>$tankertype,"sno_id"=>$sno_id);
				
			}
		
		}
		
	}
	
	$pending_string_array = json_encode($pending_string);
	return $pending_string_array;
}

function getPlant($user_id,$group_id)
{

	$post_group_id = $group_id;		
	$post_user_id = $user_id;	
	$final_plant_list_array=array();
	global $DbConnection;
	
	$query="SELECT account.account_id,account.user_type,account.group_id FROM account,account_detail WHERE (account.group_id='$post_group_id' OR account.group_id IS NULL) AND account.account_id=account_detail.account_id AND account.user_id='$post_user_id' AND (account.status=1 OR account.status=4)";	
	$result = mysql_query($query, $DbConnection);
	$count = mysql_num_rows($result);	
	if($count > 0)
	{
		$row =mysql_fetch_object($result);
		$account_id=$row->account_id;
		$userTypeLogin=$row->user_type;
		if($userTypeLogin=="raw_milk")//supplier or transporter
		{	
			
			$raw_milk_account = assign_to_till_root($account_id);
			if($raw_milk_account){
				foreach($raw_milk_account as $rma)
				{
					//plant
					$query_plant = "SELECT customer_no,station_name FROM station WHERE type=1 AND user_account_id='$rma' AND status=1";
					$result_query = mysql_query($query_plant,$DbConnection);
					while($row=mysql_fetch_object($result_query))
					{
						//echo $row->customer_no;
						//$final_plant_list[]=$row->customer_no;
						//$final_plant_name_list[]=$row->station_name;
						$final_plant_list_array[]=array("plant_id"=>$row->customer_no,"plant_name"=>$row->station_name);
					}
					
				}
			}
		
		}
	}
	return json_encode($final_plant_list_array);
}

function getChillingPlant($user_id,$group_id)
{
	$post_group_id = $group_id;		
	$post_user_id = $user_id;	
	$post_password = md5($pass);	
	global $DbConnection;
	
	$query="SELECT account.account_id,account.user_type,account.group_id FROM account,account_detail WHERE (account.group_id='$post_group_id' OR account.group_id IS NULL) AND account.account_id=account_detail.account_id AND account.user_id='$post_user_id' AND  (account.status=1 OR account.status=4)";	
	$result = mysql_query($query, $DbConnection);
	$count = mysql_num_rows($result);	
	if($count > 0)
	{
		$row =mysql_fetch_object($result);
		$account_id=$row->account_id;
		$userTypeLogin=$row->user_type;
		if($userTypeLogin=="raw_milk")//supplier or transporter
		{
			$final_chillplant_list_array=array();
			$default_customer_no_supplier=array();
			$query_default_chillplant_supplier = "SELECT customer_no FROM transporter_chilling_plant_assignment WHERE account_id ='$account_id' AND status=1";
			//echo $query_default_chillplant_supplier."<br>";
			$query_default_chillplant_supplier = mysql_query($query_default_chillplant_supplier,$DbConnection);
			while($rowchillA=mysql_fetch_object($query_default_chillplant_supplier))
			{
				$default_customer_no_supplier[] = $rowchillA->customer_no;
				
			}
			$conditionStr="";
			foreach($default_customer_no_supplier as $crma)
			{
				$conditionStr=$conditionStr." station.customer_no='$crma' OR ";
			}
			$conditionStr=substr($conditionStr,0,-3);
			//chill plant
			$query_chillplant = "SELECT customer_no,station_name FROM station WHERE type=2 AND ($conditionStr) AND status=1";
			//echo $query_chillplant;
			$result_chillquery = mysql_query($query_chillplant,$DbConnection);
			while($rowchill=mysql_fetch_object($result_chillquery))
			{
				//$final_chillplant_list[]=$rowchill->customer_no;
				//$final_chillplant_name_list[]=$rowchill->station_name;
				$final_chillplant_list_array[]=array("chillingplantid"=>$rowchill->customer_no,"chiilingplantname"=>$rowchill->station_name);
			}
			return json_encode($final_chillplant_list_array);
			
		}
	}
}


function getVehicle($user_id,$group_id)
{
    // $user_id=$_POST['user_id'];
	//$group_id=$_POST['group_id'];
    /*$v="HR38N9714,HR55E1572,HR474589,HR38H9025,HR46C4284,RJ05GA2767,RJ14GC4110,RJ11GE2340,HR38N8119,UP80CT1201,HR38M8160,HR38L2027,HR38L6365,RJ14GE0007 not move,HR38A8631 uid=".$user_id." group_id=".$group_id;
	return $v;*/
	global $DbConnection;
	$post_group_id = $group_id;		
	$post_user_id = $user_id;	
		

	
	$query="SELECT account.account_id,account.user_type,account.group_id FROM account,account_detail WHERE (account.group_id='$post_group_id' OR account.group_id IS NULL) AND account.account_id=account_detail.account_id AND account.user_id='$post_user_id' AND (account.status=1 OR account.status=4)";	
	//return $query;
	
	$result = mysql_query($query, $DbConnection);
	
	$count = mysql_num_rows($result);	
	if($count > 0)
	{
		$row =mysql_fetch_object($result);
		$account_id=$row->account_id;
		$userTypeLogin=$row->user_type;
		if($userTypeLogin=="raw_milk")//supplier or transporter
		{				
				$vehicle_list=array();
				$queryVehicle = "SELECT vehicle_grouping.vehicle_id,vehicle.vehicle_name FROM vehicle_grouping,vehicle WHERE vehicle_grouping.account_id='$account_id' AND vehicle_grouping.status=1 AND vehicle.vehicle_id=vehicle_grouping.vehicle_id and vehicle.status=1";
				$result_query = mysql_query($queryVehicle,$DbConnection);
				while($rowV=mysql_fetch_object($result_query))
				{
					$vehicle_list[]=$rowV->vehicle_name;
				}
				
				$vehicle_list1 = array_unique($vehicle_list);	
				$final_vehicle_list=array();
				$all_vehicles = "";	
				foreach($vehicle_list1 as $vl){
					$final_vehicle_list[]=$vl;
					//echo "vl=".$vl."<br>"; 
					$all_vehicles.= trim($vl).",";
					//echo "all_vehicles=".$all_vehicles."<br>";
				}	
				$all_vehicles = substr($all_vehicles, 0, -1);	
				return $all_vehicles;
				//return $queryVehicle;
		}
	}
}

/*function setInvoiceRawMilk($lorry_no,$Vehicle_no,$group_id,$transporter_id,$trans_mobile,$trans_email,$driver_name,$driver_mobile,$qty_kg,$snf_per,$snf_kg,$fat_per,$fat_kg,$manual_milk_age,$dispatch_time,$target_time,$plant,$chilling_plant,$tanker_type,$db_serial_no)*/
function setInvoiceRawMilk($lorry_no,$Vehicle_no,$group_id,$user_id,$trans_mobile,$trans_email,$driver_name,$driver_mobile,$qty_kg,$snf_per,$snf_kg,$fat_per,$fat_kg,$manual_milk_age,$dispatch_time,$target_time,$plant,$chilling_plant,$tanker_type,$db_serial_no)
{
	
	/*$QueryUpdate="UPDATE `invoice_mdrm` SET lorry_no= '$lorry_no',vehicle_no='$vehicle_no',transporter_account_id='$transporter_id',docket_no='$dock_no',email='$trans_email',mobile='$trans_mobile',qty_kg='$qty_kg',fat_percentage='$fat_per',snf_percentage='$snf_per',fat_kg = '$fat_kg',snf_kg='$snf_kg',milk_age='$manual_milk_age',dispatch_time='$dispatch_time',target_time='$target_time',validity_time='$validity_time',plant='$plant',chilling_plant='$chilling_plant',tanker_type='$tanker_type',driver_name='$driver_name',driver_mobile='$driver_mobile',parent_account_id='$transporter_id',edit_id='$transporter_id',edit_date='$date',invoice_status=1,status=1 WHERE sno='$db_serial_no' "; 
	return $QueryUpdate;*/
	/*return $lorry_no." /".$Vehicle_no." /".$group_id." /".$transporter_id." /".$trans_mobile." /".$trans_email." /".$driver_name." /".$driver_mobile." /".$qty_kg." /".$snf_per." /".$snf_kg." /".$fat_per." /".$fat_kg." /".$manual_milk_age." /".$dispatch_time." /".$target_time." /".$plant." /".$chilling_plant." /".$tanker_type." /".$db_serial_no;*/
	
	global $DbConnection;
	$post_group_id = $group_id;		
	$post_user_id = $user_id;
	$query_account_id="SELECT account.account_id,account.user_type,account.group_id FROM account,account_detail WHERE (account.group_id='$post_group_id' OR account.group_id IS NULL) AND account.account_id=account_detail.account_id AND account.user_id='$post_user_id'  AND (account.status=1 OR account.status=4)";	
	$result_account_id = mysql_query($query_account_id, $DbConnection);
	$count = mysql_num_rows($result_account_id);	
	if($count > 0)
	{
		$row =mysql_fetch_object($result_account_id);
		$transporter_id=$row->account_id;
	
	}
	
	
	date_default_timezone_set("Asia/Kolkata");
	//echo date_default_timezone_get();
	$date= date("Y-m-d H:i:s");
	$dock_no="";
	
	$msg="fail";
	//getting docket number on the basis of vehicle no and assuming it is registered with gps
	
	$query1="SELECT MAX(sno) as max_count from invoice_mdrm where parent_account_id=$transporter_id";
	//echo"query=".$query1."<br>";	
	$result1=mysql_query($query1,$DbConnection);
	$max_no = mysql_fetch_object($result1);
	$max_no1=$max_no->max_count;
	$max_no1 = preg_replace("/[^0-9]/", '', $max_no1);          
	//echo "max_no1=".$max_no1."<br>";
	
	$query_userid = "SELECT user_id FROM account WHERE account_id='$transporter_id' AND status=1";
	$result_userid = mysql_query($query_userid,$DbConnection);
	$row_userid = mysql_fetch_object($result_userid);
	$userid = $row_userid->user_id;

	if($max_no1=="")
	{
		$dock_no=$userid."/0001";
	}
	else
	{ 
		$max_no1=intval($max_no1)+1; 
		//echo "mas_no1=".$max_no1."<br>";                    
		if($max_no1<=9)
		{$dock_str="000";}
		else if($max_no1>=10 && $max_no1<=99)
		{$dock_str="00";}
		else if($max_no1>=100 && $max_no1<=999)
		{$dock_str="0";}
		else
		{$dock_str="";}
		
		$dock_no =$userid."/".$dock_str.$max_no1;            
	}
	
	$validity_time = date('Y-m-d H:i:s', strtotime($target_time .' +4 day'));
	//insertion
	if(strtotime($target_time)< strtotime($dispatch_time))
        {
            $msg="Fail";
            exit();
        }
	if($db_serial_no!="" || $db_serial_no!=null) // for pending invoice
	{
		if($Vehicle_no=="" || $Vehicle_no==null) //simply update the pending invoice
		{
			$Query="UPDATE `invoice_mdrm` SET lorry_no= '$lorry_no',transporter_account_id='$transporter_id',email='$trans_email',mobile='$trans_mobile',qty_kg='$qty_kg',fat_percentage='$fat_per',snf_percentage='$snf_per',
						fat_kg = '$fat_kg',snf_kg='$snf_kg',milk_age='$manual_milk_age',dispatch_time='$dispatch_time',target_time='$target_time',validity_time='$validity_time',plant='$plant',chilling_plant='$chilling_plant',tanker_type='$tanker_type',driver_name='$driver_name',driver_mobile='$driver_mobile',edit_id='$transporter_id', edit_date='$date',invoice_status=5,status=1 WHERE sno='$db_serial_no' "; 
		}
		else //finalize pending invoice
		{
			$Query="UPDATE `invoice_mdrm` SET lorry_no= '$lorry_no',vehicle_no='$Vehicle_no',transporter_account_id='$transporter_id',docket_no='$dock_no',email='$trans_email',mobile='$trans_mobile',qty_kg='$qty_kg',fat_percentage='$fat_per',snf_percentage='$snf_per',fat_kg = '$fat_kg',snf_kg='$snf_kg',milk_age='$manual_milk_age',dispatch_time='$dispatch_time',target_time='$target_time',validity_time='$validity_time',plant='$plant',chilling_plant='$chilling_plant',tanker_type='$tanker_type',driver_name='$driver_name',driver_mobile='$driver_mobile',parent_account_id='$transporter_id',edit_id='$transporter_id',edit_date='$date',invoice_status=1,status=1 WHERE sno='$db_serial_no' "; 
		}
	}
        //till further notice
	/*else //newly created
	{
		
		if($Vehicle_no=="" || $Vehicle_no==null) //simply add new pending invoice
		{
			$Query="INSERT INTO `invoice_mdrm`(lorry_no,transporter_account_id,email,mobile,qty_kg,fat_percentage,snf_percentage,fat_kg,snf_kg,milk_age,dispatch_time,target_time,validity_time,plant,chilling_plant,tanker_type,driver_name,driver_mobile,parent_account_id,create_id,create_date,invoice_status,status) VALUES('".
						"$lorry_no','$transporter_id','$trans_email','$trans_mobile',".
						"'$qty_kg','$fat_per','$snf_per','$fat_kg','$snf_kg','$manual_milk_age','$dispatch_time','$target_time','$validity_time','$plant','$chilling_plant','$tanker_type','$driver_name','$driver_mobile','$transporter_id','$transporter_id','$date',5,1)"; 
		}
		else //finalize newly created invoice
		{
			$Query="INSERT INTO `invoice_mdrm`(lorry_no,vehicle_no,transporter_account_id,email,mobile,qty_kg,fat_percentage,snf_percentage,fat_kg,snf_kg,milk_age,docket_no,dispatch_time,target_time,validity_time,plant,chilling_plant,tanker_type,driver_name,driver_mobile,parent_account_id,create_id,create_date,invoice_status,status) VALUES('".
						"$lorry_no','$Vehicle_no','$transporter_id','$trans_email','$trans_mobile',".
						"'$qty_kg','$fat_per','$snf_per','$fat_kg','$snf_kg','$manual_milk_age','$dock_no','$dispatch_time','$target_time','$validity_time','$plant','$chilling_plant','$tanker_type','$driver_name','$driver_mobile','$transporter_id','$transporter_id','$date',1,1)"; 
						

		}
	}*/
	
	
	//return $Query."DB=".$db_serial_no;
	
	
	$Result=mysql_query($Query,$DbConnection);
	
	if($Result )
	{
		$msg="Success";
		if($dock_no!="")
		{
			$QueryVehicle="SELECT vehicle_id from vehicle where vehicle_name='$Vehicle_no' and status=1 ";
			$result_Vehicle = mysql_query($QueryVehicle,$DbConnection);
			$row_Vehicle = mysql_fetch_object($result_Vehicle);
			$vehicle_id = $row_Vehicle->vehicle_id;
			$QueryVehicleIMEI="SELECT device_imei_no from vehicle_assignment where vehicle_id='$Vehicle_no' and status=1 ";
			$result_VehicleIMEI = mysql_query($QueryVehicleIMEI,$DbConnection);
			$row_VehicleIMEI = mysql_fetch_object($result_VehicleIMEI);
			$vehicle_IMEI = $row_VehicleIMEI->device_imei_no;

			$QueryDocket="INSERT INTO consignment_info (account_id,device_imei_no,vehicle_name,consignee_name,start_date,end_date,docket_no,create_id,create_date,status) VALUES('$transporter_id','$vehicle_IMEI','$Vehicle_no','$customer','$dispatch_time','$validity_time','$dock_no','$transporter_id','$date',1)";
			$ResultDOCKET=mysql_query($QueryDocket,$DbConnection);
			//SEND EMAIL
			$query_userid = "SELECT user_id FROM account WHERE account_id='$transporter_id' AND status=1";
			$result_userid = mysql_query($query_userid,$DbConnection);
			if($row = mysql_fetch_object($result_userid))
			{
				$user_id = $row->user_id;
			}
			
			
				$timestamp = strtotime(date('Y-m-d H:i:s'));
				$filepath = "/var/www/html/vts/beta/src/php/invoice_raw_milk/data/raw_milk_invoice_data_".$timestamp.".csv";
				$fileobj = fopen($filepath,"w");
				fwrite($fileobj,$csv_string);
				fclose($fileobj);
			
		}
	}
	
	return $msg;
	
	//return $Query;
	
}



function assign_to_till_root($account_id_local1)
{	
		global $DbConnection;	
		global $parent_account_ids;	 
		global $acc_size;			
			
		$query = "SELECT account_admin_id FROM account_detail WHERE account_id='$account_id_local1'";	
		//echo $query;
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_row($result);
		$admin_id=$row[0];
			
		$query1 = "SELECT account_id FROM account_detail WHERE admin_id='$admin_id'";
		//echo "<br>".$query;	
		$result=mysql_query($query1,$DbConnection);
		$row1=mysql_fetch_row($result);
		$function_account_id=$row1[0];
		//echo "account_id=".$function_account_id.'<br>';
		//echo "IN=".	$function_account_id;
		$queryType="SELECT user_type from account WHERE account_id='$function_account_id'";
		//echo "<br>".$queryType;
		$resultType=mysql_query($queryType,$DbConnection);
		$rowType=mysql_fetch_row($resultType);
		$function_account_type=$rowType[0];
		//echo "userType=".$function_account_type."<br>";
		
		if($function_account_type!='raw_milk')
		{
			$parent_account_ids[]=$function_account_id;
			//print_r($parent_account_ids);
			return $parent_account_ids;
		}		
		
		else
		{			
			$final_account_id=assign_to_till_root($function_account_id);
			//query to check non transporter from account table  usertype='raw_milk'
			////////
			$parent_account_ids[]=$function_account_id;
			//echo"acc1=".$function_account_id."<br>"."acc1=".$function_account_id."<br>"."acc1=".$parent_account_ids."<br>";				
			return $parent_account_ids;					
		}
		//return $account_id_local1;
	}

//function getReportInvoice($account_id,$create_date_from,$create_date_to,$orderby)
function getReportInvoice($user_id,$group_id,$create_date_from,$create_date_to,$orderby)
{
	//$account_id to check usertype
	//orderby : 0 =>cancel, 1=>open, 2=>closed , 3=>All, 5=> pending , 6=>openAll
	global $DbConnection;
	$post_group_id = $group_id;		
	$post_user_id = $user_id;
	$Report_string=array();
	
	$query_account_id="SELECT account.account_id,account.user_type,account.group_id FROM account,account_detail WHERE (account.group_id='$post_group_id' OR account.group_id IS NULL) AND account.account_id=account_detail.account_id AND account.user_id='$post_user_id'  AND (account.status=1 OR account.status=4)";	
	$result_account_id = mysql_query($query_account_id, $DbConnection);
	$count = mysql_num_rows($result_account_id);	
	if($count > 0)
	{
		$row =mysql_fetch_object($result_account_id);
		$account_id=$row->account_id;
	
		
		if($orderby==3) //for all
		{
			$query = "SELECT invoice_mdrm.*,account.user_id as uid,account_detail.name as nme FROM invoice_mdrm,account,account_detail USE INDEX(ad_account_id) WHERE account.account_id=account_detail.account_id AND invoice_mdrm.parent_account_id=account_detail.account_id AND invoice_mdrm.status=1 AND account.status=1 AND invoice_mdrm.create_date BETWEEN '$create_date_from' AND '$create_date_to' AND invoice_mdrm.transporter_account_id='$account_id' AND invoice_mdrm.invoice_status!=5";
		}
		elseif($orderby==6) //allopen
		{
			$query = "SELECT invoice_mdrm.*,account.user_id as uid,account_detail.name as nme FROM invoice_mdrm,account,account_detail USE INDEX(ad_account_id) WHERE account.account_id=account_detail.account_id AND invoice_mdrm.parent_account_id=account_detail.account_id AND invoice_mdrm.status=1 AND account.status=1 			 AND invoice_mdrm.transporter_account_id='$account_id' AND invoice_mdrm.invoice_status=1";
		}
		else
		{
			$query = "SELECT invoice_mdrm.*,account.user_id as uid,account_detail.name as nme FROM invoice_mdrm,account,account_detail USE INDEX(ad_account_id) WHERE account.account_id=account_detail.account_id AND invoice_mdrm.parent_account_id=account_detail.account_id AND invoice_mdrm.status=1 AND account.status=1 AND invoice_mdrm.create_date BETWEEN '$create_date_from' AND '$create_date_to' AND invoice_mdrm.transporter_account_id='$account_id' AND invoice_mdrm.invoice_status='$orderby'";
		}
		//return $query;
		$result = mysql_query($query,$DbConnection);	
		$i=0;
		
		while($row_select = mysql_fetch_object($result))
		{
			//echo $i;
			$user_id = $row_select->uid;
			$user_name = $row_select->nme;				
			$sno = $row_select->sno;			
			$status = $row_select->invoice_status;
			if($status==1)
			{
				$status = "Open";			
			}
			else if($status==2)
			{
				$status = "Closed";
			}
			else if($status==0)
			{
				$status = "Cancelled CR:$row_select->create_date";			
			}
			else if($status==5)
			{
				$query_transporterid = "SELECT account.user_id as uid,account_detail.name as nme FROM account ,account_detail USE INDEX(ad_account_id) WHERE account.account_id='$row_select->transporter_account_id' AND account.status=1 AND account_detail.account_id=account.account_id ";
				$result_transporterid = mysql_query($query_transporterid, $DbConnection);
				$row_transporterid = mysql_fetch_object($result_transporterid);
				$user_Tid = $row_transporterid->uid;
				$status = "Pending to $user_Tid";
			}
				$plant_acceptance_time=$row_select->plant_acceptance_time;
				//$closetime=$row_select->close_time;
				$closetime=$row_select->system_time;
				$closetype1=$row_select->close_type;
				$closetype="";
				
				//milk age calculation //
				$cdatetime1 = strtotime(date('00:00:00'));
				$cdatetime2 = strtotime(date('H:i:s'));
				$current_difftime = $cdatetime2 - $cdatetime1;
				$current_time_hrs=$current_difftime/3600;			
				$current_date1=strtotime(date('Y-m-d H:i:s'));
				
				//case 1 when not close:
					$manual_milk_hr=0;
					if( $row_select->invoice_status == 1)
					{
						$milk_hr1=$row_select->milk_age; //in hrs
						
						$disp_time1=$row_select->dispatch_time; //in date time
						$disp_time2 = strtotime($disp_time1);
						//$disp_time_hrs= ($current_date1-$disp_time2)/3600;
						if($current_date1<$disp_time2)
						{
							$manual_milk_hr="N/A(current < dispatch)";
						}
						else
						{
							$manual_milk_hr= $milk_hr1 + ($current_date1-$disp_time2)/3600;
							//$manual_milk_hr= ($milk_hr1 + $current_time_hrs)-$disp_time_hrs;
							$manual_milk_hr=round($manual_milk_hr,2);
						}
						
					}
					//case 2 when close:
					if( $row_select->invoice_status == 2 && $row_select->unload_estimated_time!="" && $row_select->unload_accept_time!="")
					{
						$milk_hr1=$row_select->milk_age; //in hrs
						
						$disp_time1=$row_select->dispatch_time; // in date time
						$disp_time2 = strtotime($disp_time1);
						//$disp_time_hrs= ($current_date1-$disp_time2)/3600;
						
						$unload_estimate_min=$row_select->unload_estimated_time; //in minutes
						$unload_estimate_hrs=$unload_estimate_min/60;
						
						$unload_accept_datetime1=$row_select->unload_accept_time; //in date time
						$unload_accept_datetime2 = strtotime($unload_accept_datetime1);
						//$unload_accept_hrs= ($current_date1-$unload_accept_datetime2)/3600;
						
						if($unload_accept_datetime2<$disp_time2)
						{
						
							$manual_milk_hr="N/A(accept < dispatch)";
						}
						else
						{
							$manual_milk_hr= ($milk_hr1 + ($unload_accept_datetime2  - $disp_time2)/3600) - ($unload_estimate_hrs);
							//$manual_milk_hr= (($milk_hr1 + $unload_accept_hrs ) - ($disp_time_hrs)) - ($unload_estimate_hrs);
							//echo $milk_hr1 ."+". $unload_accept_hrs ." - ".$disp_time_hrs." - ".$unload_estimate_hrs."<br>";
							$manual_milk_hr=round($manual_milk_hr,2);
						}
						
					}
					// pending 
					if( $row_select->invoice_status == 5)
					{
						$milk_hr1=$row_select->milk_age; //in hrs
						
						$disp_time1=$row_select->dispatch_time; //in date time
						$disp_time2 = strtotime($disp_time1);
						//$disp_time_hrs= ($current_date1-$disp_time2)/3600;
						if($current_date1<$disp_time2)
						{
							$manual_milk_hr="N/A(current < dispatch)";
						}
						else
						{
							$manual_milk_hr= $milk_hr1 + ($current_date1-$disp_time2)/3600;
							//$manual_milk_hr= ($milk_hr1 + $current_time_hrs)-$disp_time_hrs;
							$manual_milk_hr=round($manual_milk_hr,2);
						}
						
					}
					
					$invoice_createdate=$row_select->create_date;
					$lorry_no=$row_select->lorry_no;
					$vehicle_no=$row_select->vehicle_no;
					$tanker_type=$row_select->tanker_type;
					$docket_no=$row_select->docket_no;
					$email=$row_select->email;
					$mobile=$row_select->mobile;
					$qty_kg=$row_select->qty_kg;
					$fat_percentage=$row_select->fat_percentage;
					$snf_percentage=$row_select->snf_percentage;
					$fat_kg=$row_select->fat_kg;
					$snf_kg=$row_select->snf_kg;
					//$manual_milk_hr;
					$dispatch_time=$row_select->dispatch_time;
					$target_time=$row_select->target_time;
					$driver_name=$row_select->driver_name;
					$driver_mobile=$row_select->driver_mobile;
					//$user_name."(".$user_id.")"."
					$validity_time_tmp = $row_select->validity_time;
					$plant=$row_select->plant;
					$chilling_plant=$row_select->chilling_plant;
					$unload_estimated_time=$row_select->unload_estimated_time;
					$unload_accept_time=$row_select->unload_accept_time;
					
					
					
					$Report_string[]=array("id"=>$sno,"create_date"=>$invoice_createdate,"lorry_no"=>$lorry_no,"Vehicle_no"=>$vehicle_no,"tanker_type"=>$tanker_type,"docket_no"=>$docket_no,"trans_email"=>$email,"trans_mobile"=>$mobile,"driver_name"=>$driver_name,"driver_mobile"=>$driver_name,'qty_kg'=>$qty_kg,'fat_per'=>$fat_percentage,'fat_kg'=>$fat_kg,'snf_per'=>$snf_percentage,'snf_kg'=>$snf_kg,'manual_milk_age'=>$manual_milk_hr,'dispatch_time'=>$dispatch_time,'target_time'=>$target_time,'user_ut_open'=>$user_name."(".$user_id.")" ,'status'=>$status,'plant'=>$plant,'chilling_plant'=>$chilling_plant,'unload_estimated_time'=>$unload_estimated_time,'unload_accept_time'=>$unload_accept_time );
				
			}
			
	}
	return json_encode($Report_string);
}
	


$server = new soap_server();
$server->register("getLogin");
$server->register("getPendingInvoice");
$server->register("getVehicle");
$server->register("getPlant");
$server->register("getChillingPlant");
$server->register("getReportInvoice");
$server->register("setInvoiceRawMilk");
$server->service($HTTP_RAW_POST_DATA);



?>  