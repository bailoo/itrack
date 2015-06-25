<?php
	/** Include PHPExcel */
	//error_reporting(E_ALL);
	require_once 'PHPExcel2/Classes/PHPExcel/IOFactory.php';

	// Here is the sample array of data
	date_default_timezone_set('Asia/Calcutta');

	$enterDate = $_POST['enterDate']; 
	$checkedVehicleArrThis = $_POST['checkedVehicleArr']; 
	$checkedVehicleArrThis = unserialize(urldecode($_POST['checkedVehicleArr']));
	//print_r($checkedVehicleArrThis);
	$destFile="../../../logBetaXml/".$enterDate."/distanceFileGet.xml";
	$xml_original_tmp="../../../logBetaXml/".$enterDate."/distanceFileGetTmp1.xml";
	copy($destFile,$xml_original_tmp);
	//echo "desFile=".$destFile."$destFile<br>";
	/*if(file_exists($xml_original_tmp))
	{
		echo "fileexist";
	}
	else
	{
		echo "fileNotExist";
	}*/
	$xml = @fopen($xml_original_tmp, "r") or $fexist = 0; 
	//echo "destFile=".$destfile."<br>";
	$i=0;
	while(!feof($xml))          // WHILE LINE != NULL
	{			
		$DataValid = 0;
		//echo "<br>line";
		//echo fgets($file). "<br />";
		$line = fgets($xml);  // STRING SHOULD BE IN SINGLE QUOTE	
	
		$status = preg_match('/imei="[^" ]+/', $line, $vSerialTmp);
		$vSerialTmp1 = explode("=",$vSerialTmp[0]);
		$tmpVerial = preg_replace('/"/', '', $vSerialTmp1[1]);
		
		if($checkedVehicleArrThis[$tmpVerial]!="")
		{ 			
			$status = preg_match('/vn="[^"]+/', $line, $vNameTmp);
			$vNameTmp1 = explode("=",$vNameTmp[0]);
			$tmpVname= preg_replace('/"/', '', $vNameTmp1[1]);
			   
			$tmpEmpName ='not available';					
		
			preg_match('/dis="[^"]+/', $line, $disTmp);
			//print_r($addressTmp);
			$disTmp1 = explode("=",@$disTmp[0]);
			$disWithHour = preg_replace('/"/', '', @$disTmp1[1]);
			
			$disWithHour1=explode("#",$disWithHour);
			
		
			preg_match('/add="[^"]+/', $line, $addressTmp);
			//print_r($addressTmp);
			$addressTmp1 = explode("=",@$addressTmp[0]);
			$tmpAddress = preg_replace('/"/', '', @$addressTmp1[1]);
			
			
			$status = preg_match('/cdi="[^"]+/', $line, $vCdiTmp);
			$vCdiTmp1 = explode("=",$vCdiTmp[0]);
			$tmpCdi = preg_replace('/"/', '', $vCdiTmp1[1]);
			
			$vSerialArr[$tmpVerial][]=$tmpVerial;
			$vNameArr[$tmpVerial][]=$tmpVname;
			$empArr[$tmpVerial][]='not available';
			$mobNoArr[$tmpVerial][]='not available';
			$disArr1[$tmpVerial][]=$disWithHour1[0];
			$disArr2[$tmpVerial][]=$disWithHour1[1];
			$addressArr[$tmpVerial][]=$tmpAddress;
			//array('vserial'=>$tmpVerial,'pname'=>$tmpVname,'empNo'=>'not available','mobNo'=>'not available',$disWithHour1[1]=>$disWithHour1[0],'address'=>$tmpAddress);
		}
	}
	///print_r($vSerialArr);
	//$c=0;
	
	foreach($checkedVehicleArrThis as $vserial)
	{
		$totalDistance=0;
		$tmpVserialSize=sizeof($vSerialArr[$vserial]);		
		if(count($vSerialArr[$vserial])>0)
		{
			$da=1;
			for($j=0;$j<$tmpVserialSize;$j++)
			{
				
				if($j==0)
				{
					$tmpArr[$c]['serial'] = $c+1;
					$tmpArr[$c]['vname'] = $vNameArr[$vserial][$j];;
					$tmpArr[$c]['empNo'] = 'not available';
					$tmpArr[$c]['mobileNo'] = 'not available';					
				}
				//echo "da=".$da."<br>";
				$matchFlag=0;
				for($k=$da;$k<=23;$k++)
				{
					$matchFlag=0;
					if($k<10)
					{
						$disHourKey='d0'.$k;
					}
					else
					{
						$disHourKey='d'.$k;			
					}
						
					//echo "DA=".$k."<br>";
					//echo"vserial=".$vserial[$i]."name1=".$disArr2[$vserial[$i]][$j]."name2=".$disHourKey." dist=".$tmpDisThis." k=".$k." da".$da."<br>";
					if($disArr2[$vserial][$j]==$disHourKey)
					{
						$da=$k+1;
						$tmpDisThis=round($disArr1[$vserial][$j],2);
						$tmpArr[$c][$disHourKey]=$addressArr[$vserial][$j]." (".$tmpDisThis.")";
						
						$totalDistance=$totalDistance+$tmpDisThis;
						$matchFlag=1;
						break;
					}
					else
					{
						$tmpArr[$c][$disHourKey]='-';
						//echo" k=".$k." da".$da."<br>";
						//echo "<td>-</td>";
					}
				}					
			}
			if($da!=23)
			{
				for($k=$da;$k<=23;$k++)
				{
					if($k<10)
					{
						$disHourKey='d0'.$k;
						$tmpArr[$c][$disHourKey]='-';
					}
					else
					{
						$disHourKey='d'.$k;
						$tmpArr[$c][$disHourKey]='-';						
					}
					
					//echo "<td>-</td>";
				}				
			}
			$tmpArr[$c]['cdi']=$totalDistance;
			$c++;
		}
	}
	
	$data=$tmpArr;
	//print_r($data);

