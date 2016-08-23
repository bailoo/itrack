<?php
include_once("utilSetUnsetSession.php");

$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3]; //local path
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]; //server path

$filePathToS3Wrapper=$pathToRoot."/s3/S3Wrapper.php";
include_once($filePathToS3Wrapper);

$apkType=$_GET['aT'];
$versionName=$_GET['vN'];
$apkHeading=$_GET['aH'];
$downloadFileName=$_GET['dFN'];
//$S3Filename =$_POST['sourceFilePath'];


$S3Filename="android/".$apkType."/".$versionName."/".$apkHeading."/".$downloadFileName;
//echo "serverFilePath=".$S3Filename."<br>";
$sourcefileNameArr=listFile($S3Filename);
//print_r($sourcefileNameArr);
$sourceFilePath=$S3Filename."/".$sourcefileNameArr[0]['name'];

//echo "fileName=".$fileName."<br>";
$destinationFileName=$sourcefileNameArr[0]['name'];
$tmpFilePath="tmpFolder/".$destinationFileName;

$overwrite=true;
$copyResult=copyFile($sourceFilePath,$tmpFilePath,$overwrite);


if(count($sourcefileNameArr)>0)
{
    header('Content-Type: application/jar');
    header('Content-Type: application/apk');
    header('Content-Disposition: attachment; filename="'.$sourcefileNameArr[0]['name'].'"');
    header('Content-Length: ' . filesize ($tmpFilePath));
    readfile($tmpFilePath);
    unlink($tmpFilePath); 
}
else
{
    echo 'file not found';
}
?>