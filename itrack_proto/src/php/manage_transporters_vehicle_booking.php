<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 
         
	$transporter_id1 = $_POST['transporter_id'];

	$query = "SELECT name FROM transporters WHERE transporter_id='$transporter_id1' AND status=1";  
  //echo "<br>".$query;
  $result = mysql_query($query, $DbConnection);   
  $row = mysql_fetch_object($result);
  $name = $row->name;
  
  $DEBUG=0;
	 	     
  echo'
  <br><br><br>
  <fieldset class="report_fieldset">
  	<legend><strong>Book Vehicle &nbsp;<font color="#FF0000">['.$name.']</font></strong></legend>  
    <table>
    <tr><td class="text">User Name</td><td>:</td><td><input type="text" name="user_name" id="user_name"/></td></tr>
    <tr><td class="text">Email ID</td><td>:</td><td><input type="text" name="email_id" id="email_id"/></td></tr>
    <tr><td class="text">Address</td><td>:</td><td><textarea size="30" name="address" id="address"></textarea></td></tr>
    <tr><td class="text">Phone No</td><td>:</td><td><input type="text" name="phone" id="phone"/></td></tr>
    <tr><td class="text">Date Of Booking</td><td>:</td><td><input type="text" name="dobooking" id="dobooking"/>
    <a href=javascript:NewCal("date1","yyyymmdd",true,24)>
  	<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></td></tr> 
    <tr><td class="text">Place from</td><td>:</td><td><input type="text" name="placefrom" id="placefrom"/></td></tr>
    <tr><td class="text">Place to</td><td>:</td><td><input type="text" name="placeto" id="placeto"/></td></tr>  
    <tr><td class="text">Remark</td><td>:</td><td><input type="text" name="remark" id="remark"/></td></tr>  
		<tr>
			<td class="text" align="center" colspan="3"><br><input type="button" onclick="javascript:action_manage_transporters_vehicle_booking('.$transporter_id1.');" value="Submit">&nbsp;&nbsp;&nbsp;<input type="reset" value="Clear"/></td>
		</tr>
    
    </table>
    </fieldset>
    <br>
    <center><a href="javascript:show_transporter_info()" class="back_css">&nbsp;<b>Back</b></a></center>
    
  ';
?>