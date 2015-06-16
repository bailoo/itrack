<?php
  include_once('src/php/util_session_variable.php');
  include_once("src/php/util_php_mysql_connectivity.php");	

  if($account_id)
  {
  	include("src/php/main_android_route.php");
  }
  else
  {
  	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
  }
  mysql_close($DbConnection); 
?>
