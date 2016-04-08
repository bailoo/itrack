<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
        include_once('tree_hierarchy_information.php');
	
        //include('coreDb.php');
	$root=$_SESSION['root'];
	$DEBUG=0;
	$parameter_type="polyline";
	echo "edit##";        
	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	//echo "coomon_id=".$common_id1;	
	//common_id
       
?>
<br> 
<center>
<table border="0" cellspacing="2" cellpadding="2" width="100%" align="center">
    <tr>
        <td align="center">&nbsp;Polyline/Route Name&nbsp;:&nbsp;
          <select name="polyline_id" id="polyline_id">
          <option value="0">Select</option>';
          <?php
			
			$data=getDetailAllPolyline($common_id1,$DbConnection);            							
			foreach($data as $dt)
			{
				$polyline_id=$dt['polyline_id'];
				$polyline_name=$dt['polyline_name'];
				echo'<option value='.$polyline_id.'>'.$polyline_name.'</option>';
			}
                ?>
        </select>
      </td>
   </tr>
   <tr>                          
      <td align="center">
        <?php  include_once('manage_radio_account_exclude.php'); ?>
    </td>                                
   </tr>                               
  <tr>
  	<td align="center"><input type="button" id="enter_button" value="Register" onclick="javascript:action_manage_polyline('register')"/></td>
  </tr>
  </table>
</center>
 