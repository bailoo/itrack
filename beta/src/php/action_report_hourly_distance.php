<?php
include_once("main_vehicle_information_1.php");
 include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');


echo'<link rel="StyleSheet" href="src/css/menu.css">';
include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('lastRecordData.php');
include_once('getXmlData.php');	
if($personOption=="singlePerson")
{
	//echo "in if";
	//echo"vehicleserialRadio=".$vehicleserialRadio."<br>";
	
	//var_dump($root);
	$vehicle_info=get_vehicle_info($root,$vehicleserialRadio);
	//echo "vehicleInfo=".$vehicle_info."<br>";	
	$vehicle_detail_local=explode(",",$vehicle_info);
	//print_r($vehicle_detail_local);

	$parameterizeData=new parameterizeData();
	$parameterizeData->version='b';
	$LastRecordObject=getLastRecord($vehicleserialRadio,$sortBy,$parameterizeData);
	$sortBy="h";
	$versionString=$LastRecordObject->versionLR[0];
	$vehicleDetailArr[$vehicleserialRadio]=$vehicle_detail_local[0]."@".$vehicle_detail_local[8]."@".$versionString;
	//print_r($vehicleDetailArr);
	preg_match('#\((.*?)\)#', $versionString, $match);
	$versionOnly=$match[1];
	$version=substr($versionOnly,0,-2);

	$versionArr=explode('-',$version);
	if(count($versionArr)==1)
	{
		$durationFrom=0;
		$durationTo=$versionArr[0];
	}
	else
	{
		$durationFrom=$versionArr[0];
		$durationTo=$versionArr[1];
	}
	//echo "durationFron=".$durationFrom."DurationTo=".$durationTo."<br>";

	//exit();
}
else if($personOption=="multiplePerson")
{
	$durationFrom=8;
	$durationTo=21;	
}

$mysqlTableColumns="";
$mysqlDistTableColumns="";
$switchFlag=0;

for($i=$durationFrom;$i<=$durationTo;$i++) // for making dynamic column duration half hour fetching record from mysql
{
	$hr=($i<10)?'0'.$i:$i;
	if($timeInterval==0) /////// this is only for 30 minute interval
	{
            if("HR_".$hr."_00_LOC"=="HR_00_00_LOC") //for setting 00_30 colume only
            {
                $mysqlTableColumns=$mysqlTableColumns."HR_".$hr."_30_LOC,";
                 $mysqlDistTableColumns=$mysqlDistTableColumns."HR_".$hr."_30_DIST,";
           
            }
            else
            {
		$mysqlTableColumns=$mysqlTableColumns."HR_".$hr."_00_LOC,";
                $mysqlDistTableColumns=$mysqlDistTableColumns."HR_".$hr."_00_DIST,";
		if($i!=$durationTo) // for skiping last column because it exceed duration time
		{
			$mysqlTableColumns=$mysqlTableColumns."HR_".$hr."_30_LOC,";
                        $mysqlDistTableColumns=$mysqlDistTableColumns."HR_".$hr."_30_DIST,";
		}
            }
	}
	else ///// this for except interval
	{	
		if($switchFlag==0)
		{
			$mysqlTableColumns=$mysqlTableColumns."HR_".$hr."_00_LOC,";
			$mysqlTableColumns=$mysqlTableColumns."HR_".$hr."_30_LOC,";
		}
		else
		{
			$mysqlTableColumns=$mysqlTableColumns."HR_".$hr."_00_LOC,";
			//echo "i=".$i."durationTo=".$durationTo."<br>";
			if($i!=$durationTo) // for skiping last column becuase it exceed duration time
			{
				$mysqlTableColumns=$mysqlTableColumns."HR_".$hr."_30_LOC,";	
			}			
		}
	}
	$switchFlag=1;
}

$switchFlag=0;
$durationThis=0;
for($i=$durationFrom;$i<=$durationTo;$i++) ///// this is for column headings of table
{
	if($switchFlag==0) // for storing value of i one time in durationThis variable
	{
		$durationThis =$i;
	}
	
	$durationThis=$durationThis+$timeInterval;		
	if($durationThis>$durationTo) // when durationThis exceed from date end range than break the loop
	{
		break;
	}
	
	$hr=($i<10)?'0'.$i:$i;
	if($timeInterval==0) /////// this is only for 30 minute interval
	{
              
            if($hr."_00"=="00_00") //for setting 00_30 colume only
            {
                $durationArr[]=$hr.":30";
            }
            else
            {
		$durationArr[]=$hr.":00";
		if($i!=$durationTo) // for skiping last column becuase it exceed duration time
		{
			$durationArr[]=$hr.":30";
		}
            }
	}
	else ///// this for except interval
	{	
		if($switchFlag==0)
		{				
			$durationThis =$hr;
			$durationArr[]=$hr.":00";
		}
		else
		{
			$durationArr[]=$durationThis.":00";		
		}
	}
	$switchFlag=1;
}

//print_r($durationArr);
//echo"<br><br>";
//echo" personOption=".$personOption."<br>";

$mysqlTableColumns=substr($mysqlTableColumns,0,-1);
$mysqlDistTableColumns=substr($mysqlDistTableColumns,0,-1);

