<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
	
	$flag=0;
	
  $superuser1=$_POST['superuser'];
	$user1=$_POST['user'];
	$grp1=$_POST['grp'];
	//$login_name1=$_POST['login_name'];	
	$latlng1=$_POST['latlng'];	
	$refresh_rate1=$_POST['refresh_rate'];

	date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
	$date=date("Y-m-d H:i:s");

	$query="UPDATE account_preference SET latlng=$latlng1,refresh_rate=$refresh_rate1 WHERE account_id=$account_id";
	//print_query($query);
  $result1=mysql_query($query,$DbConnection); 
  	
	if($result1)
	{
	 $flag=1;
  }
	
  if($flag==1)
	{
		echo"<center><br><br><FONT color=\"blue\"><strong>Feature Preference Detail Updated Successfully</strong></font></center>";
		//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=add_account.php\">";
	}					
	else if($flag==0)
	{
		echo"<center><br><br><FONT color=\"blue\"><strong>Unable to Update Feautre Preference Detail due to some server problem!</strong></font></center>";
		//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=add_account.php\">";
	}
	else
	{
	 echo "<center>Sorry! Unable to process request.</center>";
  }
?>
        