<?php
//echo "test";
//error_reporting(-1);
//ini_set('display_errors', 'On');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//print_r($pathInPieces);
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3];
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2];

include_once($pathToRoot.'/beta/src/php/xmlParameters.php');
include_once($pathToRoot.'/beta/src/php/parameterizeData.php'); /////// for seeing parameters
include_once($pathToRoot.'/beta/src/php/data.php');   
include_once($pathToRoot.'/beta/src/php/getDeviceDataTest.php');

$sortBy="h";
$requiredData="All";

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
if($account_id=="1594")
{
    $parameterizeData->axParam = 'ax';
    $parameterizeData->ayParam = 'ay';
    $parameterizeData->azParam = 'az';
    $parameterizeData->mxParam = 'mx';
    $parameterizeData->myParam = 'my';
    $parameterizeData->mzParam = 'mz';
    $parameterizeData->bxParam = 'bx';
    $parameterizeData->byParam = 'by';
    $parameterizeData->bzParam = 'bz';
}

set_time_limit(300);
date_default_timezone_set('Asia/Calcutta');

$vserial = $_POST['imei'];
$startdate = $_POST['date1'];
$enddate = $_POST['date2'];

$startdate = str_replace('/','-',$startdate); 
$enddate = str_replace('/','-',$enddate); 
$rec = $_POST['record_len'];    

//echo "<br>vserial=".$vserial." ,st=".$startdate." ,ed=".$enddate." ,rec=".$rec;

$sts_arr = array();
$msgtype_arr = array();
$ver_arr = array();
$imei_arr = array();
$vname_arr = array();
$datetime_arr = array();                        
$sup_v_arr = array();                                         
$io1_arr = array();
$io2_arr = array();
$io3_arr = array();
$io4_arr = array();
$io5_arr = array();
$io6_arr = array();
$io7_arr = array();
$io8_arr = array();  
if($account_id=="1594")
{
  $ax_arr = array();  
  $ay_arr = array();
  $az_arr = array();
  $bx_arr = array();
  $by_arr = array();
  $bz_arr = array();
  $mx_arr = array();
  $my_arr = array();
  $mz_arr = array();
}


if($vserial)
{   
    get_log_xml_prev($vserial, $startdate, $enddate,$parameterizeData);
}

function get_log_xml_prev($vserial, $startdate, $enddate,$parameterizeData)
{
    global $DbConnection;
    $maxPoints = 1000;
    $file_exist = 0;  	
       
    $query = "SELECT vehicle_name FROM vehicle WHERE ".
    " vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$vserial' AND status=1) AND status=1";        
    //echo $query."<br>";
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $vname = $row->vehicle_name;    
     
    get_log_xml_data($vserial, $vname, $startdate, $enddate,$parameterizeData);
}

