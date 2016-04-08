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
  <td>&nbsp;Busroute&nbsp:&nbsp;
    
    <select name="busroute_id" id="busroute_id" onchange="show_busroute_record(manage1);">
      <option value="select">Select</option>
      <?php
			$query="select * from busroute where group_id='$common_id1' and status='1'";
			$result=mysql_query($query,$DbConnection);            							
			while($row=mysql_fetch_object($result))
			{
			  $busroute_id=$row->busroute_id; $busroute_name=$row->busroute_name;                								 
			  echo '<option value='.$busroute_id.'>'.$busroute_name.'</option>';
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
  			<td>Busroute Name</td>
  			<td> :</td>
  			<td><input type="text" name="edit_busroute_name" id="edit_busroute_name" onkeyup="manage_availability(this.value, 'busroute')" onmouseup="manage_availability(this.value, 'busroute')" onchange="manage_availability(this.value, 'busroute')"></td>
  		</tr>   		     
      </table>
    </div>
</td>                                
</tr>        
<tr>
	<td colspan="3" align="center">
		<input type="button" id="enter_button" value="Update" onclick="javascript:return action_manage_busroute('edit')"/>&nbsp;
		<input type="button" value="Delete" onclick="javascript:action_manage_busroute('delete')"/>
	</td>
</tr>
</table>  

  <?php 
    include_once('availability_message_div.php');
  ?>