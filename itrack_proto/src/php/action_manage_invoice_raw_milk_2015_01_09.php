<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//require 'PHPMailer_5.2.4/class.phpmailer.php';
	include_once("util_account_detail.php");
	
	$root = $_SESSION['root'];
	$DEBUG=0; 
	//$account_id_local=explode(",",$account_id_local);
    //$vehicle_imei_name=explode("@",$vehicle_imei_name);	
	$invoice_data = $_POST['invoice_data'];
	
	if($action_type =="add")       ///////// ADD
	{
		$offset_transporter = explode(",",substr($_POST['offset_transporter'],0,-1));
		$offset_vehno = explode(",",substr($_POST['offset_vehno'],0,-1));
		
		$ioffset_lrno = explode(",",substr($_POST['offset_lrno'],0,-1));		
		$offset_email = explode(",",substr($_POST['offset_email'],0,-1));
		$offset_mobile = explode(",",substr($_POST['offset_mobile'],0,-1));
		$offset_qty = explode(",",substr($_POST['offset_qty'],0,-1));
		$offset_fat_per = explode(",",substr($_POST['offset_fat_per'],0,-1));
		$offset_snf_per = explode(",",substr($_POST['offset_snf_per'],0,-1));
		$offset_fat_kg = explode(",",substr($_POST['offset_fat_kg'],0,-1));
		$offset_snf_kg = explode(",",substr($_POST['offset_snf_kg'],0,-1));
		$offset_milk_age = explode(",",substr($_POST['offset_milk_age'],0,-1));
		$offset_disp_time = explode(",",substr($_POST['offset_disp_time'],0,-1));
		$offset_target_time = explode(",",substr($_POST['offset_target_time'],0,-1));
		$offset_docketflag = explode(",",substr($_POST['offset_docketflag'],0,-1));
		$offset_plant = explode(",",substr($_POST['offset_plant'],0,-1));
		$offset_chillplant = explode(",",substr($_POST['offset_chillplant'],0,-1));
		$offset_driver_name = explode(",",substr($_POST['offset_driver'],0,-1));
		$offset_driver_mobile = explode(",",substr($_POST['offset_drivermobile'],0,-1));		
		$offset_tankertype = explode(",",substr($_POST['offset_tankertype'],0,-1));
		$offset_sno_id=array();
		if($_POST['offset_sno_id']!="")
		{
			$offset_sno_id = explode(",",substr($_POST['offset_sno_id'],0,-1));
		}
		$csv_write_flag=0;
		$csv_string ="";
		$csv_string = "USERID,DOCKET NO,LORRY NO,VEHICLE NO,EMAIL,MOBILE,QTYKG,FAT(%),SNF(%),FAT(KG),SNF(KG),MILK AGE(Hrs),DISPATCH TIME,TARGET TIME,PLANT,DRIVER NAME,DRIVER MOBILE,CHILLING PLANT,TANKER TYPE\n";
			
		$all_vehicles = str_replace('%20',' ',$_POST['vehicle_list']);
		$vehicle_input1 = explode(",",$all_vehicles);	
		//echo "Invoice Created Successfully..<br>";
		//echo'<center><a href="javascript:show_option(\'manage\',\'add_raw_milk_usertype\');" class="menuitem">&nbsp;<b>Back</b></a></center><br>';
		//flush(); @ob_flush();  ## make sure that all output is sent in real-time
		for($i=0;$i<sizeof($ioffset_lrno);$i++)
		{
			$lorry_no = $ioffset_lrno[$i];
			$vehicle_no = $offset_vehno[$i];
			$transporter = $offset_transporter[$i];
			$email = $offset_email[$i];
			$mobile = $offset_mobile[$i];
			$qty_kg = $offset_qty[$i];
			$fat_per = $offset_fat_per[$i];
			$snf_per = $offset_snf_per[$i];
			$fat_kg = $offset_fat_kg[$i];
			$snf_kg = $offset_snf_kg[$i];
			$milk_age = $offset_milk_age[$i];
			$docket_flag = $offset_docketflag[$i];
			$dispatch_time = $offset_disp_time[$i];
			$target_time =$offset_target_time[$i];
			
			$plant = $offset_plant[$i];
			$chillplant = $offset_chillplant[$i];
			$driver_name = $offset_driver_name[$i];
			$driver_mobile =$offset_driver_mobile[$i];
			$tanker_type1 =$offset_tankertype[$i];
			if($tanker_type1=="1"){
				$tanker_type="Production";
			}
			else if($tanker_type1=="2"){
				$tanker_type="Conversion";
			}
			
			////finalaize and unfinalize invoice
		//	if($vehicle_no=="" || is_null($vehicle_no)) //unfinalize invoice
		    //if(! isset($vehicle_no))
	        if($vehicle_no=="" || $vehicle_no==null)
		{				
				$validity_time = date('Y-m-d H:i:s', strtotime($target_time .' +4 day'));
				//if(sizeof($offset_sno_id)>0)
				if($offset_sno_id[$i]!="")
				{
					$sno_id1 =$offset_sno_id[$i];
					//update query
					/*$QueryUpdate="UPDATE `invoice_mdrm` SET lorry_no= '$lorry_no',transporter_account_id='$transporter',email='$email',mobile='$mobile',qty_kg='$qty_kg',fat_percentage='$fat_per',snf_percentage='$snf_per',
					fat_kg = '$fat_kg',snf_kg='$snf_kg',milk_age='$milk_age',dispatch_time='$dispatch_time',target_time='$target_time',validity_time='$validity_time',plant='$plant',chilling_plant='$chillplant',
					tanker_type='$tanker_type',driver_name='$driver_name',driver_mobile='$driver_mobile',parent_account_id='$account_id',create_id='$account_id',create_date='$date',invoice_status=5,status=1 WHERE sno='$sno_id1' ";  */
					
					$QueryUpdate="UPDATE `invoice_mdrm` SET lorry_no= '$lorry_no',transporter_account_id='$transporter',email='$email',mobile='$mobile',qty_kg='$qty_kg',fat_percentage='$fat_per',snf_percentage='$snf_per',
					fat_kg = '$fat_kg',snf_kg='$snf_kg',milk_age='$milk_age',dispatch_time='$dispatch_time',target_time='$target_time',validity_time='$validity_time',plant='$plant',chilling_plant='$chillplant',
					tanker_type='$tanker_type',driver_name='$driver_name',driver_mobile='$driver_mobile',edit_id='$account_id',parent_account_id='$account_id',edit_date='$date',invoice_status=5,status=1 WHERE sno='$sno_id1' ";  
					
					
					//echo "Query=".$Query."<br>";
					$ResultUpdate=mysql_query($QueryUpdate,$DbConnection);
					$message_web_transfer = "<center>
									<br>
										<FONT color=\"green\">
											 <strong>Invoice data Successfully Transfer to Transporter </strong>
									   </font>
									</center>";
				}
				else
				{
					$Query="INSERT INTO `invoice_mdrm`(lorry_no,transporter_account_id,email,mobile,qty_kg,fat_percentage,snf_percentage,fat_kg,snf_kg,milk_age,dispatch_time,target_time,validity_time,plant,chilling_plant,tanker_type,driver_name,driver_mobile,parent_account_id,create_id,create_date,invoice_status,status) VALUES('".
						"$lorry_no','$transporter','$email','$mobile',".
						"'$qty_kg','$fat_per','$snf_per','$fat_kg','$snf_kg','$milk_age','$dispatch_time','$target_time','$validity_time','$plant','$chillplant','$tanker_type','$driver_name','$driver_mobile','$account_id','$account_id','$date',5,1)";  
					//echo "Query=".$Query."<br>";
					if($DEBUG) print_query($Query);
					$Result=mysql_query($Query,$DbConnection);
				
					if($Result)
					{			
										
						$message_web_transfer = "<center>
									<br>
										<FONT color=\"green\">
											 <strong>Invoice data Successfully Transfer to Transporter </strong>
									   </font>
									</center>";
						
					}   
					else
					{
						$message_web_transfer = "<center><br>
										<FONT color=\"red\">
											<strong>Sorry! Unable to Transfer The Invoice to Transporter.</strong></font>
									 </center>";
					   
					}
				}
				
				//---end of unfinalize---//
			}
			else //finalize invoice
			{
				$dock_no = "";			
				//echo "Docketflag=".$docket_flag;
				$vehicle_no_ex=explode("/",$vehicle_no);
				foreach($vehicle_no_ex as $vne){
					if (in_array($vne, $vehicle_input1))
					{
						$docket_flag="1";
					}
					else{
						$docket_flag="0";
						break;
					}
				}
							
				if($docket_flag=="1")
				{
					$query1="SELECT MAX(sno) as max_count from invoice_mdrm where parent_account_id=$account_id";
					//echo"query=".$query1."<br>";
					if($DEBUG) print_query($query1);
					$result1=mysql_query($query1,$DbConnection);
					$max_no = mysql_fetch_object($result1);
					$max_no1=$max_no->max_count;
					$max_no1 = preg_replace("/[^0-9]/", '', $max_no1);          
					//echo "max_no1=".$max_no1."<br>";
					
					$query_userid = "SELECT user_id FROM account WHERE account_id='$account_id' AND status=1";
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
				}
				$validity_time = date('Y-m-d H:i:s', strtotime($target_time .' +4 day'));
				//if(sizeof($offset_sno_id)>0)
				if($offset_sno_id[$i]!="" && $offset_vehno[$i]!="" )
				{
					$sno_id1 =$offset_sno_id[$i];
					/*$QueryUpdate="UPDATE `invoice_mdrm` SET lorry_no= '$lorry_no',vehicle_no='$vehicle_no',transporter_account_id='$transporter',docket_no='$dock_no',email='$email',mobile='$mobile',qty_kg='$qty_kg',fat_percentage='$fat_per',snf_percentage='$snf_per',
					fat_kg = '$fat_kg',snf_kg='$snf_kg',milk_age='$milk_age',dispatch_time='$dispatch_time',target_time='$target_time',validity_time='$validity_time',plant='$plant',chilling_plant='$chillplant',
					tanker_type='$tanker_type',driver_name='$driver_name',driver_mobile='$driver_mobile',parent_account_id='$account_id',create_id='$account_id',create_date='$date',invoice_status=1,status=1 WHERE sno='$sno_id1' ";  
					*/
					
					
					$QueryUpdate="UPDATE `invoice_mdrm` SET lorry_no= '$lorry_no',vehicle_no='$vehicle_no',transporter_account_id='$transporter',docket_no='$dock_no',email='$email',mobile='$mobile',qty_kg='$qty_kg',fat_percentage='$fat_per',snf_percentage='$snf_per',
					fat_kg = '$fat_kg',snf_kg='$snf_kg',milk_age='$milk_age',dispatch_time='$dispatch_time',target_time='$target_time',validity_time='$validity_time',plant='$plant',chilling_plant='$chillplant',
					tanker_type='$tanker_type',driver_name='$driver_name',driver_mobile='$driver_mobile',parent_account_id='$account_id',edit_id='$account_id',edit_date='$date',invoice_status=1,status=1 WHERE sno='$sno_id1' ";  
					
					//echo "Query=".$Query."<br>";
					$ResultUpdate=mysql_query($QueryUpdate,$DbConnection);
					/*if($Result )
					{*/			
						//Insert Docket number to track
						if($dock_no!=""){
							$QueryVehicle="SELECT vehicle_id from vehicle where vehicle_name='$vehicle_no' and status=1 ";
							$result_Vehicle = mysql_query($QueryVehicle,$DbConnection);
							$row_Vehicle = mysql_fetch_object($result_Vehicle);
							$vehicle_id = $row_Vehicle->vehicle_id;
							$QueryVehicleIMEI="SELECT device_imei_no from vehicle_assignment where vehicle_id='$vehicle_id' and status=1 ";
							$result_VehicleIMEI = mysql_query($QueryVehicleIMEI,$DbConnection);
							$row_VehicleIMEI = mysql_fetch_object($result_VehicleIMEI);
							$vehicle_IMEI = $row_VehicleIMEI->device_imei_no;

							$QueryDocket="INSERT INTO consignment_info (account_id,device_imei_no,vehicle_name,consignee_name,start_date,end_date,docket_no,create_id,create_date,status) VALUES('$account_id','$vehicle_IMEI','$vehicle_no','$customer','$dispatch_time','$validity_time','$dock_no','$account_id','$date',1)";
							$ResultDOCKET=mysql_query($QueryDocket,$DbConnection);
						}
					
						$message_web = "<center>
									<br>
										<FONT color=\"green\">
											 <strong>Invoice data created Successfully </strong>
									   </font>
									</center>";
										
						//SEND EMAIL
						$query_userid = "SELECT user_id FROM account WHERE account_id='$account_id' AND status=1";
						$result_userid = mysql_query($query_userid,$DbConnection);
						if($row = mysql_fetch_object($result_userid))
						{
							$user_id = $row->user_id;
						}
						
						$csv_string = $csv_string.$user_id.','.$dock_no.','.$lorry_no.','.$vehicle_no.','.$email.','.$mobile.','.$qty_kg.','.$fat_per.','.$snf_per.','.$fat_kg.','.$snf_kg.','.$milk_age.','.$dispatch_time.','.$target_time.','.$plant.','.$driver_name.','.$driver_mobile.','.$chillplant.','.$tanker_type."\n";										
						$csv_write_flag=1;
						//################################//
					/*}   
					else
					{
						$message_web = "<center><br>
										<FONT color=\"red\">
											<strong>Sorry! Unable to process request.</strong></font>
									 </center>";
					   
					}*/
				}
				else
				{
					$Query="INSERT INTO `invoice_mdrm`(lorry_no,vehicle_no,transporter_account_id,email,mobile,qty_kg,fat_percentage,snf_percentage,fat_kg,snf_kg,milk_age,docket_no,dispatch_time,target_time,validity_time,plant,chilling_plant,tanker_type,driver_name,driver_mobile,parent_account_id,create_id,create_date,invoice_status,status) VALUES('".
						"$lorry_no','$vehicle_no','$transporter','$email','$mobile',".
						"'$qty_kg','$fat_per','$snf_per','$fat_kg','$snf_kg','$milk_age','$dock_no','$dispatch_time','$target_time','$validity_time','$plant','$chillplant','$tanker_type','$driver_name','$driver_mobile','$account_id','$account_id','$date',1,1)";  
					//echo "Query=".$Query."<br>";
					if($DEBUG) print_query($Query);
					$Result=mysql_query($Query,$DbConnection);
					if($Result )
					{			
						//Insert Docket number to track
						if($dock_no!=""){
							$QueryVehicle="SELECT vehicle_id from vehicle where vehicle_name='$vehicle_no' and status=1 ";
							$result_Vehicle = mysql_query($QueryVehicle,$DbConnection);
							$row_Vehicle = mysql_fetch_object($result_Vehicle);
							$vehicle_id = $row_Vehicle->vehicle_id;
							$QueryVehicleIMEI="SELECT device_imei_no from vehicle_assignment where vehicle_id='$vehicle_id' and status=1 ";
							$result_VehicleIMEI = mysql_query($QueryVehicleIMEI,$DbConnection);
							$row_VehicleIMEI = mysql_fetch_object($result_VehicleIMEI);
							$vehicle_IMEI = $row_VehicleIMEI->device_imei_no;

							$QueryDocket="INSERT INTO consignment_info (account_id,device_imei_no,vehicle_name,consignee_name,start_date,end_date,docket_no,create_id,create_date,status) VALUES('$account_id','$vehicle_IMEI','$vehicle_no','$customer','$dispatch_time','$validity_time','$dock_no','$account_id','$date',1)";
							$ResultDOCKET=mysql_query($QueryDocket,$DbConnection);
						}
					
						$message_web = "<center>
									<br>
										<FONT color=\"green\">
											 <strong>Invoice data created Successfully </strong>
									   </font>
									</center>";
										
						//SEND EMAIL
						$query_userid = "SELECT user_id FROM account WHERE account_id='$account_id' AND status=1";
						$result_userid = mysql_query($query_userid,$DbConnection);
						if($row = mysql_fetch_object($result_userid))
						{
							$user_id = $row->user_id;
						}
						
						$csv_string = $csv_string.$user_id.','.$dock_no.','.$lorry_no.','.$vehicle_no.','.$email.','.$mobile.','.$qty_kg.','.$fat_per.','.$snf_per.','.$fat_kg.','.$snf_kg.','.$milk_age.','.$dispatch_time.','.$target_time.','.$plant.','.$driver_name.','.$driver_mobile.','.$chillplant.','.$tanker_type."\n";										
						$csv_write_flag=1;
						//################################//
					}   
					else
					{
						$message_web = "<center><br>
										<FONT color=\"red\">
											<strong>Sorry! Unable to process request.</strong></font>
									 </center>";
					   
					}
				}
				
			
				
			
				///---end of finalize---
			}
			
			
			
		} //INVOICE DATA LOOP CLOSED
		
		//######## WRITE TO FILE
		if($csv_write_flag==1)
		{
			$timestamp = strtotime(date('Y-m-d H:i:s'));
			$filepath = "/var/www/html/vts/beta/src/php/invoice_raw_milk/data/raw_milk_invoice_data_".$timestamp.".csv";
			$fileobj = fopen($filepath,"w");
			fwrite($fileobj,$csv_string);
			fclose($fileobj);		
		}
		
		echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
				<tr>
					<td colspan="3" align="center"><b>'.$message_web.$message_web_transfer.'</b></td>    
				</tr>
			</table><br>'; 
		if($user_type=="raw_milk" || $user_type=="plant_raw_milk"){
			//echo'<center><a href="javascript:show_option(\'manage\',\'add_raw_milk_usertype\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
			
		}
		else{
			//echo'<center><a href="manage_invoice_milk_add_upload.htm" class="menuitem">&nbsp;<b>Back</b></a></center>';
		}
		
	}
	else if($action_type =="edit")                     ///////// ADD
	{
		$update_serials = explode(',',substr($_POST['update_serials'],0,-1));
		$delete_serials = explode(',',substr($_POST['delete_serials'],0,-1));
		$close_serials = explode(',',substr($_POST['close_serials'],0,-1));
		$plant_serials = explode(',',substr($_POST['plant_serials'],0,-1));
		$approval_serials=explode(',',substr($_POST['approval_serials'],0,-1));//approved
		$closetime_serials=explode(',',substr($_POST['closetime_serials'],0,-1));//approved
		$unload_estimatetime_serials=explode(',',substr($_POST['unload_estimatetime_serials'],0,-1));//upload estimate
		$unload_accepttime_serials=explode(',',substr($_POST['unload_accepttime_serials'],0,-1));//upload accept
		//print_r($plant_serials);
		//echo $update_serials;
		$cnt=0;
		foreach($update_serials as $sno)
		{
			
			if($unload_accepttime_serials[$cnt]=="" || $unload_accepttime_serials[$cnt]==null){
				if($approval_serials[$cnt]=="approved"){
				$query_update = "UPDATE invoice_mdrm SET plant='$plant_serials[$cnt]',edit_id='$account_id',edit_date='$date' ,plant_acceptance_time='$date',unload_estimated_time='$unload_estimatetime_serials[$cnt]' WHERE sno='$sno'";
				}
				else{
					$query_update = "UPDATE invoice_mdrm SET plant='$plant_serials[$cnt]',edit_id='$account_id',edit_date='$date' ,unload_estimated_time='$unload_estimatetime_serials[$cnt]' WHERE sno='$sno'";
				}
			}
			else
			{
				if($approval_serials[$cnt]=="approved"){
				$query_update = "UPDATE invoice_mdrm SET plant='$plant_serials[$cnt]',edit_id='$account_id',edit_date='$date' ,plant_acceptance_time='$date',unload_estimated_time='$unload_estimatetime_serials[$cnt]',unload_accept_time='$unload_accepttime_serials[$cnt]' WHERE sno='$sno'";
				}
				else{
					$query_update = "UPDATE invoice_mdrm SET plant='$plant_serials[$cnt]',edit_id='$account_id',edit_date='$date' ,unload_estimated_time='$unload_estimatetime_serials[$cnt]',unload_accept_time='$unload_accepttime_serials[$cnt]',unload_accept_time='$unload_accepttime_serials[$cnt]' WHERE sno='$sno'";
				}
			}
			
			
			//echo "<br>".$query_update;
			$result_update = mysql_query($query_update,$DbConnection);	
			$cnt++;			
		}
		$cnt=0;
		foreach($close_serials as $sno)
		{
			//$docket_no = $inv['DOCKET_NO'];
			$closetime="";
			if($closetime_serials[$cnt]==""){
				$closetime=$date;
			}
			else{
				$closetime=$closetime_serials[$cnt];
			}
			$query_update = "UPDATE invoice_mdrm SET invoice_status=2,edit_id='$account_id',edit_date='$date',system_time='$date',close_time='$closetime',close_type='m'  WHERE sno='$sno'";
			/*if($unload_accepttime_serials[$cnt]=="" || $unload_accepttime_serials[$cnt]==null)
			{
				$query_update = "UPDATE invoice_mdrm SET invoice_status=2,edit_id='$account_id',edit_date='$date',system_time='$date',close_time='$closetime' ,unload_estimated_time='$unload_estimatetime_serials[$cnt]' WHERE sno='$sno'";
			}
			else
			{
				$query_update = "UPDATE invoice_mdrm SET invoice_status=2,edit_id='$account_id',edit_date='$date',system_time='$date',close_time='$closetime' ,unload_estimated_time='$unload_estimatetime_serials[$cnt]',unload_accept_time='$unload_accepttime_serials[$cnt]' WHERE sno='$sno'";
			}
			
			echo "C=". $query_update;*/
			$result_update = mysql_query($query_update,$DbConnection);	
			$cnt++;
		}
		$cnt=0;
		foreach($delete_serials as $sno)
		{
			//$docket_no = $inv['DOCKET_NO'];
			$query_update = "UPDATE invoice_mdrm SET invoice_status=0,edit_id='$account_id',edit_date='$date' ,system_time='$date' WHERE sno='$sno'";
			/*if($unload_accepttime_serials[$cnt]=="" || $unload_accepttime_serials[$cnt]==null)
			{
				$query_update = "UPDATE invoice_mdrm SET invoice_status=0,edit_id='$account_id',edit_date='$date' ,system_time='$date' ,unload_estimated_time='$unload_estimatetime_serials[$cnt]' WHERE sno='$sno'";
			}
			else
			{
				$query_update = "UPDATE invoice_mdrm SET invoice_status=0,edit_id='$account_id',edit_date='$date' ,system_time='$date' ,unload_estimated_time='$unload_estimatetime_serials[$cnt]',unload_accept_time='$unload_accepttime_serials[$cnt]' WHERE sno='$sno'";
			}
			
			echo "D=".$query_update;*/
			$result_update = mysql_query($query_update,$DbConnection);	
			$cnt++;			
		}
		
		
		

		echo "<center>
		<br>
			<FONT color=\"green\">
				 <strong>Invoice updated Successfully </strong>
		   </font>
		</center>";
		if($user_type=="raw_milk" || $user_type=="plant_raw_milk")
		{		
			echo'<br><center><a href="javascript:show_option(\'manage\',\'edit_raw_milk_prev\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
		}
		else
		{
			echo'<br><center><a href="javascript:show_option(\'manage\',\'edit_raw_milk_admin_prev\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
		}
	}
	/*else if($action_type =="delete")                     ///////// ADD
	{
		$delete_serials = explode(',',substr($_POST['delete_serials'],0,-1));
		//echo $delete_serials;
		foreach($delete_serials as $sno)
		{
			//$docket_no = $inv['DOCKET_NO'];
			$query_update = "UPDATE invoice_mdrm SET invoice_status=0,edit_id='$account_id',edit_date='$date' WHERE sno='$sno'";
			//echo $query_update;
			$result_update = mysql_query($query_update,$DbConnection);			
		}
		echo "<center>
		<br>
			<FONT color=\"red\">
				 <strong>Raw milk Invoice(s) closed Successfully</strong>
		   </font>
		</center><br>";
		echo'<center><a href="javascript:show_option(\'manage\',\'edit_delete_raw_milk\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
	}*/	
	
  //include_once("manage_action_message.php");
?>
        
