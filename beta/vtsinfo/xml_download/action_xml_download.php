<?php
 //error_reporting(-1);
 //ini_set('display_errors', 'On');
//ini_set('memory_limit','200M');
set_time_limit(10000);	
date_default_timezone_set("Asia/Kolkata");
$vserial = $_POST['filename'];
//echo "vserial".$vserial."<br>";



$startdate = $_POST['startdate'];
$startdate = str_replace("/","-",$startdate);

$enddate = $_POST['enddate'];
$enddate = str_replace("/","-",$enddate);

//if((strtotime($enddate)-strtotime($startdate))>7*24*60*60)
{
    /*echo "<center>
                <br><br>
                <FONT color=\"red\">
                        Maximum 7 days report is allowed
                </font>
        </center>";*/
    //exit("Maximum 7 days report is allowed");
}

include_once('../../src/php/xmlParameters.php');
include_once('../../src/php/parameterizeData.php');
include_once('../../src/php/data.php');
include_once('../../src/php/getXmlData.php');

$sortBy="h";
$requiredData="All";

$parameterizeData=null;
$parameterizeData=new parameterizeData();
$parameterizeData->messageType='a';
$parameterizeData->version='b';
$parameterizeData->fix='c';
$parameterizeData->latitude='d';
$parameterizeData->longitude='e';
$parameterizeData->speed='f';
$parameterizeData->cellName='ab';
$parameterizeData->supVoltage='r';
$parameterizeData->dataLog='yes';
$parameterizeData->io1='i';
$parameterizeData->io2='j';
$parameterizeData->io3='k';
$parameterizeData->io4='l';
$parameterizeData->io5='m';
$parameterizeData->io6='n';
$parameterizeData->io7='o';
$parameterizeData->io8='p';
$parameterizeData->latitude='d';
$parameterizeData->longitude='e';
$parameterizeData->axParam = 'ax';
$parameterizeData->ayParam = 'ay';
$parameterizeData->azParam = 'az';
$parameterizeData->mxParam = 'mx';
$parameterizeData->myParam = 'my';
$parameterizeData->mzParam = 'mz';
$parameterizeData->bxParam = 'bx';
$parameterizeData->byParam = 'by';
$parameterizeData->byParam = 'bz';

    
$SortedDataObject=new data(); 
$rawData[]=array(
                'Vehicle Name',
                   $vserial
                );
$rawData[]=array(
                            'SNo','STS', 'DateTime','MsgTp','Version','Fix','Latitude','Longitude','Longitude','Speed',
                    'SupplyV', 'SgnlSt','io1','io2','io3', 'io4','io5','io6','io7','io8','ax','ay','az',
                    'mx','my','mz','bx','by','bz'
                );
$date_1 = explode(" ",$startdate);
$date_2 = explode(" ",$enddate);
$datefrom = $date_1[0];
$dateto = $date_2[0]; 
  
get_All_Dates($datefrom, $dateto, $userdates);    
$date_size = sizeof($userdates); 

