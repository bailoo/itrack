<?php
//include_once('main_vehicle_information_1.php');
//include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

//$pathtowrite = $_REQUEST['xml_file_station'];
$vserial1 = $_REQUEST['vserial'];
$xls_date1 = $_REQUEST['xls_date'];
$xls_shift1 = $_REQUEST['xls_shift'];

$vserial1 = "862170018366334";
$xls_date1 = "2013-08-20";
$xls_shift1 = "ev1";
$account_id = "231";

//$vehicle_info = get_vehicle_info($root,$vserial1);
//$vehicle_detail_local = explode(",",$vehicle_info);
//$vname = $vehicle_detail_local[0];
//$vname = "DL1GC1003";
$vname = "HR14E0682";

echo "<br>vname".$vname;
switch($account_id)
{
	case "231" :
		$account_name = "delhi";
		break;
	case "723" :
		$account_name = "ice_cream";
		break;
	case "322" :
		$account_name = "mumbai";
		break;
	case "568" :
		$account_name = "tanker";
		break;				
}
$dir = "/daily_report_ln/motherdairy/".$account_name."/excel_reports/".$xls_date1;
echo "<br>Dir=".$dir." ,SHIFT=".$xls_shift1;
$dh = opendir($dir);
while (($file = readdir($dh)) !== false) {
	//echo "<A HREF=\"$file\">$file</A><BR>\n";
	$file_tmp = explode("#",$file);
	$file_ext = explode(".",$file_tmp[1]);
	if($file_ext[0]!="")
	{
		echo "<br>file_ext=".$file_ext[0];
		if($file_ext[0] == $xls_shift1)
		{
			$xls_path = $dir."/".$file;
			break;
		}
	}
}

//$xls_path = "/var/www/html/vts/test1/src/php/test_xls.xls";
echo "<br>xls_path=".$xls_path."<br>";
//############ READ XLS DATA ###############
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
$abspath = "/var/www/html/vts/beta/src/php";
require_once $abspath.'/PHPExcel/IOFactory.php';
//require_once '../PHPExcel/Classes/PHPExcel.php';
$objPHPExcel = PHPExcel_IOFactory::load($xls_path);

$highestColumm = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
$highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
//echo 'getHighestColumn() =  [' . $highestColumm . ']<br/>';
//echo 'getHighestRow() =  [' . $highestRow . ']<br/>';
		
$highestRow = 0;
$no = $highestRow+1;
/*
echo "\nNo=".$no."\n";
echo "\nColor:A2=".$objPHPExcel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->getARGB();
echo "\nColor:A3=".$objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->getARGB();
echo "\nColor:A4=".$objPHPExcel->getActiveSheet()->getStyle('A4')->getFill()->getStartColor()->getARGB();
echo "\nColor:A5=".$objPHPExcel->getActiveSheet()->getStyle('A5')->getFill()->getStartColor()->getARGB();
echo "\nColor:A6=".$objPHPExcel->getActiveSheet()->getStyle('A6')->getFill()->getStartColor()->getARGB();

echo "\nColor:B2=".$objPHPExcel->getActiveSheet()->getStyle('B2')->getFill()->getStartColor()->getARGB();
echo "\nColor:B3=".$objPHPExcel->getActiveSheet()->getStyle('B3')->getFill()->getStartColor()->getARGB();
echo "\nColor:B4=".$objPHPExcel->getActiveSheet()->getStyle('B4')->getFill()->getStartColor()->getARGB();
echo "\nColor:B5=".$objPHPExcel->getActiveSheet()->getStyle('B5')->getFill()->getStartColor()->getARGB();
echo "\nColor:B6=".$objPHPExcel->getActiveSheet()->getStyle('B6')->getFill()->getStartColor()->getARGB();
echo "\nA2=".$objPHPExcel->getActiveSheet()->getCell('A2')->getValue();
echo "\nC2=".$objPHPExcel->getActiveSheet()->getCell('C2')->getValue();
*/
/*
Color:A2=FF99CCFF - light blue
Color:A3=FFFFFFFF - white
Color:A4=FFFF99CC - pink
Color:A5=FFFFFF99 - yellow
Color:A6=FFFF0000 - red
*/

