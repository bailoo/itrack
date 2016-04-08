<?php 
	//include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//$root=$_SESSION['root'];  
	$DEBUG=0;
	$parameter_type="student";
	echo "add##";
	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	//include_once('tree_hierarchy_information.php');
	//include_once('manage_radio_account.php');
  
  //date_default_timezone_set('Asia/Calcutta');
	$start_date=date("Y/m/d 00:00:00");
         
 ?>  
	<br>
	<table border="0" align="center" class="manage_interface" cellspacing="2" cellpadding="2">
  
  <tr>
			<td>Schedule Date</td>
			<td> :</td>
			<td>
          <input type="text" id="date1" name="start_date" value="<?php echo $start_date ?>" size="20" maxlength="19">

					<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
						<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
					</a>
				</td>
  </tr>
  
  <tr>
  <td>&nbsp;Person Name &nbsp; &nbsp;</td>
			<td> :</td>
			<td>
    
        <select name="person_id" id="person_id" >
          <option value="select">Select</option>
          <?php
    			//$query="select vehicle_id,vehicle_name from vehicle where account_id='$common_id1' and status='1'";
          $query = "select vehicle_name,vehicle_id from vehicle where vehicle_id IN(select vehicle_id from vehicle_grouping where account_id='$common_id1' and status='1') and status=1";    			
    			$result=mysql_query($query,$DbConnection);            							
    			while($row=mysql_fetch_object($result))
    			{
    			  $person_id=$row->vehicle_id; $person_name=$row->vehicle_name;                								 
    			  echo '<option value='.$person_id.'>'.$person_name.'</option>';
    			}
    			?>
        </select>
      </td>
</tr>


<tr>
			<td>Locations</td>
			<td> :</td>
			<td><textarea readonly="readonly" style="width:350px;height:60px" name="route_coord" id="route_coord" onclick="javascript:showCoordinateInterface('route');"></textarea></td>	
</tr>      
<tr>
	<td colspan="3" align="center">
		<input type="button" id="enter_button" value="save" onclick="javascript:return action_manage_visit('add')"/>&nbsp;
		
	</td>
</tr>
</table>
	<?php
		include_once('availability_message_div.php');
	?>

  
