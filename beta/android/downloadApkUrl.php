<?php
$apkType=$_GET['aT'];
$filePath="tmpFolder/".$apkType;

header('Content-Type: application/jar');
header('Content-Type: application/apk');
header('Content-Disposition: attachment; filename="'.$apkType.'"');
header('Content-Length: ' . filesize ($filePath));
readfile($filePath);
//unlink($tmpFilePath); 
return true; 

?>