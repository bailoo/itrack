<?php
	
	include_once('Hierarchy.php');	
	include_once('util_session_variable.php');	
	include_once('util_php_mysql_connectivity.php');
  
	$DEBUG=0;	
	$root = $_SESSION['root'];
	
	$post_vehicle_id1=trim($_POST['vehicle_id']); 
	$tmp_post_vehicle_id1 = explode(',',$post_vehicle_id1);
	$post_destination_account1=trim($_POST['destination_account']);	
	
	
	$third_party_vehicle_id=getVehicleIdThirdPartyVehicleAssign($account_id,$post_destination_account1,$DbConnection);
	if(sizeof($third_party_vehicle_id)>0)
	{
		$resultUpdate=updateThirdPartyVehicleAssignment($date,$account_id,$account_id,$post_destination_account1,$DbConnection);
		
	}
	
	$resultInsert=insertThirdPartyVehicleAssignment($tmp_post_vehicle_id1,$account_id,$post_destination_account1,$vid,$date,$account_id,$DbConnection);
	
	echo "<center><font color=green>Updated Successfully</font></center><br>";
	
  echo'<center><a href="javascript:show_option(\'manage\',\'assign_vehicle_thirdparty\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 


?>
        