$clmBreakFlag=0;

	//$todayTime=explode(":",date("H:i:s"));
	$timeCnt=23;
	//$timeCnt=(integer)$tmpHr;
	//echo "timeCnt=".$timeCnt."<br>";
	$timeCntClm=$timeCnt+3;
	if($timeCnt>12)
	{
		$afterNoonCnt=$timeCnt-12;
		$clmBreakFlag=1;
		//echo $afterNoonCnt." , ".$clmBreakFlag."<br>";
	}
	 
	$alphabet = array('A', 'B', 'C', 'D', 'E',
                            'F', 'G', 'H', 'I', 'J',
                            'K', 'L', 'M', 'N', 'O',
                            'P', 'Q', 'R', 'S', 'T',
                            'U', 'V', 'W', 'X', 'Y','Z',
							'AA','AB','AC','AD','AE',
							'AF','AG','AH','AI','AJ',
							'AK','AL');
//print_r($alphabet);
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

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
	echo "clmAlphabet".$clmAlphabet."<br>";
	$cell_range='C1:'.$clmAlphabet.'1';
	echo "celrange=".$cell_range."<br>";
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
	if($clmBreakFlag==0)
	{
            $clmAlphabet=$alphabet[$timeCntClm];
            $cell_range='E2:'.$clmAlphabet.'2';
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', "AM");
            $cell_range=$alphabet[$timeCntClm+1].'2:'.$alphabet[$timeCntClm+1].'3';
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$timeCntClm+1].'2', "Total Distance");
            $cell_range=$alphabet[$timeCntClm+2].'2:'.$alphabet[$timeCntClm+2].'3';
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$timeCntClm+2].'2', "Final Closure");
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


		$cell_range=$alphabet[16+$afterNoonCnt].'2:'.$alphabet[16+$afterNoonCnt].'3';
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[16+$afterNoonCnt].'2', "Total Distance");
		$cell_range=$alphabet[17+$afterNoonCnt].'2:'.$alphabet[17+$afterNoonCnt].'3';
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[17+$afterNoonCnt].'2', "Final Closure");
	}
		
	$objPHPExcel->getActiveSheet()->fromArray($data, null, 'A4');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$excelFileDestination="../../../logBetaXml/".$enterDate."/MyExcel.xlsx";
        
	$objWriter->save($excelFileDestination);
	
	$files = array($excelFileDestination);

    # create new zip opbject
    $zip = new ZipArchive();

    # create a temp file & open it
    $tmp_file = tempnam('.','');
    $zip->open($tmp_file, ZipArchive::CREATE);

    # loop through each file
    foreach($files as $file){

        # download file
        $download_file = file_get_contents($file);

        #add it to the zip
        $zip->addFromString(basename($file),$download_file);

    }

    # close zip
    $zip->close();

    # send the file to the browser as a download
    header('Content-disposition: attachment; filename=HourlyReport.zip');
    header('Content-type: application/zip');
    readfile($tmp_file);
        /*echo "desFile=".$excelFileDestination."<br>";
        if(file_exists($excelFileDestination))
        {
            echo "fileexist";
        }
        else
        {
            echo "fileNotExist";
        }*/
	/*header( "Pragma: public" );
	header( "Expires: 0" );
	header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
	header( "Cache-Control: public" );
	header( "Content-Description: File Transfer" );
	header( "Content-type: application/zip" );
	header( "Content-Disposition: attachment; filename=\"MyExcel.xlsx\"" );
	header( "Content-Transfer-Encoding: binary" );
	header( "Content-Length: " . filesize( $excelFileDestination ) );
	readfile( $excelFileDestination );*/
	
	unlink($excelFileDestination);
?>