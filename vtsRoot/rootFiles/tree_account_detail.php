<?php
  //include_once('src/php/util_session_variable.php');
  include_once("src/php/util_php_mysql_connectivity.php");
  include_once("src/php/util_computer_info.php");
  require_once 'Hierarchy.php'; 
  session_start();  
  $root_1 = $_SESSION['root_1'];
  //$account_id_local1=$_GET['account_id_local'];
  //echo "account_id_local1=".$account_id_local1;
  echo "account_name=".$root_1->data->AccountName;
?>
