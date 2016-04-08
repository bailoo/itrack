<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $edit_vehicle = $_POST['edit_vehicle'];
  
  echo 'edit##';
  
  $DEBUG = 0;
  $query = "SELECT * FROM vehicle WHERE VehicleID='$edit_vehicle' AND create_id='$account_id'";
  $result = mysql_query($query,$DbConnection);
  
  if($DEBUG == 1) print_query($query);
  
  $row = mysql_fetch_object($result);
  $vehicle_id = $row->VehicleID;
  $vehicle_name = $row->VehicleName;
  $vehicle_number = $row->vehicle_number;
  $max_speed = $row->max_speed;
  $vehicle_tag = $row->tag;
  $vehicle_type = $row->VehicleType;
  
echo'
  
<center>
  <input type="hidden" name="vehicle_id_edit" id="vehicle_id_edit" value="'.$edit_vehicle.'">
  
  <fieldset class="manage_fieldset">
		<legend><strong>Edit Vehicle<strong></legend>		
      
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
    
    <!--<tr>
      <td colspan="3" align="center"><b>Add Vehicle</b><div style="height:5px;"></div></td>    
    </tr>-->
       
    <tr>
      <td>Name</td>
      <td>&nbsp;:&nbsp;</td>
      <td> 
      <input type="text" name="vehicle_name_edit" id="vehicle_name_edit" value="'.$vehicle_name.'"> </td>
    </tr>	
    <tr>
      <td>Number</td>
      <td>&nbsp;:&nbsp;</td>
      <td> <input type ="text" name="vehicle_number_edit" id="vehicle_number_edit" value="'.$vehicle_number.'"> </td>
    </tr>
    <tr>
      <td>Max Speed</td>
      <td>&nbsp;:&nbsp;</td>
      <td> <input type ="text" name="max_speed_edit" id="max_speed_edit" value="'.$max_speed.'"> </td>
    </tr>  		
    <tr>
      <td>Tag</td>
      <td>&nbsp;:&nbsp;</td>
      <td> <input type ="text" name="vehicle_tag_edit" id="vehicle_tag_edit" value="'.$vehicle_tag.'"> </td>
    </tr> 
    <tr>
      <td>Type</td>
      <td>&nbsp;:&nbsp;</td>
      <td>
        <select name="vehicle_type_edit" id="vehicle_type_edit">';
         if($vehicle_type!="")
            echo '<option value="'.$vehicle_type.'" selected>'.ucfirst($vehicle_type).'</option>';
         
         echo'
          <option value="car">Car</option>
          <option value="truck">Truck</option>
          <option value="bas">Bas</option>
        </select>
      </td>
    </tr> 								
    <tr>                    									
      <td align="center"  colspan="3">
        <div style="height:10px"></div>
        <input type="button" Onclick="javascript:action_manage_vehicle(thisform, \'edit\')" value="Update" id="id="u_d_enter_button"">&nbsp;
        <input type="reset" value="Clear">&nbsp;
        <input type="button" Onclick="javascript:action_manage_vehicle(thisform, \'delete\')" value="Delete Vehicle" id="id="u_d_enter_button"">
      </td>
    </tr>
  </table>
  </fieldset>
</center>
';
?>