<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');  
	echo "edit_div_station1##"; 
	$account_id_local1=$_REQUEST['account_id_local'];
	echo "<input type='hidden' id='account_id_local' value=".$account_id_local1.">";
	//echo "account_id_local=".$account_id_local;

	echo"<br>			
				<center><strong> EDIT DISTANCE VARIABLE </strong>
				<br><br>
			
        <div style='overflow:auto;height:50px'> 
        <table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>					
					<tr>
						<td colspan='3'>Select Station Type</td>
						<td colspan='3'>&nbsp;:&nbsp;</td>
            <td colspan='3'>
      				<select name='station_type' id='station_type' onchange='javascript:show_station_person(this.value);'>
      				<option value='select'>Select</option>
      				<option value='0'>Customer</option>
      				
						</td>																														
					</tr>	          
        </table>
			 </div>
			   
      <div style='display:none;' id='edit_div_station2'> </div>      
			   
	</center>";
?>	