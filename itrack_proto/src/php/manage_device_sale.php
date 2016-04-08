<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $DEBUG = 0; 
 
  $device_imei=getDeviceIMEINoDeviceSalesInfo($account_id,$DbConnection);
?>   

<center>

<form method = "post"  name="thisform">
 
  <fieldset class="manage_fieldset">
		<legend><strong>Device Sale</strong></legend>  
		
		<input type="radio" name="option" value="new" onclick="javascript:show_add('manage','add_device_sale');document.getElementById('exist').style.display='none';document.getElementById('available_message').style.display='none';document.getElementById('edit_div').style.display='none';">New
		<input type="radio" name="option" value="exist" onclick="javascript:document.getElementById('edit_div').style.display='none';document.getElementById('available_message').style.display='none';document.getElementById('exist').style.display='';">Existing
 	
    
<!-- EDIT ACCOUNT OPTION OPENS -->

  <fieldset class="manage_fieldset" style="display:none;" id="exist">
		<legend><strong>Existing Device Sale</strong></legend>		 
     <table class="manage_interface" border="0" align="center">
     <tr valign="true">
     <td>
       Device IMEI No <select name="edit_device_sale" id="edit_device_sale" Onchange="javascript:return show_edit('manage','edit_device_sale');">
  		  
        <?php
  		    echo '<option value="select">Select</option>';          
          for($i=0;$i<$j;$i++)
          {
            echo '<option value="'.$device_imei[$i].'">'.$device_imei[$i].'</option>';
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

<!-- EDIT ACCOUNT OPTION CLOSED -->

<!-- EDIT VEHICLE OPENS -->

<div align="center" id="edit_div" style="display:none;"></div>

<!-- EDIT VEHICLE CLOSED-->
    
 </fieldset>   
   </form>
  <div id="available_message" align="center"></div>
  </center>
  