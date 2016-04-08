		
 <?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];  
	//echo "edit##";
  echo'<div style="height:10px"> </div>';
	include_once('manage_escalation_school.php'); 
	include_once('tree_hierarchy_information.php');
	echo "<form name='manage1'><center>";
	include_once('manage_radio_account.php');  
	echo'<div style="height:10px"> </div> 		
		    <input type="button" value="Enter" onclick="javascript:manage_edit_prev_1(\'src/php/manage_add_escalation_school.php\',\'add\');">&nbsp;			
	   </center>
  </form>';
?>
  

  