<?php
include_once("utilSetUnsetSession.php");
include_once("utilDatabaseConnectivity.php"); 
if(!$accountIdSession)
{
print"<br><br><br><br><br><br><br><br><br>
    <center>
        <FONT color=\"red\" 
            font size=\"2\" 
            face=\"verdana\">
        
            <strong>
               You are not a authorizes user
            </strong>
        </font>
    </center>";
    //echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">";
    exit();
}
$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3]; //local path
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]; //server path

$filePathToS3Wrapper=$pathToRoot."/s3/S3Wrapper.php";
include_once($filePathToS3Wrapper);

$S3Filename =$_POST['sourceFilePath'];
$sourcefileNameArr=listFile($S3Filename);
$seperatePath=explode("/",$S3Filename);
$sourceFilePath=$S3Filename."/".$sourcefileNameArr[0]['name'];

if(count($sourcefileNameArr)>0)
{
    if(delFile($sourceFilePath))
    {
        echo "S3::File Deleted():".$sourceFilePath."<br>";    
        $apkType = $seperatePath[1];
        $versionName= $seperatePath[2];
        $headingName = $seperatePath[3];
        $downloadFileName=$seperatePath[4];

    
        $results = $mysqli->query("UPDATE android_apk_upload_format SET status=0 WHERE apk_type='$apkType' AND ".
        "apk_version_name='$versionName' AND apk_heading='$headingName' AND download_file_name=".
        "'$downloadFileName' AND status=1");

        if($results)
        {
            print 'Success! record Deleted';
        }
        else
        {
            print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
        }
        $returningPage="deleteApkFile.htm";
        echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=".$returningPage."\">";

    }
    else
    {
        echo "S3::File not Deleted():".$sourceFilePath."\n";
    }
}
else
{
    echo "S3::File Not Found\n";
    echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=".$returningPage."\">";
}


?>

