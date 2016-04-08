<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include_once('manage_hierarchy_header1.php');
	$root=$_SESSION['root'];
	$account_id_local1=$_POST['account_id_local'];
	$no_of_intermediate_halt_1=$_POST['no_of_intermediate_halt'];
	//get_user_vehicle($root,$common_id1);
	echo "intermediate_check_halt_time##
	<input type='hidden' value=".$no_of_intermediate_halt_1." id='total_intermediate_halt_no'>
	<table class='menu' border=0>";	
	echo"<tr>";
	for($i=1;$i<=$no_of_intermediate_halt_1;$i++)
	{
		echo"
			<td>			
				<input type='text' id='intermediate_min_halt_time_hr_".$i."' size='3' value='00'>&nbsp;hr&nbsp;
				<input type='text' id='intermediate_min_halt_time_min_".$i."' size='3' value='00'>&nbsp;min&nbsp;
			</td>				
			";	
	}
	echo"</tr>
	</table>";
?>
