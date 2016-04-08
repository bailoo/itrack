<?php 
	//include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//$root=$_SESSION['root'];  
	$DEBUG=0;
	$parameter_type="student";
	echo "edit##";
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
    			$data=getDetailAllVisitPerson($common_id1,$DbConnection);				
    			foreach($data as $dt)
				{
				   $person_id=$dt['person_id'];
				   $person_name=$dt['person_name'];			
    			                								 
    			   echo '<option value='.$person_id.'>'.$person_name.'</option>';
    			}
    			?>
        </select>
        &nbsp;&nbsp;
        <input type="button" id="enter_button1" value="Get Locations" onclick="javascript:return action_manage_visit('getlocations')"/>
      </td>
</tr>


<tr>
			<td>Locations</td>
			<td> :</td>
			<td><textarea readonly="readonly" style="width:350px;height:60px" name="route_coord" id="route_coord" onclick="javascript:showCoordinateInterface('route');"></textarea></td>	
</tr>      
<tr>
	<td colspan="3" align="center">
		<input type="button" id="enter_button" value="Update" onclick="javascript:return action_manage_visit('edit')"/>&nbsp;
		<input type="button" value="Delete" onclick="javascript:action_manage_visit('delete')"/>
	</td>
</tr>
</table>
	<?php
		include_once('availability_message_div.php');
	?>

  
