<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $DEBUG = 0;
  
  $query = "SELECT device_id,device_imei_no FROM device_manufacturing_info WHERE create_id='$account_id' AND status='1'";
  if($DEBUG == 1)
    print_query($query);
  $result = mysql_query($query,$DbConnection);
  $j=0;                   
  while($row = mysql_fetch_object($result))
  {
    $device_id[$j] = $row->device_id;
    $device_name[$j] = $row->device_imei_no;
    if($DEBUG == 1)
      echo $device_id[$j]." ,".$devic_name[$j]."<br>";
    $j++;
  }    
 ?>   

  <center>
  <form method = "post"  name="thisform">
  
  <fieldset class="manage_fieldset">
		<legend><strong>Device</strong></legend>  
		
		<input type="radio" name="option" value="new" onclick="javascript:document.getElementById('new').style.display='';document.getElementById('exist').style.display='none';document.getElementById('edit_div').style.display='none';document.getElementById('available_message').style.display='none';">New
		<input type="radio" name="option" value="exist" onclick="javascript:document.getElementById('new').style.display='none';document.getElementById('exist').style.display='';document.getElementById('available_message').style.display='none';">Existing
 
  
  <fieldset style="display:none;" id="new">
		<legend><strong>Add Device</strong></legend>		
   
    <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">        
        
        <tr>
            <td>Device IMEI No</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="imei_no" id="imei_no" onkeyup="manage_availability(this.value, 'imei_no', 'new')" onmouseup="manage_availability(this.value, 'imei_no', 'new')" onchange="manage_availability(this.value, 'imei_no', 'new')">
            </td>
        </tr> 
        
        <tr>
            <td>Manufacturing Date</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="manufacturing_date" id="manufacturing_date" size="17" maxlength="19" readonly="true" Onclick="javascript:NewCal('manufacturing_date','yyyymmdd,true,24)"> 
          	   <a href=javascript:NewCal("manufacturing_date","yyyymmdd",true,24)>
                  <img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
                 </a>
      </td> 
        </tr>
        
        <tr>
            <td>Make</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="make" id="make">
            </td>
        </tr>
			  <tr>                    									
					<td align="center" colspan="3"><input type="button" id="enter_button" Onclick="javascript:return action_manage_device(thisform, 'add')" value="Enter">&nbsp;<input type="reset" value="Clear"></td>
				</tr>
    </table>
    </fieldset>
   <!-- ADD CLOSED --> 
  
   <!-- EDIT OPTION OPENS -->
   
  <fieldset class="manage_fieldset" style="display:none;" id="exist">
		<legend><strong>Existing Device</strong></legend>		 
     <table class="manage_interface" border="0" align="center">
     <tr>
     <td>
       Device Name <select name="edit_device" id="edit_device" Onchange="javascript:return show_edit('manage','edit_device');">
  		  
        <?php  		    
          echo '<option value="select">Select</option>';
          for($i=0;$i<$j;$i++)
          {
            echo '<option value="'.$device_id[$i].'">'.$device_name[$i].'</option>';
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
           
  <!-- EDIT DEVICE OPENS -->
  
  <div align="center" id="edit_div" style="display:none;"></div>
  
  <!-- EDIT DEVICE CLOSED-->
  
   
   <div id="available_message" style="display:none;" align="center"></div>
   
   </fieldset>   
   
   </form>
 </center> 