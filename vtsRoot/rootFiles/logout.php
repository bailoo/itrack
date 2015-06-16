<?php
  include_once('src/php/util_session_variable.php');
  include_once("src/php/util_php_mysql_connectivity.php");	
?>

<?php
  //date_default_timezone_set('Asia/Calcutta');
  $datetime_out = date("Y-m-d H:i:s");

	/*$query="UPDATE log_login SET datetime_out='$datetime_out' WHERE log_id='$log_id'"; 
	$result = mysql_query($query, $DbConnection);*/
	// echo "query=".$query."<br>";

	foreach ($_SESSION as $VarName => $Value)  
	{
		unset($_SESSION[$VarName]);
	}
	
	if(file_exists("src/php/client_map_feature_data/".$unique_client_customer))
	{
		unlink("src/php/client_map_feature_data/".$unique_client_customer);
	}
	
	if(file_exists("src/php/client_map_feature_data/".$unique_client_plant))
	{
		unlink("src/php/client_map_feature_data/".$unique_client_plant);
	}
	
	session_unset();
	//session_destroy();

  print"<font color=\"Red\" size=4><strong>Logout Successful! Please Wait ...</strong></font>";
	
	session_start();		

  mysql_close();	
  echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.htm\">";
?>
