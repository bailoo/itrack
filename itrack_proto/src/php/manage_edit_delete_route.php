<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  $DEBUG=0;
  echo "edit##"; 

	$common_id1=$_POST['common_id'];
	//echo "<br>account_id_hidden=". $common_id1."<br>";
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';  
?>
<br> 
<table border="0" align="center" class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
  <td>&nbsp;Route Name&nbsp:&nbsp;
    
    <select name="route_id" id="route_id" onchange="javascript:manage_show_sector_ids();">
      <option value="select">Select</option>
      <?php			
			$date=getDetailAllRoute($common_id1,$DbConnection);            							
			foreach($data as $dt)
			{
			  		  
			  $route_id=$dt['route_id'];
			  $route_name=$dt['route_name'];               								 
			  echo '<option value='.$route_id.'>'.$route_name.'</option>';
			}
			?>
    </select>
  </td>
</tr>
<!--<tr>                          
  <td>
   <div id="route_area" style="display:none">
     <table class="manage_interface">          					         
       <tr>                          
          <td>Route Name</td><td>:</td>
          <td><input type="text" name="route_name" id="route_name" onkeyup="manage_availability(this.value, 'route')" onmouseup="manage_availability(this.value, 'route')" onchange="manage_availability(this.value, 'route')"></td>                                
       </tr>
      </table>
    </div>    
</td>                                
</tr>-->       
                            
<tr>
<td>
<div id="sector_area" style="display:none"></div>
</td>
</tr>

<tr>
	<td colspan="3" align="center">
		<input type="button" id="enter_button" value="Update" onclick="javascript:action_manage_route('edit')"/>
		<input type="button" value="Delete" onclick="javascript:action_manage_route('delete')"/>
	</td>
</tr>
</table>  

  <?php 
    include_once('availability_message_div.php');
  ?>