<?php
    
  include_once('src/php/util_session_variable.php');
  include_once("src/php/util_php_mysql_connectivity.php");	
  include_once("src/php/util_account_detail.php");
  if($account_id)
  {
						
		
			include("src/php/report_plot_plant_customer_on_map.php");
		
  }
  else
  {
  	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
  }
  mysql_close($DbConnection); 
?>