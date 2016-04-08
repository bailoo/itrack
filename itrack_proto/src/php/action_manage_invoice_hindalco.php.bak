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
	
	if($action_type =="add")                     ///////// ADD
	{
		$ioffset_lrno = explode(",",substr($_POST['offset_lrno'],0,-1));
		$offset_vehno = explode(",",substr($_POST['offset_vehno'],0,-1));
		$offset_email = explode(",",substr($_POST['offset_email'],0,-1));
		$offset_mobile = explode(",",substr($_POST['offset_mobile'],0,-1));
		$offset_qty = explode(",",substr($_POST['offset_qty'],0,-1));
		$offset_customer = explode(",",substr($_POST['offset_customer'],0,-1));		
		$offset_disp_time = explode(",",substr($_POST['offset_disp_time'],0,-1));
		$offset_target_time = explode(",",substr($_POST['offset_target_time'],0,-1));		
		$offset_product_type = explode(",",substr($_POST['offset_product_type'],0,-1));
		$offset_driver_name = explode(",",substr($_POST['offset_driver'],0,-1));
		$offset_driver_mobile = explode(",",substr($_POST['offset_drivermobile'],0,-1));		
					
		$csv_string ="";
		$csv_string = "USERID,DOCKET NO,LORRY NO,VEHICLE NO,EMAIL,MOBILE,QTY,CUSTOMER,DISPATCH TIME,TARGET TIME,PRODUCT TYPE,DRIVER NAME,DRIVER MOBILE\n";
			
		$all_vehicles = str_replace('%20',' ',$_POST['vehicle_list']);
		$vehicle_input1 = explode(",",$all_vehicles);	
		
		$all_othervehicles = str_replace('%20',' ',$_POST['vehicle_list_other_hidden']);
		$vehicle_input2 = explode(",",$all_othervehicles);
		//print_r($vehicle_input2);
		//echo "Invoice Created Successfully..<br>";
		//echo'<center><a href="javascript:show_option(\'manage\',\'add_raw_milk_usertype\');" class="menuitem">&nbsp;<b>Back</b></a></center><br>';
		//flush(); @ob_flush();  ## make sure that all output is sent in real-time
		for($i=0;$i<sizeof($ioffset_lrno);$i++)
		{
			$flag_self_docket=0;
			$flag_other_docket=0;
			$lorry_no = $ioffset_lrno[$i];
			//$vehicle_no = $offset_vehno[$i];
			$vehicle_no_hash = explode("#",$offset_vehno[$i]);
			if($vehicle_no_hash[0]!=""){
				$vehicle_no=$vehicle_no_hash[0]; //self vehicle
				$flag_self_docket=1;
			}
			else{
				$vehicle_no=$vehicle_no_hash[1]; //other vehicle
				$flag_other_docket=1;	
			}
			
			//getting email of other transporter
			$acc_email="";
			
			$acc_email_id=getAccountIdVehicleGrouping($vehicle_no,$DbConnection);
			$tmp_acc_email=getEmailIDAccountDetail($acc_email_id,$DbConnection);
			if($tmp_acc_email!=""){
				$acc_email.=" ".$tmp_acc_email;
			}
			
			
			
			
			
			
			$email=str_replace('/',' ', $offset_email[$i]);
			if($acc_email!=""){
				$email.=$acc_email;
			}
			//echo $email;

			$mobile = $offset_mobile[$i];
			$qty_kg = $offset_qty[$i];
			$customer = $offset_customer[$i];
			
			$dispatch_time = $offset_disp_time[$i];
			$target_time =$offset_target_time[$i];
			
			$product_type = $offset_product_type[$i];
			$driver_name = $offset_driver_name[$i];
			$driver_mobile =$offset_driver_mobile[$i];
			
			$dock_no = "";
			
			if($flag_self_docket==1){
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
			}
			if($flag_other_docket==1){
				//echo "Docketflag=".$docket_flag;
				$vehicle_no_ex=explode("/",$vehicle_no);
				foreach($vehicle_no_ex as $vne){
					if (in_array($vne, $vehicle_input2))
					{
						$docket_flag="1";
					}
					else{
						$docket_flag="0";
						break;
					}
				}
			}
			//echo "DK=".	$docket_flag;		
			if($docket_flag=="1")
			{
				
				$max_no1=getMaxCountInvoiceHindalco($account_id,$DbConnection);
				$max_no1 = preg_replace("/[^0-9]/", '', $max_no1);
				$userid = getUserID($account_id,1,$DbConnection);

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
			$validity_time = date('Y-m-d H:i:s', strtotime($target_time .' +2 day'));
  
			
			$Result=insertInvoiceHindalco($lorry_no,$vehicle_no,$email,$mobile,$qty_kg,$customer,$dock_no,$dispatch_time,$target_time,$validity_time,$product_type,$product_type,$driver_name,$driver_mobile,$account_id,$date,$DbConnection);
		
			if($Result)
			{
				//Insert Docket number to track
				if($dock_no!=""){
					
					$vehicle_id = getVehicleId($$vehicle_no,1,$DbConnection);
					$vehicle_IMEI = getVADeviceImeiNo($vehicle_id,1,$DbConnection);					
					$ResultDOCKET=insertConsignmetInfoDoct($account_id,$vehicle_IMEI,$vehicle_no,$customer,$dispatch_time,$validity_time,$dock_no,$account_id,$date,$DbConnection);
					
				}
					
					$message_web = "<center>
								<br>
									<FONT color=\"green\">
										 <strong>Invoice data created Successfully </strong>
								   </font>
								</center>";
									
					//SEND EMAIL
					
					$user_id = getUserID($accountId,$status,$DbConnection);					
					$csv_string = $csv_string.$user_id.','.$dock_no.','.$lorry_no.','.$vehicle_no.','.$email.','.$mobile.','.$qty_kg.','.$customer.','.$dispatch_time.','.$target_time.','.$product_type.','.$driver_name.','.$driver_mobile."\n";										
					//################################//
			}   
			else
			{
				$message_web = "<center><br>
								<FONT color=\"red\">
									<strong>Sorry! Unable to process request.</strong></font>
							 </center>";
			   
			}
		} //INVOICE DATA LOOP CLOSED
		
		//######## WRITE TO FILE
		$timestamp = strtotime(date('Y-m-d H:i:s'));
		$filepath = "/var/www/html/vts/beta/src/php/invoice_raw_milk/data_hindalco/hindalco_invoice_data_".$timestamp.".csv";
		$fileobj = fopen($filepath,"w");
		fwrite($fileobj,$csv_string);
		fclose($fileobj);		
		
		echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
				<tr>
					<td colspan="3" align="center"><b>'.$message_web.'</b></td>    
				</tr>
			</table><br>'; 
		echo'<center><a href="javascript:show_option(\'manage\',\'add_hindalco_invoice_usertype\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
		
	}
	else if($action_type =="edit")                     ///////// ADD
	{
		$update_serials = explode(',',substr($_POST['update_serials'],0,-1));
		$delete_serials = explode(',',substr($_POST['delete_serials'],0,-1));
		$close_serials = explode(',',substr($_POST['close_serials'],0,-1));
		$product_type_serials = explode(',',substr($_POST['product_type_serials'],0,-1));
		$approval_serials=explode(',',substr($_POST['approval_serials'],0,-1));//approved
		$closetime_serials=explode(',',substr($_POST['closetime_serials'],0,-1));//approved
		//print_r($plant_serials);
		//echo $update_serials;
		$cnt=0;
		foreach($update_serials as $sno)
		{
			if($approval_serials[$cnt]=="approved")
			{
				$result_update = updateInvoiceHindalcoAccept($product_type_serials[$cnt],$account_id,$date,$sno,$DbConnection);
			}
			else
			{
				$result_update = updateInvoiceHindalco($product_type_serials[$cnt],$account_id,$date,$sno,$DbConnection);
			}
			
			//echo "<br>".$query_update;
			
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
			
			$result_update =updateInvoiceHindalcoClose($account_id,$date,$closetime,$sno,$DbConnection);	
			$cnt++;
		}
		foreach($delete_serials as $sno)
		{
			$result_update =deleteInvoiceHindalco($account_id,$date,$sno,$DbConnection);			
		}
		

		echo "<center>
		<br>
			<FONT color=\"green\">
				 <strong>Invoice updated Successfully </strong>
		   </font>
		</center>";
		if($user_type=="hindalco_invoice" )
		{		
			echo'<br><center><a href="javascript:show_option(\'manage\',\'edit_hindalco_invoice_prev\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
		}
		else
		{
			echo'<br><center><a href="javascript:show_option(\'manage\',\'edit_hindalco_invoice_admin_prev\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
		}
	}
	
?>
        
