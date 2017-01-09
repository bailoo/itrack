<?php
  include_once('src/php/util_session_variable.php');
  include_once("src/php/util_php_mysql_connectivity.php");	
  include_once("src/php/util_account_detail.php");
  if($account_id)
  {
	if($user_type=="raw_milk" || $user_type=="plant_gate"){			
		include("src/php/report_raw_milk.php");			
	}
	else if($user_type=="hindalco_invoice"){		
		include("src/php/report_hindalco_invoice.php");
	}		
	else{
		include("src/php/main_report.php");
	}
  	//include("src/php/main_report.php");
  }
  else
  {
  	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
  }
  mysql_close($DbConnection); 
?>