//echo" mysqlTableColumns=".$mysqlTableColumns."<br>";
if($personOption=="singlePerson")
{
$Query="SELECT imei,date,".$mysqlTableColumns.",".$mysqlDistTableColumns." FROM hourly_distance_log USE INDEX(date_imei) WHERE imei='$vehicleserialRadio'".
	   " AND date BETWEEN '$start_date' AND '$end_date'";
//echo "Query1=".$Query."<br>";
$Result=mysql_query($Query,$DbConnection);
}
else if($personOption=="multiplePerson")
{
    
$imeiCondition="";
for($i=0;$i<sizeof($vehicleserial);$i++)
{
    $vehicle_info=get_vehicle_info($root,$vehicleserial[$i]);
    //echo "vehicleInfo=".$vehicle_info."<br>";	
    $vehicle_detail_local=explode(",",$vehicle_info);	
    $parameterizeData=new parameterizeData();
    $parameterizeData->version='b';
    $LastRecordObject=getLastRecord($vehicleserial[$i],$sortBy,$parameterizeData);
    $sortBy="h";
    $versionString=$LastRecordObject->versionLR[0];
    $vehicleDetailArr[$vehicleserial[$i]]=$vehicle_detail_local[0]."@".$vehicle_detail_local[8]."@".$versionString;
    $vSerialMultiple=explode(',',$vSerial[$i]);	
    $imeiCondition=$imeiCondition."imei='".$vehicleserial[$i]."' OR ";
}
$imeiCondition=substr($imeiCondition,0,-3);
$Query="SELECT imei,date,latitude,longitude,".$mysqlTableColumns.",".$mysqlDistTableColumns." FROM hourly_distance_log USE INDEX(imei,date) WHERE  date='$single_date' AND".
		" ($imeiCondition)";
//echo "Query2=".$Query."<br>";
$Result=mysql_query($Query,$DbConnection);
//print_r($vSerial);
}
if($timeInterval==0)
{
	$dataInterval=1;
}
else
{
	$dataInterval=($timeInterval*60)/30;
}

echo'<center><br>
		<font color="black"><b>Hourly Distance Report</font>
	</center>';
if(mysql_num_rows($Result)==0)
{
echo'<center><br>
		<font color="red"><b>No data found for selected option.</font>
	</center>';
	exit();
}

echo'<center><br>
<table class="menu" border=1 rules=all bordercolor="#e5ecf5" style="font-size: 10pt;margin: 0px;padding: 0px;font-weight: normal;" cellspacing=3 cellpadding=3>
		<tr bgcolor="darkgray">
			<td>
			<b>Serial
			</td>
			<td>
			<b>Date
			</td>
			<td>
			<b>User Name
			</td>
			<td>
			<b>Mobile Number
			</td>
			<td>
			<b>Apd Version
			</td>';
	for($i=0;$i<sizeof($durationArr);$i++)
	{
		echo"<td><b>".$durationArr[$i]."</td>";
	}
		echo"</tr>";
	$serial=1;
	$mysqlTableColumnsArr=explode(",",$mysqlTableColumns);
        $mysqlDistTableColumns=explode(",",$mysqlDistTableColumns);
        
	//echo "mysqlTableColumns=".$mysqlTableColumns."<br>";
	$columnSize=sizeof($mysqlTableColumnsArr);
	
	
	while($row=mysql_fetch_object($Result))
	{
            if($serial%2==0)
            {
                    echo"<tr bgcolor='lightgray'>";
            }
            else
            {
                    echo"<tr>";
            }
	$imeiDetailArr=explode("@",$vehicleDetailArr[$row->imei]);
	echo"<td>".$serial."</td>
		<td>".$row->date."</td>
		<td>".$imeiDetailArr[0]."</td>
		<td>".$imeiDetailArr[1]."</td>
		<td>".$imeiDetailArr[2]."</td>";
		//echo"columnSize=".$columnSize."<br>";
		//echo"dataInterval=".$dataInterval."<br>";
		$durationBreakCount=1;
		$culumnSum=0;
		
		for($ci=0;$ci<$columnSize;$ci++)
		{
			if($ci==0)
			{
				echo"<td>".$row->$mysqlTableColumnsArr[$ci]."</td>";
				continue;
			}
			
			if($durationBreakCount<=$dataInterval)
			{
				//$culumnSum+=$culumnSum+$row->$mysqlTableColumnsArr[$ci];
				//echo"durationBreakCount=".$durationBreakCount."dataInterval=".$dataInterval."mysqlTableColumnsArr=".$mysqlTableColumnsArr[$ci]."<br>";
				if($durationBreakCount==$dataInterval)
				{
					echo"<td>".$row->$mysqlTableColumnsArr[$ci]."[".$mysqlDistTableColumns[$ci]."]</td>";
					$culumnSum=0;
					$durationBreakCount=1;
					if($ci==$columnSize)
					{
						break;
					}
					continue;
				}			
				$durationBreakCount++;
			}
		}
echo"</tr>";
$serial++;
	}		
echo"</table></center>";
exit();
		
			
