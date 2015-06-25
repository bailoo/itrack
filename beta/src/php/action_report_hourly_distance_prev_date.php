<style>
table.menu
{font-size: 9pt;
margin: 0px;
padding: 0px;
font-weight: normal;}
</style>
<?php
    /** Include PHPExcel */
    include_once('src/php/util_session_variable.php');
	include_once('src/php/util_session_variable.php');
    $device_str = $_POST['deviceStr'];
	//print_r($device_str);
    //echo "<br>devicestr=".$device_str;
    //echo"personHourlyReport##";
	$vserial = explode(':',$device_str);
    $vsize=count($vserial);
    $chekcedVehicleSerial=array();
    for($i=0;$i<$vsize;$i++)
    {
        $chekcedVehicleSerial[$vserial[$i]]=$vserial[$i];
    }
   // print_r($chekcedVehicleSerial);
    $enterDate = $_POST['start_date'];
    $date1 = str_replace("/","-",$enterDate);
    // Here is the sample array of data
    date_default_timezone_set('Asia/Calcutta');
    $todayTime=explode(":",date("H:i:s"));
    $timeCnt=(integer)$todayTime[0];
    //echo "timeCnt=".$timeCnt."<br>";
    $timeCntClm=$timeCnt+3;	
    $destfile="../../../logBetaXml/".$date1."/distanceFileGet.xml";
  // echo "destFile=".$destfile."<br>";
    $xml = @fopen($destfile, "r") or $fexist = 0; 
    $c=0;
   
    /*if(file_exists($destfile))
    {
        echo "fileexist";
    }
    else
    {
        echo "fileNotExist";
    }*/


	while(!feof($xml))          // WHILE LINE != NULL
	{			
		$DataValid = 0;
		//echo "<br>line";
		//echo fgets($file). "<br />";
		$line = fgets($xml);  // STRING SHOULD BE IN SINGLE QUOTE	
		//echo "<textarea>".$line."</textarea>";

		$status = preg_match('/imei="[^" ]+/', $line, $vSerialTmp);
		$vSerialTmp1 = explode("=",$vSerialTmp[0]);
		$tmpVerial = preg_replace('/"/', '', $vSerialTmp1[1]);
		
		if($chekcedVehicleSerial[$tmpVerial]!="")
		{ 			
			$status = preg_match('/vn="[^"]+/', $line, $vNameTmp);
			$vNameTmp1 = explode("=",$vNameTmp[0]);
			$tmpVname= preg_replace('/"/', '', $vNameTmp1[1]);
			   
			$tmpEmpName ='not available';					
		
			preg_match('/dis="[^"]+/', $line, $disTmp);
			//print_r($addressTmp);
			$disTmp1 = explode("=",@$disTmp[0]);
			$disWithHour = preg_replace('/"/', '', @$disTmp1[1]);
			
			$disWithHour1=explode("#",$disWithHour);
			
		
			preg_match('/add="[^"]+/', $line, $addressTmp);
			//print_r($addressTmp);
			$addressTmp1 = explode("=",@$addressTmp[0]);
			$tmpAddress = preg_replace('/"/', '', @$addressTmp1[1]);
			
			
			$status = preg_match('/cdi="[^"]+/', $line, $vCdiTmp);
			$vCdiTmp1 = explode("=",$vCdiTmp[0]);
			$tmpCdi = preg_replace('/"/', '', $vCdiTmp1[1]);
			
			$vSerialArr[$tmpVerial][]=$tmpVerial;
			$vNameArr[$tmpVerial][]=$tmpVname;
			$empArr[$tmpVerial][]='not available';
			$mobNoArr[$tmpVerial][]='not available';
			$disArr1[$tmpVerial][]=$disWithHour1[0];
			$disArr2[$tmpVerial][]=$disWithHour1[1];
			$addressArr[$tmpVerial][]=$tmpAddress;
			//array('vserial'=>$tmpVerial,'pname'=>$tmpVname,'empNo'=>'not available','mobNo'=>'not available',$disWithHour1[1]=>$disWithHour1[0],'address'=>$tmpAddress);
		}
	}
	//print_r($data);
	    echo "<center>
            <table class='menu'>
                <tr>
                    <td style='font-size: 13px;font-style:bold;'>
                       Daily Tracking Report
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
           echo'<form name="personHourlyReport" method="POST" target="_blank" action="reportHoulyDistanceDownloadPrevDate.php">
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
        for($i=1;$i<=23;$i++)
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
        echo "<td><b>Total Distance</td>";
        echo "<td><b>Final Closure</td>";
        echo "</tr>";
		$firstFlag=0;
		$serial=1;
		//print_r($vSerialArr);
		//$da=1;
		$tmpCnt=0;
		for($i=0;$i<$vsize;$i++)
		{
		//echo "vSize=".$vserial[$i]."<br>";
			
			$totalDistance=0;
			$tmpVserialSize=sizeof($vSerialArr[$vserial[$i]]);
			//$tss='352384056985502';
		
			//if(count($vSerialArr[$vserial[$i]])>0 && $vserial[$i]=='358870054033281')
			if(count($vSerialArr[$vserial[$i]])>0)
			{
				$da=1;
				if($serial%2==0)
				{
				echo"<tr bgcolor='#C2DFFF'>";
				}
				else
				{
				echo"<tr bgcolor='#FFFDF9'>";
				}
				
				
				for($j=0;$j<$tmpVserialSize;$j++)
				{
					
					if($j==0)
					{
					echo"<td>".$serial."</td>
						<td>".$vNameArr[$vserial[$i]][$j]."</td>
						<td>".$empArr[$vserial[$i]][$j]."</td>
						<td>".$mobNoArr[$vserial[$i]][$j]."</td>";
					}
					//echo "da=".$da."<br>";
					$matchFlag=0;
					for($k=$da;$k<=23;$k++)
					{
						$matchFlag=0;
						if($k<10)
						{
							$disHourKey='d0'.$k;
						}
						else
						{
							$disHourKey='d'.$k;			
						}
						
						//echo "DA=".$k."<br>";
						//echo"vserial=".$vserial[$i]."name1=".$disArr2[$vserial[$i]][$j]."name2=".$disHourKey." dist=".$tmpDisThis." k=".$k." da".$da."<br>";
						if($disArr2[$vserial[$i]][$j]==$disHourKey)
						{
							$da=$k+1;
							$tmpDisThis=round($disArr1[$vserial[$i]][$j],2);
							
							echo "<td>".$addressArr[$vserial[$i]][$j]." (".$tmpDisThis.")</td>";
							$totalDistance=$totalDistance+$tmpDisThis;
							$matchFlag=1;
							break;
						}
						else
						{
							//echo" k=".$k." da".$da."<br>";
							echo "<td>-</td>";
						}
					}					
				}
				if($da!=23)
				{
					for($k=$da;$k<=23;$k++)
					{
						echo "<td>-</td>";
					}				
				}
				echo"<td>".$totalDistance."</td>";
				echo"<td>-</td>";
				echo"</tr>";
				$serial++;
		
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