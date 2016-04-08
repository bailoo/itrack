<?php
include_once('update_route_assignment.php');
//get_master_detail("231", "ZPME");
function get_master_detail($account_id, $shift_time)
{
	//echo "\nAccount_id=".$account_id." ,shift_time=".$shift_time;
	global $plant_input;
	global $transporter_input_ev;
        global $vehicle_input_ev;
	global $route_input_ev;
	global $route_input_type_ev;
	global $route_input_mor;
        global $transporter_input_mor;
        global $vehicle_input_mor;
	global $route_input_type_mor;
	global $customer_input;

	//$dir = "c:\\gps_report/231/master";
	//$dir = "c:\\halt2/test_master";
	//$dir = "D:\\test_app/gps_report/231/master_test/".$account_id;	
        $ev_file_orig = ""; $mor_file_orig="";
        $content_ev = ""; $content_mor = "";

        //$dir = "/var/www/html/vts/test/src/php/gps_report/".$account_id."/master";
	$dir = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master";
	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false) {
		//echo "<A HREF=\"$file\">$file</A><BR>\n";
		$file_tmp = explode("#",$file);
		$file_ext = explode(".",$file_tmp[2]);
		
		//echo "\nfile_ext[0]=".$file_ext[0]." ,shift_time=".$shift_time;		
		if( ($file_ext[0] == "4") && ($shift_time=="ZPME") )		//###### EVENING FILE
		{
			//echo "\nEVVVV";
			$path = $dir."/".$file;
			$row = 1;
			if (($handle = fopen($path, "r")) !== FALSE) 
			{
                                $ev_file_orig = $path;
                                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
				{
					$num = count($data);
					//echo "<p> $num fields in line $row: <br /></p>\n";
					$row++;					
					/*
					 echo "data[5]=".$data[5] . "\n";
					 echo "data[9]=".$data[9] . "\n";
					*/					
					if($num<11)
					{
                                            continue;
					}
                                        else if($row==2)
                                        {
                                            //echo "<br>Data[row2]=".$data[0];
                                            $content_ev.=$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$vstr.",".$data[9].",".$data[10]."\n";
                                        }
					if($row > 2)
					{		
                                            //$valid_rec = false;
                                            //$valid_rec = check_valid_record($data[6],$data[8]);
                                            //if($valid_rec)
                                            //{
                                                //echo "::Stored";
						$vstr = preg_replace('/[^a-zA-Z0-9\']/', '', $data[8]);
                                                $vstr = strtoupper($vstr);
                                                $plant_input_ev[] = $data[5];
                                                $route_input_ev[] = $data[6];
                                                $route_input_type_ev[] = $data[7];
                                                $vehicle_input_ev[] = $vstr;
                                                $transporter_input_ev[] = $data[9];
                                                $customer_input_ev[] = $data[10];
                                                
                                                $content_ev.=$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$vstr.",".$data[9].",".$data[10]."\n";
                                            //}
                                            //echo "\nEVE::r=".$row." ,data[5]=".$data[5]." ,data[9]=".$data[9]."\n";
					} 					
				}
				fclose($handle);
				//echo "\nsizeof(route_input)=".sizeof($route_input);
			}
		}
     
		if( ($file_ext[0] == "5") && ($shift_time=="ZPMM") )			//###### MORNING FILE
		{
			$path = $dir."/".$file;
			$row = 1;        
			if (($handle = fopen($path, "r")) !== FALSE) {
                                $mor_file_orig = $path;
                            
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					//echo "<p> $num fields in line $row: <br /></p>\n";              
					$row++;
					
					if($row==2)
                                        {
                                            $content_mor.=$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8].",".$data[9].",".$data[10]."\n";
                                        }
                                        if($num<11)
					{
                                            continue;
					}
					if($row > 2)
					{				
                                            //$valid_rec = false;
                                            //$valid_rec = check_valid_record($data[6],$data[8]);
                                            //if($valid_rec)
                                            //{						
						$vstr = preg_replace('/[^a-zA-Z0-9\']/', '', $data[8]);
                                                $vstr = strtoupper($vstr);
                                                $plant_input_mor[] = $data[5];
                                                $route_input_mor[] = $data[6];
                                                $route_input_type_mor[] = $data[7];
                                                $vehicle_input_mor[] = $vstr;
                                                $transporter_input_mor[] = $data[9];
                                                $customer_input_mor[] = $data[10];                                                
                                                $content_mor.=$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$vstr.",".$data[9].",".$data[10]."\n";
                                                //echo "\nMOR::r=".$row." ,data[5]=".$data[5]." ,data[9]=".$data[9]."\n";
                                            //}
					}											
				}
				fclose($handle);			
			}
		}	
	}  //
  
	//echo   
	closedir($dh);

        //### UPDATE EVENING ORIGINAL
        if($shift_time=="ZPME")
        {
            unlink($ev_file_orig);
            $file = fopen($ev_file_orig,"w");
            fwrite($file,$content_ev);
            fclose($file);            
        }
        else if($shift_time=="ZPMM")
        {
            unlink($mor_file_orig);
            $file = fopen($mor_file_orig,"w");
            fwrite($file,$content_mor);
            fclose($file);            
        }
        //### UPDATE MORNING ORIGINAL
        
	if($shift_time == "ZPME")
	{		
		//$plant_customer_write_path_ev = "D:\\test_app/gps_report/231/master_test/".$account_id."/evening_plant_customer#1#7.csv";
		$plant_customer_write_path_ev = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master/evening_plant_customer#1#7.csv";	
                //$plant_customer_write_path_ev = "/var/www/html/vts/test/src/php/gps_report/".$account_id."/master/evening_plant_customer#1#7.csv";	
		unlink($plant_customer_write_path_ev);
		//$plant_customer_write_path_ev = "C:\\xampp/htdocs/sorting_motherdairy/evening_plant_customer#1#7.csv";
		sort_station($plant_input_ev, $customer_input_ev, $transporter_input_ev, $route_input_ev, $route_input_type_ev, $plant_customer_write_path_ev);
                
                update_vehicle_route_assignment($vehicle_input_ev, $route_input_ev, $account_id, "ev");
	}
	
	if($shift_time == "ZPMM")
	{
		//$plant_customer_write_path_mor = "D:\\test_app/gps_report/231/master_test/".$account_id."/master/morning_plant_customer#1#8.csv";
		$plant_customer_write_path_mor = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master/morning_plant_customer#1#8.csv";
                //$plant_customer_write_path_mor = "/var/www/html/vts/test/src/php/gps_report/".$account_id."/master/morning_plant_customer#1#8.csv";
		unlink($plant_customer_write_path_mor);
		//$plant_customer_write_path_mor = "C:\\xampp/htdocs/sorting_motherdairy/morning_plant_customer#1#8.csv";	
		sort_station($plant_input_mor, $customer_input_mor, $transporter_input_mor, $route_input_mor, $route_input_type_mor, $plant_customer_write_path_mor); 
	
                update_vehicle_route_assignment($vehicle_input_mor, $route_input_mor, $account_id, "mor");
        }
  
} //function closed
  

