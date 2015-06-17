<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('get_group.php'); 
	$group_cnt=0; 
	$root=$_SESSION['root'];  
	echo "edit##";   
  echo'<table align="center">
        <tr>
          <td>
              <fieldset class=\'assignment_manage_fieldset\'>
                <legend>
                    <strong>Group</strong>
                </legend>
                <table border="0" class="manage_interface';GetGroup($root);echo'
                </table>
              </fieldset>	
          </td>
        </tr>
      </table>
	<br>
	<center>		
		<input type="button" value="Enter" onclick="javascript:manage_edit_prev(\'src/php/manage_edit_delete_shift.php\');">&nbsp;			
	</center>';
?>

  