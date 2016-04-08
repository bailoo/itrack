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
  <td>&nbsp;Student Card&nbsp:&nbsp;
    
    <select name="studentcard_id" id="studentcard_id" onchange="show_studentcard_record(manage1);">
      <option value="select">Select</option>
      <?php
			$query="select * from studentcard where user_account_id='$common_id1' and status='1'";
			$result=mysql_query($query,$DbConnection);            							
			while($row=mysql_fetch_object($result))
			{
			  $studentcard_id=$row->studentcard_id; $studentcard_number=$row->studentcard_number;                								 
			  echo '<option value='.$studentcard_id.'>'.$studentcard_number.'</option>';
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
  			<td>Card Number</td>
  			<td> :</td>
  			<td><input type="text" name="edit_studentcard_number" id="edit_studentcard_number" onkeyup="manage_availability(this.value, 'studentcard')" onmouseup="manage_availability(this.value, 'studentcard')" onchange="manage_availability(this.value, 'studentcard')"></td>
  		</tr>         		     
      </table>
    </div>
</td>                                
</tr>        
<tr>
	<td colspan="3" align="center">
		<input type="button" id="enter_button" value="Update" onclick="javascript:return action_manage_studentcard('edit')"/>&nbsp;
		<input type="button" value="Delete" onclick="javascript:action_manage_studentcard('delete')"/>
	</td>
</tr>
</table>  

  <?php 
    include_once('availability_message_div.php');
  ?>