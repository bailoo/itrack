<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

$pathtowrite = $_REQUEST['xml_file_station'];
$select_value1 = $_REQUEST['select_val'];
//echo "<br>".$pathtowrite;
//$pathtowrite = "/var/www/html/itrack_vts/xml_tmp/filtered_xml/tmp_station.xml";
//$pathtowrite = "../../../itrack_vts/xml_tmp/filtered_xml/tmp_station.xml";  
write_station_data($pathtowrite);

function write_station_data($pathtowrite)
{
  global $DbConnection;
  global $account_id;
  global $select_value1;
  //$account_id = 231;
     
  //echo $pathtowrite."<br>";
  $fh = fopen($pathtowrite, 'w') or die("can't open file 1"); // new
  fwrite($fh, "<t1>");  
  fclose($fh);
  
  $query = "SELECT DISTINCT station_name,station_coord,customer_no,type FROM station WHERE user_account_id='$account_id' AND type='$select_value1' AND status=1";
  
  $result = mysql_query($query,$DbConnection);
  //echo $query;
  
  $size=0;
  while($row=mysql_fetch_object($result))
  {
  	$station[$size]=$row->station_name;
  	$customer[$size]=$row->customer_no;
  	$coord = $row->station_coord;
  	$type[$size] = $row->type;
  	
  	$coord1 = explode(',',$coord);
  	$lat[$size]= trim($coord1[0]);
  	$lng[$size]= trim($coord1[1]);
  	
  	$size++;
  }	
  
  //echo "<br>size".$size;   
  $fh = fopen($pathtowrite, 'a') or die("can't open file pathtowrite"); //append 
   
  for($i=0;$i<$size;$i++)
  {
  	//$station[$i] = "abc";
    //$line = "\n".$line.'< marker lat="'.trim($lat[$i]).'" lng="'.trim($lng[$i]).'" station="'.$station[$i].'" customer="'.$customer[$i].'"/>';
  	$station[$i] = str_replace('/', 'by', $station[$i]);
  	$station[$i] = str_replace('\\', 'by', $station[$i]);
  	$station[$i] = str_replace('&', 'and', $station[$i]);
    $linetowrite = "\n<marker lat=\"".trim($lat[$i])."\" lng=\"".trim($lng[$i])."\" station=\"".$station[$i]."\" customer=\"".$customer[$i]."\" type=\"".$type[$i]."\"/>";
    fwrite($fh, $linetowrite);  
    //echo "In loop";     	
  } //loop $j closed

  fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
  fwrite($fh, "\n</t1>");  
  fclose($fh);
}

?>
