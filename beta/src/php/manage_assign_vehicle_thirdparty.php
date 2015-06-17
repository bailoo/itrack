<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
	
	//print_r($root);
	$selectionbox="
	<select name='destination_account_id' id='destination_account_id' onchange='show_source_vehicle(this.value);'>
	<option value='select'>Select</option>";
		
	$data=getTpaUidNameAr($account_id,$DbConnection)
	foreach($data as $dt)
	{		
		$tid=$dt['tid'];
		$tuser_id=$dt['tuser_id'];;
		$tname=$dt['tname'];
		$selectionbox.="<option value='$tid'>$tname($tuser_id)</option>";
	}
	
	$selectionbox.="</select>";
	
		echo"<form name='manage1'>
		<table width='70%' align='center'>
			<tr>
				<td align=center>
					<fieldset class='assignment_manage_fieldset'>
						<legend>
							<strong>Accounts Destination</strong>
						</legend>
						
						";
							
							echo $selectionbox;
							 
					echo"
					</fieldset>
				</td>
			</tr>
			<tr>
				<td>
					<div id='source_vehicle' />
				</td>
			</tr>
			
		</table>";
	
			include_once('manage_loading_message.php');
	echo'</center>
	</form>';
?>