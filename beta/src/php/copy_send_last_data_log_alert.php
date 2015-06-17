 <?php		
//$date=date("Y-m-d H:i:s");
$store_file_path="last_data_alert/tmp_file.txt";

$xml_file1 = "../../../xml_vts/xml_last/862170018371375.xml";
$smessage1=false;
$smessage2=false;
$smessage3=false;
$smessage4=false;

if(file_exists($xml_file1))
{
	$modified_datetime1=filemtime($xml_file1);
	$server_time1=time();
	$timediff1=($modified_datetime1-$server_time1);
	
	/*$mdt1=date('Y-m-d H:i:s', $modified_datetime1);	
	$sdt1=date('Y-m-d H:i:s', $server_time);	
	echo "time_diff=".$timediff."<br>";*/
	if($timediff1>=1200)
	{
		$imei_no='862170018371375';
		$smessage1=true;				
	}
}

$xml_file2 = "../../../xml_vts/xml_last/862170018372654.xml";
if(file_exists($xml_file2))
{
	$modified_datetime2=filemtime($xml_file2);
	$server_time2=time();
	$timediff2=($modified_datetime2-$server_time2);
	/*$mdt1=date('Y-m-d H:i:s', $modified_datetime1);	
	$sdt1=date('Y-m-d H:i:s', $server_time);	
	echo "time_diff=".$timediff."<br>";*/
	if($timediff2>=1200)
	{
		$imei_no='862170018372654';
		$smessage2=true;				
	}
}
$xml_file3 = "../../../xml_vts/xml_last/862170018370757.xml";
if(file_exists($xml_file3))
{
	$modified_datetime3=filemtime($xml_file3);
	$server_time3=time();
	$timediff3=($modified_datetime3-$server_time3);
	/*$mdt1=date('Y-m-d H:i:s', $modified_datetime1);	
	$sdt1=date('Y-m-d H:i:s', $server_time);	
	echo "time_diff=".$timediff."<br>";*/
	if($timediff3>=1200)
	{
		$imei_no='862170018370757';
		$smessage3=true;				
	}
}

$xml_file4 = "../../../xml_vts/xml_last/862170018367183.xml";
if(file_exists($xml_file4))
{
	$modified_datetime4=filemtime($xml_file4);
	$server_time4=time();
	$timediff4=($modified_datetime4-$server_time4);
	/*$mdt1=date('Y-m-d H:i:s', $modified_datetime1);	
	$sdt1=date('Y-m-d H:i:s', $server_time);
	echo "time_diff=".$timediff."<br>";*/
	if($timediff4>=1200)
	{
		$imei_no='862170018367183';
		$smessage4=true;				
	}
}
if(!file_exists($store_file_path))
{
	if($smessage1==true && $smessage2==true && $smessage3==true && $smessage4==true)
	{	
		$mdt4=date('Y-m-d H:i:s', $modified_datetime4);	
		$sdt4=date('Y-m-d H:i:s', $server_time4);
		$message1="Your vehicle ".$imei_no." data delay at mtime".$mdt4." at stime".$sdt4;
		$login_id = "ashishiembsys";
		$senderid="itrack";
		$mobnum1="9935551952";
		$url_tmp="http://onlinesms.in/api/sendValidSMSdataUrl.php?login=ashishiembsys&pword=ak93179&msg=".$message1."&senderid=itrack&mobnum=".$mobnum1;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url_tmp);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "login=$login_id&pword=ak93179&senderid=$senderid&mobnum=$mobnum1&msg=$message1");
		$buffer = curl_exec($ch);
		if(empty ($buffer))
		{ 
			echo " buffer is empty "; 
		}
		else
		{ 
			echo $buffer; 
		}
		curl_close($ch);
		
		$mobnum2="9198895688";
		$url_tmp="http://onlinesms.in/api/sendValidSMSdataUrl.php?login=ashishiembsys&pword=ak93179&msg=".$message1."&senderid=itrack&mobnum==".$mobnum2;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url_tmp);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "login=$login_id&pword=ak93179&senderid=$senderid&mobnum=$mobnum2&msg=$message1");
		$buffer = curl_exec($ch);
		if(empty ($buffer))
		{ 
			echo " buffer is empty "; 
		}
		else
		{ 
			echo $buffer; 
		}
		curl_close($ch);
		
		$mobnum3="9935385122";
		$url_tmp="http://onlinesms.in/api/sendValidSMSdataUrl.php?login=ashishiembsys&pword=ak93179&msg=".$message1."&senderid=itrack&mobnum==".$mobnum3;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url_tmp);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "login=$login_id&pword=ak93179&senderid=$senderid&mobnum=$mobnum3&msg=$message1");
		$buffer = curl_exec($ch);
		if(empty ($buffer))
		{ 
			echo " buffer is empty "; 
		}
		else
		{ 
			echo $buffer; 
		}
		curl_close($ch);
		
		$mobnum4="9044914424";
		$url_tmp="http://onlinesms.in/api/sendValidSMSdataUrl.php?login=ashishiembsys&pword=ak93179&msg=".$message1."&senderid=itrack&mobnum==".$mobnum4;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url_tmp);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "login=$login_id&pword=ak93179&senderid=$senderid&mobnum=$mobnum4&msg=$message1");
		$buffer = curl_exec($ch);
		if(empty ($buffer))
		{ 
			echo " buffer is empty "; 
		}
		else
		{ 
			echo $buffer; 
		}
		curl_close($ch);
		
		$mobnum5="8574163090";
		$url_tmp="http://onlinesms.in/api/sendValidSMSdataUrl.php?login=ashishiembsys&pword=ak93179&msg=".$message1."&senderid=itrack&mobnum==".$mobnum5;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url_tmp);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "login=$login_id&pword=ak93179&senderid=$senderid&mobnum=$mobnum5&msg=$message1");
		$buffer = curl_exec($ch);
		if(empty ($buffer))
		{ 
			echo " buffer is empty "; 
		}
		else
		{ 
			echo $buffer; 
		}
		curl_close($ch);
		//$store_file_path="last_data_alert/tmp_file.txt";
		$fh = fopen($store_file_path, 'w') or die("can't open file 1"); // new
		fwrite($fh, "ok");  
		fclose($fh);
	}
}

	/*$xml_file2 = "../../../xml_vts/xml_last/862170018372654.xml";  
	$modified_datetime2= filemtime($xml_file2)
	$xml_file3 = "../../../xml_vts/xml_last/862170018370757.xml"; 
	$modified_datetime3=filemtime($xml_file3)

	$xml_file4 = "../../../xml_vts/xml_last/862170018367183.xml";  
	$modified_datetime4=filemtime($xml_file4)*/
   
  ?>
  




