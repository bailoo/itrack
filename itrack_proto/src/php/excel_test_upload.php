<?php
echo "test<br>";
set_time_limit(3000);
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
echo "test1<br>";
//require_once 'PHPExcel/IOFactory.php';
require_once 'PHPExcel/Classes/PHPExcel.php';
echo "test2<br>";
$tmp_upload_file="rspl_data_uni/2013-05-RL.xlsx";
echo "test3<br>";
$objPHPExcel = PHPExcel_IOFactory::load($tmp_upload_file);
echo "test4<br>";
$highestColumm = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
echo "test5<br>";
$highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
echo "test6<br>";
echo "highestRow=".$highestRow." highestColumm=".$highestColumm."<br>";
?>



