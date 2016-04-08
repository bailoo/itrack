<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  $DEBUG=0;
  echo "edit##"; 

	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';  
?>
<br> 
<table border="0" align="center" class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
  <td>&nbsp;Busstop&nbsp:&nbsp;
    
    <select name="busstop_id" id="busstop_id" onchange="show_busstop_record(manage1);">
      <option value="select">Select</option>
      <?php
			$query="select * from busstop where group_id='$common_id1' and status='1'";
			$result=mysql_query($query,$DbConnection);            							
			while($row=mysql_fetch_object($result))
			{
			  $busstop_id=$row->busstop_id; $busstop_name=$row->busstop_name;                								 
			  echo '<option value='.$busstop_id.'>'.$busstop_name.'</option>';
			}
			?>
    </select>
  </td>
</tr>
<tr>                                     					         
       <tr>                          
  <td>
   <div id="display_area" style="display:none">
     <table class="manage_interface"> 
       <tr>
  			<td>Busstop Name</td>
  			<td> :</td>
  			<td><input type="text" name="edit_busstop_name" id="edit_busstop_name" onkeyup="manage_availability(this.value, 'busstop')" onmouseup="manage_availability(this.value, 'busstop')" onchange="manage_availability(this.value, 'busstop')"></td>
  		</tr>
      <tr>
			<td>Latitude</td>
			<td> :</td>
			<td><input type="text" name="edit_busstop_latitude" id="edit_busstop_latitude" ></td>
		</tr>
		<tr>
			<td>Longitude</td>
			<td> :</td>
			<td><input type="text" name="edit_busstop_longitude" id="edit_busstop_longitude" ></td>
		</tr>   		     
      </table>
    </div>
</td>                                
</tr>        
<tr>
	<td colspan="3" align="center">
		<input type="button" id="enter_button" value="Update" onclick="javascript:return action_manage_busstop('edit')"/>&nbsp;
		<input type="button" value="Delete" onclick="javascript:action_manage_busstop('delete')"/>
	</td>
</tr>
</table>  

  <?php 
    include_once('availability_message_div.php');
  ?>