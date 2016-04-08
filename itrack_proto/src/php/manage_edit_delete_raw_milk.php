<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');  
	//echo "edit_div_invoice1##"; 
	$account_id_local=$_POST['common_id'];
	//echo "<input type='hidden' id='account_id_local' value=".$account_id_local1.">";	
	//echo "account_id_local=".$account_id_local;
?>
	<CENTER><h4>RAW MILK INVOICE REPORT</h4></CENTER>
	<fieldset class="manage_fieldset_invoice">
		<legend><strong></strong></legend>
		
		<?php
			
			$user_id = getUserID($local_account,1,$DbC);
			
		
		
			$data = getDetailInvoiceMdrm($account_id,$DbConnection);			
		?>		
		<div style="height:430px;overflow:auto;font-size:11px;" align="center">
		<form name="invoice_form">
		<table style="border:thin;" class="manage_interface" cellspacing="2" cellpadding="2" rules="all" align="center" width=100%>
			<?php
			echo "<tr style='background-color:grey;'>
			<td>SNO</td><td>LORRY NO</td><td>VEHICLE NO</td><td>DOCKET NO</td><td>EMAIL</td><td>MOBILE</td><td>QTY(KG)</td>
				<td>FAT(%)</td><td>SNF(%)</td><td>FAT(KG)</td><td>SNF(KG)</td><td>DISPATCH TIME</td><td>TARGET TIME</td><td>USERID</td><td>STATUS</td><td>CANCEL</td>
			</tr>";
			$sno_local =1;
			$color=1;
			
			/*$csv_string = "";
			$title= "RAW MILK INVOICE REPORT";
			$csv_string = $csv_string.$title."\n";
			$csv_string = $csv_string."LORRY NO,VEHICLE NO ,DOCKET NO,EMAIL,MOBILE,QTY,FAT(%),SNF(%),FAT(KG),SNF(KG), DISPATCH TIME,TARGET TIME,USERID ,STATUS\n";*/
			
			foreach($data as $dt)
			{
				$status="";
				$sno = $dt['sno'];
				$status = $dt['status'];
				$lorry_no=$dt['lorry_no'];
				$vehicle_no=$dt['vehicle_no'];
				$docket_no=$dt['docket_no'];
				$email=$dt['email'];
				$mobile=$dt['mobile'];
				$qty_kg=$dt['qty_kg'];
				$fat_percentage=$dt['fat_percentage'];
				$snf_percentage=$dt['snf_percentage'];
				$fat_kg=$dt['fat_kg'];
				$snf_kg=$dt['snf_kg'];
				$dispatch_time=$dt['dispatch_time'];
				$target_time=$dt['target_time'];						
				
				
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
				<td>".$lorry_no."</td>
				<td>".$vehicle_no."</td>
				<td><font color=red>".$docket_no."</font></td>
				<td>".$email."</td>
				<td>".$mobile."</td>
				<td>".$qty_kg."</td>
				<td>".$fat_percentage."</td>
				<td>".$snf_percentage."</td>
				<td>".$fat_kg."</td>
				<td>".$snf_kg."</td>
				<td>".$dispatch_time."</td>
				<td>".$target_time."</td>
				<td>".$user_id."</td>
				
				<td><font color=green>".$status."</font></td>";
				echo '<td align=right><input type="checkbox" name="invoice_serial[]" value="'.$sno.'"</td>
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
				<input type="button" value="Close Invoice" id="enter_button" onclick="javascript:return action_manage_invoice_update('delete')"/>&nbsp;				
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
