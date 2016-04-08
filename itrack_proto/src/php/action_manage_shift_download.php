<?php
error_reporting(E_ALL);
set_time_limit(1000);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Europe/London');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
require_once 'PHPExcel/IOFactory.php';
include('manage_route_vehicle_substation_inherit.php');

$objPHPExcel_1 = new PHPExcel();  //write new file
/*echo "excel_morning_shift=".$excel_morning_shift."<br>";
echo "excel_evening_shift=".$excel_evening_shift."<br>";*/

$route_input_ZPMM = array();
$route_input_ZPME = array();	

if($excel_morning_shift==1)
{
	$morning_data = array();			
	get_route_detail($account_id, "ZPMM");	
	$route_input_mor = array_unique($route_input_ZPMM);		
	
	for($i=0;$i<sizeof($excel_assigned_mor_vname);$i++) 
	{	
		//echo "excel_assigned_vname=".$excel_assigned_vname[$i]."<br>";
		//echo "excel_morning_shift=".$excel_assigned_shift[$i]."<br>";
		if($download_excel_assigned_mor_shift[$i]!="")
		{	
			$download_excel_update_time_mor[$i] = str_replace(':','#',$download_excel_update_time_mor[$i]);
		
			$morning_data[] = array("LINEDATA"=>$excel_assigned_mor_vname[$i].":".$download_excel_assigned_mor_shift[$i].":".$download_excel_update_time_mor[$i].":".$excel_assigned_mor_userid[$i]);

		}
	}
	
	$morning_obj = new manage_route_vehicle_substation_inherit();
	$tableid = "dummy"; $type="MorningShift";
	$shiftdataconverted_mor = $morning_obj->get_db_route_vehicle_converted_admin($morning_data,$route_input_mor,$tableid,$type);
	$cnt=2;
	$j=1;
	foreach($shiftdataconverted_mor as $arr1)
	{
		$arr1['UPDATETIME'] = str_replace('#',':',$arr1['UPDATETIME']);
		//echo "Route:".$arr1['ROUTE']." ,Vehicles:".$arr1['VEHICLE']." ,UPDATETIME=".$arr1['UPDATETIME']."<BR>";
			
		//echo "in if";
		$objPHPExcel_1->getActiveSheet()->setCellValue('A1', 'SERIAL');
		$objPHPExcel_1->getActiveSheet()->setCellValue('B1', 'ROUTE');
		$objPHPExcel_1->getActiveSheet()->setCellValue('C1', 'VEHICLES');
		$objPHPExcel_1->getActiveSheet()->setCellValue('D1', 'UPDATE TIME');
		$objPHPExcel_1->getActiveSheet()->setCellValue('E1', 'UPDATED BY');			

		$objPHPExcel_1->getActiveSheet()->setCellValue('A'.$cnt,$j);
		$objPHPExcel_1->getActiveSheet()->setCellValue('B'.$cnt,$arr1['ROUTE']);
		$objPHPExcel_1->getActiveSheet()->setCellValue('C'.$cnt,$arr1['VEHICLE']);
		/*if(isset($excel_assigned_mor_remark[$i]))
		{
			$objPHPExcel_1->getActiveSheet()->setCellValue('D'.$cnt,$excel_assigned_mor_remark[$i]);
		}
		else
		{
			$objPHPExcel_1->getActiveSheet()->setCellValue('D'.$cnt,'');
		}*/
		$objPHPExcel_1->getActiveSheet()->setCellValue('D'.$cnt,$arr1['UPDATETIME']);
		$objPHPExcel_1->getActiveSheet()->setCellValue('E'.$cnt,$arr1['USERID']);		
		$j++;
		$cnt++;
	}
		
		//echo date('H:i:s') , " Write to Excel2007 format" , EOL;
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
		$objWriter->save("downloadShiftFile/Morning_Routes.xlsx");
		//echo date('H:i:s') , " File written to " , "downloadShiftFile/MorningRoutes.xlsx" , EOL;
		 $file = "downloadShiftFile/Morning_Routes.xlsx";
			header('Content-Description: File Transfer');
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename='.basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
}

