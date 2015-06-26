<?php
set_time_limit(18000);
$DBASE = "iespl_vts_beta";
$HOST = "localhost";
$USER = "root";
$PASSWD = 'neon04$VTS';

echo "\nDBASE=".$DBASE." ,USER=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

//$abspath = "D:\\test_app";
$abspath = "/var/www/html/vts/beta/src/php";

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once $abspath.'/PHPExcel/IOFactory.php';

$objPHPExcel_1 = null;
$VehicleNo = Array();
$CustomerCode = Array();
$CustomerName = Array();
$InvoiceNo = Array();
$InvoiceAmount = Array();
$EmailID = Array();
$Remarks = Array();
$DriverName = Array();
$DriverPhoneNo = Array();
$TargetTime = Array();
global $create_date_global;
	
//$read_excel_path = "C:\\xampp/htdocs/beta/src/php/invoice_data/".$_FILES['file']['name'];
//####### READ FILE NAME AND PATH
$dir = "/var/www/html/vts/beta/src/php/invoice_data";
$dh = opendir($dir);
while (($file = readdir($dh)) !== false) {
	//echo "<A HREF=\"$file\">$file</A><BR>\n";
	$file_ext = explode(".",$file);
	//echo "<br>FileExt=".$file_ext[1];
	if($file_ext[1]=="xlsx")
	{
		$read_excel_path = $dir."/".$file;
		
		$VehicleNo = Array();
		$CustomerCode = Array();
		$CustomerName = Array();
		$InvoiceNo = Array();
		$InvoiceAmount = Array();
		$EmailID = Array();
		$Remarks = Array();
		$DriverName = Array();
		$DriverPhoneNo = Array();
		$TargetTime = Array();			
		//###### READ UPLOADED FILE
		//echo "\nPath=".$read_excel_path;
		read_uploaded_file($read_excel_path);
		
		//##### SEND EMAIL
		send_email();

		//##### MOVE FILE
		move_file($read_excel_path,$file);
	}
}

function move_file($read_excel_path,$filename_title)
{
	global $create_date_global;
	//######## MOVE FILE
	$create_date_arr = explode(' ',$create_date_global);
	$sourcepath = $read_excel_path;
	$TodirPath = "/home/BACKUP/MAIL/INVOICE/".$create_date_arr[0];
	//echo "\nDirPath=".$dirPath;
	mkdir ($TodirPath, false);
	@chmod($dirPath, 0777);
	$destpath = $TodirPath."/".$filename_title;

	@chmod($destpath, 0777);
	//echo "\nSourcePath=".$sourcepath." ,DestPath=".$destpath;
	copy($sourcepath,$destpath);
	//########## BACKUP FILES CLOSED #######################
	
	unlink($read_excel_path); 
}

