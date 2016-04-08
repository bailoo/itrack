<?php
	 include_once('util_session_variable.php');
   include_once('util_php_mysql_connectivity.php');   
   include_once("module_report_get_tablename.php");
   $DEBUG=0;
   $v_serial=$_POST['v_serial'];   
   $v_serial=explode(",",$v_serial);
   $v_serial1=sizeof($v_serial);
   $startdate1=$_POST['startdate'];
   $enddate1=$_POST['enddate'];  
   $display_mode=$_POST['mode'];  
   echo"text_report##";
   if($DEBUG==1)
   {
      echo " varhicleserial=".$v_serial." size_of_vserial=".$v_serial." startdate=".$startdate1." enddate1=".$enddate1." timezone=".$time_zone." mode=".$display_mode;
   }
?>
																							
<?php
/*if($access=='Zone')
{
  //echo "zone_id=".$zoneid;
	include("get_mining_location.php");
}
else
{
	include("get_location.php");
}*/ 

$StartDate = str_replace("/","-",$startdate1);	
$end_date = str_replace("/","-",$enddate1);
$e=explode(" " ,$end_date);
$enddatetotaltime=strtotime($end_date);
$e_d=explode('-',$e[0]);
$c_e = count($e);

$ed_d=$e_d[2]*86400;
$e_d1=$enddatetotaltime-$ed_d;
$e_d1=date('Y-m-d H:i:s',$e_d1);
//date_default_timezone_set('Asia/Calcutta');
$e_d2=explode(" ",$e_d1);
$e_d3=$e_d2[0]." "."23:59:59";
$e_d4=explode("-",$e_d2[0]);

$Current_Date_Time=date('Y/m/d H:i:s');
$Current_Date_Only=explode(" ",$Current_Date_Time);				
$c_d=explode("/",$Current_Date_Only[0]);

$start_date=explode(" ",$StartDate);				
$s_d=explode("-",$start_date[0]);

$sdt=$e_d[0]."-".$e_d[1]."-"."01"." "."00:00:00";	
$sdt2=$s_d[0]."-"."12"."-"."31"." "."23:59:59";
$endt1=$e_d4[0]."-"."01"."-"."01"." "."00:00:00";

