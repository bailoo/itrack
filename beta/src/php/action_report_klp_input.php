<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$report_date=str_replace("/","-",$report_date);
	$date_tmp=substr($report_date,0,-3);
	$store_file_path="daily_report/klp_out/klp_input/".$account_id_local."/".$date_tmp.".xlsx";
	//echo "store_file_path=".$store_file_path."<br>";
	
	
	if(file_exists($store_file_path))
	{
		//echo "in if";
		//error_reporting(E_ALL);
		set_time_limit(1000);
		//ini_set('display_errors', TRUE);
		//ini_set('display_startup_errors', TRUE);
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		date_default_timezone_set('Europe/London');
		/** Include PHPExcel_IOFactory */	
		require_once 'PHPExcel2/Classes/PHPExcel/IOFactory.php';		
		$objPHPExcel_Old = PHPExcel_IOFactory::load($store_file_path);
		//echo "in if <br>";
		$csv_string = "";
		 echo'<br>
		 <table align="center">
			<tr>
				<td class="text" align="center">
					<b>'.$title.'</b> <div style="height:8px;"></div>
				</td>
			</tr>
        </table>';
	   echo'<form method = "post" target="_blank">';
		echo'<table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
				<tr>			
					<td class="text" align="left">
						<strong>
							&nbsp;Serial
						</strong>
					</td>
					<td class="text" align="left">
						<strong>
							&nbsp;Vehicle Name
						</strong>
					</td>
					<td class="text" align="left">
						<strong>
							&nbsp;ICD Code
						</strong>
					</td>
					<td class="text" align="left">
						<strong>
							&nbsp;ICD Out Date Time
						</strong>
					</td>						
					<td>
						<strong>
							&nbsp;Factory Code
						</strong>
					</td>
					<td class="text" align="left">
						<strong>
							&nbsp;Factory E.A.T.
						</strong>
					</td>
					<td class="text" align="left">
						<strong>
							&nbsp;ICD In Date Time
						</strong>
					</td>							
					<td class="text" align="left">
						<strong>
							&nbsp;Remark
						</strong>
					</td>
				</tr>';
				echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
				$csv_string = $csv_string.$title."\n";
				$csv_string = $csv_string."Serial,Vehicle Name,ICD Code,ICD Out Date Time,Factory Code,Factory E.A.T.,ICD In Date Time,Remark\n"; 
		$cnt=1;
		$c_p_cnt=0;
		foreach ($objPHPExcel_Old->setActiveSheetIndex(0)->getRowIterator() as $row) 
		{
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);	
			foreach ($cellIterator as $cell) 
			{
				if (!is_null($cell)) 
				{
					$column = $cell->getColumn();
					$row = $cell->getRow();
					//if($row>1 && $row<=50)			
					if($row>1)
					{
						if($objPHPExcel_Old->getActiveSheet()->getCell("G".$row)->getValue()!="" && $objPHPExcel_Old->getActiveSheet()->getCell("H".$row)->getValue()!="")
						{
							$vehicle_name_local=$objPHPExcel_Old->getActiveSheet()->getCell("A".$row)->getValue();
							$icd_code_local=$objPHPExcel_Old->getActiveSheet()->getCell("B".$row)->getValue();
							$icd_out_datetime_local=$objPHPExcel_Old->getActiveSheet()->getCell("C".$row)->getValue()." ".$objPHPExcel_Old->getActiveSheet()->getCell("D".$row)->getValue();
							$factory_code_local=$objPHPExcel_Old->getActiveSheet()->getCell("E".$row)->getValue();
							$factory_e_a_t_local=$objPHPExcel_Old->getActiveSheet()->getCell("F".$row)->getValue();
							$icd_in_datetime_local=$objPHPExcel_Old->getActiveSheet()->getCell("G".$row)->getValue()." ".$objPHPExcel_Old->getActiveSheet()->getCell("H".$row)->getValue();
							$remark_local=$objPHPExcel_Old->getActiveSheet()->getCell("I".$row)->getValue();
						echo'<tr>
								<td align="left">
									'.$cnt.'
								</td>
								<td align="left">
									'.$vehicle_name_local.'
								</td>
								<td>
									&nbsp;'.$icd_code_local.'
								</td>
								<td>
									&nbsp;'.$icd_out_datetime_local.'
								</td>
								<td>
									&nbsp;'.$factory_code_local.'
								</td>
								<td>
									&nbsp;'.$factory_e_a_t_local.'
								</td>
								<td>
									&nbsp;'.$icd_in_datetime_local.'
								</td>
								<td>
									&nbsp;'.$remark_local.'
								</td>									
							</tr>';
							echo"<input TYPE=\"hidden\" VALUE=\"$cnt\" NAME=\"temp[$c_p_cnt][Serial]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$vehicle_name_local\" NAME=\"temp[$c_p_cnt][Vehicle Name]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$icd_code_local\" NAME=\"temp[$c_p_cnt][Icd Code]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$icd_out_datetime_local\" NAME=\"temp[$c_p_cnt][Icd Out Date Time]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$factory_code_local\" NAME=\"temp[$c_p_cnt][Factory Code]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$factory_e_a_t_local\" NAME=\"temp[$c_p_cnt][Factory E.A.T.]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$icd_in_datetime_local\" NAME=\"temp[$c_p_cnt][Icd In Date Time]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$remark_local\" NAME=\"temp[$c_p_cnt][Remark]\">";
							$csv_string = $csv_string.$cnt.",".$vehicle_name_local.",".$icd_code_local.",".$icd_out_datetime_local.",".$factory_code_local.",".$factory_e_a_t_local.",".$icd_in_datetime_local.",".$remark_local."\n";
							$cnt++;
							$c_p_cnt++;
						}
						break;
					}			
				}		
			} 
		}
		echo '</table>
				<input TYPE="hidden" VALUE="full data" NAME="csv_type">
				<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">
				<br>
				<center>
					<input type="button" onclick="javascript:report_pdf_csv(\'src/php/report_getpdf_type3.php?size='.$c_p_cnt.'\');" value="Get PDF" class="noprint">
					&nbsp;<input type="button" onclick="javascript:report_pdf_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
					<!--<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;-->
				<center>
			</form>';  
	}
	else
	{
			echo"<br>
		<center>
			<font color='red'> 
				<b>
					No Data Found.
				</b>
			</font>
		</center>";
	
	}
	
?>  
