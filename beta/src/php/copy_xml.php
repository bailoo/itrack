<?php
	//include_once('SessionVariable.php');
	//include_once("PhpMysqlConnectivity.php");

  $source = $_POST['source']; 
  $dest = $_POST['dest'];

  $res = copy($source,$dest);                  
  //echo "copied=".$res;

?>