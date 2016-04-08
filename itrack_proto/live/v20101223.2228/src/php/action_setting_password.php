<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
	
	$flag=0;
	
  $superuser1=$_POST['superuser'];
	$user1=$_POST['user'];
	$grp1=$_POST['grp'];
	//$login_name1=$_POST['login_name'];	
	$old_pass1 = md5($_POST['old_pass']);	
	$new_pass1= md5($_POST['new_pass']);	

	date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
	$date=date("Y-m-d H:i:s");

	$query = "SELECT password from account where password='$old_pass1' and account_id=$account_id";
  $result = mysql_query($query);
  $numrows = mysql_num_rows($result);
  //print_query($query);	
  //print_query($numrows);	
  
  
  if($numrows>0)
  {  
    $query="UPDATE account SET password='$new_pass1' WHERE password='$old_pass1' and account_id=$account_id";
  	//print_query($query);
    $result1=mysql_query($query,$DbConnection); 
    //echo "res1=";
    //print_query($result1);	
  	if($result1)
  	{
  	 $flag=1;
    }
  }
  else
  {
    $flag=0;
  }
	
  if($flag==1)
	{
		echo"<center><br><br><FONT color=\"blue\"><strong>Password Updated Successfully</strong></font></center>";		
	}					
	else if($flag==0)
	{
		echo"<center><br><br><FONT color=\"blue\"><strong>Wrong Old password Entered!</strong></font></center>";	
	}
	else
	{
	 echo "<center>Sorry! Unable to process request.</center>";
  }
?>
        