$r=0;
$statino_str = "";
foreach ($objPHPExcel->setActiveSheetIndex(0)->getRowIterator() as $row) 
{
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);
	$i=0;
    foreach ($cellIterator as $cell) 
	{
        if (!is_null($cell)) 
		{
			$column = $cell->getColumn();
			$row = $cell->getRow();
			//if($row>1 && $row<=50)
			if($r > 0)
			{				
				$cell_name="A".$row;
				$vehicle_tmp=$objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();
				//echo "\nVal=".$vehicle_tmp."<br>";
				
				if(trim($vehicle_tmp) == trim($vname))
				{										
					//echo "<br>VehicleFound";
					//$cell_name = "A".$row;
					$color_code = $objPHPExcel->getActiveSheet()->getStyle($cell_name)->getFill()->getStartColor()->getARGB();
					
					if(trim($color_code)== "FF99CCFF") $color[] = "blue";										
					else if(trim($color_code)== "FFFFFFFF") $color[] = "white";
					else if(trim($color_code)== "FFFF99CC") $color[] = "pink";
					else if(trim($color_code)== "FFFFFF99") $color[] = "yellow";
					else if(trim($color_code)== "FFFF0000") $color[] = "red";
					
					$vehicle[] = $vname;
					
					$cell_name="C".$row;
					$station_no_tmp = $objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();
					$station_str = $station_str.$station_no_tmp.",";
					$station[] = $station_no_tmp;
					//echo "\nVal=".$station_no_tmp."<br>";

					$cell_name="D".$row;
					$type[] = $objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();
					//echo "\nVal=".$cell_value."<br>";
					
					$cell_name="E".$row;
					$route_no[] = $objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();
					//echo "\nVal=".$cell_value."<br>";

					$cell_name="F".$row;
					$report_shift[] = $objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();
					//echo "\nVal=".$cell_value."<br>";

					$cell_name="G".$row;
					$arrival_date_tmp =$objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();
					$cell_name="H".$row;
					$arrival_time_tmp =$objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();					
					$arrival_time[] = $arrival_date_tmp." ".$arrival_time_tmp;

					$cell_name="I".$row;
					$departure_date_tmp =$objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();
					$cell_name="J".$row;
					$departure_time_tmp =$objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();					
					$departure_time[] = $departure_date_tmp." ".$departure_time_tmp;
					
					$cell_name="K".$row;
					$schedule_time[]=$objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();					

					$cell_name="L".$row;
					$delay[]= $objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();
					
					$cell_name="M".$row;
					$halt_duration[]= $objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();

					$cell_name="N".$row;
					$report_date1_tmp = $objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();
					$cell_name="O".$row;
					$report_time1_tmp = $objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();
					$report_date1[] = $report_date1_tmp." ".$report_time1_tmp;

					$cell_name="P".$row;
					$report_date2_tmp = $objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();
					$cell_name="Q".$row;
					$report_time2_tmp = $objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();
					$report_date2[] = $report_date2_tmp." ".$report_time2_tmp;
					
					$cell_name="R".$row;
					$transporter_m[]= $objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();
					
					$cell_name="S".$row;
					$transporter_i[]= $objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();

					$cell_name="T".$row;
					$plant[]= $objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();

					$cell_name="U".$row;
					$km[]= $objPHPExcel->getActiveSheet()->getCell($cell_name)->getValue();		
					break;
				}				
				//$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, "SHEET2 AFTER");
			}			
		}		
    }
	$r++;
}


$station_str = substr($station_str,0,-1);
$query = "SELECT DISTINCT customer_no,station_coord FROM station WHERE customer_no IN($station_str) AND user_account_id='$account_id' AND status=1";
echo "<br>".$query;
$result = mysql_query($query, $DbConnection);

while($row = mysql_fetch_object($result))
{
	$station_tmp[] = $row->customer_no;
	$coord = $row->station_coord;
	$coord1 = explode(',',$coord);
  	$lat[]= trim($coord1[0]);
  	$lng[]= trim($coord1[1]);
	echo "<br>Query executed:".$coord;
}

$current_time = date('Y-m-d H:i:s');
$time_c = strtotime($current_time);
$pathtowrite = "/var/www/html/itrack_vts/xml_tmp/filtered_xml/tmp_customer".$time_c.".xml";
//$fh = fopen($pathtowrite, 'w') or die("can't open file 1"); // new
//fwrite($fh, "<t1>");  
//fclose($fh);

//$fh = fopen($pathtowrite, 'a') or die("can't open file pathtowrite"); //append 

for($i=0;$i<sizeof($station_tmp);$i++)
{
	for($j=0;$j<sizeof($vehicle);$j++)
	{
		echo "<br>station_tmp=".$station_tmp[$i]." ,station=".$station[$j];
		if(($station_tmp[$i] == $station[$j]) && ( ($lat[$i]!="") && ($lng[$i]!="")) )
		{
			$linetowrite = "\n< marker color=\"".$color[$i]."\" lat=\"".trim($lat[$i])."\" lng=\"".trim($lat[$i])."\" vehicle=\"".$vehicle[$j]."\" station_no=\"".$station[$j]."\" type=\"".$type[$j]."\" route_no=\"".$route_no[$j]."\" report_shift=\"".$report_shift[$j]."\" arrival_time=\"".$arrival_time[$j]."\" departure_time=\"".$departure_time[$j]."\" schedule_time=\"".$schedule_time[$j]."\" delay=\"".$delay[$j]."\" halt_duration=\"".$halt_duration[$j]."\" report_date1=\"".$report_date1[$j]."\" report_date2=\"".$report_date2[$j]."\" transporter_m=\"".$transporter_m[$j]."\" transporter_i=\"".$transporter_i[$j]."\" plant=\"".$plant[$j]."\" km=\"".$km[$j]."\"/>";								
			echo "<br>".$linetowrite;
			//fwrite($fh, $linetowrite);			
			break;
		}
	}
}

//fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
//fwrite($fh, "\n</t1>");  
//fclose($fh);

//echo $pathtowrite;

//######### READ XLS DATA CLOSED ###########
