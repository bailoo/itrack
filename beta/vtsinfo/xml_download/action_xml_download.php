<?php
 //error_reporting(-1);
 //ini_set('display_errors', 'On');
set_time_limit(300);	
date_default_timezone_set("Asia/Kolkata");
$vserial = $_POST['filename'];
echo "vserial".$vserial."<br>";



$xmldate1 = $_POST['xmldate'];
$xmldate1 = str_replace("/","-",$xmldate1);

include_once('../../src/php/xmlParameters.php');
include_once('../../src/php/parameterizeData.php');
include_once('../../src/php/data.php');
include_once('../../src/php/getXmlData.php');

$sortBy="h";
$requiredData="All";


$parameterizeData=new parameterizeData();
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

    
$SortedDataObject=new data(); 	
$flag =0;
$rec_count =0;


$SortedDataObject=new data();
//echo "xmldate1=".$xmldate1."<br>";
readFileXmlNew($vserial,$xmldate1,$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
//var_dump($SortedDataObject);
  $csv_string = "";
  $csv_string = $csv_string."SNo,STS,DateTime,MsgTp,Version,Fix,Latitude,Longitude,Speed,SupplyV,SgnlSt,io1,io2,o3,io4,io5,io6,io7,io8\n";
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
                    $csv_string = $csv_string.$sno.','.$sts.','.$datetime.','.$msgtype.','.$ver.','.$fix.','.$lat.','.$lng.','.$speed.','.$sup_v.','.$sig_str.','.$io1.','.$io2.','.$io3.','.$io4.','.$io5.','.$io6.','.$io7.','.$io8."\n";
            }
        }
        //echo $csv_string;
        $stringData = $csv_string;
$csv_type1 = $_POST['csv_type'];

//CREATE FILE
$t=time();
$filename = "reportDataLog".$t.".csv";
$root_dir = getcwd();
$path = "../../src/php/csv_reports/".$filename;

//echo "<br>path1=".$path;

$fh = fopen($path, 'w') or die("can't open file");
fwrite($fh, $stringData);
fclose($fh);
// CREATE FILE CLOSED

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
  //exit;
    
  /*echo "<br><br><font color=green>Downloding file...</font>";
  if($buffer)
    echo "<br><br><br><font color=green><strong>file Download Successful...</strong></font>"; */
    
  //UNLINK FILE AFTER DOWNLOAD
  
  //$del_path = "csv_reports/".$filename;
  //echo "<br>path2=".$path;
  unlink($path); 
  
  exit;   
}
/// SCRIPT CLOSED
?>