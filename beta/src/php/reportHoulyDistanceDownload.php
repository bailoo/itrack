<?php
	/** Include PHPExcel */
	//error_reporting(E_ALL);
	require_once 'PHPExcel2/Classes/PHPExcel/IOFactory.php';

	// Here is the sample array of data
	date_default_timezone_set('Asia/Calcutta');
	$todayTime=explode(":",date("H:i:s"));
	$timeCnt=(integer)$todayTime[0];
	//echo "timeCnt=".$timeCnt."<br>";
	$timeCntClm=$timeCnt+3;
	$enterDate = $_POST['enterDate']; 
        $checkedVehicleArrThis = $_POST['checkedVehicleArr']; 
        $checkedVehicleArrThis = unserialize(urldecode($_POST['checkedVehicleArr']));
        
        $destFile="../../../logBetaXml/".$enterDate."/processedData.xml";
        //echo "desFile=".$destFile."<br>";
        /*if(file_exists($destFile))
        {
            echo "fileexist";
        }
        else
        {
            echo "fileNotExist";
        }*/
	$xml = @fopen($destFile, "r") or $fexist = 0; 
	$c=0;
	while(!feof($xml))          // WHILE LINE != NULL
	{			
		$DataValid = 0;
		//echo "<br>line";
		//echo fgets($file). "<br />";
		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE	
		//echo "<textarea>".$line."</textarea>";
                $status = preg_match('/vs="[^" ]+/', $line, $vSerialTmp);
		$vSerialTmp1 = explode("=",$vSerialTmp[0]);
		$tmpVerial = preg_replace('/"/', '', $vSerialTmp1[1]);		
                
                if($checkedVehicleArrThis[$tmpVerial]!="")
                { 
                    $tmpArr[$c]['serial'] = $c+1;		
                    $status = preg_match('/vn="[^"]+/', $line, $vNameTmp);
                    $vNameTmp1 = explode("=",$vNameTmp[0]);
                    $tmpArr[$c]['vname'] = preg_replace('/"/', '', $vNameTmp1[1]);	

                    $tmpArr[$c]['empNo'] ='not available';		
                    $tmpArr[$c]['mobileNo'] ='not available';
		
                    for($i=1;$i<=$timeCnt;$i++)
                    {
                            if($i<10)
                            {
                                $tmpVal='a0'.$i;
                                preg_match('/'.$tmpVal.'="[^"]+/', $line, $addressTmp);
                                $addressTmp1 = explode("=",@$addressTmp[0]);
                                $tmpArr[$c][$tmpVal] = preg_replace('/"/', '', @$addressTmp1[1]);

                            }
                            else
                            {
                                $tmpVal='a'.$i;
                                preg_match('/'.$tmpVal.'="[^"]+/', $line, $addressTmp);
                                $addressTmp1 = explode("=",@$addressTmp[0]);
                                $tmpArr[$c][$tmpVal] = preg_replace('/"/', '', @$addressTmp1[1]);
                            }
                    }
                    /*$status = preg_match('/cdi="[^"]+/', $line, $vCdiTmp);
                    $vCdiTmp1 = explode("=",$vCdiTmp[0]);
                    $tmpArr[$c]['cdi'] = preg_replace('/"/', '', $vCdiTmp1[1]);*/
                    $c++;
                }
	}
	
	$data=$tmpArr;
	//print_r($data);
	$clmBreakFlag=0;
	if($timeCnt>12)
	{
            $afterNoonCnt=$timeCnt-12;
            $clmBreakFlag=1;
	}
	
	$alphabet = array('A', 'B', 'C', 'D', 'E',
                            'F', 'G', 'H', 'I', 'J',
                            'K', 'L', 'M', 'N', 'O',
                            'P', 'Q', 'R', 'S', 'T',
                            'U', 'V', 'W', 'X', 'Y','Z');
        //echo "before Object";

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
        
        //echo "after Object";
	

	//$objPHPExcel->getActiveSheet()->getRowDimension('A1')->setRowHeight(40);
