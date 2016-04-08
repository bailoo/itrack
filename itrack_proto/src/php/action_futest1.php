<?php

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
echo "1<br>";
$tmpdir="upload_3_test";
mkdir($tmpdir, 0777);
echo "2<br>";
$tmp_file_path=$tmpdir."/".$_FILES["file"]["name"];

copy($_FILES['file']['tmp_name'],$tmp_file_path);
echo "copied succesfully to ".$tmp_file_path."<br/>";
/*if(move_uploaded_file($_FILES['file']['tmp_name'],$tmpdir))
{ 
	echo "copied succesfully to ".$tmpdir."<br/>";
}*/
?>