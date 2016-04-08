<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
?>

<form method = "post"  name="thisform">
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
    <tr>
      <td colspan="2" align="center">
        <b>New Feedback Request</b>
        <div style="height:5px;"></div>
      </td>    
    </tr>              
    <tr>
      <td class="vt">Name : </td>
      <!--<td class="vt">&nbsp;:&nbsp;</td>-->
      <td><input style="width:300px" type="text" name="name" id="name"></td>
    </tr>
    <tr>
      <td class="vt">Subject : </td>
      <!--<td class="vt">&nbsp;:&nbsp;</td>-->
      <td><input style="width:300px" type ="text" name="subject" id="subject"></td>
    </tr> 
    <tr>
      <td class="vt">Feedback : </td>
      <!--<td class="vt">&nbsp;:&nbsp;</td>-->
      <td><textarea style="width:300px" rows="10" name="body" id="body"></textarea></td>
    </tr> 									
    <tr>
      <td colspan="2"></td>
    </tr>
    <tr>                    									
      <td align="center" colspan="2">
        <input type="button" onclick="javascript:action_feedback_new(thisform)" value="Submit" id="submit">
      </td>
    </tr>
  </table>
</form>

