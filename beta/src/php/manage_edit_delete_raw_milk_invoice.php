<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');  
	//echo "edit_div_invoice1##"; 
	$account_id_local=$_POST['common_id'];
	//echo "<input type='hidden' id='account_id_local' value=".$account_id_local1.">";	
	//echo "account_id_local=".$account_id_local;
?>
	<CENTER><h4>CLOSE INVOICE</h4></CENTER>
	<fieldset class="manage_fieldset_invoice">
		<legend><strong>Select Invoice To Close</strong></legend>
		
		<?php
			
			$user_id = getUserID($local_account_id,1,$DbC);
			$data = getDetailAllInvoice($account_id_local,$DbConnection);			
		?>		
		<div style="width:100%;height:430px;overflow:auto;font-size:11px;">
		<form name="invoice_form">
		<table style="border:thin;" class="manage_interface" cellspacing="2" cellpadding="2" rules="all">
			<?php
			echo "<tr style='background-color:grey;'>
				<td>SNO</td><td>VehicleNo</td><td>CustomerCode</td><td>CustomerName</td><td>InvoiceNo</td><td>InvoiceAmount</td>
				<td>EmailID</td><td>Remark</td><td>DriverName</td><td>Driver PhoneNo</td><td>Target Time</td><td>Account</td><td>Tracking No</td><td>Status</td><td>Check</td>
			</tr>";
			$sno_local =1;
			$color=1;
			foreach($data as $dt)
			{
				$final_plant_list=$dt['final_plant_list'];
				
				$status="";
				$sno = $dt['sno'];
				$vehicle_no = $dt['vehicle_no'];
				$customer_code = $dt['customer_code'];
				$customer_name = $dt['customer_name'];
				$invoice_no = $dt['invoice_no'];
				$invoice_amount =$dt['invoice_amount'];
				$email_id = $dt['email_id'];
				$remarks = $dt['remarks'];
				$driver_name = $dt['driver_name'];
				$driver_phone_no = $dt['driver_phone_no'];
				$target_time = $dt['target_time'];	
				$tracking_no = $dt['tracking_no'];
				$status = $dt['status'];				
				
				if($status==1)
				$status = "Open";
				
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
				
				echo "<td>".$sno_local."</td>
				<td>".$vehicle_no."</td>
				<td>".$customer_code."</td>
				<td>".$customer_name."</td>
				<td>".$invoice_no."</td>
				<td>".$invoice_amount."</td>
				<td>".$email_id."</td>
				<td>".$remarks."</td>
				<td>".$driver_name."</td>
				<td>".$driver_phone_no."</td>
				<td>".$target_time."</td>
				<td>".$user_id."</td>
				<td><font color=red>".$tracking_no."</font></td>
				<td><font color=green>".$status."</font></td>";
				echo '<td><input type="checkbox" name="invoice_serial[]" value="'.$sno.'"</td>
				</tr>';
				$sno_local++;
			}
			?>
	</table>
	</form>
	</div>
	
	<table align="center">
		<tr>
			<td colspan="3">
				<input type="button" value="Close Invoice" id="enter_button" onclick="javascript:return action_manage_invoice('edit')"/>&nbsp;				
			</td>
		</tr>
	</table>
		
	<div id="available_message" align="center"></div> 
	<div id="blackout"> </div>
	<div id="divpopup">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#c3c3c3">							
			<tr>
				<td class="manage_interfarce" align="right"><a href="#" onclick="javascript:return close_landmark_div()" class="hs3">Close</a></td> 													
			</tr> 
			<tr>
				<td colspan="5" valign="top" align="justify"><div id="landmark_map" style="width:707px; height:397px; position: relative; background-color: rgb(229, 227, 223);" class="ukseries_div_map"></div></td>
			</tr>							
		</table>
	</div>
