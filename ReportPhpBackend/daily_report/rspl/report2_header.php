<?php
//##### DEFINE REPORT HEADER #########
$c1 = "D";
$c2 = "E";
$c3 = "F";
$c4 = "G";
$c5 = "H";
$c6 = "I";
$c7 = "J";
$c8 = "K";
$c9 =  "L";
$c10 = "M";
$c11 = "N";
$c12 = "O";
$c13 = "P";
$c14 = "Q";
$c15 = "R";
$c16 = "S";
$c17 = "T";
$c18 = "U";
$c19 = "V";
$c20 = "W";
$c21 = "X";
$c22 = "Y";
$c23 = "Z";
$c24 = "AA";
$c25 = "AB";
$c26 = "AC";
$c27 = "AD";
$c28 = "AE";
$c29 = "AF";
$c30 = "AG";
$c31 = "AH";

$ac_total = "AI";
$na_total = "AJ";
$ng_total = "AK";
$remark = "AL";

//###### DEFINE BORDER STYLE ############
$borderArray = array(
	'borders' => array(
             'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000')
             )
	)
);	

//###### DEFINE HEADER STYLE
$styleHeader = array(
	'font'  => array(
		'bold'  => true,
		//'color' => array('rgb' => 'FF0000'), //RED
		'color' => array('rgb' => '000000'), //BLACK
		//'color' => array('rgb' => 'FFFFFF'), //WHITE
		'size'  => 11,
		//'name'  => 'Verdana',
				
		/*'alignment' => array(
		'wrap'       => true,	
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		)*/		

		/*'borders' => array(
				 'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('argb' => '000000')
				 )
		)*/
		
	),
	'borders' => array(
			'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
	)			
);

//### DEFINE TEXT STYLE
$styleText = array(
	'font'  => array(
		//'bold'  => true,
		//'color' => array('rgb' => 'FF0000'), //RED
		'color' => array('rgb' => '000000'), //BLACK
		//'color' => array('rgb' => 'FFFFFF'), //WHITE
		'size'  => 11,
		//'name'  => 'Verdana',
				
		/*'alignment' => array(
		'wrap'       => true,	
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		)*/		
	),
	'borders' => array(
			'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
	)			
);

//######### SET HEADER FORMAT
$objPHPExcel2->getActiveSheet()->getCell('A1')->setValue('Group');
$objPHPExcel2->getActiveSheet()->getStyle('A1')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth(8);

$objPHPExcel2->getActiveSheet()->getCell('B1')->setValue('SubGroup');
$objPHPExcel2->getActiveSheet()->getStyle('B1')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth(10);

$objPHPExcel2->getActiveSheet()->getCell('C1')->setValue('Vehicle Detail');
$objPHPExcel2->getActiveSheet()->getStyle('C1')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth(16);

$objPHPExcel2->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel2->setActiveSheetIndex(0)->mergeCells('A1:A2');
$objPHPExcel2->getActiveSheet()->getStyle('A1:A2')->applyFromArray($styleHeader);