function get_log_xml_data($vserial, $vname, $startdate, $enddate,$parameterizeData)
{
    global $DbConnection;
    global $rec;
    global $sts_arr;
    global $msgtype_arr;
    global $ver_arr;
    global $imei_arr;
    global $vname_arr;
    global $datetime_arr;                        
    global $sup_v_arr;                                         
    global $io1_arr;
    global $io2_arr;
    global $io3_arr;
    global $io4_arr;
    global $io5_arr;
    global $io6_arr;
    global $io7_arr;
    global $io8_arr;
    global $account_id;
    if($account_id=="1594")
    {
        global $ax_arr;  
        global $ay_arr;
        global $az_arr;
        global $bx_arr;
        global $by_arr;
        global $bz_arr;
        global $mx_arr;
        global $my_arr;
        global $mz_arr;
    }
    global $lat_arr;
    global $lng_arr;
	
    $firstData = 0;	
    $linetowrite="";
    $firstdata_flag =0;
    $date_1 = explode(" ",$startdate);
    $date_2 = explode(" ",$enddate);

    $datefrom = $date_1[0];
    $dateto = $date_2[0];
    $timefrom = $date_1[1];
    $timeto = $date_2[1];

    get_All_Dates($datefrom, $dateto, $userdates);

    date_default_timezone_set("Asia/Calcutta");
    $current_datetime = date("Y-m-d H:i:s");
    $current_date = date("Y-m-d");
    //print "<br>CurrentDate=".$current_date;
    $date_size = sizeof($userdates);							
    $flag =0;	
    $rec_count =0;
  
    for($i=($date_size-1);$i>=0;$i--)
    {
        //echo "<br>in date=".$userdates[$i];	  
        if(($flag == 1) && ($rec!="all"))
        {
	    //echo "<br>in break";
            break;   // BREAK LOOP AFTER TAKING ONE DAY RECORDS- LAST 30/100/ALL
        }
        //echo "userdate=".$userdates[$di]."<br>";
        $SortedDataObject=null;
        $SortedDataObject=new data();
        if($date_size==1)
        {
            $dateRangeStart=$startdate;
            $dateRangeEnd=$enddate;
        }
        else if($di==($date_size-1))
        {
            $dateRangeStart=$userdates[$i]." 00:00:00";
            $dateRangeEnd=$enddate;
        }
        else if($di==0)
        {
            $dateRangeStart=$startdate;
            $dateRangeEnd=$userdates[$i]." 23:59:59";
        }
        else
        {
           $dateRangeStart=$userdates[$i]." 00:00:00";
            $dateRangeEnd=$userdates[$i]." 23:59:59";
        }
        deviceDataBetweenDates($vserial,$dateRangeStart,$dateRangeEnd,$sortBy,$parameterizeData,$SortedDataObject);
        //var_dump($SortedDataObject);
    	
              
        $vehicleserial_tmp=null;      
     
        $total_lines=count($SortedDataObject->deviceDatetime);
        if(count($SortedDataObject->deviceDatetime)>0)
        {
            $limit0 = $total_lines-10;
	    $limit1 = $total_lines-30;
            $limit2 = $total_lines-100; 
            $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);                   
            for($obi=0;$obi<$prevSortedSize;$obi++)
            {
                //echo "<br>line";
                //echo "<textarea>".$line."</textarea>";
                $rec_count++;            				
                //echo "<br>flag=".$flag." ,rec_count=".$rec_count." ,total_lines=".$total_lines." rec=".$rec." strlen=".strlen($line);
                
                if( ( ($rec_count == $limit1) || ($total_lines<30) ) && ($rec=="30") ) 
                {                   
                    //echo "<br>in 30 ,rec_count=".$rec_count;
                    $flag =1;
                }
                else if( ( ($rec_count == $limit2) || ($total_lines<100) )  && ($rec=="100") ) 
                {
                  //echo "<br>in 100";
                    $flag =1;
                }
                else if( ( ($rec_count == $limit0) || ($total_lines<10) ) && ($rec=="10") ) 
                {
                    //echo "<br>in 100";
                    $flag =1;
                }
                else if($rec=="all")
                {
                    //echo "<br>in all";
                    $flag =1;
                }	
                //echo "<br>flag=".$flag;
                if($flag == 1)
                { 
                $sts_arr[] = $SortedDataObject->serverDatetime[$obi];
                $msgtype_arr[] = $SortedDataObject->messageTypeData[$obi];;
                $ver_arr[] =$SortedDataObject->versionData[$obi];
                $imei_arr[] = $vserial; 
                $vname_arr[] = $vname;
                $datetime_arr[] = $SortedDataObject->deviceDatetime[$obi]; 
                $sup_v_arr[] =$SortedDataObject->supVoltageData[$obi];
                $io1_arr[]=$SortedDataObject->io1Data[$obi];
                $io2_arr[]=$SortedDataObject->io2Data[$obi];
                $io3_arr[]=$SortedDataObject->io3Data[$obi];;
                $io4_arr[]=$SortedDataObject->io4Data[$obi];
                $io5_arr[]=$SortedDataObject->io5Data[$obi];
                $io6_arr[]=$SortedDataObject->io6Data[$obi];
                $io7_arr[]=$SortedDataObject->io7Data[$obi];
                $io8_arr[]=$SortedDataObject->io8Data[$obi];
                
                if($account_id=='1594')
                {                      
                    $ax_arr[]=$SortedDataObject->axParamData[$obi];
                    $ay_arr[]=$SortedDataObject->ayParamData[$obi];
                    $az_arr[]=$SortedDataObject->azParamData[$obi];
                    $mx_arr[]= $SortedDataObject->mxParamData[$obi];
                    $my_arr[]=$SortedDataObject->myParamData[$obi];
                    $mz_arr[]=$SortedDataObject->mzParamData[$obi];
                    $bx_arr[]= $SortedDataObject->bxParamData[$obi];
                    $by_arr[]=$SortedDataObject->byParamData[$obi];
                    $bz_arr[]=$SortedDataObject->bzParamData[$obi];	
                }
                
                $lat_arr[]=$SortedDataObject->latitudeData[$obi];
                $lng_arr[]=$SortedDataObject->longitudeData[$obi];
                }
            }  // for closed    
        } // if (file_exists closed
    }  // for closed 
}

  // echo'<div class="scrollTableContainer"><table width="100%" border="1" cellpadding="0" cellspacing="0">';
  //echo'<tr><td>';
  echo '<center>';
  echo '<div style="width:1025px;height:600px;overflow:auto;" align="center">';
  ///////////////////  READ SPEED XML 	//////////////////////////////				                      
  //$xml_path = $xmltowrite;
  //echo "<br>xml_path=".$xml_path;
  //read_datalog_report_xml($xml_path, &$sts, &$msgtype, &$ver, &$fix, &$imei, &$vname, &$datetime, &$sup_v, &$speed_a, &$geo_in_a, &$geo_out_a, &$stop_a, &$move_a, &$lowv_a);
  //////////////////////////////////////////////////////////////////////
  //echo "<br>size datalog=".sizeof($vname);         
  if(sizeof($imei_arr)==0)
  {
echo '<br><font color=red><strong>Sorry No record found</strong></font>';
// echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=home.php\">";
  }  
  else
  {
    $sts_arr = array_reverse($sts_arr);
    $msgtype_arr = array_reverse($msgtype_arr);
    $ver_arr = array_reverse($ver_arr);    
    $imei_arr = array_reverse($imei_arr);
    $vname_arr = array_reverse($vname_arr);     
    $datetime_arr = array_reverse($datetime_arr);    
    $sup_v_arr = array_reverse($sup_v_arr);
    $io1_arr = array_reverse($io1_arr);
    $io2_arr = array_reverse($io2_arr);
    $io3_arr = array_reverse($io3_arr);
    $io4_arr = array_reverse($io4_arr);
    $io5_arr = array_reverse($io5_arr);
    $io6_arr = array_reverse($io6_arr);
    $io7_arr = array_reverse($io7_arr);
    $io8_arr = array_reverse($io8_arr);
    if($account_id=='1594')
    {
        $ax_arr = array_reverse($ax_arr);
        $ay_arr = array_reverse($ay_arr);
        $az_arr = array_reverse($az_arr);
        $mx_arr= array_reverse($mx_arr);
        $my_arr = array_reverse($my_arr);
        $mz_arr = array_reverse($mz_arr);
        $bx_arr = array_reverse($bx_arr);
        $by_arr = array_reverse($by_arr);
        $bz_arr = array_reverse($bz_arr);		
    }
    $lat_arr = array_reverse($lat_arr);
    $lng_arr = array_reverse($lng_arr);
  }    				  
  
  $csv_string = ""; 
  
  $sno = 1;
   
  for($i=0;$i<sizeof($imei_arr);$i++)
	{								              
    if(($i==0) || (($i>0) && ($imei_arr[$i-1] != $imei_arr[$i])) )
    {      
      $title = "VTS DataLog : ".$vname_arr[$i]." &nbsp;<font color=red>(".$imei_arr[$i].")</font>";
      echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
      echo'<form  name="text_data_report" method="post" target="_blank">
      <br><table align="center">
      <tr>
      	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
      </tr>
      </table>                					
  
      <table border="1" width="98%" rules="all" bordercolor="#e5ecf5" cellspacing="0" cellpadding="1">	
        <tr bgcolor="#C9DDFF"> 
       <td class="text"><b><font size="1">SNo</font></b></td>   
       <td class="text"><b><font size="1">STS</font></b></td>  
       <td class="text"><b><font size="1">DateTime</font></b></td>           				
       <td class="text"><b><font size="1">MsgTp</font></b></td>
        <td class="text"><b><font size="1">Ver</font></b></td>  
        <td class="text"><b><font size="1">SupplyV</font></b></td>  
        <td class="text"><b><font size="1">IO1</font></b></td>
        <td class="text"><b><font size="1">IO2</font></b></td>
        <td class="text"><b><font size="1">IO3</font></b></td>
        <td class="text"><b><font size="1">IO4</font></b></td>
        <td class="text"><b><font size="1">IO5</font></b></td>
        <td class="text"><b><font size="1">IO6</font></b></td>
        <td class="text"><b><font size="1">IO7</font></b></td>
        <td class="text"><b><font size="1">IO8</font></b></td>';
        if($account_id=='1594')
        {
            echo '<td class="text"><b><font size="1">AX &nbsp;&nbsp;</font></b></td>';
            echo '<td class="text"><b><font size="1">AY &nbsp;&nbsp;</font></b></td>';
            echo '<td class="text"><b><font size="1">AZ &nbsp;&nbsp;</font></b></td>';
            echo '<td class="text"><b><font size="1">MX &nbsp;&nbsp;</font></b></td>';
            echo '<td class="text"><b><font size="1">MY &nbsp;&nbsp;</font></b></td>';
            echo '<td class="text"><b><font size="1">MZ &nbsp;&nbsp;</font></b></td>';
            echo '<td class="text"><b><font size="1">BX &nbsp;&nbsp;</font></b></td>';
            echo '<td class="text"><b><font size="1">BY &nbsp;&nbsp;</font></b></td>';
            echo '<td class="text"><b><font size="1">BZ &nbsp;&nbsp;</font></b></td>';
        }
    echo'<td class="text"><b><font size="1">Latitude</font></b></td>
        <td class="text"><b><font size="1">Longitude</font></b></td>';
      echo'</tr>';      	
    }	

    if ($sno%2==0)
    {
      echo '<tr bgcolor="#F7FCFF">';
    }										
    else 
    {
      echo '<tr bgcolor="#E8F6FF">';	
    }
					                                     
    echo'<td class="text"><font size="1">'.$sno.'</font></td>';
    echo'<td class="text"><font size="1">'.$sts_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$datetime_arr[$i].'</font></td>';      
    echo'<td class="text"><font size="1">'.$msgtype_arr[$i].'</font></td>';      
    echo'<td class="text"><font size="1">'.$ver_arr[$i].'</font></td>'; 
    echo'<td class="text"><font size="1">'.$sup_v_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io1_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io2_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io3_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io4_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io5_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io6_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io7_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io8_arr[$i].'</font></td>';
    if($account_id=='1594')
    {
        echo'<td class="text">'.$ax_arr[$i].'</td>';
        echo'<td class="text">'.$ay_arr[$i].'</td>';
        echo'<td class="text">'.$az_arr[$i].'</td>';
        echo'<td class="text">'.$mx_arr[$i].'</td>';
        echo'<td class="text">'.$my_arr[$i].'</td>';
        echo'<td class="text">'.$mz_arr[$i].'</td>';
        echo'<td class="text">'.$bx_arr[$i].'</td>';
        echo'<td class="text">'.$by_arr[$i].'</td>';
        echo'<td class="text">'.$bz_arr[$i].'</td>';
    }	
    echo'<td class="text"><font size="1">'.$lat_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$lng_arr[$i].'</font></td>';
echo'</tr>'; 
	  $sno++; 
  }
  if( (($i>0) && ($imei_arr[$i+1] != $imei_arr[$i])) )
  {
  	echo '</table>';
	
  	/*if($report_type=="Person")
  	{
  	 echo'<input TYPE="hidden" VALUE="halt" NAME="csv_type">';
      echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';
    echo'<br>
  	<input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size=1\');" value="Get PDF" class="noprint">&nbsp;
      <input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">';
    }  */
	
  } 

echo '</div></center>';
// <form  name="text_data_report" method="post" target="_blank">'
echo'</tbody></table></div></form>';

if(sizeof($imei)>0)
{
  echo'
  <script type="text/javascript">
    //alert("k");
    var table3Filters = {
  	paging:true,
  	sort_select:true,				
  	col_1:"select",
  	col_2:"select",
  	col_3:"none",
  	col_4:"none"
  }
  setFilterGrid("table1",0,table3Filters); 
  </script>
  ';
}

//unlink($xml_path);
//echo "test";

?>
