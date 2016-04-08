<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');  
	$DEBUG=0;	
	//$postPars = array('action_type' , 'escalation_serial_number', 'common_id');
	//include_once('action_post_data.php');
  //$pd = new PostData();
	//$common_id1=$pd->data[common_id];
	$common_id1=$_POST['common_id'];
  echo "edit##"; 
	//echo  "commonid=".$common_id1;
 
  echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';	
	$query ="SELECT escalation_id,person_name from escalation WHERE create_id IN($common_id1) AND status=1";
	//echo "query1=".$query."<br>";
	$result = mysql_query($query, $DbConnection);
	$num_rows=mysql_num_rows($result); 

	$sector_cnt=0;
	//$escalation_cnt=0;
	$no_sector_cnt=0; //////// in case of no one vehicle exist in geofence
	$flag=0;
	
  if($num_rows>0)
	{ 
    $p=0;
    $q = 0;
   
    while($row=mysql_fetch_object($result))
		{    
      $person_id_tmp = $row->escalation_id;
      $person_name_tmp = $row->person_name; 
            			      
      $query = "SELECT sector.sector_name,alert.alert_name,sector_alert_assignment.serial,sector_alert_assignment.alert_duration FROM alert,sector,sector_alert_assignment ".
      "WHERE sector_alert_assignment.escalation_id='$person_id_tmp' AND sector_alert_assignment.sector_id = sector.sector_id ".
      "AND sector_alert_assignment.alert_id = alert.alert_id AND sector_alert_assignment.status=1"; 
		  //echo "<br>".$query;
			
      $result_1 = mysql_query($query, $DbConnection);
			$num_row_child=mysql_num_rows($result_1);       
      
      $q = 0;
      $count_found = 0;
      //echo "num_rows=".$num_row_child."<br>";			
			if($num_row_child>0)
			{				
        while($row_1=mysql_fetch_object($result_1))
				{									
          $tmp = $row_1->serial;
          //echo "<bt>".$tmp;
          $serial_1[$p][$q] = $tmp;
          //echo "<br>p=".$p." ,q=".$q." ,serial_1[p][escalation_cnt]".$serial_1[$p][$q];
          $tmp = $row_1->alert_duration;
          //echo "<bt>".$tmp;
          $alert_duration_1[$p][$q] = $tmp;
          //echo "<bt>".$tmp;
          $tmp =  $row_1->alert_name;
          //echo "<bt>".$tmp;
          $alert_1[$p][$q]=  $tmp;
          //echo "<bt>".$tmp;
          $tmp =  $row_1->sector_id;
          //echo "<bt>".$tmp;
          $sector_id_1[$p][$q]=  $tmp;
          //echo "<bt>".$tmp;
          $tmp = $row_1->sector_name;
          //echo "<bt>".$tmp;
					$sector_name_1[$p][$q]= $tmp;
          //echo "<bt>p=".$p." ,esclacnt=". $escalation_cnt;                   
          //echo '<br>LOOP='.$serial_1[$p][$escalation_cnt].'>'.$sector_name[$p][$escalation_cnt].'->'.$alert_1[$p][$escalation_cnt].' ('.$alert_duration[$p][$escalation_cnt].')';          				
					$q++;					
					
          //$escalation_cnt++;
					$count_found =1;
					$flag=1;
				} // inner while closed   	
      }  // if closed				
				
		  if($count_found)
		  {
        $person_id_1[$p] = $person_id_tmp;
        $person_name_1[$p] = $person_name_tmp;			          
        
        $count[$p] = $q;    
        
        $p++;             // $escalation_cnt++; 
      }						               
		  
		}  // outer while closed
	}
	else
	{
		$flag=0;
	}
	
	//echo "<br>P=".$p." ,Escalation_cnt=".$escalation_cnt;
	
	echo'<br><!--<form name="manage1">--> 
			<center> 	
				<fieldset class="manage_fieldset">
					<legend>
						<strong>Escalation Alert Sector De Assignment<strong>
					</legend>	';
				  echo '<div style=height:400px;overflow:auto;>';
          echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">';
					if($flag==1)
					{
            //echo "cali_cnt=".$calibration_cnt."<br>"; 
						if($p>0) //////////when vehicle found in geofence
						{
							for($i=0;$i<$p;$i++)
							{							   
                 //echo "<br>count=".$count[$i];
                 if(($person_name_1[$i]!="") && ($count[$i]>0) )
							   {
								  echo'<tr valign="top">
										<td><strong><font color="blue" size="2">Person Name-></font><font color="green" size="2">'.$person_name_1[$i].'</font></strong></td>						
									</tr>'; 

  								 for($j=0;$j<$count[$i];$j++)
  								 {
  								    //echo "<br>i=".$i." ,j=".$j." ,count=".$count[$i]." sn=".$serial_1[$i][$j];
    								 if($serial_1[$i][$j]!="")
    								 {
      								  echo'<tr>
      										<td>
      											<input type="checkbox" name="escalation_serial_number[]" value="'.$serial_1[$i][$j].'"><font color=blue>'.$sector_name_1[$i][$j].'-></font>&nbsp;<font color=red>'.$alert_1[$i][$j].'</font>'; if($alert_1[$i][$j]!="sector_change") { echo '('.$alert_duration_1[$i][$j].'&nbsp;mins)'; }
      										echo'
                          </td>
      									</tr>';
                     }	
  								}
                }		
							}
						}
					 /*if($no_sector_cnt>0)
					 {
							for($i=0;$i<$no_sector_cnt;$i++)
							{
							echo'<tr valign="top">
									<td><font color="blue" size="2">Escalation Name-></font><font color="green" size="2">'.$person_name[$i].'</font></td>						
								</tr>
								<tr valign="top">
									<td>
										NO ALERT ASSIGNED FOR THIS ESCALATION
									</td>
								</tr>';	
							}
						} */
					}
					else
					{
					   echo'<tr valign="top">
							<td>
								<font color=red>NO ESCALATION FOR THIS ACCOUNT TO DE-ASSIGN</font>
							</td>
						</tr>';		
					}
				echo "</table>";
				echo "</div>";
				
        if($flag == 1)
        {
          echo"<br>
					<input type='button' id='enter_button' Onclick='javascript:return action_manage_sector(\"deassign\")'\ value='De-Assign'>";
				}
					
				echo"</fieldset> 				
			</center>			
		<!--</form>-->";
   
?>  