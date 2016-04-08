<?php
	include_once('util_session_variable.php');		
	include_once('util_php_mysql_connectivity.php');
	//echo "account_id_local=".$account_id_local."startdate=".$start_date;
	$account_id_local_1=$_POST['account_id_local'];
	$title1=$_POST['title'];
	$prev_month_1=$_POST['prev_month'];
	$prev_year_1=$_POST['prev_year'];
	$current_month_1=$_POST['current_month'];
	$current_year_1=$_POST['current_year'];
	
	$month1 = $prev_year_1."_".$prev_month_1;
  $month2 = $prev_year_1."_".$current_month_1; 
  
  //echo "<br>m1=".$month1." ,m2=".$month2;

  $m1_total_closed;
  $m1_total_closed_by_actual_pod;
  $m1_total_closed_on_time;
  $m1_total_closed_delayed;
  $m1_percentage_on_time;
  
  $m2_total_closed;
  $m2_total_closed_by_actual_pod;
  $m2_total_closed_on_time;
  $m2_total_closed_delayed;
  $m2_percentage_on_time;
  
  performance_month1($account_id, $month1);
  performance_month2($account_id, $month2);
  
  //1.########### Month1
  function performance_month1($account_id, $month1)
  {
    global $m1_total_closed;
    global $m1_total_closed_by_actual_pod;
    global $m1_total_closed_on_time;
    global $m1_total_closed_delayed;
    global $m1_percentage_on_time;
   									        
    $xml_month1 = "daily_vehicle_status/".$account_id."/monthly_performance/".$month1.".xml";	
    //echo "<br>xml_path=".$xml_month1;
	
	  $data_valid = false;
      		
    if (file_exists($xml_month1))      
    {		      
        $total_lines = count(file($xml_month1));
        //echo "<br>Total lines orig=".$total_lines;
        
        $xml = @fopen($xml_month1, "r") or $fexist = 0;  
          
        while(!feof($xml))          // WHILE LINE != NULL
        {
        		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
        		$data_valid = false;
				
        		if(strlen($line)>20)
        		{
        			$data_valid =  true;
            }
             		            				    				
            if($data_valid)
            {                        			                                   
                  //echo "<br>found";
                  $status = preg_match('/total_closed="[^"]+/', $line, $total_closed_tmp);
                  if($status)
                  {
                   $total_closed_tmp1 = explode("=",$total_closed_tmp[0]);
                   $m1_total_closed = preg_replace('/"/', '', $total_closed_tmp1[1]);
                  }             
                  
                  $status = preg_match('/total_closed_by_actual_pod="[^"]+/', $line, $total_closed_by_actual_pod_tmp);
                  if($status)
                  {
                   $total_closed_by_actual_pod_tmp1 = explode("=",$total_closed_by_actual_pod_tmp[0]);
                   $m1_total_closed_by_actual_pod = preg_replace('/"/', '', $total_closed_by_actual_pod_tmp1[1]);
                  }         
                  
                  $status = preg_match('/total_closed_on_time="[^"]+/', $line, $total_closed_on_time_tmp);
                  if($status)
                  {
                   $total_closed_on_time_tmp1 = explode("=",$total_closed_on_time_tmp[0]);
                   $m1_total_closed_on_time = preg_replace('/"/', '', $total_closed_on_time_tmp1[1]);
                  }         
                                   
                  $status = preg_match('/total_closed_delayed="[^"]+/', $line, $total_closed_delayed_tmp);
                  if($status)
                  {
                   $total_closed_delayed_tmp1 = explode("=",$total_closed_delayed_tmp[0]);
                   $m1_total_closed_delayed = preg_replace('/"/', '', $total_closed_delayed_tmp1[1]);
                  }
                                            
                  $status = preg_match('/percentage_on_time="[^"]+/', $line, $percentage_on_time_tmp);
                  if($status)
                  {
                   $percentage_on_time_tmp1 = explode("=",$percentage_on_time_tmp[0]);
                   $m1_percentage_on_time = preg_replace('/"/', '', $percentage_on_time_tmp1[1]);
                  }         
                  break;
                }  //if imei
             } //if data_valid closed		        			  		
          }   // while closed            	
      	    	      				
      fclose($xml);            
		 //unlink($xml_original_tmp);
		} // if (file_exists closed
		
		
  //2.########### Month2
  function performance_month2($account_id, $month2)
  {
    global $m2_total_closed;
    global $m2_total_closed_by_actual_pod;
    global $m2_total_closed_on_time;
    global $m2_total_closed_delayed;
    global $m2_percentage_on_time;
   									        
    $xml_month2 = "daily_vehicle_status/".$account_id."/monthly_performance/".$month2.".xml";	
    //echo "<br>xml_path=".$xml_reporting;
	
	  $data_valid = false;
      		
    if (file_exists($xml_month2))      
    {		      
        $total_lines = count(file($xml_month2));
        //echo "<br>Total lines orig=".$total_lines;
        
        $xml = @fopen($xml_month2, "r") or $fexist = 0;  
          
        while(!feof($xml))          // WHILE LINE != NULL
        {
        		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
        		$data_valid = false;
				
        		if(strlen($line)>20)
        		{
        			$data_valid =  true;
            }
             		            				    				
            if($data_valid)
            {                        			                                   
                  $status = preg_match('/total_closed="[^"]+/', $line, $total_closed_tmp);
                  if($status)
                  {
                   $total_closed_tmp1 = explode("=",$total_closed_tmp[0]);
                   $m2_total_closed = preg_replace('/"/', '', $total_closed_tmp1[1]);
                  }             
                  
                  $status = preg_match('/total_closed_by_actual_pod="[^"]+/', $line, $total_closed_by_actual_pod_tmp);
                  if($status)
                  {
                   $total_closed_by_actual_pod_tmp1 = explode("=",$total_closed_by_actual_pod_tmp[0]);
                   $m2_total_closed_by_actual_pod = preg_replace('/"/', '', $total_closed_by_actual_pod_tmp1[1]);
                  }         
                  
                  $status = preg_match('/total_closed_on_time="[^"]+/', $line, $total_closed_on_time_tmp);
                  if($status)
                  {
                   $total_closed_on_time_tmp1 = explode("=",$total_closed_on_time_tmp[0]);
                   $m2_total_closed_on_time = preg_replace('/"/', '', $total_closed_on_time_tmp1[1]);
                  }         
                                   
                  $status = preg_match('/total_closed_delayed="[^"]+/', $line, $total_closed_delayed_tmp);
                  if($status)
                  {
                   $total_closed_delayed_tmp1 = explode("=",$total_closed_delayed_tmp[0]);
                   $m2_total_closed_delayed = preg_replace('/"/', '', $total_closed_delayed_tmp1[1]);
                  }
                                            
                  $status = preg_match('/percentage_on_time="[^"]+/', $line, $percentage_on_time_tmp);
                  if($status)
                  {
                   $percentage_on_time_tmp1 = explode("=",$percentage_on_time_tmp[0]);
                   $m2_percentage_on_time = preg_replace('/"/', '', $percentage_on_time_tmp1[1]);
                  }         
                  break;
                }  //if imei
             } //if data_valid closed		        			  		
          }   // while closed            	
      	    	      				
      fclose($xml);            
		 //unlink($xml_original_tmp);
		} // if (file_exists closed		
      	
