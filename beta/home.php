<?php
  include_once('src/php/Hierarchy.php');  
//echo "<br>D5";
  include_once('src/php/util_session_variable.php');
//echo "<br>D6";
  include_once("src/php/util_php_mysql_connectivity.php");	
  include_once("src/php/util_account_detail.php");
  if($account_id)
  {
						
	if($user_type=="substation" || $user_type=="raw_milk" || $user_type=="plant_raw_milk" || $user_type=="hindalco_invoice" || $user_type=="plant_admin" || $user_type=="plant_gate"){
		
		//include("src/php/main_substation_home.php");
		include("src/php/main_usertype_home.php");
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
