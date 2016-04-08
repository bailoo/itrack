<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$DEBUG=0;
	$parameter_type="escalation";
	//echo "edit##";
	$postPars = array('escalation_id' , 'action_type' , 'local_account_ids' , 'person_name', 'person_mob', 'person_email' , 'other_detail', 'common_id');
	include_once('action_post_data.php');
	$pd = new PostData();
	$common_id1=$pd->data[common_id];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	include_once('manage_escalation.php');
?>
<form name='manage1'>
  <center>
    <div style="height:10px"> </div>
      <table border="0" class="manage_interface" cellspacing="2" cellpadding="2">
        <tr>
          <td>&nbsp;Person Name&nbsp;:&nbsp;
            <select id="escalation_id" onchange="show_escalation_detail(manage1);">
              <option value="select">Select</option>';
                <?php
                    $query="select * from escalation where escalation_id IN (SELECT escalation_id from escalation_grouping where account_id='$common_id1' and status=1) and status='1'";
                    if($DEBUG==1){print $query;}
                    $result=mysql_query($query,$DbConnection);            							
                    while($row=mysql_fetch_object($result))
                    {
                      $escalation_id=$row->escalation_id; 
					  $person_name=$row->person_name;
                      echo'<option value='.$escalation_id.'>'.$person_name.'</option>';
                    }
                ?>
            </select>
          </td>
        </tr>
      </table>
      <div style="height:10px"> </div>    
      <div id="escalation_area" style="display:none">
      <table width="100%" border=0>
        <tr>
          <td width="36%">&nbsp;</td>
          <td>
            <table class="manage_interface" cellspacing="2" cellpadding="2">         					         
              <tr>                          
                <td>Person Name</td>
                <td>:</td>
                <td>
                  <input type="text" id="person_name">
                </td>                                
              </tr>
              <tr>                          
                <td>Person Mobile</td>
                <td>:</td>
                <td>
                  <input type="text" id="person_mob">
                </td>                                
              </tr>
              
              <tr>                          
                <td>Person Email</td>
                <td>:</td>
                <td>
                  <input type="text" id="person_email">
                </td>                                
              </tr>                                     
              <tr>                          
                <td>Other Detail</td>
                <td>:</td>
                <td><textarea style="width:350px;height:60px" id="other_detail"></textarea></td>                                
              </tr>
              <tr>
                <td colspan="3">
                <div style="height:10px"> </div>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   <input type="button" id="enter_button" value="Update" onclick="javascript:action_manage_escalation('edit')"/>
                  &nbsp;<input type="button" value="Delete" onclick="javascript:action_manage_escalation('delete')"/>
                </td>
              </tr>
          </table>
          </td>
          </tr>
          </table>
    </div>
</center>
</form>
  
  <?php 
    include_once('availability_message_div.php');
	include_once('manage_loading_message.php');
  ?>