function sort_station($plant_input, $customer_input, $transporter_input, $route_input, $route_input_type, $plant_customer_write_path)
{
	//echo "\nSORT STATION=".$plant_customer_write_path." ,size=".sizeof($customer_input)."\n";
	for($x = 1; $x < sizeof($customer_input); $x++)
	{
		$tmp_customer_input = $customer_input[$x];
		$tmp_plant_input = $plant_input[$x];   				     				
		$tmp_transporter_input = $transporter_input[$x];
		$tmp_route_input = $route_input[$x];
		$tmp_route_input_type = $route_input_type[$x];

		//echo "<br>Customer=".$tmp_customer_input." ,Plant=".$tmp_plant_input." ,Route=".$tmp_route_input;		
		///////////      				

		$z = $x - 1;
		$done = false;
		while($done == false)
		{
			$customer_tmp1 = $customer_input[$z];

			if ($customer_tmp1 >$tmp_customer_input)
			{
				$customer_input[$z + 1] = $customer_input[$z];
				$plant_input[$z + 1] = $plant_input[$z];			                         			
				$transporter_input[$z + 1] = $transporter_input[$z];
				$route_input[$z + 1] = $route_input[$z];
				$route_input_type[$z + 1] = $route_input_type[$z];
				//////////////////
				$z = $z - 1;
				if ($z < 0)
				{
					$done = true;
				}
			}
			else
			{
				$done = true;
			}
		} //WHILE CLOSED

		$customer_input[$z + 1] = $tmp_customer_input;
		$plant_input[$z + 1] = $tmp_plant_input;
		$transporter_input[$z + 1] = $tmp_transporter_input;
		$route_input[$z + 1] = $tmp_route_input;
		$route_input_type[$z + 1] = $tmp_route_input_type;
                    
	}  // FOR CLOSED        
	
	///////////// SORTING CLOSED /////////////////////
	$linetowrite = "Customer,Plant,Transporter,Route,RouteType";
	$repeated_index = array();
	$repeatflag_plant=false;
	$repeatflag_transporter=false;
	$repeatflag_route=false;
	$repeatflag_route_type=false;
	
	//echo "\nSizeofCustomer=".sizeof($customer_input);	
	for($y=0;$y<sizeof($customer_input);$y++)
	{		
		//if($customer_input[$y] == "38") { echo "\nFound-38"; }
		//echo "\nRepeatIndexSize=".sizeof($repeated_index);
	//	echo "y1=".$y."\n";
		//echo "\nCustomer=".$customer_input[$y];
		
		$continue_flag = false;
		
		$customer_string = $customer_input[$y];
		$plant_string = $plant_input[$y];
		$transporter_string = $transporter_input[$y];
		$route_string = $route_input[$y];		
		$route_type_string = $route_input_type[$y];		
	
		for($m=$y+1;$m<sizeof($customer_input);$m++)
		{
			if(trim($customer_input[$y]) == trim($customer_input[$m]))
			{
				$repeatflag_plant=false;
				$repeatflag_transporter =false;
				$repeatflag_route =false;
				$repeatflag_route_type =false;
				
				for($i=$y+1;$i<=$m;$i++)
				{
					if(trim($plant_input[$i])==trim($plant_input[$y]))
					{
							$repeatflag_plant=true;
					}
					
					if(trim($transporter_input[$i])==trim($transporter_input[$y]))
					{
							$repeatflag_transporter=true;
					}

					if(trim($route_input[$i])==trim($route_input[$y]))
					{
							$repeatflag_route=true;
					}
					if(trim($route_input_type[$i])==trim($route_input_type[$y]))
					{
							$repeatflag_route_type=true;
					}											
				}
				/*if($repeatflag_plant==false)
				{
					$plant_string = $plant_string.'/'.$plant_input[$m];				
				}*/
				if($repeatflag_route==false)
				{
					$route_string = $route_string.'/'.$route_input[$m];
					$route_type_string = $route_type_string.'/'.$route_input_type[$m];				
					$transporter_string = $transporter_string.'/'.$transporter_input[$m];
					$plant_string = $plant_string.'/'.$plant_input[$m];	
				}			
			}			
			else
			{
				break;
			}
		}
		$y=$m-1;
		//echo "y=".$y."\n";
		
		if($plant_string == "") $plant_string = "-";
		$linetowrite =  $linetowrite."\n"." ".$customer_string.",".$plant_string.",".$transporter_string.",".$route_string.",".$route_type_string;
		//echo "linetowrite=".$linetowrite."\n";		         
	}		
	
	//echo "linetowrite=".$linetowrite."\n";
	//FINAL FILE WRITE
	$file = fopen($plant_customer_write_path,"w");
	fwrite($file,$linetowrite);
	fclose($file);         
   
} // FUNCTION CLOSED
 
function check_valid_record($route_tmp, $vehicle_tmp)
{                     
    //echo "<br>vehicle_tmp=".$vehicle_tmp;
    if(preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/', $vehicle_tmp))      //## VALIDATE ALPHANUMERIC VEHICLE
    {
        if (is_numeric($route_tmp))                       //## VALIDATE NUMERIC ROUTE
        {
            //echo ":TRUE"; 
            return true;
        }
    }
    return false; 
}
?> 
