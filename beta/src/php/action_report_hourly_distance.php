<style>
table.menu
{font-size: 9pt;
margin: 0px;
padding: 0px;
font-weight: normal;}
</style>
<?php 
    include_once('src/php/util_session_variable.php');
	include_once('src/php/util_session_variable.php');
    $device_str = $_POST['deviceStr'];
	$vserial = explode(':',$device_str);
    
	$vsize=count($vserial);
    $chekcedVehicleSerial=array();
    for($i=0;$i<$vsize;$i++)
    {
        $chekcedVehicleSerial[$vserial[$i]]=$vserial[$i];
    }
 
    date_default_timezone_set('Asia/Calcutta');
	$date1=date('Y-m-d');
    $todayTime=explode(":",date("H:i:s"));
    //$timeCnt=(integer)$todayTime[0];
  
    $timeCntClm=$timeCnt+3;	
    $destfile="../../../logBetaXml/".$date1."/processedData.xml";
	//echo "destFile=".$destfile."<br>";
	/*if(file_exists($destfile))
	{
		echo "true";
	}
	else
	{
		echo "false";
	}*/
	//echo "destFile=".$destfile."<br>";
    $xml = @fopen($destfile, "r") or $fexist = 0; 
    $c=0;
   

	
	  echo "<center>
            <table class='menu'>
                <tr>
                    <td style='font-size: 16px;font-style:bold;'>
                       Hourly Tracking Report
                    </td>
                 </tr>
            </table>
            <table>
                <tr>
                    <td style='height:5px;'>
                    </td>
                </tr>
           </table>
            </center>";
           echo'<form name="personHourlyReport" method="POST" target="_blank" action="reportHoulyDistanceDownload.php">
		   <div style="height:550px;overflow:auto">
               <table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3 class="menu"> ';   
            echo"<tr bgcolor='#EAC752'>
                    <td>
                    <b>SR No.
                    </td>
                     <td>
                   <b> Name
                    </td>
                     <td>
                   <b>Emp No
                    </td>
                     <td>
                 <b>  Mobile No
                    </td>";
        for($i=1;$i<=$todayTime[0];$i++)
		{
			if($i<10)
			{
				echo "<td><b>0".$i.":00</td>";
			}
			else
			{
				echo "<td><b>".$i.":00</td>";			
			}
		}
       // echo "<td><b>Total Distance</td>";
        //echo "<td><b>Final Closure</td>";
        echo "</tr>";
	$rowCnt=1;
	while(!feof($xml))          // WHILE LINE != NULL
	{			
		$DataValid = 0;
		//echo "<br>line";
		//echo fgets($file). "<br />";
		$line = fgets($xml);  // STRING SHOULD BE IN SINGLE QUOTE	
		//echo "<textarea>".$line."</textarea>";

		$status = preg_match('/vs="[^" ]+/', $line, $vSerialTmp);
		$vSerialTmp1 = explode("=",$vSerialTmp[0]);
		$tmpVerial = preg_replace('/"/', '', $vSerialTmp1[1]);
		
		if($chekcedVehicleSerial[$tmpVerial]!="")
		{ 
			if($$rowCnt%2==0)
			{
			echo"<tr bgcolor='#C2DFFF'>";
			}
			else
			{
			echo"<tr bgcolor='#FFFDF9'>";
			}
			$status = preg_match('/vn="[^"]+/', $line, $vNameTmp);
			$vNameTmp1 = explode("=",$vNameTmp[0]);
			$tmpVname= preg_replace('/"/', '', $vNameTmp1[1]);
			   
			$tmpEmpName ='not available';
			$tmpMobNo ='not available';	
			
		echo"<td>".$rowCnt."</td>
			<td>".$tmpVname."</td>
			<td>".$tmpEmpName."</td>
			<td>".$tmpMobNo."</td>";
			
			
			/*preg_match('/dis="[^"]+/', $line, $disTmp);
			//print_r($addressTmp);
			$disTmp1 = explode("=",@$disTmp[0]);
			$disWithHour = preg_replace('/"/', '', @$disTmp1[1]);
			
			$disWithHour1=explode("#",$disWithHour);*/
			
			for($i=1;$i<=$todayTime[0];$i++)
			{
				if($i<10)
				{
					preg_match('/a0'.$i.'="[^"]+/', $line, $addressTmp);
					//print_r($addressTmp);
					$addressTmp1 = explode("=",@$addressTmp[0]);
					$tmpAddress = preg_replace('/"/', '', @$addressTmp1[1]);
					if($tmpAddress!="")
					{
						echo"<td>".$tmpAddress."</td>";
					}
					else
					{
						echo"<td>-</td>";
					}
				}
				else
				{
					preg_match('/a'.$i.'="[^"]+/', $line, $addressTmp);
					//print_r($addressTmp);
					$addressTmp1 = explode("=",@$addressTmp[0]);
					$tmpAddress = preg_replace('/"/', '', @$addressTmp1[1]);
					if($tmpAddress!="")
					{
						echo"<td>".$tmpAddress."</td>";
					}
					else
					{
						echo"<td>-</td>";
					}
				}
				
			}
			echo"</tr>";
			$rowCnt++;
		}
	}
		echo"</table></div>";
        $strArr = serialize($chekcedVehicleSerial);
       $strArrEnc = urlencode($strArr);
        echo "<input type='hidden' name='checkedVehicleArr' value=".$strArrEnc.">";
	echo"</table>
             <table>
                <tr>
                    <td style='height:5px;'>
                    </td>
                </tr>
           </table>
            <input type='hidden' name='enterDate' value='".$date1."'>
            <center>
                <input type='submit' Value='Get Excel' onclick='javascript:getReportExcel()'>
           </center>
        </form>";	
?>