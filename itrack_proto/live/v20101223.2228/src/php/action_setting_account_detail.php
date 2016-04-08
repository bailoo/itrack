<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
	
	$flag=0;
	
  $superuser1=$_POST['superuser'];
	$user1=$_POST['user'];
	$grp1=$_POST['grp'];
	//$login_name1=$_POST['login_name'];	
	$user_name1=$_POST['user_name'];	
	$address_1=$_POST['address1'];
	$address_2=$_POST['address2'];
	$city1=$_POST['city'];
	$state1=$_POST['state'];
	$country1=$_POST['country'];
	$zip1=$_POST['zip'];	
	$phoneno1=$_POST['phoneno'];
	$email1=$_POST['email'];
	//$time_zone1=$_POST['time_zone'];

	date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
	$date=date("Y-m-d H:i:s");

	$query="UPDATE account SET email='$email1' WHERE account_id=$account_id";
	//echo "query1=".$query."<br>";
	//print_query($query);
  $result1=mysql_query($query,$DbConnection); 
  	 
  $query="UPDATE account_detail set name='$user_name1',address1='$address_1',address2='$address_2',city='$city1',state='$state1',country='$country1',zip='$zip1',phone='$phoneno1' where account_id=$account_id";
  $result2=mysql_query($query,$DbConnection);	
	//print_query($query);

  //echo "result1=".$result1." result2=".$result2." result3=".$result3." result4=".$result4." result5=".$result5;
	if($result1 && $result2)
	{
	 $flag=1;
  }
	
  if($flag==1)
	{
		echo"<center><br><br><FONT color=\"blue\"><strong>Account Detail Updated Successfully</strong></font></center>";
		//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=add_account.php\">";
	}					
	else if($flag==0)
	{
		echo"<center><br><br><FONT color=\"blue\"><strong>Unable to Update Account Detail due to some server problem!</strong></font></center>";
		//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=add_account.php\">";
	}
	else
	{
	 echo "<center>Sorry! Unable to process request.</center>";
  }
?>
        