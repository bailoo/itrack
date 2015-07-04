<?php

//echo "Evening file";
$abspath = "/var/www/html/vts/beta/src/php";
require_once $abspath."/excel_lib/class.writeexcel_workbook.inc.php";
require_once $abspath."/excel_lib/class.writeexcel_worksheet.inc.php";

function tempnam_sfx($path, $suffix)
{
	$file = $path.$suffix;
	/*do
	{
		$file = $path.$suffix;
		$fp = @fopen($file, 'x');
	}
	while(!$fp);
	fclose($fp);*/
	return $file;
}

	$fname = tempnam("/tmp", "colors.xls");
	$workbook = &new writeexcel_workbook($fname);
	
	$border1 =& $workbook->addformat();
	$border1->set_color('white');
	$border1->set_bold();
	$border1->set_size(9);
	$border1->set_pattern(0x1);
	$border1->set_fg_color('green');
	$border1->set_border_color('yellow');
	
	$text_format =& $workbook->addformat(array(
		bold    => 1,
		//italic  => 1,
		//color   => 'blue',
		size    => 10,
		//font    => 'Comic Sans MS'
	));
	
	$text_red_format =& $workbook->addformat(array(
		bold    => 1,
		//italic  => 1,
		color   => 'red',
		size    => 10,
		//font    => 'Comic Sans MS'
	));	

	/*$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy'));
	
	$blank_format = & $workbook->addformat();
	$blank_format->set_color('white');
	$blank_format->set_bold();
	$blank_format->set_size(12);
	$blank_format->set_merge();*/
			
	$worksheet2 =& $workbook->addworksheet('Halt Report');
	$r=0;   //row 
	$c=0;	
	//echo"Test1\n";

$excel_time_format = & $workbook->addformat(array(num_format => 'hh:mm:ss'));
	
	//$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color'=>0x0A,'size'=>10,'bold'=>1));
//	$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color'=>0x0A,'size'=>10,'bold'=>1));
	//$excel_normal_format =& $workbook->addformat(array('fg_color'=>0x0A,'size'=>10,'bold'=>1));
	//$excel_normal_format =& $workbook->addformat(array('fg_color'=>0x09,'size'=>10,'bold'=>1));

	$tmp_date = "1970-01-01 05:00:00";
	$time_obj4 = strtotime($tmp_date);
	$time_obj4 = $time_obj4 + 19800;	//ADD 5:30
	//$date_tmp = date('d/m/Y',$date_obj);
	$excel_time4= $time_obj4 / 86400 + 25569;
	$worksheet2->write($r,$c, $excel_time4, $excel_time_format);										

    //########### WRITE CUSTOMER NOT VISITED CLOSED ###############    	
   
  $workbook->close(); //CLOSE WORKBOOK
  //echo "\nWORKBOOK CLOSED"; 
 
header("Content-Type: application/x-msexcel; name=\"example-colors.xls\"");
header("Content-Disposition: inline; filename=\"example-colors.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);

}
 
?>
