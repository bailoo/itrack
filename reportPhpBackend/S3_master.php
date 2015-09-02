<?php
//######### S3 BLOCK OPENS #########
$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
if($DEBUG_OFFLINE) {
    $pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]; /// server Path
} else {
    $pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]; /// server Path
}
//echo "\nONE=".$pathToRoot;
$filePathToS3Wrapper=$pathToRoot."/s3/S3Wrapper.php";
//echo "\nTWO=".$filePathToS3Wrapper;
include_once($pathToRoot."/s3/S3Wrapper.php");
//echo "\nTHREE";
$S3DirPath='gps_report/'.$account_id."/master";
if($DEBUG_OFFLINE) {
    $LocalPath = "D:\\MOTHERDELHI_REPORT/gps_report/".$account_id."/master";   
} else {
    $LocalPath = "/mnt/itrack/beta/src/php/gps_report/".$account_id."/master";
}
//echo "\nFOUR=,exit1=".file_exists($LocalPath);
$overwrite = true;
$res = copyDir($S3DirPath, $LocalPath, $overwrite);
echo "\nRes=".$res;
//######### S3 BLOCK CLOSED #########
?>