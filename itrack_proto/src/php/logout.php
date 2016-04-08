<?php
  include_once('util_session_variable.php');
  include_once("util_php_mysql_connectivity.php");	
?>

<?php
  //date_default_timezone_set('Asia/Calcutta');
  $datetime_out = date("Y-m-d H:i:s");

	/*$query="UPDATE log_login SET datetime_out='$datetime_out' WHERE log_id='$log_id'"; 
	$result = mysql_query($query, $DbConnection); */
	// echo "query=".$query."<br>";

	foreach ($_SESSION as $VarName => $Value)  
	{
    unset ($_SESSION[$VarName]);
	}

	session_unset();
	//session_destroy();

  print"<font color=\"Red\" size=4><strong>Logout Successful! Please Wait 1111...</strong></font>";

	session_start();		

  mysql_close();	
  echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">";
?>
