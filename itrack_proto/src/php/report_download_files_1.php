<?php
$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3]; //local path
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]; //server path

$filePathToS3Wrapper=$pathToRoot."/s3/S3Wrapper.php";
include_once($filePathToS3Wrapper);

$S3Filename =$_POST['download_file_id'];

$fileNameArr=explode("/",$S3Filename);
$fileName=$fileNameArr[sizeof($fileNameArr)-1];
$filePath="master_tmp/".$fileName;
//echo "filePath=".$filePath."<br>";

$overwrite=true;
$copyResult=copyFile($S3Filename,$filePath,$overwrite);

if(file_exists($filePath))
{
    if($fd = fopen ($filePath, "r")) 
    {
        $fsize = filesize($filePath);
        $path_parts = pathinfo($filePath);
        $fnl=explode(".",$path_parts["basename"]);
        $fn2=explode("#",$fnl[0]);
        $path_parts["basename"]=$fn2[0].".".$fnl[1];
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
        unlink($filePath); 
    }
}
else
{
	echo 'file not found';
}
?>