<?php
	
	include_once('Hierarchy.php');	
	include_once('util_session_variable.php');	
	include_once('util_php_mysql_connectivity.php');
  
	$DEBUG=0;	
	$root = $_SESSION['root'];
	
	$post_destination_account1=trim($_POST['destination_account']); 
	$tmp_post_destination_account1 = explode(',',$post_destination_account1);
	$post_source_account=trim($_POST['source_account']);	
	
	$third_party_account_id=getThirdPartyIDThirdPartyAccountAssignment($post_source_account,1,$DbConnection);
	if(sizeof($third_party_account_id)>0)
	{		
		$resultUpdate=updateThirdPartyAccountAssignment(0,$date,$account_id,$post_source_account,1,$DbConnection);
	}
	foreach($tmp_post_destination_account1 as $tid)
	{
		$queryInsert="INSERT into third_party_account_assignment (admin_account_id,third_party_account_id,create_date,create_id,status) values($post_source_account,$tid,'$date','$account_id',1)";
		$resultInsert=mysql_query($queryInsert,$DbConnection);
	}
	
	echo "<center><font color=green>Updated Successfully</font></center><br>";
	
  echo'<center><a href="javascript:show_option(\'manage\',\'assign_account_thirdparty\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 
?>
        