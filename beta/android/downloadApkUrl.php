<?php
$fileName=$_GET['fileName'];
$tmpFilePath="tmpFolder/".$fileName;

if($fd = fopen ($tmpFilePath, "r")) 
{
    $fsize = filesize($tmpFilePath);
    $path_parts = pathinfo($tmpFilePath);

    $path_parts["basename"]=$fileName;
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
    //unlink($tmpFilePath); 
}
else
{
        echo 'file not found';
}

?>