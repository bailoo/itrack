<?php
	$HOST = "localhost";
	$DBASE = "iespl_vts_beta";
	$USER = "root";
	$PASSWD = "mysql";
	$account_id = "715";
	//echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
	$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
	mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
	
	$abspath = "/var/www/html/vts/beta/src/php";
	//$abspath = "D:\\test_app";
	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	require_once $abspath.'/PHPExcel/IOFactory.php';
	
	echo "\nREAD_SENT_FILE";
	//echo "\nPath=".$path;
	$file_path = $abspath."/daily_report/klp_out/LOCATION_MASTER.xls";
	//echo "\nFilePath=".$file_path;
	//$file_path = "C:\\xampp/htdocs/klp_out/LOCATION_MASTER.xls";
	//######### SENT FILE NAME CLOSED 			
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = PHPExcel_IOFactory::load($file_path);	

	$cellIterator = null;
	$column = null;
	$row = null;

	//################ FIRST TAB ############################################
	$read_completed = false;
	$read_red = false;
	foreach ($objPHPExcel_1->setActiveSheetIndex(0)->getRowIterator() as $row) 
	{
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false);
		$i=0;
		foreach ($cellIterator as $cell) 
		{
			//if (!is_null($cell)) 
			//{
				$column = $cell->getColumn();
				$row = $cell->getRow();
				//if($row > $sheet2_row_count)				
				
				$tmp_val="A".$row;
				$location_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();

				if($location_tmp=="")
				{				
					break;
				}				
				
				if($row>1)
				{
					//echo "\nRecord:".$row;
					$location_id[] = $location_tmp;
					//echo "\nRow=".$row." ,Val=".$objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					
					$tmp_val="B".$row;
					$location_name[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="C".$row;
					$distance[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					//echo "\nRow=".$row." read";
					break;
				}				
			//}		
		}
		if($read_completed)
		{
			break;
		}
	}

//echo "\nSize=".sizeof($location_id);
for($i=0;$i<sizeof($location_id);$i++)
{
	$query = "INSERT INTO location(location_id,location_name,distance,create_id,create_date,status) ".
	"values('$location_id[$i]','$location_name[$i]','$distance[$i]',715,'2014-04-07 18:22:00',1)";
	echo $query."\n";
	$result = mysql_query($query,$DbConnection);
}	

?>