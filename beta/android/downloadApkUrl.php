<?php
$fileName=$_GET['fileName'];
$filePath="tmpFolder/".$fileName;

header('Content-Type: application/apk');
header('Content-Disposition: attachment; filename="'.$fileName.'"');
header('Content-Length: ' . filesize ($filePath));
readfile($filePath);
//unlink($tmpFilePath); 
return true;
?>