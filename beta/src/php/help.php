<?php
  include_once('util_session_variable.php');
  include_once("util_php_mysql_connectivity.php");	

  if($account_id)
  {
  	include("main_help.php");
  }
  else
  {
  	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
  }
  mysql_close($DbConnection); 
?>