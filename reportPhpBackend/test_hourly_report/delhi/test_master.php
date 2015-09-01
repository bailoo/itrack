<?php
//######### S3 BLOCK OPENS #########
$account_id = 231;
$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//print_r($pathInPieces);
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3]; ////// local path
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]; ////// local path
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]; /// server Path
$filePathToS3Wrapper=$pathToRoot."/s3/S3Wrapper.php";
//$filePathToS3Wrapper="D:\\itrack/s3/S3Wrapper.php";
echo "\nfilePathToS3Wrapper=".file_exists($filePathToS3Wrapper);
//$filePathToS3Wrapper="/mnt/itrack/s3/S3Wrapper.php";

include_once($pathToRoot."/s3/S3Wrapper.php");
echo "\npathToRoot=".$pathToRoot;

$S3DirPath='gps_report/'.$account_id."/master";

//$LocalPath = "D:\\MOTHERDELHI_REPORT/gps_report/".$account_id."/master";
$LocalPath = "/mnt/itrack/beta/src/php/gps_report/test_master";

echo "\nFileExist=".file_exists($filePathToS3Wrapper);
$overwrite = true;
$res = copyDir($S3DirPath, $LocalPath, $overwrite);
echo "\nRes=".$res;
//######### S3 BLOCK CLOSED #########
?>
