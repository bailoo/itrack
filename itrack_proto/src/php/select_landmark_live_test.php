<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

$query = "SELECT * FROM landmark WHERE account_id='$account_id' AND status=1";
$result = mysql_query($query,$DbConnection);

$size=0;
while($row=mysql_fetch_object($result))
{
	$landmark[$size]=$row->landmark_name;
	$coord = $row->landmark_coord;
	
	$zoomlevel[$size]=$row->zoom_level;
	
	$coord1 = explode(',',$coord);
	$lat[$size]= $coord1[0];
	$lng[$size]= $coord1[1];
	
	$size++;
}	
	
///////////////////////////////////// xml code starts here///////////////////////
//require("phpsqlajax_dbinfo.php");
function parseToXML($htmlStr) 
{ 
$xmlStr=str_replace('<','&lt;',$htmlStr); 
$xmlStr=str_replace('>','&gt;',$xmlStr); 
$xmlStr=str_replace('"','&quot;',$xmlStr); 
$xmlStr=str_replace("'",'&apos;',$xmlStr); 
$xmlStr=str_replace("&",'&amp;',$xmlStr); 
return $xmlStr; 
} 

header("Content-type: text/xml;charset=iso-8859-1");

// Start XML file, echo parent node
echo '<t1>';

for($i=0;$i<$size;$i++)
{
	//ADD TO XML DOCUMENT NODE
	echo '<marker ';
	echo 'lat="' . parseToXML($lat[$i]) . '" ';	
	echo 'lng="' . parseToXML($lng[$i]) . '" ';	
	echo 'landmark="' . parseToXML($landmark[$i]) . '" ';	
	echo 'zoomlevel="' . parseToXML($zoomlevel[$i]) . '" ';	
	echo '/>';		
	
} //loop $j closed

echo '</t1>';

?>