if($v_serial1)    /////////////////////// for last data of selected vehicle and start first if  /////////
{
	for($i=0;$i<$v_serial1;$i++)
	{
	  $veh_serial2=$v_serial[$i];
	  get_tablename_1($veh_serial2, &$tablename, &$vehicle_details, $DbConnection);  
    if($display_mode == 1)
    {
      $record='=(select max(DateTime) from '.$tablename.' WHERE DateTime';
      $bracket_close=")";
      //echo "last_record=".$last_record; 
    }
    else if($display_mode == 2)
    {
        $record="";
        $bracket_close="";
    } 
	  $vehicle_details=explode(",",$vehicle_details);
	  $vehicle_name_pdf[$i]= $vehicle_details[1];
	  //echo "vehiclename=".$vehicle_name[$k];
    if($startdate1 == "")  /////////////// when startdate not entered  and start second if //////////
  	{
  		if($c_e == 2)	
  		{$EDate = $e[0]." "."00:00:00";} 				
			if($c_d[0]==$e_d[0] && $c_d[1]==$e_d[1])  ////////// start third  if
			{
        $Query="Select * from $tablename WHERE DeviceIMEINo='$veh_serial2' AND DateTime =(select max(DateTime) from $tablename WHERE DateTime between '$EDate' and '$EndDate')";				
			} //////////// end third if
			else   ////////////// start first else
			{
					$yealy_tables=$tablename."_".$e_d[0];				
					$Query="Select * from $yealy_tables WHERE DeviceIMEINo='$veh_serial2' AND DateTime =(select max(DateTime) from $tablename WHERE DateTime between '$EDate' and '$EndDate')";				
		  }   //////////// end first else 
    }	
	  else if($startdate1!="")  ////////// when startdate is not blank  and start of else if  for s_date which is not null//////////
	  {
			if($c_d[0]==$s_d[0] && $c_d[1]==$s_d[1] && $c_d[0]==$e_d[0] && $c_d[1]==$e_d[1])   /// strat of if
			{
					$Query1="Select * from $tablename WHERE DeviceIMEINo='$veh_serial2' AND DateTime $record between '$startdate1' and '$enddate1'$bracket_close";				
			}  /// end of if
			else          ///////start of else
			{
				if($c_d[0]==$s_d[0] && $c_d[0]==$e_d[0] && $c_d[1]==$e_d[1])
				{									
					$yearly_table=$tablename."_".$s_d[0];													
					$Query1="Select * from $tablename WHERE DeviceIMEINo='$veh_serial2' AND DateTime =(select max(DateTime) from $tablename where between '$sdt' and '$EndDate')";
					$Query1=$Query1." UNION All Select * from $yearly_table WHERE DeviceIMEINo='$veh_serial2' AND DateTime =(select max(DateTime) from $tablename where between '$StartDate' and '$e_d3')";
	  		}
				else if($c_d[0]!=$s_d[0] && $c_d[0]==$e_d[0] && $c_d[1]==$e_d[1])
				{                                    
					$yearly_table1=$tablename."_".$e_d4[0];
					$yearly_table2=$tablename[$i]."_".$s_d[0];
					$Query1="Select * from $tablename WHERE  DeviceIMEINo='$veh_serial2' AND DateTime =(select max(DateTime) from $tablename where between '$sdt' and '$EndDate')";
					$Query1=$Query1." UNION All Select * from $yearly_table1 WHERE DeviceIMEINo='$veh_serial2' AND DateTime =(select max(DateTime) from $tablename where between '$endt1' and '$e_d3')";
					$Query1=$Query1." UNION All Select * from $yearly_table2 WHERE DeviceIMEINo='$veh_serial2' AND DateTime =(select max(DateTime) from $tablename where between '$StartDate' and '$sdt2')";
			  }
				else if($s_d[0]!=$e_d[0])
				{
					$yearly_table1=$tablename[$i]."_".$s_d[0];
					$yearly_table2=$tablename[$i]."_".$e_d[0];
					$Query1="Select * from $yearly_table1 WHERE DeviceIMEINo='$veh_serial2' AND DateTime =(select max(DateTime) from $tablename where between '$StartDate' and '$sdt2')";
					$Query1=$Query1." UNION All Select * from $yearly_table2 WHERE DeviceIMEINo='$veh_serial2' AND DateTime =(select max(DateTime) from $tablename where between '$sdt' and '$EndDate')";
			  }
				else if($s_d[0]==$e_d[0])
				{
					$yearly_table=$tablename[$i]."_".$s_d[0];
				  $Query1="Select * from $yearly_table1 WHERE AND DeviceIMEINo='$veh_serial2' AND DateTime =(select max(DateTime) from $tablename where between '$StartDate' and '$EndDate')";
   			}									
			}  //////////// end of else/// 		  
  	 }
  	   if($DEBUG==1){echo "query".$Query1;}  	   
  	   $QueryResult = mysql_query($Query1, $DbConnection);  
       $num_rows=mysql_num_rows($QueryResult);
    
     echo '<table border=0 width = 100% cellspacing=2 cellpadding=2><tr><td align="center"><font color="black" size="2"><b>'.$vehicle_details[1].'</b></font></td></tr></table>';
		 
            	
      if($num_rows=="0")
      {$pdf_flag[$i]='off';echo "<center><font color='blue' size='2'>No Data Found </font></center>";}
      else
      {
       if($display_mode == 1)
       {	
         $k=0;  
    		 $row=mysql_fetch_object($QueryResult);     	
      	 if($time_zone=="GMT+4")
      	 {
        		$datetime_1=$row->DateTime;	 $start_timestamp=strtotime($datetime_1[$i]);  $start_gmt_time=$start_timestamp-5400;
        		$gmt_start_date=date('Y-m-d H:i:s',$start_gmt_time); 
			//date_default_timezone_set('Asia/Calcutta');
			$datetime_2=$gmt_start_date;
      	 }
    		 else if($time_zone=="IST")
    		 {
    		  $datetime_2=$row->DateTime;
          if($datetime_2==null){$datetime_2='-';}
          $datetime_pdf[$i][$k]=$datetime_2;	
         }		 
  		  
      		$speed_1=$row->Speed;
      		if($speed_1==null){$speed_1='-';}
          $speed_pdf[$i][$k]=$speed_1;
      
          if($speed_1<=3)
          {$speed_1=0;$speed_pdf[$i][$k]=$speed_1;}
                		
      	  $latitude_1=$row->Latitude;
          if($latitude_1==null){$latitude_1='-';}
          else{$latitude_1 = round(($latitude_1),4);}    
      		$latitude_pdf[$i][$k]=$latitude_1;
      	
      		$longitude_1=$row->Longitude;
          if($longitude_1==null){$longitude_1='-';}
          else{$longitude_1 = round(($longitude_1),4);}    
      		$longitude_pdf[$i][$k]=$longitude_1;     
       
      		$cellname_1 = $row->CellName;
          if($cellname_1==null){$cellname_1='-';}  
          $cellname_pdf[$i][$k] = $cellname_1;  		
    	 
        	if($DEBUG==1){echo " vname_1=".$vehicle_details[1]."<br>cellname_1=".$cellname_1."<br>altitude_1=".$altitude_1."<br>longitude_1=".$longitude_1."<br>latitude_1=".$latitude_1."<br>speed_1=".$speed_1."<br>datetime_1=".$datetime_2."<br>";}
    		
    	   /*if($login=="jindal2")
    		{
    			$fuel_prev[$i]=$row->CoverOpen;
    			$y = ((3.3 * 11) / 1024) * $fuel_prev[$i];		// Actual Fuel Voltage
    			$level =  ((12-$y) / 12)*100;
    			$litres = $level * 0.6;
    
    			$fuel_litres[$i] = round($litres,2);			
    			$fuel_level[$i] = round($level,2);
    		}
    		else
    		{
    			if($login=="vincent")
    			{
    			$fuel_prev[$i]=$row->IO_Value1;
    			}
    			else
    			{
    			$fuel_prev[$i]=$row->Fuel;
    			}*/
  			
    		  $fuel_prev=$row->Fuel;
    			$fuel_voltage = $vehicle_details[2];     			
    			$tank_capacity = $vehicle_details[3];
    			//echo "fuel_prev=".$fuel_prev."<br>fuel_voltage=".$fuel_voltage."<br>tank_capacity=".$tank_capacity;
      		if($DEBUG==1)
      		{echo "fuel_prev=".$fuel_prev."<br>fuel_voltage=".$fuel_voltage."<br>tank_capacity=".$tank_capacity;}       
    			if($fuel_voltage==null && $tank_capacity==null)
          {
            $fuel_litres ='-';
            $fuel_litres_pdf[$i][$k]= $fuel_litres;
            $fuel_level='-';
            $fuel_level_pdf[$i][$k]=$fuel_level;
          }
          else
          {
            if(strcmp($fuel_voltage,"12v")==0){$max_fuel = 340;}    					
      		  else if(strcmp($fuel_voltage,"24v")==0){$max_fuel = 680;}     					
    						
      			$litres = 0;		
      			if($fuel_prev == $max_fuel)
      			{$litres = "Tank Full"; $level = "100%";}					
      			else
      			{
              $litres = ($fuel_prev * $tank_capacity)/$max_fuel; 
              $level = ($fuel_prev*100)/$max_fuel;
            }									
      			$fuel_litres = round($litres,2);
            $fuel_litres_pdf[$i][$k]= $fuel_litres;
            $fuel_level = round($level,2);
            $fuel_level_pdf[$i][$k]= $fuel_level;
          }     		
      		/*if($access=='Zone')
      		{
      			get_location($latitude_1[$i],$longitude_1[$i],$altitude,&$placename[$i],$zoneid,$DbConnection);	
      		}
      		else
      		{
      			get_location($latitude_1[$i],$longitude_1[$i],$altitude,&$placename[$i],$DbConnection);	
      		} */
  	 	echo'
      				<table bordercolor="#e5ecf5" border=1 width="100%" rules=all align="center" cellspacing=0 cellpadding=3>										
      					<tr width="100%" valign="top" bgcolor="#9BB5C4">      						
                  <td class="text" align="left" style="font-size:12px;color:#000000" width="8%"><strong>DateTime</strong></td>
      						<td class="text" align="left" style="font-size:12px;color:#000000" width="32%"><strong>Location</strong></td>
      						<td class="text" align="left" style="font-size:12px;color:#000000" width="8%"><strong>GSM CBM</strong></td>
      						<td class="text" align="left" style="font-size:12px;color:#000000" width="8%"><strong>Latitude</strong></td>
      						<td class="text" align="left" style="font-size:12px;color:#000000" width="7%"><strong>Longitude</strong></td>
      						<td class="text" align="left" style="font-size:12px;color:#000000" width="6%"><strong>Speed</strong></td>
      						<td class="text" align="left" style=" font-size:13px;color:#000000" width="10%"><strong>Fuel(litres)</strong></td>
      						<td class="text" align="left" style=" font-size:13px;color:#000000" width="10%"><strong>Fuellevel(%)</strong></td>
      			     </tr>';					
    					if($vehicle_details[1] && $latitude_1 && $longitude_1)
        			{
            echo'<tr valign="top" bgcolor="#E8F6FF"> 
            				<td class="text" style="font-size:11px;color:#000000">'.$datetime_2.'</td>								
            				<td class="text" style=" font-size:11px;color:#000000">'.$placename.'&nbsp;
                    <a href="javascript:MapWindow(\''.$vehicle_details[1].'\',\''.$datetime_2.'\','.$latitude_1.','.$longitude_1.');">
                    <font color="green">Show location</font></a></td>																		
            				<td class="text" style="font-size:11px;color:#000000">'.$cellname_1.'</td>
            				<td class="text" style="font-size:11px;color:#000000">'.$latitude_1.'</td>
            				<td class="text" style="font-size:11px;color:#000000">'.$longitude_1.'</td>            		
            				<td class="text" style="font-size:11px;color:#000000">'.$speed_1.'</td>	
            				<td class="text" style=" font-size:12px;color:#000000">'.$fuel_litres.'</td>
            				<td class="text" style=" font-size:12px;color:#000000">'.$fuel_level.'</td>
            			</tr>';
        		     $k++; 
        		     $pdf_size[$i]=$k;
        			}
        echo'</table>';
        }         
        else if($display_mode == 2)
        {    
            $k=0;    $m=0;   $sno=1;
          	while($row2=mysql_fetch_object($QueryResult))	/////////// while condition start/////////
        		{         		
        			$lt_prev=$row2->Latitude;   $lng_prev=$row2->Longitude;	
        
        			if(($lt_prev!="-" || $lng_prev!="-")  && ($lt_prev!="" || $lng_prev!="") &&  ($lt_prev!="0" || $lng_prev!="0"))
        			{
        			  $vid_prev=$row2->VehicleID;
        			  if($vid_prev==null){$vid_prev='-';}
        			  
        			  $date_prev=$row2->DateTime;
        				if($date_prev==null)
        				{$date_prev='-';} 
        			  
        				$speed_prev=$row2->Speed;
                if($speed_prev==null){$speed_prev='-';}	
                					
        				$cellid_prev=$row2->CellName;
                if($cellid_prev==null){$cellid_prev='-';}
               
                $vehname1=$vehicle_details[1];
               
                $fuel_prev=$row2->Fuel; 
                if($fuel_prev==null)
        				{$fuel_prev='-';} 
                                      				
        				$fuel_voltage1 = $vehicle_details[2];       				
        				$tank_capacity1 = $vehicle_details[3];
                			
      					if($m==0)
      					{
      					  $vid[$k]= $vid_prev;
      					  $speed[$k]= $speed_prev;      					  
      					  if($date_prev=='-')
        				  {
                   	$date1[$k]=$date_prev;
                  }
                  else
                  { 
            				if($time_zone=="GMT+4")
            				{
              				$date[$k]=$date_prev;				
              				$start_timestamp=strtotime($date[$k]);
              				$start_gmt_time=$start_timestamp-5400;	
              				$gmt_start_date=date('Y-m-d H:i:s',$start_gmt_time);
              				//date_default_timezone_set('Asia/Calcutta');
              				$date1[$k]=$gmt_start_date;
            				}
            				else if($time_zone=="IST")
            				{
            					$date1[$k]=$date_prev;			
            				}
            		  }
            		  $cellid[$k]=$cellid_prev;
      					  
      					 	$lt[$k]= $lt_prev;            
          				$lt[$k] = round(($lt[$k]),4);
          				$lng[$k]= $lng_prev;							
          				$lng[$k] = round(($lng[$k]),4);
          			          			
          				$fuel_voltage[$k] = $fuel_voltage1;
          				$tank_capacity[$k] = $tank_capacity1;
          				 
        					if($fuel_voltage[$k]==null && $tank_capacity[$k]==null)
                  {
                    $fuel_litres[$k] = '-';		
        				    $fuel_level[$k] = '-';                 
                  } 
                  else
                  {       
          					$fuel[$k]=$fuel_prev;        					
          					if(strcmp($fuel_voltage[$k],"12v")==0)
          						 $max_fuel[$k] = 340;    
          					else if(strcmp($fuel_voltage[$k],"24v")==0)
          						 $max_fuel[$k] = 680;
          					
          					$litres = 0;          				
          					if($fuel[$k] == $max_fuel[$k])
          					{
          						$litres = "Tank Full";
          						$level = "100%";
          					}					
          					else
          					{										
          						$litres = ($fuel[$k] * $tank_capacity[$k])/$max_fuel[$k];
          						$level = ($fuel[$k] *100)/$max_fuel[$k];
          					}           				          
            				$fuel_litres[$k] = round($litres,2);		
            				$fuel_level[$k] = round($level,2);
          				}
          				/*if($access=='Zone')
        					{
        						get_location($lt[$j],$lng[$j],$alt[$j],&$placename[$j],$zoneid,$DbConnection);			
        					}
        					else
        					{
        						get_location($lt[$j],$lng[$j],$alt[$j],&$placename[$j],$DbConnection);
        					} */
        					$placename_1[$k]='-';
      						echo'
      								<table width="100%" border="1" cellpadding="0" cellspacing="0">      							
      										<tr valign="top" bgcolor="#9BB5C4">
      											<th class="text" align="left" style="font-size:11px;color:#000000" width="4%"><b>&nbsp;SNo</b></th>
      											<th class="text" align="left" style="font-size:12px;color:#000000" width="8%"><strong>DateTime</strong></th>      											
      											<th class="text" align="left" style="font-size:11px;color:#000000" width="28%"><b>Location</b></td>
      											<th class="text" align="left" style="font-size:11px;color:#000000" width="5%"><b>GSMCBM</b></th>
      											<th class="text" align="left" style="font-size:11px;color:#000000" width="9%"><b>Latitude</b></th>
      											<th class="text" align="left" style="font-size:11px;color:#000000" width="9%"><b>Longitude</b></th>      											
      											<th class="text" align="left" style="font-size:11px;color:#000000" width="9%"><b>Speed</b></th>
      											<th class="text" align="left" style="font-size:11px;color:#000000" width="12%"><b>Distance&nbsp;(Km)</b></th>
      											<th class="text" align="center" style="font-size:12px;color:#000000"><b>Fuel<br>(litres)</b></th>
      											<th class="text" align="center" style="font-size:12px;color:#000000"><b>Fuel<br>level(%)</b></th>
      										</tr>
      								    <tr valign="top" bgcolor="#E8F6FF">
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.$sno.'</td> 
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.$date1[$k].'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.$placename_1[$k].'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.$cellid[$k].'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.$lt[$k].'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.	$lng[$k].'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.$speed[$k].'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;0</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.	$fuel_litres[$k].'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.$fuel_level[$k].'</td>
                          </tr>';
                  $m=1;
                          $datetime_pdf[$i][$k]=$date1[$k];	
        			            $placename[$i][$k]=$placename_1[$k];										
        		              $latitude_pdf[$i][$k]=$lt[$k];
        			            $longitude_pdf[$i][$k]=$lng[$k];
        			            $speed_pdf[$i][$k]=$speed[$k];
                          $ditance_pdf[$i][$k]="0";			
        			           //$altitude_pdf1=$altitude_pdf[$i][$j];
        			            $cellname_pdf[$i][$k]=$cellid[$k]; 
        		              $fuel_litres_pdf[$i][$k]=$fuel_litres[$k];
                          $fuel_level_pdf[$i][$k]=$fuel_level[$k]; 
                          $pdf_size[$i]=$k;
      					}
        				
        				$count=0;
        				$distance_sum = 0;
        				//echo "num_rows=".$num_rows;
        				for($j=1;$j<($num_rows);$j++)
        				{        				  
        					$row2=mysql_fetch_object($QueryResult);
        					$lt2=$row2->Latitude;
        					$lng2=$row2->Longitude;
        			
        					if($lt2=="-" || $lt2=="" || $lng2=="-" || $lng2=="" || $lt2=="0" || $lng2=="0")
        					{
        					}
        					else
        					{
                    $vid2=$row2->VehicleID;                    		
        						$speed2=$row2->Speed;
        						if($speed2==null)
        						{$speed2='-';}
        						
        						$cellid2=$row2->CellName;
        						if($cellid2==null)
        						{$cellid2='-';}
        						
        						$date2=$row2->DateTime;
        						if($date2==null)
        						{$date2='-';}
                            						                   
                    $fuel_voltage1 = $vehicle_details[2]; 
        						$tank_capacity1 = $vehicle_details[3];
        						$fuel2=$row2->Fuel;	  					
        						
        						calculate_mileage($lt_prev,$lt2,$lng_prev,$lng2,&$distance);
        						//echo "distance=".$distance."<br>";
        						$distance = round($distance,2);
        					  $distance_sum = $distance_sum + $distance;
        					  
        						if($distance > 0.2 || ($j == ($num_rows-1)))
        						{	
                      $sno++;										
        							$k++;					
        							$lt[$k]=$lt2;
        							$lng[$k]=$lng2;
        							$alt[$k]=$alt2;
        							$alt[$k] = round(($alt[$k]),4);
        							$lt[$k] = round(($lt[$k]),4);
        							$lng[$k] = round(($lng[$k]),4);
        							
                      /*if($access=='Zone')
            					{
            						get_location($lt[$k],$lng[$k],$alt[$k],&$placename[$k],$zoneid,$DbConnection);			
            					}
            					else
            					{
            						get_location($lt[$k],$lng[$k],$alt[$k],&$placename[$k],$DbConnection);
            					}*/
            					
            					/*$Query3 = "select * from landmark where user_account_id='$account'";
            					$Result3 = mysql_query($Query3,$DbConnection);
            					
            					if($row3 = mysql_fetch_object($Result3))
            					{
            						$lat2 = $row3->Latitude;
            						$lng2 = $row3->Longitude;
            						calculate_mileage($lt[$k],$lat2,$lng[$k],$lng2,&$distance);
            
            						$distance = round($distance,2);							
            
            						if($distance <=1)
            						{
            							$placename1[$k] = $row3->Landmark;								
            							$placename[$k] = $placename1[$k];
            							$placename[$k] = $distance." km from ".$placename[$k];
            						}
            					}*/ 
            					
            					$placename_2[$k]='-';
            					
        							$vid[$k]=$vid2;
        							$cellid[$k]=$cellid2;
        							$speed[$k] = round(($speed[$k]),3);
        							
        							$placename[$k]='-';
        							$speed[$k]=$speed2;        							
        							
        							if($date2=='-')
        							{$date3[$k]=$date2;}         							
                      else
                      {             							
            							if($time_zone=="GMT+4")
            							{
              							$date[$k]=$date2;				
              							$start_timestamp=strtotime($date[$k]);
              							$start_gmt_time=$start_timestamp-5400;	
              							$gmt_start_date=date('Y-m-d H:i:s',$start_gmt_time);
              							//date_default_timezone_set('Asia/Calcutta');
              							$date3[$k]=$gmt_start_date;
            							}
            							else if($time_zone=="IST")
            							{$date3[$k]=$date2;}
            				  }
            				  
        							$fuel_voltage[$k] = $fuel_voltage1;
        							$tank_capacity[$k] = $tank_capacity1;
                      if($fuel_voltage[$k]==null && $tank_capacity[$k]==null)
                      {
                        $fuel_litres[$k]='-';		
        								$fuel_level[$k]='-';                      
                      }
                      else
                      { 
          							if(strcmp($fuel_voltage[$k],"12v")==0)
          								$max_fuel[$k] = 340;    
          							else if(strcmp($fuel_voltage[$k],"24v")==0)
          								$max_fuel[$k] = 680;
          							
                        /*if($login=="jindal2")
          							{
          								$y = ((3.3 * 11) / 1024) * $fuel[$k];	// Actual Fuel Voltage
          								$level =  ((12-$y) / 12)*100;
          								$litres = $level * 0.6;
          								
          								$fuel_litres[$k] = round($litres,2);		
          								$fuel_level[$k] = round($level,2);
          							}
          						  else
          							{	*/
          								$litres = 0;						
          						
          								if($fuel[$k] == $max_fuel)
          								{
          									$litres = "Tank Full";
          									$level = "100%";
          								}										
          								else
          								{
          									$litres = ($fuel[$k] * $tank_capacity[$k])/$max_fuel[$k];
          									$level = ($fuel[$k] * 100)/$max_fuel[$k];
          								}
          
          								$fuel_litres[$k] = round($litres,2);		
          								$fuel_level[$k] = round($level,2);							
          							//}           					
          						} 
          					echo '<tr valign="top" bgcolor="#E8F6FF">
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.$sno.'</td> 
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.$date3[$k].'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.$placename_2[$k].'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.$cellid[$k].'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.$lt[$k].'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.	$lng[$k].'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.$speed[$k].'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">'.$distance_sum.'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.	$fuel_litres[$k].'</td>
                            <td class="text" align="left" style="font-size:11px;color:#000000">&nbsp;'.$fuel_level[$k].'</td>
                          </tr>';  
                          
                          $datetime_pdf[$i][$k]=$date3[$k];	
        			            $placename[$i][$k]=$placename_1[$k];										
        		              $latitude_pdf[$i][$k]=$lt[$k];
        			            $longitude_pdf[$i][$k]=$lng[$k];
        			            $speed_pdf[$i][$k]=$speed[$k];
                          $ditance_pdf[$i][$k]=$distance_sum;			
        			           //$altitude_pdf1=$altitude_pdf[$i][$j];
        			            $cellname_pdf[$i][$k]=$cellid[$k]; 
        		              $fuel_litres_pdf[$i][$k]=$fuel_litres[$k];
                          $fuel_level_pdf[$i][$k]=$fuel_level[$k]; 
                          $pdf_size[$i]=$k; 
                          
            							//$fuel[$k]=$fuel2;
            							$lt_prev=$lt[$k];
            							$lng_prev=$lng[$k];       
            							//$alt_prev=$alt[$k];
            							/*$speed_prev=$speed[$k];
            							$cellid_prev=$cellid[$k];							
            							$vid_prev=$vid[$k];
            							$date_prev=$date[$k];
            							$fuel_prev=$fuel[$k];*/ 
        						} 
        					}
        				}
                //$sno=0;        				
        			break;
        			}
        		} /////// close while loop ////////////
        		echo '</table>' ;        
        }        	
     }
 }
echo'<form method = "post" action="src/php/report_getpdf_type4.php?size='.$v_serial1.'" target="_blank">';	
	for($i=0;$i<$v_serial1;$i++)
	{					
		$title=$vehicle_name_pdf[$i].": Report";
		echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$i]\">";		
		$sno=0;
		if($pdf_flag[$i]=='off')
    { $pdf_size[$i]="0";  $final_pdf_size=$pdf_size[$i];  }
    else
    {
  		if($display_mode==1)
  		{ $final_pdf_size=$pdf_size[$i];  }
      else if($display_mode==2)
      { $final_pdf_size=$pdf_size[$i]+1; }///////add 1 for show last data for the pdf 
    }
    
		for($j=0;$j<$final_pdf_size;$j++)
		{ 
		  $sno++; 
			$datetime_pdf1=$datetime_pdf[$i][$j];	
			$placename1=$placename[$i][$j];										
			$latitude_pdf1=$latitude_pdf[$i][$j];
			$longitude_pdf1=$longitude_pdf[$i][$j];
			$speed_pdf1=$speed_pdf[$i][$j];	
			if($display_mode==2)
      {$ditance_pdf1=$ditance_pdf[$i][$j];}		
			//$altitude_pdf1=$altitude_pdf[$i][$j];
			$cellname_pdf1=$cellname_pdf[$i][$j]; 
			$fuel_litres_pdf1=$fuel_litres_pdf[$i][$j];
      $fuel_level_pdf1=$fuel_level_pdf[$i][$j];    			
		  if($display_mode==2)        
			{echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$i][$j][SNo]\">";}
			
      echo"<input TYPE=\"hidden\" VALUE=\"$datetime_pdf1\" NAME=\"temp[$i][$j][DateTime]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$placename1\" NAME=\"temp[$i][$j][Location]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$cellname_pdf1\" NAME=\"temp[$i][$j][GSM CBM]\">";			
			echo"<input TYPE=\"hidden\" VALUE=\"$latitude_pdf1\" NAME=\"temp[$i][$j][Latitude]\">";									
			echo"<input TYPE=\"hidden\" VALUE=\"$longitude_pdf1\" NAME=\"temp[$i][$j][Longitude]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$speed_pdf1\" NAME=\"temp[$i][$j][Speed]\">";
     
      if($display_mode==2)	
      {echo"<input TYPE=\"hidden\" VALUE=\"$ditance_pdf1\" NAME=\"temp[$i][$j][Distance]\">";}
     
      echo"<input TYPE=\"hidden\" VALUE=\"$speed_pdf1\" NAME=\"temp[$i][$j][Speed]\">";								
			echo"<input TYPE=\"hidden\" VALUE=\"$fuel_litres_pdf1\" NAME=\"temp[$i][$j][Fuel(litres)]\">";	
      echo"<input TYPE=\"hidden\" VALUE=\"$fuel_level_pdf1\" NAME=\"temp[$i][$j][Fuellevel(%)]\">";							
		}																																		
	}					
echo'<table align="center"><tr><td><input type="submit" value="Get PDF" class="noprint">&nbsp;<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;</td></tr></table>
</form> ';
  }  ////////// end of else if condition for s_date which is not null /////////////
  
function calculate_mileage($lat1, $lat2, $lon1, $lon2, &$distance) 
{		
	$lat1 = deg2rad($lat1);
	$lon1 = deg2rad($lon1);
	$lat2 = deg2rad($lat2);
	$lon2 = deg2rad($lon2);
	$delta_lat = $lat2 - $lat1;
	$delta_lon = $lon2 - $lon1;
	$temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
	$distance = 3956 * 2 * atan2(sqrt($temp),sqrt(1-$temp));
	$distance = $distance*1.609344;	
}

?>