else if($excel_evening_shift==1)
{
	$evening_data = array();
	get_route_detail($account_id, "ZPME");
	$route_input_eve = array_unique($route_input_ZPME);		
	
	for($i=0;$i<sizeof($excel_assigned_ev_vname);$i++) 
	{	
		//echo "excel_assigned_vname=".$excel_assigned_vname[$i]."<br>";
		//echo "excel_morning_shift=".$excel_assigned_shift[$i]."<br>";
		if($download_excel_assigned_ev_shift[$i]!="")
		{	
			$download_excel_update_time_ev[$i] = str_replace(':','#',$download_excel_update_time_ev[$i]);
		
			$evening_data[] = array("LINEDATA"=>$excel_assigned_ev_vname[$i].":".$download_excel_assigned_ev_shift[$i].":".$download_excel_update_time_ev[$i].":".$excel_assigned_ev_userid[$i]);
			/*$route_tmp = str_replace('@','',explode('/',$download_excel_assigned_ev_shift[$i]));
			for($j=0;$j<sizeof($route_tmp);$j++)
			{
				$route_input_ZPME[] = $route_tmp[$j];
			}*/
		}
	}
	
	$evening_obj = new manage_route_vehicle_substation_inherit();
	$tableid = "dummy"; $type="EveningShift";
	$shiftdataconverted_ev = $evening_obj->get_db_route_vehicle_converted_admin($evening_data,$route_input_eve,$tableid,$type);
	$cnt=2;
	$j=1;
	foreach($shiftdataconverted_ev as $arr1)
	{
		$arr1['UPDATETIME'] = str_replace('#',':',$arr1['UPDATETIME']);
		//echo "Route:".$arr1['ROUTE']." ,Vehicles:".$arr1['VEHICLE']." ,UPDATETIME=".$arr1['UPDATETIME']."<BR>";
			
		//echo "in if";
		$objPHPExcel_1->getActiveSheet()->setCellValue('A1', 'SERIAL');
		$objPHPExcel_1->getActiveSheet()->setCellValue('B1', 'ROUTE');
		$objPHPExcel_1->getActiveSheet()->setCellValue('C1', 'VEHICLES');
		$objPHPExcel_1->getActiveSheet()->setCellValue('D1', 'UPDATE TIME');
		$objPHPExcel_1->getActiveSheet()->setCellValue('E1', 'UPDATED BY');		

		$objPHPExcel_1->getActiveSheet()->setCellValue('A'.$cnt,$j);
		$objPHPExcel_1->getActiveSheet()->setCellValue('B'.$cnt,$arr1['ROUTE']);
		$objPHPExcel_1->getActiveSheet()->setCellValue('C'.$cnt,$arr1['VEHICLE']);
		/*if(isset($excel_assigned_mor_remark[$i]))
		{
			$objPHPExcel_1->getActiveSheet()->setCellValue('D'.$cnt,$excel_assigned_mor_remark[$i]);
		}
		else
		{
			$objPHPExcel_1->getActiveSheet()->setCellValue('D'.$cnt,'');
		}*/
		$objPHPExcel_1->getActiveSheet()->setCellValue('D'.$cnt,$arr1['UPDATETIME']);			
		$objPHPExcel_1->getActiveSheet()->setCellValue('E'.$cnt,$arr1['USERID']);			
		$j++;
		$cnt++;
	}
		
		//echo date('H:i:s') , " Write to Excel2007 format" , EOL;
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
		$objWriter->save("downloadShiftFile/Evening_Routes.xlsx");
		//echo date('H:i:s') , " File written to " , "downloadShiftFile/Evening_Routes.xlsx" , EOL;
		 $file = "downloadShiftFile/Evening_Routes.xlsx";
			header('Content-Description: File Transfer');
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename='.basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
}
/*
else if($excel_evening_shift==1)
{
		//echo "in if";
	$objPHPExcel_1->getActiveSheet()->setCellValue('A1', 'Serial');
	$objPHPExcel_1->getActiveSheet()->setCellValue('B1', 'Vehicle Name');
	$objPHPExcel_1->getActiveSheet()->setCellValue('C1', 'Evening Shift');
	$objPHPExcel_1->getActiveSheet()->setCellValue('D1', 'Remark');
	$objPHPExcel_1->getActiveSheet()->setCellValue('E1', 'Evening UpdateTime');
	$cnt=2;
	$j=1;
    for($i=0;$i<sizeof($excel_assigned_ev_vname);$i++) 
	{	
		//echo "excel_assigned_vname=".$excel_assigned_vname[$i]."<br>";
		//echo "excel_morning_shift=".$excel_assigned_shift[$i]."<br>";
		if($download_excel_assigned_ev_shift[$i]!="")
		{
			$objPHPExcel_1->getActiveSheet()->setCellValue('A'.$cnt,$j);
			$objPHPExcel_1->getActiveSheet()->setCellValue('B'.$cnt,$excel_assigned_ev_vname[$i]);
			$objPHPExcel_1->getActiveSheet()->setCellValue('C'.$cnt,$download_excel_assigned_ev_shift[$i]);
			if(isset($excel_assigned_ev_remark[$i]))
			{
				$objPHPExcel_1->getActiveSheet()->setCellValue('D'.$cnt,$excel_assigned_ev_remark[$i]);	
			}
			else
			{
				$objPHPExcel_1->getActiveSheet()->setCellValue('D'.$cnt,'');	
			}
			$objPHPExcel_1->getActiveSheet()->setCellValue('E'.$cnt,$download_excel_update_time_ev[$i]);	
			$j++;
			$cnt++;
		}
   } 
  	
	//echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save("downloadShiftFile/evening.xlsx");
	//echo date('H:i:s') , " File written to " , "downloadShiftFile/morning.xlsx" , EOL;
	 $file = "downloadShiftFile/evening.xlsx";
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
}*/