function send_email()
{ 
	global $DbConnection;
	global $VehicleNo;			//UPLOADED FILE VARS
	global $CustomerCode;
	global $CustomerName;
	global $InvoiceNo;
	global $InvoiceAmount;
	global $EmailID;
	global $Remarks;
	global $DriverName;
	global $DriverPhoneNo;
	global $TargetTime;
	global $create_date_global;	
	
	for($i=0;$i<sizeof($VehicleNo);$i++)
    	{
		$flag=0;
		//$to = "rizwan@iembsys.com";
		$to = $EmailID[$i];
		$subject = "Confirmation:VTS-Invoice Generation";
		
		$query = "SELECT account.user_id,invoice.tracking_no FROM account,invoice WHERE account.account_id=invoice.account_id AND ".
			"invoice.vehicle_no='$VehicleNo[$i]' AND invoice.invoice_no='$InvoiceNo[$i]' AND ".
			"invoice.email_id='$EmailID[$i]' AND invoice.status=1";
		//echo "\n".$query;
		$result = mysql_query($query,$DbConnection);
		if($row = mysql_fetch_object($result))
		{
			$userid = $row->user_id;
			$tracking_no = $row->tracking_no;
			
			$query2 = "SELECT start_date,end_date,create_date FROM consignment_info WHERE docket_no='$tracking_no' AND status=1";
			//echo "\n".$query2;
			$result2 = mysql_query($query2,$DbConnection);
			$row2 = mysql_fetch_object($result2);
			$start_date = $row2->start_date;
			$end_date = $row2->end_date;
			$create_date = $row2->create_date;
			$flag =1;
		}
				
		/*$CustomerCode[$i];
		$CustomerName[$i];
		$InvoiceNo[$i];
		$InvoiceAmount[$i];
		$EmailID[$i];
		$Remarks[$i];
		$DriverName[$i];
		$DriverPhoneNo[$i];
		$TargetTime[$i];*/
		if($flag==1)
		{
			$message = "<strong>Invoice Generated :User:-<font color='green'>".$userid."</font> At:- <font color='red'>".$create_date."</font></strong><br>";
			$message.= "<strong>TrackingNo:<font color='red'>".$tracking_no."</font></strong><br>";
			//$message.= "<strong>StartDate:<font color=blue>".$start_date."</font></strong><br>";
			//$message.= "<strong>EndDate:<font color=blue>".$end_date."</font></strong><br>";
			$message.= "<strong>Lorry No:<font color='blue'>".$VehicleNo[$i]."</font></strong><br>";
			$message.= "<strong>CustomerName:<font color='blue'>".$CustomerName[$i]."</font></strong><br>";
			$message.= "<strong>InvoiceNo:<font color='blue'>".$InvoiceNo[$i]."</font></strong><br>";
			$message.= "<strong>InvoiceAmount:<font color='blue'>".$InvoiceAmount[$i]."</font></strong><br>";
			//$message.= "<strong>EmailID:<font color=blue>".$EmailID[$i]."</font></strong><br>";
			$message.= "<strong>Remarks:<font color='blue'>".$Remarks[$i]."</font></strong><br>";
			$message.= "<strong>DriverName:<font color='blue'>".$DriverName[$i]."</font></strong><br>";
			$message.= "<strong>DriverPhoneNo:<font color='blue'>".$DriverPhoneNo[$i]."</font></strong><br>";
			$message.= "<strong>TargetTime:<font color='blue'>".$TargetTime[$i]."</font></strong><br>";
			
			//$message = "<b>This is HTML message-".$i."</b>";
			$header = "From:support@iembsys.co.in \r\n";
			$header = "Cc:Raju.Ranjan@unilever.com,SushilBothra@westcong.com,hourlyreport4@gmail.com \r\n";
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html\r\n";
			$retval = mail ($to,$subject,$message,$header);
			if( $retval == true )
			{
			  echo "Message sent successfully-$i...";		  		  
			}
			else
			{
			  echo "Message could not be sent-$i...";
			}
		}				
    }
	$create_date_global	= $create_date;
}

function read_uploaded_file($read_excel_path)
{
	global $objPHPExcel_1;
	global $VehicleNo;			//UPLOADED FILE
	global $CustomerCode;
	global $CustomerName;
	global $InvoiceNo;
	global $InvoiceAmount;
	global $EmailID;
	global $Remarks;
	global $DriverName;
	global $DriverPhoneNo;
	global $TargetTime;		
	
	//echo "<br>READ_SENT_FILE";
	//echo "\nPath=".$path;
	//######### SENT FILE NAME CLOSED 			
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);	

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
				
				if($row>1)
				{
					//echo "\nRecord:".$row;
					$tmp_val="A".$row;
					$vehicle_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
															
					if($vehicle_tmp=="")
					{
						$read_completed = true;
						break;
					}
					$VehicleNo[] = $vehicle_tmp;
					//echo "\nRow=".$row." ,Val=".$objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					
					$tmp_val="B".$row;
					$CustomerCode[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="C".$row;
					$CustomerName[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="D".$row;
					$InvoiceNo[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="E".$row;
					$InvoiceAmount[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="F".$row;
					$EmailID[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="G".$row;
					$Remarks[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="H".$row;
					$DriverName[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="I".$row;
					$DriverPhoneNo[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="J".$row;
					//$TargetTime[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					$TargetTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

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
	//#################################################################
}
?>
