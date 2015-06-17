<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');
	$DEBUG=0;
	$parameter_type="calibration";
	//echo "edit##";
	$postPars = array('calibration_id' , 'action_type' , 'local_account_ids' , 'calibration_name' , 'calibration_data', 'common_id');
	include_once('action_post_data.php');
	$pd = new PostData();
	$common_id1=$pd->data[common_id];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	include_once('manage_calibration.php');
?>
  <center>
  <form name='manage1'>
    <div style="height:10px"> </div>
		
      <table border="0" class="manage_interface" cellspacing="2" cellpadding="2">
        <tr>
          <td>&nbsp;Calibration Name&nbsp;:&nbsp;
		  <?php 
				//$data=getClCNameCalibration($common_id1,$DbConnection); // for debug this query 
			?>
            <select id="calibration_id" onchange="show_calibration_detail(manage1);">
              <option value="select">Select</option>';
                <?php
                    
                    $data=getClCNameCalibration($common_id1,$DbConnection);            							
                    foreach($data as $dt)
					{
                      $calibration_id=$dt['calibration_id']; 
					  $calibration_name=$dt['calibration_name'];
                      echo'<option value='.$calibration_id.'>'.$calibration_name.'</option>';
                    }
                ?>
            </select>
          </td>
        </tr>
      </table>
      <div style="height:10px"> </div>    
      <div id="calibration_area" style="display:none">
      <table width="100%" border=0>
        <tr>
          <td width="36%">&nbsp;</td>
          <td>
            <table class="manage_interface" cellspacing="2" cellpadding="2">         					         
              <tr>                          
                <td>Calibration Name</td>
                <td>:</td>
                <td>
                  <input type="text" id="calibration_name" onkeyup="manage_availability(this.value, 'calibration')" onmouseup="manage_availability(this.value, 'calibration')" onchange="manage_availability(this.value, 'calibration')">
                </td>                                
              </tr> 
              <tr>                          
                <td>Calibration Data</td>
                <td>:</td>
                <td><textarea style="width:350px;height:60px" id="calibration_data"></textarea></td>                                
              </tr>
              <tr>
                <td colspan="3">
                <div style="height:10px"> </div>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   <input type="button" id="enter_button" value="Update" onclick="javascript:action_manage_calibration('edit')"/>
                  &nbsp;
				  <input type="button" value="Delete" onclick="javascript:action_manage_calibration('delete')"/>
                </td>
              </tr>
          </table>
          </td>
          </tr>
          </table>
    </div>
</center>
  
  <?php 
    include_once('availability_message_div.php');
	include_once('manage_loading_message.php');
  ?>
  </form>