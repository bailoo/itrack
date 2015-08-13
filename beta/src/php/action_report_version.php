<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include("user_type_setting.php");

$root=$_SESSION["root"];
set_time_limit(200);

include_once('parameterizeData.php');
include_once('data.php');  
include_once("getXmlData.php");
include_once("calculate_distance.php");

$device_str = $_POST['vehicleserial'];
//echo "<br>devicestr=".$device_str;
$vserial = explode(':',$device_str);
$vsize=count($vserial);

$sortBy='h';
$requiredData="All";
$parameterizeData=new parameterizeData();
$parameterizeData->version="b";
$current_date = date("Y-m-d");
if($vsize>0)
{
    for($i=0;$i<$vsize;$i++)
    {
        $dataCnt=0;
        $vehicle_info=get_vehicle_info($root,$vserial[$i]);
        $vehicle_detail_local=explode(",",$vehicle_info);
        $SortedDataObject=new data();
        readFileXmlNew($vserial[$i],$current_date,$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
        //var_dump($SortedDataObject);
        if(count($SortedDataObject->deviceDatetime)>0)
        {
            $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
            for($obi=0;$obi<$prevSortedSize;$obi++)
            {           
                $versionThis = $SortedDataObject->versionData[$obi];  
                if($versionThis)
                {
                    $imei[]=$vserial[$i];
                    $vname[]=$vehicle_detail_local[0];
                    $version[]=$versionThis;
                    $versionThis =0;
                    break; 		                		                     
                }
            }
        }
         $SortedDataObject=null;
    }
    $parameterizeData=null;
    $o_cassandra->close();
}
    

echo '<center><h3>Version Report</h3><br>';

echo '<div align="center" style="width:100%;height:450px;overflow:auto;">'; 					
///////////////////  READ SPEED XML 	//////////////////////////////				                      
$xml_path = $xmltowrite;
$j=-1;

echo '<table border=1 width="60%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>    
    <tr>
				<td class="text" align="left" width="10%"><b>SNo</b></td>														
				<td class="text" align="left" width="20%"><b>IMEI</b></td>
				<td class="text" align="left"><b>VehicleName</b></td>
				<td class="text" align="left" width="15%"><b>Version</b></td>				
    </tr>';	

$sno = 1;
for($i=0;$i<sizeof($imei);$i++)
{				        														
	echo'<tr><td class="text" align="left" width="10%"><b>'.$sno.'</b></td>';
	echo'<td class="text" align="left">'.$imei[$i].'</td>';	
	echo'<td class="text" align="left">'.$vname[$i].'</td>';	
	echo'<td class="text" align="left">'.$version[$i].'</td>';							
	echo'</tr>';							
  $sno++;      				  				
}

echo '</table>';							
//PDF CODE

echo '<form method = "post" target="_blank">';
$csv_string = "";
    
for($i=0;$i<sizeof($imei);$i++)
{	                   
    if($i==0)
    {
    	$title="Version Report";
    	//echo "<br>pl=".$pdf_place_ref;
    	$csv_string = $csv_string.$title."\n";
    	$csv_string = $csv_string."SNo,IMEI, VehicleName, Version\n";
      echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
    }
    														
    $sno_1 = $i+1;										
    echo"<input TYPE=\"hidden\" VALUE=\"$sno_1\" NAME=\"temp[$i][SNo]\">";
    echo"<input TYPE=\"hidden\" VALUE=\"$imei[$i]\" NAME=\"temp[$i][IMEI]\">";
    echo"<input TYPE=\"hidden\" VALUE=\"$vname[$i]\" NAME=\"temp[$i][VehicleName]\">";    
    echo"<input TYPE=\"hidden\" VALUE=\"$version[$i]\" NAME=\"temp[$i][Version]\">";
    
    $csv_string = $csv_string.$sno_1.','.$imei[$i].','.$vname[$i].','.$version[$i]."\n"; 
    ////////////////////////////////////         	          
}		
				
if(sizeof($imei)==0)
{						
	print"<center><FONT color=\"Red\" size=2><strong>No Version Record found</strong></font></center>";
	echo'<br><br>';
}	
else
{
  echo'<input TYPE="hidden" VALUE="Version" NAME="csv_type">';
  echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
  echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size='.sizeof($imei).'\');" value="Get PDF" class="noprint">&nbsp;
  <input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
  <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';
}
echo '</form>';
echo '</center>';
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';
?>
					