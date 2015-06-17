<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//echo "file_path=".$file_path."<br>";
	//$file_path1=explode("/",$file_path);
//	$tmp_path=$file_path1[0]."/".$file_path1[1]."/".$file_path1[2]."/";
	//echo "tmp_path=".$tmp_path."<br>";
	if(file_exists($file_path))
	{

		unlink($file_path);
		echo "report_delete_file##".$tr_id;
		//echo "success";
		
	}
	else
	{
		echo "failure";
	}
	
	/*include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	echo "file_path=".$file_path."<br>";
	$file_path1=explode("/",$file_path);
	$tmp_path=$file_path1[0]."/".$file_path1[1]."/".$file_path1[2]."/";
	echo "tmp_path=".$tmp_path."<br>";
	if(file_exists($file_path))
	{
		chmod($tmp_path, 0777);   // decimal; probably incorrect
		unlink($file_path);
		echo "report_delete_file##".$tr_id;
		//echo "success";
		
	}
	else
	{
		echo "failure";
	}*/

?>
