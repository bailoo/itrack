<?php
$fileName=$_GET['fileName'];
$tmpFilePath="tmpFolder/".$fileName;

header('Content-Type: application/vnd.android.package-archive');
header("Content-length: " . filesize($tmpFilePath));
header('Content-Disposition: attachment; filename="' . $fileName . '"');
ob_end_flush();
readfile($tmpFilePath);
return true;

?>