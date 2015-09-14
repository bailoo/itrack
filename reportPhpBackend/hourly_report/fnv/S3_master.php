<?php
//######### S3 BLOCK OPENS #########
$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]; /// server Path
$filePathToS3Wrapper=$pathToRoot."/s3/S3Wrapper.php";
include_once($pathToRoot."/s3/S3Wrapper.php");
$S3DirPath='gps_report/'.$account_id."/master";
$LocalPath = "/mnt/itrack/beta/src/php/gps_report/".$account_id."/master";
$overwrite = true;
$res = copyDir($S3DirPath, $LocalPath, $overwrite);
echo "\nRes=".$res;
//######### S3 BLOCK CLOSED #########
?>