<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');
	$DEBUG=0;
	$parameter_type="milestone";
	echo "edit##";
	$groupo_id_local=$_POST['common_id'];
	echo'<input type="hidden" id="group_id_hidden" value='.$groupo_id_local.'>';

?>
<br><center> 
<table border="0" class="manage_interface" cellspacing="2" cellpadding="2">
    <tr>
        <td>&nbsp;Milestone Name&nbsp;:&nbsp;
          <select name="ms_id" id="ms_id" onchange="javascript:manage_get_edit_latlng_fields(0,document.getElementById('ms_id').value);"/>
          <option value="select">Select</option>';
          <?php
				
				$data=getDetailAllMileStone($groupo_id_local,$DbConnection);            							
				foreach($data as $dt)
				{
					$ms_id=$dt['ms_id'];
					$ms_name=$dt['ms_name'];
					$ms_type=$dt['ms_type'];
					
					echo'<option value='.$ms_id.'>'.$ms_name.' ( '.$ms_type.' )</option>';
				}
  				?>
  		    </select>
      </td>
   </tr>
   <tr>                          
</table>
 <div id="number_of_fields" style="display:none;"></div>
<table>                                     
   </tr>                               
  <tr>
  	<td colspan="3" align="center">
        <input type="button" id="enter_button" value="Update" onclick="javascript:action_manage_milestone('edit');"/>&nbsp;
        <input type="button" value="Delete" onclick="javascript:action_manage_milestone('delete')"/>
    </td>
  </tr>
</table>
</center>
  