?>
<input type="hidden" id="common_display_id">
<table width="100%">
	<tr>
		<td align="center">
			<table align="center" class="menu" border="1" rules="all" bordercolor="black">
				<tr class="moto_headings">
					<td align="center" colspan="3">
						<?php echo $title1; ?>
					</td>
				</tr>
				<tr class="moto_exceptions">					
					<td align="center" >																	 
					</td>
					<td align="center">
						 Previous Month (<?php echo $month1; ?>)									 
					</td>
					<td align="right">
						 Current Month (<?php echo $month2; ?>)
					</td>						
				</tr>				
				<tr>					
					<td align="center">	
						Total Closed
					</td>
					<td align="center">
						 <?php echo $m1_total_closed; ?>								 
					</td>
					<td align="right">
						 <?php echo $m2_total_closed; ?>
					</td>						
				</tr>
				<tr>					
					<td align="center">	
						Total Closed by Actual POd
					</td>
					<td align="center">
						 <?php echo $m1_total_closed_by_actual_pod; ?>								 
					</td>
					<td align="right">
						 <?php echo $m2_total_closed_by_actual_pod; ?>
					</td>						
				</tr>
				<tr>					
					<td align="center">	
						Total Closed On Time
					</td>
					<td align="center">
						 <?php echo $m1_total_closed_on_time; ?>							 
					</td>
					<td align="right">
						 <?php echo $m2_total_closed_on_time; ?>
					</td>						
				</tr>
				<tr>					
					<td align="center">	
						Total Closed Delayed
					</td>
					<td align="center">
						 <?php echo $m1_total_closed_delayed; ?>								 
					</td>
					<td align="right">
						 <?php echo $m2_total_closed_delayed; ?>
					</td>						
				</tr>
				<tr>					
					<td align="center">	
						Percentage On Time
					</td>
					<td align="center">
						 <?php echo $m1_percentage_on_time; ?>								 
					</td>
					<td align="right">
						 <?php echo $m2_percentage_on_time; ?>
					</td>						
				</tr>				
			</table>
		</td>
	</tr>
</table>
