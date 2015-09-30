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
include_once('../../src/php/xmlParameters.php');
include_once('../../src/php/parameterizeData.php');
include_once('../../src/php/data.php');
include_once('../../src/php/getXmlData.php');

$parameterizeData=null;
$parameterizeData=new parameterizeData();

$parameterizeData->messageType='a';
$parameterizeData->version='b';
$parameterizeData->fix='c';
$parameterizeData->latitude='d';
$parameterizeData->longitude='e';
$parameterizeData->speed='f';	
$parameterizeData->io1='i';
$parameterizeData->io2='j';
$parameterizeData->io3='k';
$parameterizeData->io4='l';
$parameterizeData->io5='m';
$parameterizeData->io6='n';
$parameterizeData->io7='o';
$parameterizeData->io8='p';	
$parameterizeData->sigStr='q';
$parameterizeData->supVoltage='r';
$parameterizeData->dayMaxSpeed='s';
$parameterizeData->dayMaxSpeedTime='t';
$parameterizeData->lastHaltTime='u';
$parameterizeData->cellName='ab';


for($i=0;$i<=($date_size-1);$i++)
{
    $LastDataObject =null;
    $LastDataObject=new lastDataObj();		
    $type="unSorted";
    $date1=$userdates[$i]." 00:00:00";
    $date2=$userdates[$i]." 23:59:59";

    $vserial=$imei1.".xml";
    //var_dump($LastDataObject);
    getLastPositionXMl($vserial,$date1,$date2,$datefrom,$dateto,$sortBy,$type,$parameterizeData,$LastDataObject);
    //var_dump($LastDataObject);
    if ($LastDataObject->deviceDatetimeLD[0]!="")
    {
        //var_dump($LastRecordObject);
        $linetmp=$linetmp.'<x a="'.$LastDataObject->messageTypeLD[0].'" b="'.$LastDataObject->versionLD[0].'" c="'.$LastDataObject->fixLD[0].'" d="'.$LastDataObject->latitudeLD[0].'" e="'.$LastDataObject->longitudeLD[0].'" f="'.$LastDataObject->speedLD[0].'" g="'.$LastDataObject->serverDatetimeLD[0].'" h="'.$LastDataObject->deviceDatetimeLD[0].'" i="'.$LastDataObject->io1LD[0].'" j="'.$LastDataObject->io2LD[0].'" k="'.$LastDataObject->io3LD[0].'" l="'.$LastDataObject->io4LD[0].'" m="'.$LastDataObject->io5LD[0].'" n="'.$LastDataObject->io6LD[0].'" o="'.$LastDataObject->io7LD[0].'" p="'.$LastDataObject->io8LD[0].'" q="'.$LastDataObject->sigStrLD[0].'" r="'.$LastDataObject->suplyVoltageLD[0].'" s="'.$LastDataObject->dayMaxSpeedLD[0].'" t="'.$LastDataObject->dayMaxSpeedTimeLD[0].'" u="'.$LastDataObject->lastHaltTimeLD[0].'" v="'.$vserial[$i].'" w="'.$vehicle_detail_local[0].'" x="'.$vehicle_detail_local[2].'" y="'.$vehicle_detail_local[1].'" z="'.$vehicle_detail_local[8].'"/>#';
    }
  
    //echo $xml_file;
    echo '<div align="center">';
    if (count($LastDataObject->deviceDatetimeLD)>0) 
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
