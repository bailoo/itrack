<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$root=$_SESSION['root'];
	//include_once('tree_hierarchy_information.php');
	//include_once('manage_radio_group_account.php');
		
	echo "edit##"; 
	include_once("util_account_detail.php");
	$account_id_local=$_POST['common_id'];	
	//echo "<input type='hidden' id='account_id_local' value=".$account_id_local1.">";	
	//echo "account_id_local=".$account_id_local;
	$startdate = str_replace('/','-',$startdate);
	$enddate = str_replace('/','-',$enddate);
	$all_data=0;
	if($order=="3")
	{
		$all_data = 1;
	}
	
	//===Getting product_type 
	$final_product_type_list=getProductNameProductType($parent_admin_id,$DbConnection);
		

	
	
	function assign_to_till_root_parent($account_id_local1)
	{	
		global $DbConnection;	
		global $parent_account_ids;	 
		global $acc_size;		
		
		$admin_id=getAccountAdminId($account_id_local1,$DbConnection);			
	
		$function_account_id=getAccountIdByAdminId($admin_id,$DbConnection);		
		
		$function_account_type=getUserTypeAccount($function_account_id,$DbConnection);
		//echo "userType=".$function_account_type."<br>";
		
		if($function_account_type!='hindalco_invoice')
		{
			//$parent_account_ids[]=$function_account_id;
			//print_r($parent_account_ids);
			return $parent_account_ids;
		}		
		
		else
		{			
			$final_account_id=assign_to_till_root_parent($function_account_id);
			//query to check non transporter from account table  usertype='raw_milk'
			////////
			$parent_account_ids[]=$function_account_id;
				
			return $parent_account_ids;					
		}
		//return $account_id_local1;
	}
