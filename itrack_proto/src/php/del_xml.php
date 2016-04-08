<?php
	//include_once('src/php/util_session_variable.php');
	//include_once('src/php/util_php_mysql_connectivity.php');
	include_once('src/php/util_session_variable.php');
	include_once('src/php/util_php_mysql_connectivity.php');	
  $dest = $_POST['dest'];
  //echo "dest=".$dest;
  unlink($dest);

?>