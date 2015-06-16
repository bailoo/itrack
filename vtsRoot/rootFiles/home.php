<?php
  include_once('src/php/Hierarchy.php');  
  include_once('src/php/util_session_variable.php');
  include_once("src/php/util_php_mysql_connectivity.php");	
  include_once("src/php/util_account_detail.php");
  if($account_id)
  {					
	if($user_type=="substation"){
		
		include("src/php/main_substation_home.php");
		//include("src/php/main_home.php");
	}
	else{
		include("src/php/main_home.php");
	}
  }
  else
  {
  	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
  }
  mysql_close($DbConnection); 
?>