for($di=0;$di<=($date_size-1);$di++)
{
    $SortedDataObject=null;
    $SortedDataObject=new data();
    if($date_size==1)
    {
        $dateRangeStart=$startdate;
        $dateRangeEnd=$enddate;
    }
    else if($di==($date_size-1))
    {
        $dateRangeStart=$userdates[$di]." 00:00:00";
        $dateRangeEnd=$enddate;
    }
    else if($di==0)
    {
        $dateRangeStart=$startdate;
        $dateRangeEnd=$userdates[$di]." 23:59:59";
    }
    else
    {
       $dateRangeStart=$userdates[$di]." 00:00:00";
        $dateRangeEnd=$userdates[$di]." 23:59:59";
    }
    deviceDataBetweenDatesLogOnly($vserial,$dateRangeStart,$dateRangeEnd,$sortBy,$parameterizeData,$SortedDataObject);

   // readFileXmlNew($vserial,$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
    //var_dump($SortedDataObject);
 
    if(count($SortedDataObject->deviceDatetime)>0)
    {
        //echo "in sorted=".$SortedDataObject->deviceDatetime."<br><br><br><br><br><br>";
        $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
        //echo "prevSortedSize=".$prevSortedSize."<br>";
        $sno=0;
        for($obi=0;$obi<$prevSortedSize;$obi++)
        {
            $sno++;                  
            $sts=$SortedDataObject->serverDatetime[$obi];
            $msgtype=$SortedDataObject->messageTypeData[$obi];
            $ver=$SortedDataObject->versionData[$obi];
            $fix=$SortedDataObject->fixData[$obi];
            $imei=$vserial[$i];
            $vname=$vehicle_detail_local[0];
            $vtype=$vehicle_detail_local[1];
            $userid=$user_id;
            $datetime=$SortedDataObject->deviceDatetime[$obi];
            $lat=$SortedDataObject->latitudeData[$obi];
            $lng=$SortedDataObject->longitudeData[$obi];
            $speed=$SortedDataObject->speedData[$obi];
            $cellname=$SortedDataObject->cellNameData[$obi];
            $io1=$SortedDataObject->io1Data[$obi];
            $io2=$SortedDataObject->io2Data[$obi];
            $io3=$SortedDataObject->io3Data[$obi];
            $io4=$SortedDataObject->io4Data[$obi];
            $io5=$SortedDataObject->io5Data[$obi];
            $io6=$SortedDataObject->io6Data[$obi];
            $io7=$SortedDataObject->io7Data[$obi];
            $io8=$SortedDataObject->io8Data[$obi];
            $sup_v=$SortedDataObject->supVoltageData[$obi];
            $sig_str=$SortedDataObject->sigStrData[$obi]; 

            $ax = ($SortedDataObject->axParamData[$obi]!='')? $SortedDataObject->axParamData[$obi] : '-';
            $ay = ($SortedDataObject->ayParamData[$obi]!='')? $SortedDataObject->ayParamData[$obi] : '-';
            $az = ($SortedDataObject->azParamData[$obi]!='')? $SortedDataObject->azParamData[$obi] : '-';
            $mx = ($SortedDataObject->mxParamData[$obi]!='')? $SortedDataObject->mxParamData[$obi] : '-';
            $my = ($SortedDataObject->myParamData[$obi]!='')? $SortedDataObject->myParamData[$obi] : '-';
            $mz = ($SortedDataObject->mzParamData[$obi]!='')? $SortedDataObject->mzParamData[$obi] : '-';
            $bx = ($SortedDataObject->bxParamData[$obi]!='')? $SortedDataObject->bxParamData[$obi] : '-';
            $by = ($SortedDataObject->byParamData[$obi]!='')? $SortedDataObject->byParamData[$obi] : '-';
            $bz = ($SortedDataObject->bzParamData[$obi]!='')? $SortedDataObject->bzParamData[$obi] : '-';
            $rawData[]=array(
                            "sno"=>$sno,
                 "sts"=>$sts,                
                "dateTime"=>$datetime,
                "msgtype"=>$msgtype,
                "ver"=>$ver,
                "fix"=>$fix,
                "lat"=>$lat,
                "lng"=>$lng,
                "speed"=>$speed,
                "sup_v"=>$sup_v,
                "sig_str"=>$sig_str,
                "io1"=>$io1,
                "io2"=>$io2,
                "io3"=>$io3,
                "io4"=>$io4,
                "io5"=>$io5,
                "io6"=>$io6,
                "io7"=>$io7,
                "io8"=>$io8,
                "ax"=>$ax,
                "ay"=>$ay,
                "az"=>$az,
                "mx"=>$mx,
                "my"=>$my,
                "mz"=>$mz,
                "bx"=>$bx,
                "by"=>$by,
                "bz"=>$bz
                );
            //$csv_string = $csv_string.$sno.','.$sts.','.$datetime.','.$msgtype.','.$ver.','.$fix.','.$lat.','.$lng.','.$speed.','.$sup_v.','.$sig_str.','.$io1.','.$io2.','.$io3.','.$io4.','.$io5.','.$io6.','.$io7.','.$io8.','.$ax.','.$ay.','.$az.','.$mx.','.$my.','.$mz.','.$bx.','.$by.','.$bz."\n";
        }
    }
}

//print_r($rawData);
        //echo $csv_string;
        //$stringData = $csv_string;
//$csv_type1 = $_POST['csv_type'];

//CREATE FILE
$t=time();
$filename = "reportDataLog".$t.".csv";
$root_dir = getcwd();
//$fileTmpPath= "C:\\xampp/htdocs/itrackDevelop/beta/src/php/csv_reports/";
$fileTmpPath="../../src/php/csv_reports/";
$path = $fileTmpPath.$filename;

//echo "<br>path1=".$path;

$fh = fopen($path, 'w') or die("can't open file");
$delimiter = ',';
//fputcsv($fh, $headerArr[0]->getProperties(), $delimiter);
foreach ($rawData as $element) {
    fputcsv($fh, $element);
}
//fwrite($fh, $stringData);
fclose($fh);
// CREATE FILE CLOSED
@chmod($path, 0777);
/// DOWNLOAD SCRIPT
if(file_exists(trim($path)))      
{	
  //$path = $_SERVER['DOCUMENT_ROOT']."/".$filepath; // change the path to fit your websites document structure   
  if ($fd = fopen ($path, "r")) 
  {
      $fsize = filesize($path);
      $path_parts = pathinfo($path);
      $ext = strtolower($path_parts["extension"]);
      switch ($ext) {
          case "pdf":
          header("Content-type: application/pdf"); // add here more headers for diff. extensions
          header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
          break;
          default;
          header("Content-type: application/octet-stream");
          header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
      }
      header("Content-length: $fsize");
      header("Cache-control: private"); //use this to open files directly
          
      while(!feof($fd)) {
          $buffer = fread($fd, 2048);
          echo $buffer;
      }
  }
  fclose ($fd);
  ignore_user_abort(true);
  unlink($path); 
  //exit;   
}
/// SCRIPT CLOSED
?>
