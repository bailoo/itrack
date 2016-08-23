<?php
    header('Content-Type: application/apk');
    header('Content-Disposition: attachment; filename="PersonTracker_8_21_hr.apk"');
    header('Content-Length: ' . filesize ("tmpFolder/PersonTracker_8_21_hr.apk"));
    readfile("tmpFolder/PersonTracker_8_21_hr.apk");
    //unlink($tmpFilePath); 
    return true;

?>