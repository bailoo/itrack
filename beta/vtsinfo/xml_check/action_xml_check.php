<?php
include_once("../get_all_dates_between.php");
echo '<html>
<body>
<div align="right"><a href="../home.php" style="text-decoration:none;"><font color=blue size=3><strong>Home</strong></font></a>&nbsp;<a href="../logout.php" style="text-decoration:none;"><font color=green size=3><strong>Logout</strong></font></a></div> 
<br>';
 
$imei1 = $_POST['imei'];

$datefrom1 = $_POST['datefrom'];
$datefrom1 = str_replace("/","-",$datefrom1);

$dateto1 = $_POST['dateto'];
$dateto1 = str_replace("/","-",$dateto1);

//echo "<br>REC".$imei1." ,".$datefrom1." ,".$dateto1;

date_default_timezone_set("Asia/Calcutta");
$current_datetime = date("Y-m-d H:i:s");
$current_date = date("Y-m-d");


get_All_Dates($datefrom1, $dateto1, &$userdates);

$date_size = sizeof($userdates);
	
//echo "date size=".$date_size." ,".$datefrom1." ,".$dateto1;

for($i=0;$i<=($date_size-1);$i++)
{
	if($userdates[$i] == $current_date)
	{			
		//echo "in if";
		$xml_file = "/mnt/volume3/current_data/xml_data/".$userdates[$i]."/".$imei1.".xml";
		$CurrentFile = 1;
	}		
	else
	{
		$xml_file = "/mnt/volume3/current_data/sorted_xml_data/".$userdates[$i]."/".$imei1.".xml";
		$CurrentFile = 0;
	}  
  
  //echo $xml_file;
  echo '<div align="center">';
  if (file_exists($xml_file)) 
  {
	  echo "<br>IMEI:<font color=red>".$imei1."</font>  &nbsp;|&nbsp;  Date:".$userdates[$i]." &nbsp;|&nbsp; Status:<font color=green><strong>Found</strong></font>";
  }
  else
  {
    echo "<br>IMEI:<font color=red>".$imei1."</font> &nbsp;|&nbsp; Date:".$userdates[$i]." &nbsp;|&nbsp;Status:<font color=red><strong>Not Found</strong></font>";
  }
  echo '</div>';
}

echo '<br>
</body>
</html>';

?>
