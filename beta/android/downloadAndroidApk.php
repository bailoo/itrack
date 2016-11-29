<?php
include_once("utilSetUnsetSession.php");
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
//echo "serverFilePath=".$S3Filename."<br>";
$sourcefileNameArr=listFile($S3Filename);
//print_r($sourcefileNameArr);
$sourceFilePath=$S3Filename."/".$sourcefileNameArr[0]['name'];
//echo "sourceFilePath=".$sourceFilePath."<br>";

$destinationFileName=$sourcefileNameArr[0]['name'];
$tmpFilePath="tmpFolder/".$destinationFileName;
//echo "tmpFilePath=".$tmpFilePath."<br>";

$overwrite=true;
$copyResult=copyFile($sourceFilePath,$tmpFilePath,$overwrite);

if(count($sourcefileNameArr)>0)
{
    if($fd = fopen ($tmpFilePath, "r")) 
    {
        $fsize = filesize($tmpFilePath);
        $path_parts = pathinfo($tmpFilePath);
       
        $path_parts["basename"]=$destinationFileName;
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