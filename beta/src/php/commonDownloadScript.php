<?php
$fileName=$_REQUEST['downloadFileName'];
$filePath=$_REQUEST['downloadFilePath'];

header('Content-Type: application/apk');
header('Content-Disposition: attachment; filename="'.$fileName.'"');
header('Content-Length: ' . filesize ($filePath));
readfile($filePath);
unlink($filePath); 
return true;
?>