<?php
include_once('util_session_variable.php');
//error_reporting(E_ALL);
set_time_limit(1000);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
date_default_timezone_set('Europe/London');
/** Include PHPExcel_IOFactory */
require_once 'PHPExcel2/Classes/PHPExcel/IOFactory.php';
$date_tmp=substr(date("Y-m-d"),0,-3);
$destfile="daily_report/klp_out/klp_input/".$local_account_id;
$create_file_flag=0;
if(!file_exists($destfile))
{
	mkdir($destfile);
}

$store_file_path=$destfile."/".$date_tmp.".xlsx";

if(!file_exists($store_file_path))
{
	$create_file_flag=1;
}

//echo "create_file_flag".$create_file_flag."<br>";
if($create_file_flag==1)
{
	//echo "in if <br>";
	$objPHPExcel_New = new PHPExcel();  //write new file
	$objPHPExcel_New->getActiveSheet()->setCellValue('A1', 'Vehicle Name');
	$objPHPExcel_New->getActiveSheet()->setCellValue('B1', 'ICD Code');
	$objPHPExcel_New->getActiveSheet()->setCellValue('C1', 'ICD Out Date');
	$objPHPExcel_New->getActiveSheet()->setCellValue('D1', 'ICD Out Time');
	$objPHPExcel_New->getActiveSheet()->setCellValue('E1', 'Factory Code');
	$objPHPExcel_New->getActiveSheet()->setCellValue('F1', 'Factory E.A.D.');
	$objPHPExcel_New->getActiveSheet()->setCellValue('G1', 'Factory E.A.T.');
	$objPHPExcel_New->getActiveSheet()->setCellValue('H1', 'ICD In Date');
	$objPHPExcel_New->getActiveSheet()->setCellValue('I1', 'ICD In Time');
	$objPHPExcel_New->getActiveSheet()->setCellValue('J1', 'Remark');
	$klp_input_values_local=explode(",",substr($klp_input_values,0,-1));
	$tmp_cnt=2;
	for($i=0;$i<sizeof($klp_input_values_local);$i++)
	{
		$klp_input_values_local_1=explode("@",$klp_input_values_local[$i]);
		$in_datetime_tmp=explode(" ",$klp_input_values_local_1[2]);
		$out_datetime_tmp=explode(" ",$klp_input_values_local_1[5]);
		$objPHPExcel_New->getActiveSheet()->setCellValue('A'.$tmp_cnt, $klp_input_values_local_1[0]);
		$objPHPExcel_New->getActiveSheet()->setCellValue('B'.$tmp_cnt, $klp_input_values_local_1[1]);
		if(isset($klp_input_values_local_1[2]))
		{
			$objPHPExcel_New->getActiveSheet()->setCellValue('C'.$tmp_cnt, $in_datetime_tmp[0]); 
			$objPHPExcel_New->getActiveSheet()->setCellValue('D'.$tmp_cnt, $in_datetime_tmp[1]);
		}
		$objPHPExcel_New->getActiveSheet()->setCellValue('E'.$tmp_cnt, $klp_input_values_local_1[3]);
		
		$factory_ea_datetime_tmp=explode(" ",$klp_input_values_local_1[4]);
		$objPHPExcel_New->getActiveSheet()->setCellValue('F'.$tmp_cnt, $factory_ea_datetime_tmp[0]);
		$objPHPExcel_New->getActiveSheet()->setCellValue('G'.$tmp_cnt, $klp_input_values_local_1[1]);		
		if(isset($klp_input_values_local_1[5]))
		{
			$objPHPExcel_New->getActiveSheet()->setCellValue('H'.$tmp_cnt, $out_datetime_tmp[0]);
			$objPHPExcel_New->getActiveSheet()->setCellValue('I'.$tmp_cnt, $out_datetime_tmp[1]);
		}
		$objPHPExcel_New->getActiveSheet()->setCellValue('J'.$tmp_cnt, $klp_input_values_local_1[6]);
		$tmp_cnt++;
	}	

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_New, 'Excel2007');
	$objWriter->save($store_file_path);
	
	echo"<br><br><br>
		<center>
			<font color='green'> 
				<b>
					Inputs Store Successfully.
				</b>
			</font>
		</center>";
}
else if($create_file_flag==0)
{
	//echo "in else <br>";
	$objPHPExcel_Old = PHPExcel_IOFactory::load($store_file_path);
	
	/*$highestColumm = $objPHPExcel->setActiveSheetIndex(5)->getHighestColumn();
	$highestRow = $objPHPExcel->setActiveSheetIndex(5)->getHighestRow();
	echo 'getHighestColumn() =  [' . $highestColumm . ']<br/>';
	echo 'getHighestRow() =  [' . $highestRow . ']<br/>';*/
	$klp_input_values_local=explode(",",substr($klp_input_values,0,-1));
	$cell_insert_no="";
	$insert_flag=0;
	$error_message=0;
	$vehicle_name_tmp
	for($i=0;$i<sizeof($klp_input_values_local);$i++)
	{
		$klp_input_values_local_1=explode("@",$klp_input_values_local[$i]);
		$vehicle_name_tmp_1="";
		$highestRow="";
		$highestRow = $objPHPExcel_Old->setActiveSheetIndex(0)->getHighestRow();
		$update_flag=0;
		$cmp_in_datetime="";
		foreach ($objPHPExcel_Old->setActiveSheetIndex(0)->getRowIterator() as $row) 
		{
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);	
			$j=0;
			foreach ($cellIterator as $cell) 
			{
				if (!is_null($cell)) 
				{
					$column = $cell->getColumn();
					$row = $cell->getRow();
					//if($row>1 && $row<=50)			
					if($row>1)
					{
						$vehicle_name_tmp=$objPHPExcel_Old->getActiveSheet()->getCell("A".$row)->getValue();
						$excel_icd_in_date=$objPHPExcel_Old->getActiveSheet()->getCell("H".$row)->getValue();
						$excel_icd_in_time=$objPHPExcel_Old->getActiveSheet()->getCell("I".$row)->getValue();
						if($klp_input_values_local_1[0]==$vehicle_name_tmp)
						{
							$vehicle_name_tmp_1=$vehicle_name_tmp;								
							if($excel_icd_in_date!="" && $excel_icd_in_time!="")
							{
								$cmp_in_datetime=$excel_icd_in_date." ".$excel_icd_in_time;
							}
							if($excel_icd_in_date=="" && $excel_icd_in_time=="")
							{
								if($klp_input_values_local_1[2]<$cmp_in_datetime)
								{
								   $error_message=1;
							    }
								else
								{								   
									$update_flag=1;
									$in_datetime_tmp="";
									$out_datetime_tmp="";
									$in_datetime_tmp=explode(" ",$klp_input_values_local_1[2]);
									$out_datetime_tmp=explode(" ",$klp_input_values_local_1[5]);
									$objPHPExcel_Old->getActiveSheet()->setCellValue('A'.$row, $klp_input_values_local_1[0]);
									$objPHPExcel_Old->getActiveSheet()->setCellValue('B'.$row, $klp_input_values_local_1[1]);
									if(isset($klp_input_values_local_1[2]))
									{
										$objPHPExcel_Old->getActiveSheet()->setCellValue('C'.$row, $in_datetime_tmp[0]); 
										$objPHPExcel_Old->getActiveSheet()->setCellValue('D'.$row, $in_datetime_tmp[1]);
									}									
									$objPHPExcel_Old->getActiveSheet()->setCellValue('E'.$row, $klp_input_values_local_1[3]);
									$factory_ea_datetime_tmp=explode(" ",$klp_input_values_local_1[4]);
									$objPHPExcel_Old->getActiveSheet()->setCellValue('F'.$row, $factory_ea_datetime_tmp[0]);
									$objPHPExcel_Old->getActiveSheet()->setCellValue('G'.$row, $factory_ea_datetime_tmp[1]);
									if(isset($klp_input_values_local_1[5]))
									{
										$objPHPExcel_Old->getActiveSheet()->setCellValue('H'.$row, $out_datetime_tmp[0]);
										$objPHPExcel_Old->getActiveSheet()->setCellValue('I'.$row, $out_datetime_tmp[1]);
									}
									$objPHPExcel_Old->getActiveSheet()->setCellValue('J'.$row, $klp_input_values_local_1[6]);
								}
							}
							break;
						}
						else if($vehicle_name_tmp!="" && $excel_icd_in_time=="")
						{
							$objPHPExcel_Old->getActiveSheet()->removeRow($row);
						}						
					}			
				}		
			} 
		}
		if(($klp_input_values_local_1[0]!=$vehicle_name_tmp_1) || ($update_flag==0))
		{
			if($klp_input_values_local_1[2]<$cmp_in_datetime)
			{
			   $error_message=1;
			}
			else
			{
				$cell_insert_no="";
				$highestRow="";
				$highestRow = $objPHPExcel_Old->setActiveSheetIndex(0)->getHighestRow();
				$cell_insert_no=$highestRow+1;
				$in_datetime_tmp="";
				$out_datetime_tmp="";
				$in_datetime_tmp=explode(" ",$klp_input_values_local_1[2]);
				$out_datetime_tmp=explode(" ",$klp_input_values_local_1[5]);
				$objPHPExcel_Old->getActiveSheet()->setCellValue('A'.$cell_insert_no, $klp_input_values_local_1[0]);
				$objPHPExcel_Old->getActiveSheet()->setCellValue('B'.$cell_insert_no, $klp_input_values_local_1[1]);
				if(isset($klp_input_values_local_1[2]))
				{
					$objPHPExcel_Old->getActiveSheet()->setCellValue('C'.$cell_insert_no, $in_datetime_tmp[0]); 
					$objPHPExcel_Old->getActiveSheet()->setCellValue('D'.$cell_insert_no, $in_datetime_tmp[1]);
				}
				$objPHPExcel_Old->getActiveSheet()->setCellValue('E'.$cell_insert_no, $klp_input_values_local_1[3]);
				$factory_ea_datetime_tmp=explode(" ",$klp_input_values_local_1[4]);
				$objPHPExcel_Old->getActiveSheet()->setCellValue('F'.$cell_insert_no, $factory_ea_datetime_tmp[0]);
				$objPHPExcel_Old->getActiveSheet()->setCellValue('G'.$cell_insert_no, $factory_ea_datetime_tmp[1]);
				if(isset($klp_input_values_local_1[5]))
				{
					$objPHPExcel_Old->getActiveSheet()->setCellValue('H'.$cell_insert_no, $out_datetime_tmp[0]);
					$objPHPExcel_Old->getActiveSheet()->setCellValue('I'.$cell_insert_no, $out_datetime_tmp[1]);
				}
				$objPHPExcel_Old->getActiveSheet()->setCellValue('J'.$cell_insert_no, $klp_input_values_local_1[6]);
			}
		}
	}
	if($error_message==1)
	{
		echo"<br>
			<center>
				<font color='green'> 
					<b>
						Out time should be greater than last in time.
					</b>
				</font>
			</center>";
	}
	else
	{
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_Old, 'Excel2007');
		$objWriter->save($store_file_path);
		
		echo"<br>
			<center>
				<font color='green'> 
					<b>
						Inputs Store Successfully.
					</b>
				</font>
			</center>";
	}
	
}
?>





