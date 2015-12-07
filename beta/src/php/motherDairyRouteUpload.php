<?php
include('util_session_variable.php');
$fileName=$_FILES["routeFile"]["name"];
/*echo "fileName=".$fileName."<br>";
if(file_exists("/var/www/html/vts/uploadFile"))
{
echo "true";
}
else
{
echo "false";
}*/
$uploadFilePath='/mnt/itrack/beta/src/php/client_map_feature_data/'.$account_id;
if(!file_exists($uploadFilePath))
{
    mkdir($uploadFilePath,0777);
}
$final_dest_file=$uploadFilePath.'/'.$fileName;
if (move_uploaded_file($_FILES['routeFile']['tmp_name'], $final_dest_file)) 
{
    echo "File is valid, and was successfully uploaded.\n";
} 
else 
{
    echo "Failed to Upload\n";
}

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
//date_default_timezone_set('Europe/London');
$filePath=$final_dest_file;
require_once 'PHPExcel/IOFactory.php';
$objPHPExcel_1 = null;	

$objPHPExcel_1 = PHPExcel_IOFactory::load($filePath);

	$cellIterator = null;
	$column = null;
	$row = null;
 

	$cnt=0;
	foreach ($objPHPExcel_1->setActiveSheetIndex(0)->getRowIterator() as $row) 
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
				//if($row > $sheet2_row_count)
				if($row >1)
				{
					//echo "duration=".$objPHPExcel_1->getActiveSheet()->getCell("P".$row)->getValue()."<br>";
					//if($objPHPExcel_1->getActiveSheet()->getCell("H".$row)->getValue()!="")
					{
						$vname[$cnt]=trim($objPHPExcel_1->getActiveSheet()->getCell("A".$row)->getValue());
						$cno[$cnt]=trim($objPHPExcel_1->getActiveSheet()->getCell("C".$row)->getValue());
						$rno[$cnt]=trim($objPHPExcel_1->getActiveSheet()->getCell("D".$row)->getValue());
						$plant[$cnt]=trim($objPHPExcel_1->getActiveSheet()->getCell("E".$row)->getValue());
						$ad_at[$cnt]=trim($objPHPExcel_1->getActiveSheet()->getCell("F".$row)->getValue())." ".str_replace("-","",trim($objPHPExcel_1->getActiveSheet()->getCell("G".$row)->getValue()));
						$hd[$cnt]=trim($objPHPExcel_1->getActiveSheet()->getCell("H".$row)->getValue());
						$tname[$cnt]=trim($objPHPExcel_1->getActiveSheet()->getCell("I".$row)->getValue());
						$cnt++;
					}
					break;				
				}			
			}		
		}
	}
	//echo "cnt=".$cnt."<br>";	
	for($x = 1; $x < sizeof($ad_at); $x++)
	{
		$tmp_vname = $vname[$x];
		$tmp_cno = $cno[$x];
		$tmp_rno = $rno[$x];
		$tmp_plant = $plant[$x];
		$tmp_ad_at= $ad_at[$x];
		//echo "ad_at=".$ad_at[$x]."<br>";
		$tmp_hd = $hd[$x];
		$tmp_tname = $tname[$x];
		$z = $x - 1;
		$done = false;
		while($done == false)
		{
			$ad_at_tmp1 = $ad_at[$z];
			//echo "<br>first=".strtotime($ad_at_tmp1)." ,next=".strtotime($tmp_ad_at);
			if($ad_at_tmp1 > $tmp_ad_at)
			{
				$vname[$z + 1] = $vname[$z];
				$cno[$z + 1] = $cno[$z];
				$rno[$z + 1] = $rno[$z];
				$plant[$z + 1] = $plant[$z];
				$ad_at[$z + 1] = $ad_at[$z];
				$hd[$z + 1] = $hd[$z];
				$tname[$z + 1] = $tname[$z];
				
				//////////////////
				$z = $z - 1;
				if ($z < 0)
				{
					$done = true;
				}
			}
			else
			{
				$done = true;
			}
		} //WHILE CLOSED
		$vname[$z + 1] = $tmp_vname;
		$cno[$z + 1] = $tmp_cno;
		$rno[$z + 1] = $tmp_rno;
		$plant[$z + 1] = $tmp_plant;
		$ad_at[$z + 1] = $tmp_ad_at;
		$hd[$z + 1] = $tmp_hd;
		$tname[$z + 1] = $tmp_tname;
	}
	$pathtowrite="/var/www/html/vts/beta/src/php/client_map_feature_data/commonXmlFile.xml";
	$fh = fopen($pathtowrite, 'w') or die("can't open file 1"); // new
	fwrite($fh, "<t1>"); 
	//print_r($ad_dt);
	for($i = 1; $i < sizeof($hd); $i++)
	{
		//echo "hd=".$ad_at[$i]."<br>";
		$linetowrite = "\n<marker vname=\"".$vname[$i].
				"\" cno=\"".$cno[$i].
				"\" rno=\"".$rno[$i].
				"\" plant=\"".$plant[$i].
				"\" adt=\"".$ad_at[$i].
				"\" hd=\"".$hd[$i].
				"\" tname=\"".$tname[$i].
			"\"/>";
			fwrite($fh, $linetowrite);
	}
	
	fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
	fwrite($fh, "\n</t1>");  
	fclose($fh);
	
	/*function sort_all_routes()
	{
		global $Vehicle_CI;
		global $StationNo_CI;
		global $RouteNo_CI;
		global $RouteType_CI;
		global $ArrivalTime_CI;
		global $TransporterI_CI;


		for($x = 1; $x < sizeof($RouteNo_CI); $x++)
		{
			$tmp_vehicle_ci = $Vehicle_CI[$x];
			$tmp_station_ci = trim($StationNo_CI[$x]);
			$tmp_route_ci = $RouteNo_CI[$x];
			$tmp_routetype_ci = $RouteType_CI[$x];
			$tmp_arrival_ci = $ArrivalTime_CI[$x];
			$tmp_transporter_ci = $TransporterI_CI[$x];
			///////////

			$z = $x - 1;
			$done = false;
			while($done == false)
			{
				$route_tmp1 = $RouteNo_CI[$z];
				//echo "<br>RouteTmp1=".$route_tmp1." ,tmp_route_ci=".$tmp_route_ci;
				if (trim($route_tmp1) > trim($tmp_route_ci))
				{
					$Vehicle_CI[$z + 1] = $Vehicle_CI[$z];
					$StationNo_CI[$z + 1] = trim($StationNo_CI[$z]);
					$RouteNo_CI[$z + 1] = trim($RouteNo_CI[$z]);
					$RouteType_CI[$z + 1] = $RouteType_CI[$z];
					$ArrivalTime_CI[$z + 1] = $ArrivalTime_CI[$z];
					$TransporterI_CI[$z + 1] = $TransporterI_CI[$z];
					//////////////////
					$z = $z - 1;
					if ($z < 0)
					{
						$done = true;
					}
				}
				else
				{
					$done = true;
				}
			} //WHILE CLOSED
			$Vehicle_CI[$z + 1] = $tmp_vehicle_ci;
			$StationNo_CI[$z + 1] = $tmp_station_ci;
			$RouteNo_CI[$z + 1] = $tmp_route_ci;
			$RouteType_CI[$z + 1] = $tmp_routetype_ci;
			$ArrivalTime_CI[$z + 1] = $tmp_arrival_ci;
			$TransporterI_CI[$z + 1] = $tmp_transporter_ci;
		}
}*/

	
	
	
	

?>