$objPHPExcel2->getActiveSheet()->getStyle('B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel2->setActiveSheetIndex(0)->mergeCells('B1:B2');
$objPHPExcel2->getActiveSheet()->getStyle('B1:B2')->applyFromArray($styleHeader);

$objPHPExcel2->getActiveSheet()->getStyle('C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel2->setActiveSheetIndex(0)->mergeCells('C1:C2');
$objPHPExcel2->getActiveSheet()->getStyle('C1:C2')->applyFromArray($styleHeader);

$objPHPExcel2->setActiveSheetIndex(0)->mergeCells('D1:AH1');
//$date1 = "July 2013";
if($monthtmp2[1]=="01") $month_name="Jan";
else if($monthtmp2[1]=="02") $month_name="Feb";
else if($monthtmp2[1]=="03") $month_name="Mar";
else if($monthtmp2[1]=="04") $month_name="Apr";
else if($monthtmp2[1]=="05") $month_name="May";
else if($monthtmp2[1]=="06") $month_name="Jun";
else if($monthtmp2[1]=="07") $month_name="Jul";
else if($monthtmp2[1]=="08") $month_name="Aug";
else if($monthtmp2[1]=="09") $month_name="Sep";
else if($monthtmp2[1]=="10") $month_name="Oct";
else if($monthtmp2[1]=="11") $month_name="Nov";
else if($monthtmp2[1]=="12") $month_name="Dec";
$date1 = $month_name." 2014";
$vstatus_string = "Vehicle Status (".$date1.")";
$objPHPExcel2->getActiveSheet()->getCell('D1')->setValue($vstatus_string);
$objPHPExcel2->getActiveSheet()->getStyle('D1')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel2->setActiveSheetIndex(0)->mergeCells('AI1:AK1');
$objPHPExcel2->getActiveSheet()->getCell('AI1')->setValue('No. Of  Days');
$objPHPExcel2->getActiveSheet()->getStyle('AI1')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getStyle('AI1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel2->setActiveSheetIndex(0)->mergeCells('AL1:AL2');
$objPHPExcel2->getActiveSheet()->getCell('AL1')->setValue('Remark    ');
$objPHPExcel2->getActiveSheet()->getStyle('AL1')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getStyle('AL1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(38)->setWidth(10);
						
$objPHPExcel2->getActiveSheet()->getCell('D2')->setValue('1st');
$objPHPExcel2->getActiveSheet()->getStyle('D2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('E2')->setValue('2nd');
$objPHPExcel2->getActiveSheet()->getStyle('E2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('F2')->setValue('3rd');
$objPHPExcel2->getActiveSheet()->getStyle('F2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('G2')->setValue('4th');
$objPHPExcel2->getActiveSheet()->getStyle('G2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(6)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('H2')->setValue('5th');
$objPHPExcel2->getActiveSheet()->getStyle('H2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(7)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('I2')->setValue('6th');
$objPHPExcel2->getActiveSheet()->getStyle('I2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(8)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('J2')->setValue('7th');
$objPHPExcel2->getActiveSheet()->getStyle('J2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(9)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('K2')->setValue('8th');
$objPHPExcel2->getActiveSheet()->getStyle('K2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(10)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('L2')->setValue('9th');
$objPHPExcel2->getActiveSheet()->getStyle('L2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(11)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('M2')->setValue('10th');
$objPHPExcel2->getActiveSheet()->getStyle('M2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(12)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('N2')->setValue('11th');
$objPHPExcel2->getActiveSheet()->getStyle('N2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(13)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('O2')->setValue('12th');
$objPHPExcel2->getActiveSheet()->getStyle('O2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(14)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('P2')->setValue('13th');
$objPHPExcel2->getActiveSheet()->getStyle('P2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(15)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('Q2')->setValue('14th');
$objPHPExcel2->getActiveSheet()->getStyle('Q2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(16)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('R2')->setValue('15th');
$objPHPExcel2->getActiveSheet()->getStyle('R2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(17)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('S2')->setValue('16th');
$objPHPExcel2->getActiveSheet()->getStyle('S2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(18)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('T2')->setValue('17th');
$objPHPExcel2->getActiveSheet()->getStyle('T2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(19)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('U2')->setValue('18th');
$objPHPExcel2->getActiveSheet()->getStyle('U2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(20)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('V2')->setValue('19th');
$objPHPExcel2->getActiveSheet()->getStyle('V2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(21)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('W2')->setValue('20th');
$objPHPExcel2->getActiveSheet()->getStyle('W2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(22)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('X2')->setValue('21th');
$objPHPExcel2->getActiveSheet()->getStyle('X2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(23)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('Y2')->setValue('22nd');
$objPHPExcel2->getActiveSheet()->getStyle('Y2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(24)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('Z2')->setValue('23rd');
$objPHPExcel2->getActiveSheet()->getStyle('Z2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(25)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('AA2')->setValue('24th');
$objPHPExcel2->getActiveSheet()->getStyle('AA2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(26)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('AB2')->setValue('25th');
$objPHPExcel2->getActiveSheet()->getStyle('AB2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(27)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('AC2')->setValue('26th');
$objPHPExcel2->getActiveSheet()->getStyle('AC2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(28)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('AD2')->setValue('27th');
$objPHPExcel2->getActiveSheet()->getStyle('AD2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(29)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('AE2')->setValue('28th');
$objPHPExcel2->getActiveSheet()->getStyle('AE2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(30)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('AF2')->setValue('29th');
$objPHPExcel2->getActiveSheet()->getStyle('AF2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(31)->setWidth(5);
		
$objPHPExcel2->getActiveSheet()->getCell('AG2')->setValue('30th');
$objPHPExcel2->getActiveSheet()->getStyle('AG2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(32)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('AH2')->setValue('31st');
$objPHPExcel2->getActiveSheet()->getStyle('AH2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(33)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('AI2')->setValue('AC');
$objPHPExcel2->getActiveSheet()->getStyle('AI2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(34)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('AJ2')->setValue('NA');
$objPHPExcel2->getActiveSheet()->getStyle('AJ2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(35)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('AK2')->setValue('NG');
$objPHPExcel2->getActiveSheet()->getStyle('AK2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(36)->setWidth(5);

$objPHPExcel2->getActiveSheet()->getCell('AL2')->setValue('NG');
$objPHPExcel2->getActiveSheet()->getStyle('AL2')->applyFromArray($styleHeader);
$objPHPExcel2->getActiveSheet()->getColumnDimensionByColumn(37)->setWidth(5);


//######### 500 FILL EMPTY CELLS
if (!file_exists($destpath2))
{
	for($i=3;$i<500;$i++)
	{
		$tmpcell ="A".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	
		
		$tmpcell ="B".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="C".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="D".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="E".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="F".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="G".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="H".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="I".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="J".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="K".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="L".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);

		$tmpcell ="M".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	
		
		$tmpcell ="N".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="O".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="P".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="Q".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="R".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="S".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="T".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="U".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="V".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="W".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="X".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="Y".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	
		
		$tmpcell ="Z".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="AA".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="AB".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="AC".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="AD".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="AE".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="AF".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="AG".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="AH".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="AI".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="AJ".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);

		$tmpcell ="AK".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);	

		$tmpcell ="AL".$i;
		$objPHPExcel2->getActiveSheet()->getCell($tmpcell)->setValue("");
		$objPHPExcel2->getActiveSheet()->getStyle($tmpcell)->applyFromArray($styleText);				
	}
}

//######## START FROM GROUP -(EG.RSPL) - SET IN COLUMN 1
$r2 = 3;
$group_cell = 'A'.$r2;
$objPHPExcel2->getActiveSheet()->getCell($group_cell)->setValue('RSPL');
$objPHPExcel2->getActiveSheet()->getStyle($group_cell)->applyFromArray($styleText);

?>
