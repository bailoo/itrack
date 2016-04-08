<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $DEBUG = 0;
  
  $query = "SELECT VehicleID,VehicleName FROM vehicle WHERE create_id='$account_id' AND status='1'";
  if($DEBUG == 1)
    print_query($query);
      
  $result = mysql_query($query,$DbConnection);
  $j=0;                   
  while($row = mysql_fetch_object($result))
  {
    $vid[$j] = $row->VehicleID;
    $vname[$j] = $row->VehicleName;
    if($DEBUG == 1)
      echo $vid[$j]." ,".$vname[$j]."<br>";
    $j++;
  }
?>
  
<center>
<form method = "post"  name="thisform">
  
  <fieldset class="manage_fieldset">
		<legend><strong>Vehicle</strong></legend>  
		
		<input type="radio" name="option" value="new" onclick="javascript:document.getElementById('new').style.display='';document.getElementById('exist').style.display='none';document.getElementById('edit_div').style.display='none';document.getElementById('available_message').style.display='none';">New
		<input type="radio" name="option" value="exist" onclick="javascript:document.getElementById('new').style.display='none';document.getElementById('exist').style.display='';document.getElementById('available_message').style.display='none';">Existing

  <!-- ADD OPENS-->
  <fieldset class="manage_fieldset" style="display:none;" id="new">
		<legend><strong>Add Vehicle<strong></legend>		
        
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
    
    <!--<tr>
      <td colspan="3" align="center"><b>Add Vehicle</b><div style="height:5px;"></div></td>    
    </tr>-->       
    <tr>
      <td>Name</td>
      <td>&nbsp;:&nbsp;</td>
      <td> <input type="text" name="vehicle_name" id="vehicle_name"> </td>
    </tr>	
    <tr>
      <td>Number</td>
      <td>&nbsp;:&nbsp;</td>
      <td> <input type ="text" name="vehicle_number" id="vehicle_number"> </td>
    </tr>
    <tr>
      <td>Max Speed</td>
      <td>&nbsp;:&nbsp;</td>
      <td> <input type ="text" name="max_speed" id="max_speed"> </td>
    </tr>  		
    <tr>
      <td>Tag</td>
      <td>&nbsp;:&nbsp;</td>
      <td> <input type ="text" name="vehicle_tag" id="vehicle_tag"> </td>
    </tr> 
    <tr>
      <td>Type</td>
      <td>&nbsp;:&nbsp;</td>
      <td>
        <select name="vehicle_type" id="vehicle_type">
          <option value="car">Car</option>
          <option value="truck">Truck</option>
          <option value="bas">Bas</option>
        </select>
      </td>
    </tr> 								
    <tr>                    									
      <td align="center"  colspan="3">
        <div style="height:10px"></div>
        <input type="button" Onclick="javascript:action_manage_vehicle(thisform, 'add')" value="Enter" id="enter_button">
        &nbsp;
        <input type="reset" value="Clear">
      </td>
    </tr>
  </table>
  </fieldset>
  <!-- ADD CLOSED -->
                
  <!-- EDIT OPTION OPENS -->
  
  <fieldset class="manage_fieldset" style="display:none;" id="exist">
		<legend><strong>Existing Vehicle</strong></legend>		 
     <table class="manage_interface" border="0" align="center">
     <tr>
     <td>
       Vehicle Name <select name="edit_vehicle" id="edit_vehicle" Onchange="javascript:return show_edit('manage','edit_vehicle');">
  		  
        <?php
  		    echo '<option value="select">Select</option>';          
          for($i=0;$i<$j;$i++)
          {
            echo '<option value="'.$vid[$i].'">'.$vname[$i].'</option>';
          }		  
        ?>      
       
       </select>
     </td>
     </tr>
     <tr><td></td></tr>
     <tr><td>
     </td></tr>
     </table>
		
  </fieldset>
  
<!-- EDIT OPTION CLOSED -->
  
<!-- EDIT VEHICLE OPENS -->

<div align="center" id="edit_div" style="display:none;"></div>

<!-- EDIT VEHICLE CLOSED-->
      
  </fieldset>
</form>
</center>