//############ FUNTION ROUTE DETAIL ####################
function get_route_detail($account_id, $shift_time)
{	
	global $route_input_ZPME;
	global $route_input_ZPMM;
	
	$dir = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master";		
	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false) {			
		$file_tmp = explode("#",$file);
		$file_ext = explode(".",$file_tmp[2]);
		if($file_ext[0]!="")
		{			
			//echo "\nfile_ext[0]=".$file_ext[0]." ,shift_time=".$shift_time;
			if( ($file_ext[0] == "4") && ($shift_time=="ZPME") )		//###### EVENING FILE
			{
				$path = $dir."/".$file;

				$row = 1;
				if (($handle = fopen($path, "r")) !== FALSE)
				{
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
					{
						$num = count($data);
						//echo "<p> $num fields in line $row: <br /></p>\n";
						$row++;
							
						if($num<10)
						{
							continue;
						}
						if($row > 2)
						{
							//$shift_input[] = $data[4];
							//if($data[5]==$user_name){
							$route_input_ZPME[] = $data[6];
							//}								
						}
					}
					fclose($handle);						
				}
			}
			 
			if( ($file_ext[0] == "5") && ($shift_time=="ZPMM") )			//###### MORNING FILE
			{
				$path = $dir."/".$file;
				$row = 1;
				if (($handle = fopen($path, "r")) !== FALSE) {
						
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
						$num = count($data);
						//echo "<p> $num fields in line $row: <br /></p>\n";
						$row++;
							
						if($num<10)
						{
							continue;
						}
						if($row > 2)
						{
							//$shift_input[] = $data[4];
							//if($data[5]==$user_name){
								$route_input_ZPMM[] = $data[6];
							//}								
						}
					}
					fclose($handle);
				}
			}								
		}  //
	}
	closedir($dh);
} //function closed			





?>