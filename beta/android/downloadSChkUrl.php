<?php

$fileName=$_GET['fileName'];
$tmpFilePath="/mnt/android_tmp/schk/".$fileName;

header('Content-Type: application/vnd.android.package-archive');
header("Content-length: " . filesize($tmpFilePath));
header('Content-Disposition: attachment; filename="' . $fileName . '"');
ob_end_flush();
readfile($tmpFilePath);
return true;

?>
