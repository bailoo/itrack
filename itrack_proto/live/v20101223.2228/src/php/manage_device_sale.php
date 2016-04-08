<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $DEBUG = 0;
  
  //$query = "SELECT device_sales_info.device_imei_no,account.user WHERE account.user IN ( SELECT device_sales_info.user_account_id from device_sales_info WHERE create_id='$account_id' AND status='1')";
  $query = "SELECT device_imei_no FROM device_sales_info  WHERE create_id='$account_id' AND status='1'";
  if($DEBUG == 1)
    print_query($query);
      
  $result = mysql_query($query,$DbConnection);
  $j=0;                   
  while($row = mysql_fetch_object($result))
  {
    $device_imei[$j] = $row->device_imei_no;
    //$user_acc[$j] = $row->user;
    //if($DEBUG == 1)
      //echo $device_imei[$j]." ,".$user_accs[$j]."<br>";
    $j++;
  }
    
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
  