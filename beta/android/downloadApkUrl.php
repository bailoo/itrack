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
$S3Filename =$_POST['sourceFilePath'];

//echo "serverFilePath=".$S3Filename."<br>";
$S3Filename="android/".$apkType."/".$versionName."/".$apkHeading."/".$downloadFileName;
$sourcefileNameArr=listFile($S3Filename);
print_r($sourcefileNameArr);
$sourceFilePath=$S3Filename."/".$sourcefileNameArr[0]['name'];
//echo "sourceFilePath=".$sourceFilePath."<br>";

$copyFileNameArr=explode("/",$S3Filename);
$copyFileName=$copyFileNameArr[sizeof($copyFileNameArr)-1];
//echo "fileName=".$fileName."<br>";

$tmpFilePath="tmpFolder/".$sourcefileNameArr[0]['name'].".apk";
//echo "tmpFilePath=".$tmpFilePath."<br>";

$overwrite=true;
$copyResult=copyFile($sourceFilePath,$tmpFilePath,$overwrite);

if(count($sourcefileNameArr)>0)
{
    if($fd = fopen ($tmpFilePath, "r")) 
    {
        $fsize = filesize($tmpFilePath);
        $path_parts = pathinfo($tmpFilePath);
       
        $path_parts["basename"]=$destinationFileName.".apk";
        $ext = strtolower($path_parts["extension"]);
		
        switch ($ext) 
        {
            case "pdf":
            header("Content-type: application/pdf"); // add here more headers for diff. extensions
            header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
            break;
            default;
            header("Content-type: application/octet-stream");
            header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
        }
        header("Content-length: $fsize");
        header("Cache-control: private"); //use this to open files directly

        while(!feof($fd)) 
        {
            $buffer = fread($fd, 2048);
            echo $buffer;
        }
        fclose ($fd);
        unlink($tmpFilePath); 
    }
}
else
{
    echo 'file not found';
}
?>