//$objWorksheet->getActiveSheet()->getColumnDimension('A')->setWidth(100);
	$cell_range='A1:B1';
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
         //echo "Emp No 1<br>";
	$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
	//$objDrawing = new PHPExcel_Worksheet_Drawing();
	//$objDrawing->setName('PHPExcel logo');
	//$objDrawing->setDescription('PHPExcel logo');
	/*$objDrawing->setPath('images/rsplLogo.png'); // filesystem reference for the image file
	$objDrawing->setHeight(56); // sets the image height to 36px (overriding the actual image height);
	$objDrawing->setCoordinates('A1'); // pins the top-left corner of the image to cell D24
	$objDrawing->setOffsetX(30); // pins the top left corner of the image at an offset of 10 points horizontally to the right of the top-left corner of the cell
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());*/
	//echo "Emp No 2<br>";
	$cell_range='C1:E1';
	$clmAlphabet=$alphabet[$timeCntClm];
	$cell_range='C1:'.$clmAlphabet.'1';
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', "Daily Tracking Report");
	$cell_range='A2:A3';
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "SR No.");
	$cell_range='B2:B3';
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', "Name");
	$cell_range='C2:C3';
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', "Emp No");
	$cell_range='D2:D3';
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
        //echo "Emp No<br>";
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', "Mobile No");
	if($clmBreakFlag==0)
	{
            $clmAlphabet=$alphabet[$timeCntClm];
            $cell_range='E2:'.$clmAlphabet.'2';
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', "AM");
            $cell_range=$alphabet[$timeCntClm+1].'2:'.$alphabet[$timeCntClm+1].'3';
            /*$objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$timeCntClm+1].'2', "Total Distance");
            $cell_range=$alphabet[$timeCntClm+2].'2:'.$alphabet[$timeCntClm+2].'3';
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$timeCntClm+2].'2', "Final Closure");*/
        }
	if($clmBreakFlag==1)
	{
            $amClmAlphabet=$alphabet[15];
            $cell_range='E2:'.$amClmAlphabet.'2';
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', "AM");
            //$pmClmAlphabet=$alphabet[16];

            $cell_range=$alphabet[16].'2:'.$alphabet[16].'2';
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[16].'2', "PM");


            /*$cell_range=$alphabet[16+$afterNoonCnt].'2:'.$alphabet[16+$afterNoonCnt].'3';
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[16+$afterNoonCnt].'2', "Total Distance");
            $cell_range=$alphabet[17+$afterNoonCnt].'2:'.$alphabet[17+$afterNoonCnt].'3';
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[17+$afterNoonCnt].'2', "Final Closure");*/
        }
	for($i=1;$i<=$timeCnt;$i++)
	{
            if($i<10)
            {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$i+3]."3", '0'.$i.":00");
            }
            else
            {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$i+3]."3", $i.":00");
            }
	}	
	$objPHPExcel->getActiveSheet()->fromArray($data, null, 'A4');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $excelFileDestination="../../../logBetaXml/".$enterDate."/MyExcel.xlsx";
        
      
        
	$objWriter->save($excelFileDestination);
        /*echo "desFile=".$excelFileDestination."<br>";
        if(file_exists($excelFileDestination))
        {
            echo "fileexist";
        }
        else
        {
            echo "fileNotExist";
        }*/
	header( "Pragma: public" );
	header( "Expires: 0" );
	header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
	header( "Cache-Control: public" );
	header( "Content-Description: File Transfer" );
	header( "Content-type: application/zip" );
	header( "Content-Disposition: attachment; filename=\"MyExcel.xlsx\"" );
	header( "Content-Transfer-Encoding: binary" );
	header( "Content-Length: " . filesize( $excelFileDestination ) );
	readfile( $excelFileDestination ); 	
	unlink($excelFileDestination);
?>