?>
	<fieldset class="manage_fieldset_invoice">
		<legend><strong>HINDALCO INVOICE REPORT</strong></legend>
		
		<?php
			if($user_type=="hindalco_invoice")
			{
				$Child_account="";
				include_once('tree_hierarchy_information.php');
				radio_group_account_hierarchy_transporter_child($root);
				//echo $Child_account;
				$child_account = $account_id.",".$Child_account;
				$child_account = substr($child_account,0,-1);
				
				if($all_data)
				{
					$query = "SELECT * FROM invoice_hindalco WHERE status=1 AND dispatch_time BETWEEN '$startdate' AND '$enddate' AND create_id IN($child_account)";
					//echo $query1;
				}
				else
				{
					$query = "SELECT * FROM invoice_hindalco WHERE status=1 AND invoice_status='$order' AND dispatch_time BETWEEN '$startdate' AND '$enddate' AND create_id IN($child_account)";
					//echo $query2;
				}			
			}
			else //admin
			{
				if($all_data)
				{
					$query = "SELECT * FROM invoice_hindalco WHERE status=1 AND dispatch_time BETWEEN '$startdate' AND '$enddate'";
				}
				else
				{
					$query = "SELECT * FROM invoice_hindalco WHERE status=1 AND invoice_status='$order' AND dispatch_time BETWEEN '$startdate' AND '$enddate'";
				}
			}
			//echo $query;
			$result = mysql_query($query,$DbConnection);			
		?>		
		<div style="height:430px;overflow:auto;font-size:11px;" align="center">
		<form name="invoice_form" method = "post" target="_blank">
		<table style="border:thin;" class="manage_interface" cellspacing="2" cellpadding="2" rules="all" align="center" width=100%>
			<?php
			if($user_type=="hindalco_invoice"){
				echo "<tr style='background-color:silver;' rules='all'>
			<td>SNO</td><td>LORRY NO</td><td>VEHICLE NO</td><td>DOCKET NO</td><td>EMAIL</td><td>TRANSPORTER MOBILE</td><td>QTY</td>
				<td>CUSTOMER</td><td>DISPATCH TIME</td><td>TARGET TIME</td><td>DRIVER NAME</td><td>DRIVER MOBILE</td><td>USERID</td><td>STATUS</td><td><font color=red>CANCEL</font></td><td><font color=blue>PRODUCT TYPE</font></td><td>ACCEPT.TIME</td>
			</tr>";
			}
			else{ //admin
			echo "<tr style='background-color:silver;' rules='all'>
			<td>SNO</td><td>LORRY NO</td><td>VEHICLE NO</td><td>DOCKET NO</td><td>EMAIL</td><td>TRANSPORTER MOBILE</td><td>QTY</td>
				<td>CUSTOMER</td><td>DISPATCH TIME</td><td>TARGET TIME</td><td>DRIVER NAME</td><td>DRIVER MOBILE</td><td>USERID</td><td>STATUS</td><td>CLOSE</td><td><font color=red>CANCEL</font></td><td><font color=blue>PRODUCT TYPE</font></td><td>APPROVED</td><td>ACCEPT.TIME</td>
			</tr>";
			}
			
			
			$sno_local =1;
			$color=1;
			
			$csv_string = "";
			$title= "HINDALCO INVOICE REPORT";
			$csv_string = $csv_string.$title."\n";
			if( $user_type=="hindalco_invoice"){
				$csv_string = $csv_string."SNO,LORRY NO,VEHICLE NO,DOCKET NO,EMAIL,TRANSPORTER MOBILE,QTY,CUSTOMER,DISPATCH TIME,TARGET TIME,DRIVER NAME,DRIVER MOBILE,USERID,STATUS,PRODUCT TYPE,ACCEPT.TIME\n";
			}
			else{
				$csv_string = $csv_string."SNO,LORRY NO,VEHICLE NO,DOCKET NO,EMAIL,TRANSPORTER MOBILE,QTY,CUSTOMER,DISPATCH TIME,TARGET TIME,DRIVER NAME,DRIVER MOBILE,USERID,STATUS,PRODUCT TYPE,ACCEPT.TIME,CLOSE TIME\n";
			
			}
			
			echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
			$i=0;
			$user_id_a="";
			while($row_select = mysql_fetch_object($result))
			{
				$user_id_a="";
				if($user_type=="hindalco_invoice")
				{
					//$query_userid = "SELECT user_id FROM account WHERE account_id='$row_select->create_id' AND status=1";
					$hindalco_parent_transport_account=array();$parent_account_ids=array();
					$parent_account_ids[]=$row_select->parent_account_id;
					$hindalco_parent_transport_account = assign_to_till_root_parent($row_select->parent_account_id);
					//print_r($hindalco_parent_transport_account);
					$top_cnt=sizeof($hindalco_parent_transport_account);
					$top_cnt1=$top_cnt-1;
					if($top_cnt>1){
						$query_userid = "SELECT user_id FROM account WHERE account_id='$hindalco_parent_transport_account[1]' AND status=1";
					}
					else{
						$query_userid = "SELECT user_id FROM account WHERE account_id='$hindalco_parent_transport_account[0]' AND status=1";					
					}
					
					if($hindalco_parent_transport_account[$top_cnt1]!=$row_select->create_id){
						$query_userid_a = "SELECT user_id FROM account WHERE account_id='$row_select->parent_account_id' AND status=1";
						$result_userid_a = mysql_query($query_userid_a, $DbConnection);
						if($row_userid_a = mysql_fetch_object($result_userid_a))
						{
							$user_id_a= $row_userid_a->user_id;
							$user_id_a="(".$user_id_a.")";
						}
					}
					
				}
				else
				{
					$hindalco_parent_transport_account=array();$parent_account_ids=array();
					$parent_account_ids[]=$row_select->parent_account_id;
					$hindalco_parent_transport_account = assign_to_till_root_parent($row_select->parent_account_id);
					//print_r($hindalco_parent_transport_account);
					$top_cnt=sizeof($hindalco_parent_transport_account);
					$top_cnt1=$top_cnt-1;
					if($top_cnt>1){
						$query_userid = "SELECT user_id FROM account WHERE account_id='$hindalco_parent_transport_account[1]' AND status=1";
					}
					else{
						$query_userid = "SELECT user_id FROM account WHERE account_id='$hindalco_parent_transport_account[0]' AND status=1";					
					}
					
					if($hindalco_parent_transport_account[$top_cnt1]!=$row_select->create_id){
						$query_userid_a = "SELECT user_id FROM account WHERE account_id='$row_select->parent_account_id' AND status=1";
						$result_userid_a = mysql_query($query_userid_a, $DbConnection);
						if($row_userid_a = mysql_fetch_object($result_userid_a))
						{
							$user_id_a= $row_userid_a->user_id;
							$user_id_a="(".$user_id_a.")";
						}
					}
				}
				
				$result_userid = mysql_query($query_userid, $DbConnection);
				if($row_userid = mysql_fetch_object($result_userid))
				{
					$user_id = $row_userid->user_id;
				}
				$user_id=$user_id.$user_id_a;
				$sno = $row_select->sno;				
				$status="";
				$status = $row_select->invoice_status;
				//echo "<br>status=".$status;
				if($status==1)
				{
					$status = "Open";
					$status_download = "Open";
				}
				else if($status==2)
				{
					$status = "<font color=brown>Closed</font>";
					$status_download = "Closed";
				}
				else if($status==0)
				{
					$status = "<font color=red>Cancelled</font>";
					$status_download = "Cancelled";
				}
				
				if($color==1)
				{
					echo "<tr>";
					$color=2;
				}
				else if($color==2)
				{
					echo "<tr style='background-color:#C2DFFF;'>";
					$color=1;					
				}
				
				$acceptance_time=$row_select->acceptance_time;
				$closetime=$row_select->close_time;
				echo "<td>".$sno_local."</td>
				<td>".$row_select->lorry_no."</td>
				<td>".$row_select->vehicle_no."</td>
				<td><font color=red>".$row_select->docket_no."</font></td>
				<td>".$row_select->email."</td>
				<td>".$row_select->mobile."</td>
				<td>".$row_select->qty_kg."</td>
				<td>".$row_select->customer."</td>
				
				<td>".$row_select->dispatch_time."</td>
				<td>".$row_select->target_time."</td>
				<td>".$row_select->driver_name."</td>
				<td>".$row_select->driver_mobile."</td>";					
				echo "<td>".$user_id."</td>			
				
				";
					//1 => OPEN
					//2 => CLOSED
					//0 => CANCELLED
					$close_invoice_flag = 0;
					$validity_time_tmp = $row_select->validity_time;
					if((strtotime($date) > strtotime($validity_time_tmp) && ($row_select->invoice_status !=2) && ($row_select->invoice_status !=0)))
					{
						$close_invoice_flag = 1;
						echo '<td><font color=brown>Closed</font></td>";'; //to show as it is closed when expiry
						$status_download = "Closed";
					}
					else{
						echo"<td><font color=green>".$status."</font></td>";
					}					
					
					if($close_invoice_flag == 1)
					{	
						
						$query_close = "UPDATE invoice_hindalco SET invoice_status=2 ,system_time='$date',close_time='$date' WHERE sno='$sno'";
						$result_close = mysql_query($query_close);
						if( $user_type!="hindalco_invoice"){
							echo '<td align=right>'.$date.'</td>';	
						}
						
						
							echo '<td align=right><input type="checkbox" name="invoice_serial_cancel[]" value="'.$sno.'"></td>';
						
						
						echo '<input type="hidden" name="invoice_serial_close[]" value="'.$sno.'"/>
						<input type="hidden" id="closetime_'.$sno.'" name="closetime'.$sno.'" >';

						//$query_close = "UPDATE invoice_mdrm SET invoice_status=2 WHERE sno='$sno'";
						//$result = mysql_query($query_close);
					}					
					else if($row_select->invoice_status == 1)
					{
						if( $user_type!="hindalco_invoice"){
							echo '<td align=right><input type="checkbox" name="invoice_serial_close[]" value="'.$sno.'" id="close_chk_'.$sno.'" onclick=setclosetime('.$sno.') > <input type="hidden" id="closetime_'.$sno.'" name="closetime'.$sno.'" ></td>
							
							';	
						}
						else{
							echo'<input type="hidden" name="invoice_serial_close[]" value="'.$sno.'" id="close_chk_'.$sno.'"  >';
						}
						
						
						echo '<td align=right><input type="checkbox" name="invoice_serial_cancel[]" value="'.$sno.'"></td>';
												
					}
					else{
						if($row_select->invoice_status == 2)
						{
							if( $user_type!="hindalco_invoice"){
								echo '<td align=right>'.$closetime.'</td>';	
							}
							
								echo '<td align=right><input type="checkbox" name="invoice_serial_cancel[]" value="'.$sno.'"></td>';
							
							
							echo '<input type="hidden" name="invoice_serial_close[]" value="'.$sno.'"/>
								<input type="hidden" id="closetime_'.$sno.'" name="closetime'.$sno.'" >
							';
						}
						if($row_select->invoice_status == 0)
						{
							if( $user_type!="hindalco_invoice" ){
								echo '<td align=right>'.$closetime.'</td>';
							}
							echo '<td align=right>&nbsp;</td>';	
							echo '<input type="hidden" name="invoice_serial_close[]" value="'.$sno.'"/>
								<input type="hidden" id="closetime_'.$sno.'" name="closetime'.$sno.'" >
							';
							echo '<input type="hidden" name="invoice_serial_cancel[]" value="'.$sno.'"/>';						

						}
					}										
					
					echo '<td><input type="hidden" id="product_type_'.$sno.'" name="product_type_'.$sno.'" value="'.$row_select->product_type.'">';
					
					//echo $row_select->invoice_status."<br>";
					
						if($row_select->invoice_status == 1)
						{
							
							echo '<div id="label_'.$sno.'"><font color=green>'.$row_select->product_type.'</font></div>';
						}
						else
						{
							if($acceptance_time==""){
								echo '<div><font color=red>'.$row_select->product_type.'</font></div>';
							}
							else{
								echo '<div><font color=green>'.$row_select->product_type.'</font></div>';
							}
							
						}						
					
									
					echo '</td>';
					if( $user_type!="hindalco_invoice"  ){//admin
						if($acceptance_time=="" && $row_select->invoice_status == 1 && $close_invoice_flag == 0 ){
							echo '<td align=right><input type="checkbox" name="approvalcheck_'.$sno.'" id="approvalcheck_'.$sno.'" onclick=setapproval('.$sno.') ></td>';
						}
						else{
							echo '<td align=right></td>';
						}						
					}
					//if( $user_type!="hindalco_invoice"){
						echo '<td><input type="hidden" id="pre_plant_'.$sno.'" name="pre_plant_'.$sno.'" value='.$row_select->product_type.' ><input type="hidden" id="acceptancetime_'.$sno.'" name="acceptancetime_'.$sno.'" value='.$acceptance_time.' >
						'.$acceptance_time.'</td>';
					//}
					echo '<input type="hidden" id="approval_'.$sno.'" name="approval_'.$sno.'" >';
				echo '</tr>';
				
				
				//######## PDF /CSV
				echo"<input TYPE=\"hidden\" VALUE=\"$sno_local\" NAME=\"temp[$i][SNo]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$row_select->lorry_no\" NAME=\"temp[$i][LORRY NO]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$row_select->vehicle_no\" NAME=\"temp[$i][VEHICLE NO]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$row_select->docket_no\" NAME=\"temp[$i][DOCKET NO]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$row_select->email\" NAME=\"temp[$i][EMAIL]\">";
				
				echo"<input TYPE=\"hidden\" VALUE=\"$row_select->mobile\" NAME=\"temp[$i][TRANSPORTER MOBILE]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$row_select->qty_kg\" NAME=\"temp[$i][QTY]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$row_select->customer\" NAME=\"temp[$i][CUSTOMER]\">";
				
				echo"<input TYPE=\"hidden\" VALUE=\"$row_select->dispatch_time\" NAME=\"temp[$i][DISPATCH TIME]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$row_select->target_time\" NAME=\"temp[$i][TARGET TIME]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$row_select->driver_name\" NAME=\"temp[$i][DRIVER NAME]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$row_select->driver_mobile\" NAME=\"temp[$i][DRIVER MOBILE]\">";
				
				echo"<input TYPE=\"hidden\" VALUE=\"$user_id\" NAME=\"temp[$i][USERID]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$status_download\" NAME=\"temp[$i][STATUS]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$row_select->product_type\" NAME=\"temp[$i][PRODUCT TYPE]\">";
				
				
				if( $user_type!="hindalco_invoice"){
					echo"<input TYPE=\"hidden\" VALUE=\"$closetime\" NAME=\"temp[$i][CLOSE TIME]\">";
				}
				
				if( $user_type=="hindalco_invoice"){
					$csv_string = $csv_string.$sno_local.','.$row_select->lorry_no.','.$row_select->vehicle_no.','.$row_select->docket_no.','.$row_select->email.','.$row_select->mobile.','.$row_select->qty_kg.','.$row_select->customer.','.$row_select->dispatch_time.','.$row_select->target_time.','.$row_select->driver_name.','.$row_select->driver_mobile.','.$user_id.','.$status_download.','.$row_select->product_type.','.$acceptance_time."\n";
				}
				
				else{
					$csv_string = $csv_string.$sno_local.','.$row_select->lorry_no.','.$row_select->vehicle_no.','.$row_select->docket_no.','.$row_select->email.','.$row_select->mobile.','.$row_select->qty_kg.','.$row_select->customer.','.$row_select->dispatch_time.','.$row_select->target_time.','.$row_select->driver_name.','.$row_select->driver_mobile.','.$user_id.','.$status_download.','.$row_select->product_type.','.$acceptance_time.','.$closetime."\n";
				}
				
				//###########
				$i++;
				$sno_local++;
			}
			echo '<input type="hidden" value="'.$sno.'" id="counter"/>';
			echo '<input type="hidden" id="tmp_serial"/>';
			
	echo '		
	</table>
	
	<center>
	<input TYPE="hidden" VALUE="Hindalco_Invoice" NAME="csv_type">
	<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">			
	<input type="button" onclick="javascript:manage_csv(\'src/php/report_getpdf_type3.php?size='.$sno_local.'\');" value="Get PDF" class="noprint">
	&nbsp;
	<input type="button" onclick="javascript:manage_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">
	&nbsp;
	</center>
	
	</form>';
	
	?>
	</div>
	
	<table align="center">
		<tr>
			<td colspan="3">
				<?php		
					
				echo '<input type="button" value="Update/Close/Cancel" id="enter_button" onclick="javascript:return action_manage_hindalco_invoice_update(\'edit\')"/>&nbsp;';
						
					
				
				?>
			</td>
		</tr>
	</table>
		
	
	
	