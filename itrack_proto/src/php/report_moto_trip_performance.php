<?php
	include_once('util_session_variable.php');		
	include_once('util_php_mysql_connectivity.php');	
	//$account_id_local1 = $_POST['account_id_local'];		 
	
	/*$QueryConsignment="SELECT consignment.consignment_id,consignment.consignment_code,consignment_name,trip_details_moto.reached_at,".	
					  "trip_details_moto.vehicle_name FROM consignment,trip_details_moto WHERE consignment.consignment_id=trip_details_moto.".
					  "consignment_id AND consignment.create_id='$account_id_local1' AND consignment.status=1 AND trip_details_moto.".
					  "reached_at BETWEEN '$start_date' AND '$end_date' Order BY trip_details_moto.reached_at DESC";*/
	$QueryConsignment="SELECT consignment.consignment_id,consignment.consignment_code,consignment_name,trip_details_moto.reached_at,".	
					  "trip_details_moto.vehicle_name FROM consignment,trip_details_moto WHERE consignment.consignment_id=trip_details_moto.".
					  "consignment_id AND consignment.create_id='$account_id' AND consignment.status=1 AND trip_details_moto.".
					  "reached_at BETWEEN '$start_date' AND '$end_date' Order BY trip_details_moto.reached_at DESC";
	//echo "query=".$QueryConsignment."<br>";
	$ResultConsignment=mysql_query($QueryConsignment,$DbConnection);
	$NumRowsConsignment=mysql_num_rows($ResultConsignment);
	
	if($NumRowsConsignment==0)
	{
		echo "<br>
		<center>
			
			<font color='red'>
				<b>
					No Consignment Found During this Date Time
				</b>
			</font>
		</center>";	
	}
	else
	{
		echo '<input type="hidden" value="'.$title.'" id="title">';
	echo'<center>
			<table border=0 width = 100% cellspacing=2 cellpadding=0>
				<tr>
					<td height=10 class="report_heading" align="center">'.$title.'</td>
				</tr>
			</table>													
			<form  method="post" name="thisform">							
				<br>
					<table border=0  cellspacing=3 cellpadding=3 align="center"  bordercolor="black">
						<tr>							
							<td align="center">							
								<b>Consignment Code &nbsp;:&nbsp;
							</td>
							<td>
							:
							</td>
							<td align="center">							
								<input type="text" id="consignment_code">
							</td>
							<td align="center">							
								<input type="button"  value="Get Details" onclick="javascript:action_report_moto_trip_performance(\'\',\'\');">
							</td>							
						</tr>
					</table>
					<table border=1  cellspacing=3 cellpadding=3 align="center" rules="all" bordercolor="black">						
						<tr bgcolor="grey">
							<td align="center">							
								<b>Serial
							</td>
							<td align="center">							
								<b>Consignment ID
							</td>
							<td align="center">							
								<b>Consignment Code
							</td>
							<td align="center">							
								<b>Consignment Name
							</td>
							<td align="center">							
								<b>Vehicle Name
							</td>
							<td align="center">							
								<b>Reached AT
							</td>
							<td align="center">							
								<b>Action
							</td>
						</tr>';
					$i=1;
					while($Row=mysql_fetch_object($ResultConsignment))
					{
					echo'<tr>
							<td align="center">'.$i++.'</td>
							<td align="center">'.$Row->consignment_id.'</td>
							<td align="center">'.$Row->consignment_code.'</td>
							<td align="center">'.$Row->consignment_name.'</td>
							<td align="center">'.$Row->vehicle_name.'</td>
							<td align="center">'.$Row->reached_at.'</td>
							<td align="center">
								<input type="button"  value="Get Details" onclick="javascript:action_report_moto_trip_performance(\''.$Row->consignment_code.'\',\''.$title.'\');">
							</td>
						</tr>';
					}						
			echo'</table>					
			</form>       
			<div align="center" id="loading_msg" style="display:none;">			
				<font color="green">loading...</font>
			</div>		
    <center>';
}
?>						
					 
