<?php
include_once('util_session_variable.php');
include ('util.ezpdf.php');

date_default_timezone_set('Asia/Calcutta');
$y = date('Y');
$m = date('m');
$d = date('d');
$h = date('H');
$i = date('i');

$datetime = $y.$m.$d.'_'.$h.$i;

//echo "dt=".$datetime;

$file_name= 'pdf_reports/report_'.$account_id.'_'.$datetime.'.pdf';

$option=array('showLines'=>1,
				'showHeadings'=>1,
				'shaded'=> 0,
//				'shadeCol' =>(0.8,0.8,0.8),
//				'shadeCol2' =>(0.7,0.7,0.7),
				'fontSize' => 8,
//				'textCol' => (0,0,0),
				'titleFontSize' => 9,
				'rowGap' => 2 ,
				'colGap' => 5 ,
//				'lineCol' => (0,0,0),
				'xPos' => 'center',
				'xOrientation' => 'center',
				'width'=>500,
//				'maxWidth' => 400
				'innerLineThickness' =>0.5,
				'outerLineThickness' =>0.5,
//				'protectRows' => <number>
				);

	$pdf =& new Cezpdf();
	$pdf->selectFont('../fonts/Helvetica.afm');
	$pdf = new Cezpdf('a4','portrait');

	$pdf -> ezSetMargins(10,70,30,30);
	$pdf->addJpegFromFile('../../images/IES.jpg',199,$pdf->y-5,200,0);
	$size=$_GET['size'];
	for($i=0;$i<$size;$i++)
	{	
		if($temp[$i]||$temp1[$i])
		{
		$pdf->ezTable($temp[$i],'',$title[$i],$option);
		$pdf->ezText(' ');
		$pdf->ezText(' ');
		$pdf->ezTable($temp1[$i],'','',$option);
		$pdf->ezText(' ');
		$pdf->ezText(' ');
		}
		else
		{
		$pdf->ezText($title[$i],12,array('justification' => 'center'));
		$pdf->ezText(' ');
		$pdf->ezText("No Match Found",10,array('justification' => 'center'));
		$pdf->ezText(' ');
		}

	}
	$pdf->ezStream();
//	$pdf->ezOutput('invoice.pdf');


	$pdfcode = $pdf->ezOutput();
	$fp=fopen($file_name,'wb');
	fwrite($fp,$pdfcode);
	fclose($fp);

//echo $file_name;
//print_r($temp2);
//print_r($temp3);
//echo $heading;
//echo $heading[1];
//echo $heading[2];

//$heading= $_SESSION['heading'];
//$heading=$_POST['heading'];
//printf("$heading[0]=%s",$heading[0]);
//printf("$heading[1]=%s",$heading[1]);
   
?>

