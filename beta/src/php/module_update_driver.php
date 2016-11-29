<?php
echo '
  <div id="update_driver" class="white_content" height="100%">
  <strong>Update Driver Profile</strong>
  <input type="hidden" id="imei">
  <table width="100%" border="0" height="100px;">
  <tr>
    <td>Driver Name:</td>
    <td><input type="text" id="driver_name"></td>
  </tr>
  <tr>
    <td>Mobile No:</td>
    <td><input type="text" id="driver_mobile"></td>
  </tr>
  <tr>    
    <td></td>
	<td><input type="button" value="Update Info" onclick="javascript:action_update_driver_history()">&nbsp;<input type="reset"></td>
  </tr>
</table> <br>
  <div align="right">
  <a href="javascript:void(0)" onclick="document.getElementById(\'update_driver\').style.display=\'none\';document.getElementById(\'fade\').style.display=\'none\'">Close</a>
  </div>
  
  </div>
  <div id="fade" class="black_overlay"></div>';
                
 ?>