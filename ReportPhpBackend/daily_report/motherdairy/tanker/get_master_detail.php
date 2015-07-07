<?php
/*//######## GET MASTER DETAIL ################
$account_id="231";
$shift_time = "ZPME";
get_master_detail($account_id, $shift_time);        //GET SHIFT, POINT, TIMING, AND INPUT FILE RECORDS
//######### MASTER DETAIL CLOSED ############
*/
function get_master_detail($account_id, $shift_time)
{
	//echo "\nAccount_id=".$account_id." ,shift_time=".$shift_time;	
	global $shift;
	global $point;
	global $timing;
	global $vehicle_t;
	global $transporter;
	
	global $route_input;
	global $vehicle_input;
	global $customer_input;
	global $shift_input;
	global $transporter_input;

	global $min_date_ev;
	global $max_date_ev;
	global $min_date_mor;
	global $max_date_mor;
	
	global $relative_plant_input;
	global $relative_customer_input;
	global $relative_transporter_input;
	global $relative_route_input;		

	//$dir = "c:\\gps_report/231/master";
	//$dir = "c:\\halt2/test_master";
	$dir = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master";
	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false) {
		//echo "<A HREF=\"$file\">$file</A><BR>\n";
		$file_tmp = explode("#",$file);
		$file_ext = explode(".",$file_tmp[2]);
		if($file_ext[0]!="")
		{
			//echo "<br>file_ext=".$file_ext[0];
			if($file_ext[0] == "1")
			{
			$path = $dir."/".$file;

			$row = 1;
			if (($handle = fopen($path, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					//echo "<p> $num fields in line $row: <br /></p>\n";
					$row++;
					for ($c=0; $c < $num; $c++) {
						//echo $data[$c] . "<br />\n";
						if($c==0)
						{
							$shift[] = $data[$c];
						}
						else if($c==1)
						{
							$point[] = $data[$c];
						}
						else if($c==2)
						{
							$timing[] = $data[$c];
						}                                  
					}
				}
				fclose($handle);
			}
		}
      
		//echo "\nfile_ext[0]=".$file_ext[0]." ,shift_time=".$shift_time;  
		if( ($file_ext[0] == "4") && ($shift_time=="ZPME") )		//###### EVENING FILE
		{
			$path = $dir."/".$file;

			$row = 1;
			if (($handle = fopen($path, "r")) !== FALSE) 
			{
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
				{
					$num = count($data);
					//echo "<p> $num fields in line $row: <br /></p>\n";
					$row++;
					$date1="";
					$time1="";
					$date2="";
					$time2="";					
					/*
					 echo "\ndata[0]=".$data[0] . "\n";
					 echo "data[1]=".$data[1] . "\n";
					 echo "data[2]=".$data[2] . "\n";
					 echo "data[3]=".$data[3] . "\n";
					 echo "data[4]=".$data[4] . "\n";
					 echo "data[5]=".$data[5] . "\n";
					 echo "data[6]=".$data[6] . "\n";
					 echo "data[7]=".$data[7] . "\n";
					 echo "data[8]=".$data[8] . "\n";
					 echo "data[9]=".$data[9] . "\n";
					*/
					
					if($num<10)
					{
						continue;
					}
					if($row > 2)
					{
						$shift_input[] = $data[4];
						$route_input[] = $data[6];
						$vehicle_input[] = $data[7];
						$transporter_input[] = $data[8];
						$customer_input[] = $data[9];
						
						$tmp_date1_ev[] = $data[0]." ".$data[1];
						$tmp_date2_ev[] = $data[2]." ".$data[3];								
						//echo "\nEV:r=".$row." ,data[4]=".$data[4]." ,data[6]=".$data[6]." ,data[7]=".$data[7]." ,data[9]=".$data[9];
					} 					
				}
				fclose($handle);
				//echo "\nsizeof(route_input)=".sizeof($route_input);
			}

			$min_date_ev = min($tmp_date1_ev);
			$max_date_ev = max($tmp_date2_ev);
		}
     
		if( ($file_ext[0] == "5") && ($shift_time=="ZPMM") )			//###### MORNING FILE
		{
			$path = $dir."/".$file;
			$row = 1;        
			if (($handle = fopen($path, "r")) !== FALSE) {
					
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					//echo "<p> $num fields in line $row: <br /></p>\n";              
					$row++;
					$date1="";
					$time1="";
					$date2="";
					$time2="";
					
					if($num<10)
					{
						continue;
					}
					if($row > 2)
					{				
						$shift_input[] = $data[4];
						$route_input[] = $data[6];
						$vehicle_input[] = $data[7];
						$customer_input[] = $data[9];
						//echo "\nMOR::r=".$row." ,data[4]=".$data[4]." ,data[6]=".$data[6]." ,data[7]=".$data[7]." ,data[9]=".$data[9];
					}

					if($row>2)
					{
						$tmp_date1_mor[] = $data[0]." ".$data[1];
						$tmp_date2_mor[] = $data[2]." ".$data[3];
						//echo "<br>Date2=".$date2." ,Time2=".$time2;    
					}											
				}
				fclose($handle);			
			}

			$min_date_mor = min($tmp_date1_mor);
			$max_date_mor = max($tmp_date2_mor);
		}
		
		if($file_ext[0] == "6")	//######## TRANSPORTER FILE
		{
			$path = $dir."/".$file;

			$row = 1;
			if (($handle = fopen($path, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				//echo "<p> $num fields in line $row: <br /></p>\n";
				$row++;
				for ($c=0; $c < $num; $c++) {
				 //echo "\ndata=".$data[$c] . "<br />\n";
					if($c==0)
					{
						$vehicle_t[] = $data[$c];
					}
					else if($c==1)
					{
						$transporter[] = $data[$c];
					}                                 
				  }
				}
				fclose($handle);
			}     
		} //IF FORMAT 5

				
		//######## GET PLANT CUSTOMER MAPPING DETAIL ##################			
		if( ($file_ext[0] == "7") && ($shift_time=="ZPME") )		//###### EVENING FILE		
		{
			//echo "\nEVVVV";
			$path = $dir."/".$file;

			$row = 1;
			if (($handle = fopen($path, "r")) !== FALSE) 
			{
				//echo "\nREADEV";
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
				{
					$num = count($data);
					//echo "<p> $num fields in line $row: <br /></p>\n";
					$row++;					
					/*
					 echo "data[5]=".$data[5] . "\n";
					 echo "data[9]=".$data[9] . "\n";
					*/					
					if($num<2)
					{
						continue;
					}
					//echo "\nRow=".$row;
					if($row > 2)
					{												
						$relative_customer_input[] = $data[0];																
						$relative_plant_input[] = $data[1];
						$relative_transporter_input[] = $data[2];
						$relative_route_input[] = $data[3];
						//echo "\nEVE::r=".$row." ,data[0]=".$data[0]." ,data[1]=".$data[1]."\n";
					} 					
				}
				fclose($handle);
				//echo "\nsizeof(route_input)=".sizeof($route_input);
			}
		}
     
		if( ($file_ext[0] == "8") && ($shift_time=="ZPMM") )			//###### MORNING FILE
		{
			$path = $dir."/".$file;
			$row = 1;        
			if (($handle = fopen($path, "r")) !== FALSE) {
					
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					//echo "<p> $num fields in line $row: <br /></p>\n";              
					$row++;
					
					if($num<2)
					{
						continue;
					}
					if($row > 2)
					{										
						$relative_customer_input[] = $data[0];																
						$relative_plant_input[] = $data[1];
						$relative_transporter_input[] = $data[2];
						$relative_route_input[] = $data[3];
						//echo "\nMOR::r=".$row." ,data[5]=".$data[5]." ,data[9]=".$data[9]."\n";
					}											
				}
				fclose($handle);			
			}
		}	
		
		
	}  //
}  
  
  //echo 
  
closedir($dh);

  /*for($k=0;$k<sizeof($shift);$k++)
  {
    echo "<br>Shift=".$shift[$k]." ,Point=".$point[$k]." ,Timing=".$timing[$k];
  }
  
  for($k=0;$k<sizeof($vehicle);$k++)
  {
    echo "<br>Vehicle=".$vehicle[$k]." ,Transporter=".$transporter[$k];
  } */
  
  /*echo "<br>MinDateMor=".$min_date_mor." ,MaxDateMor=".$max_date_mor;
  echo "<br>MinDateEv=".$min_date_ev." ,MaxDateEv=".$max_date_ev;*/
} //function closed
   
?>