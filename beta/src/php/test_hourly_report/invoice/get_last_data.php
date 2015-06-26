<?php
$DBASE = "iespl_vts_beta";
$HOST = "localhost";
$USER = "root";
$PASSWD = 'neon04$VTS';
$account_id = "715";
echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

$vname = "CG 04JD 5155";
$query_imei = "SELECT device_imei_no FROM vehicle_assignment WHERE vehicle_name='$vname' AND status=1";
echo "\nQuery_imei=".$query_imei;
$result_imei = mysql_query($query_imei, $DbConnection);
$row_imei = mysql_fetch_object($result_imei);
$imei = $row_imei->device_imei_no;
//$imei='862170016373839';
echo "\nIMEI=".$imei;
$coord = get_last_data($imei);
//echo "\nCoord=".$coord;
$coord_arr = explode(':',$coord);

//$coord_arr[0] = "26.34234";
//$coord_arr[1] = "80.34324";
//$file = file_get_contents('www.itracksolution.co.in/get_url_location.php?lat='.$coord_arr[0].'&lng='.$coord_arr[1], true);
$location = file_get_contents('http://www.itracksolution.co.in/src/php/get_url_location.php?lat='.$coord_arr[0].'&lng='.$coord_arr[1], FILE_USE_INCLUDE_PATH);
//var_dump($file);
echo $location;


function get_last_data($imei)
{
	$vh ="h";
	$vd = "d";
	$ve = "e";	
	$fix_tmp = 1;
	$xml_file = "/var/www/html/vts/xml_vts/xml_last/".$imei.".xml";	
		
	//echo "\nXmlFile=".$xml_file;
	
	if (file_exists($xml_file)) 
	{
		//echo "\nXmlFile-Exist";
		$t=time();
		$xml_original_tmp = "/var/www/html/vts/xml_tmp/original_xml/tmp_".$imei."_".$t.".xml";
		//echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
										  
		//echo "<br>ONE<br>";
		copy($xml_file,$xml_original_tmp);
  
		//$total_lines = count(file($xml_original_tmp));
		//echo "<br>Total lines orig=".$total_lines;      
		$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
		$format =2;
	   
		if (file_exists($xml_original_tmp)) 
		{					
			//echo "\nXmlOriginalExist";			
			while(!feof($xml))          // WHILE LINE != NULL
			{
				$DataValid = 0;
				//echo fgets($file). "<br />";
				$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			

				if(strlen($line)>20)
				{
				  $linetmp =  $line;
				}
			 
				if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
				{
					$format = 1;
					$fix_tmp = 1;
				}
					
				else if(strpos($line,''.$vc.'="0"'))
				{
					$format = 1;
					$fix_tmp = 0;
				}		
				
				if( (preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
				{ 
					$lat_value = explode('=',$lat_match[0]);
					$lng_value = explode('=',$lng_match[0]);
					//echo " lat_value=".$lat_value[1];         
					if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
					{
						$DataValid = 1;
					}
				}
		  
				if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
				{
					$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
					$datetime_tmp1 = explode("=",$datetime_tmp[0]);
					$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
					$xml_date = $datetime;
				}				
				//echo "\nxml_date=".$xml_date." datavalid=".$DataValid;				  
				if($xml_date!=null)
				{
					if( ($xml_date!="-") && ($DataValid==1) )
					{
						//echo "\nLine=".$line." ,vd=".$vd." ,ve=".$ve;
						$lat = get_xml_data('/'.$vd.'="\d+\.\d+[NS]\"/', $line);
						$lng = get_xml_data('/'.$ve.'="\d+\.\d+[EW]\"/', $line);
						$coord = $lat.":".$lng;
						//echo $coord;
						return $coord;						
					} // $xml_date_current >= $startdate closed
				}   // if xml_date!null closed
				$count++;
			}   // while closed
		} // if original_tmp exist closed 
	  
		fclose($xml);            
		unlink($xml_original_tmp);
	} // if (file_exists closed
	
	return null;
}


function get_xml_data($reg, $line)
{
		$data = "";
		if(preg_match($reg, $line, $data_match))
		{
				$data = explode_i('"', $data_match[0], 1);
		}
		return $data;
}

function explode_i($reg, $str, $i)
{
		$tmp = explode($reg, $str);
		return $tmp[$i];
}

?>								
