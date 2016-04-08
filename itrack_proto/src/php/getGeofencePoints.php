<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$Query = "select * FROM geofence WHERE user_account_id=$account_id AND status=1";
//echo "query=".$Query."<br>";
$Result=mysql_query($Query,$DbConnection);
//echo "query=".$Result."<br>";
$NumRows=mysql_num_rows($Result);
//echo "query=".$NumRows."<br>";
if($NumRows>0)
{
	echo '<t1>';
	while($Row=mysql_fetch_object($Result))
	{
		$geo_code=str_replace("),(",":",base64_decode($Row->geo_coord));
		$geo_code1=str_replace(",","",$geo_code);
		$geo_code2=str_replace(":",",",$geo_code1);
		$geo_code3 = substr($geo_code2, 0, -1);  // returns "abcde"
		$geo_code4 = substr($geo_code3, 1);  // returns "abcde"
		echo '<marker ';
		echo 'geo_name="' . parseToXML($Row->geo_name) . '" ';	
		echo 'points="' . parseToXML($geo_code4) . '" ';	
		echo '/>';
	}
	echo '</t1>';
}
else
{
	echo "Geofence Not Found";
}

function parseToXML($htmlStr) 
{ 
$xmlStr=str_replace('<','&lt;',$htmlStr); 
$xmlStr=str_replace('>','&gt;',$xmlStr); 
$xmlStr=str_replace('"','&quot;',$xmlStr); 
$xmlStr=str_replace("'",'&apos;',$xmlStr); 
$xmlStr=str_replace("&",'&amp;',$xmlStr); 
return $xmlStr; 
} 
?>