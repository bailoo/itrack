<?php

function SortFile($xml_unsorted, $xml_original_tmp)
{
  //echo "<br>TEST";
  //echo "<br>unsorted=".$xml_unsorted." orgi=".$xml_original_tmp;  
  $fexist =1;
  $fix_tmp = 1;
  $xml = fopen($xml_unsorted, "r") or $fexist = 0;
  
  $count = count(file($xml_unsorted));
  //echo "<BR>COUNT======== $count lines in $xml";
  //$xml2 = '"'.$xml.'"';
  if($fexist)
  {
      //echo "<br>exists";
      $i=0;
      $format = 2;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
      		$DataValid = 0;
          $line = trim(fgets($xml));        // STRING SHOULD BE IN SINGLE QUOTE   
          //echo '<textarea>'.$line.'</textarea>';  
      
          if(strpos($line,'fix="1"'))     // RETURN FALSE IF NOT FOUND
          {
            $fix_tmp = 1;
            $format = 1;
          }      
          else if(strpos($line,'fix="0"'))
          {
            $fix_tmp = 0;
            $format = 1;
          } 
                               
          if( (strlen($line)>10) )
          {
            //for($p=0;$p<strlen($line);$p++)
              $len1 = strlen($line)-3;
              //echo "<br>char:".$line[$len1]." ,p=".$p;
              
              $len1 = strlen($line)-3;
              //echo "<br>char:".$line[$len1]." ,p=".$p;              
              
            //echo '<br>';
            $DataValid = 1;
          }
          //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,Datavalid=".$DataValid." ,fix".$fix_tmp." format=".$format;                    
          
      		$line_1 = preg_replace('/ /', '', $line);
      		$line_1 = preg_replace('/\//', '', $line_1);
      		$reg = '/^<[^<].*">$/';
              
          //if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($format==2) && ($DataValid == 1) )
          if( (preg_match($reg, $line_1, $data_match)) && ($format==2) && ($DataValid == 1) )
          { 			        
              //$status = preg_match('/sts="[^"]+/', $line, $sts_tmp);
              //echo "<br>statusA=".$status;
              $status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);              
              //$status = preg_match('/vehiclename="[^"]+/', $line, $vehiclename_tmp);
              $status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
              $status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
              $status = preg_match('/alt="[^" ]+/', $line, $alt_tmp);
              $status = preg_match('/speed="[^" ]+/', $line, $speed_tmp);
              $status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
              //$status = preg_match('/fuel="[^" ]+/', $line, $fuel_tmp);
              //$status = preg_match('/vehicletype="[^" ]+/', $line, $vehicletype_tmp); 
          }
          
          //else if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($format==1) && ($DataValid == 1) )
          else if( (preg_match($reg, $line_1, $data_match)) && ($format==1) && ($DataValid == 1) )
          {
              //$status = preg_match('/sts="[^"]+/', $line, $sts_tmp);
              
              //$status = preg_match('/msgtype="[^" ]+/', $line, $msgtype_tmp);
              //echo "<br>status1=".$status;
              $status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
              //echo "<br>status2=".$status;
              //$status = preg_match('/vehiclename="[^"]+/', $line, $vehiclename_tmp);
              //echo "<br>status3=".$status;
              $status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
              //echo "<br>status4=".$status;
              $status = preg_match('/lat="[^"]+/', $line, $lat_tmp);
              //echo "<br>status5=".$status;
              $status = preg_match('/lng="[^"]+/', $line, $lng_tmp);
              //echo "<br>status6=".$status;
              $status = preg_match('/alt="[^"]+/', $line, $alt_tmp);
              //echo "<br>status7=".$status;
              //$status = preg_match('/vehicletype="[^" ]+/', $line, $vehicletype_tmp);
              //echo "<br>status8=".$status; 
              $status = preg_match('/speed="[^" ]+/', $line, $speed_tmp);
              //echo "<br>status9=".$status;
              //$status = preg_match('/io8="[^" ]+/', $line, $fuel_tmp);
              //echo "<br>status10=".$status;

              //////////////////////////////////////////////////////////////////              
              //$status = preg_match('/fix="[^" ]+/', $line, $fix_tmp);
              //echo "<br>status11=".$status;
              //$status = preg_match('/ver="[^" ]+/', $line, $ver_tmp);
              //echo "<br>status12=".$status;
              //$status = preg_match('/no_of_sat="[^" ]+/', $line, $no_of_sat_tmp);
              //echo "<br>status13=".$status;
              //$status = preg_match('/cellname="[^" ]+/', $line, $cellname_tmp);
              //echo "<br>status14=".$status;
              $status = preg_match('/distance="[^" ]+/', $line, $distance_tmp);
              //echo "<br>status15=".$status;
              //$status = preg_match('/io1="[^" ]+/', $line, $io1_tmp);
              //echo "<br>status16=".$status;
              //$status = preg_match('/io2="[^" ]+/', $line, $io2_tmp);
              //echo "<br>status17=".$status;
              //$status = preg_match('/io3="[^" ]+/', $line, $io3_tmp);
              //echo "<br>status18=".$status;
              //$status = preg_match('/io4="[^" ]+/', $line, $io4_tmp);
              //echo "<br>status19=".$status;
              //$status = preg_match('/io5="[^" ]+/', $line, $io5_tmp);
              //echo "<br>status20=".$status;
              //$status = preg_match('/io6="[^" ]+/', $line, $io6_tmp);
              //echo "<br>status21=".$status;
              //$status = preg_match('/io7="[^" ]+/', $line, $io7_tmp);
              //echo "<br>status22=".$status;
              //$status = preg_match('/sig_str="[^" ]+/', $line, $sig_str_tmp);
              //echo "<br>status23=".$status;
              //$status = preg_match('/sup_v="[^" ]+/', $line, $sup_v_tmp);
              //echo "<br>status24=".$status;
              //$status = preg_match('/speed_a="[^" ]+/', $line, $speed_a_tmp);
              //echo "<br>status25=".$status;
              //$status = preg_match('/geo_in_a="[^" ]+/', $line, $geo_in_a_tmp);
              //echo "<br>status26=".$status;
              //$status = preg_match('/geo_out_a="[^" ]+/', $line, $geo_out_a_tmp);
              //echo "<br>status27=".$status;
              //$status = preg_match('/stop_a="[^" ]+/', $line, $stop_a_tmp);
              //echo "<br>status28=".$status;
              //$status = preg_match('/move_a="[^" ]+/', $line, $move_a_tmp);
              //echo "<br>status29=".$status;
              //$status = preg_match('/lowv_a="[^" ]+/', $line, $lowv_a_tmp);
              //echo "<br>status30=".$status;
          } // FORMAT 1 CLOSED                                                                                                                                                                                                                                                                        
               
         //echo "test3".'<BR>';           
                             
         if( ($format == 2) && ($DataValid == 1))
         {
            //echo "<br>In Format22";
			      //$datetime[$i] = 'datetime="'.$datetime_tmp[0].'"';                // Store Name with Value
            //$sts[$i] = $sts_tmp[0].'"';
            $datetime[$i] = $datetime_tmp[0].'"';
            $vehicleserial[$i] = $vehicleserial_tmp[0].'"';
            //$vehiclename[$i] = $vehiclename_tmp[0].'"';
            
            if($lat_tmp[0]=="" || $lng_tmp[0]=="")
            {
             $lat_tmp[0] = 'lat="-';
             $lng_tmp[0] = 'lng="-';
            }
            
            $lat[$i] = $lat_tmp[0].'"';
            $lng[$i] = $lng_tmp[0].'"';
            //$alt[$i] = $alt_tmp[0].'"';            
            
            $alt[$i] = 'alt="-"';
            $speed[$i] = $speed_tmp[0].'"';
            //$fuel[$i] = $fuel_tmp[0].'"'; 
            //$vehicletype[$i] = $vehicletype_tmp[0].'"';         
			
            //echo "<br>rec1=".$datetime[$i].",".$vehicleserial[$i].",".$vehiclename[$i].",".$lat[$i].",".$lng[$i].",".$speed[$i].",".$fuel[$i].",".$vehicletype[$i];                          
            $i++;                                     
         }   // IF FORMAT 2 CLOSED
         else if( ($format == 1) && ($DataValid == 1))
         {   
            //echo "<br>In Format11";
			      //$datetime[$i] = 'datetime="'.$datetime_tmp[0].'"';                // Store Name with Value
			      //$sts[$i] = $sts_tmp[0].'"';
            $datetime[$i] = $datetime_tmp[0].'"';
            //$msgtype[$i] = $msgtype_tmp[0].'"';
            $vehicleserial[$i] = $vehicleserial_tmp[0].'"';
            //$vehiclename[$i] = $vehiclename_tmp[0].'"';
            
            //echo "<br>lattmp[0]=".$$lat_tmp[0];
            if($lat_tmp[0]=="" || $lng_tmp[0]=="")
            {
             //echo "<br>lattmp[0]before".$$lat_tmp[0];
             $lat_tmp[0] = 'lat="-';
             $lng_tmp[0] = 'lng="-';
             //echo "<br>lattmp[0]after".$$lat_tmp[0];
            }
                        
            $lat[$i] = $lat_tmp[0].'"';
            $lng[$i] = $lng_tmp[0].'"';
            $alt[$i] = $alt_tmp[0].'"';
            //$vehicletype[$i] = $vehicletype_tmp[0].'"';
            $speed[$i] = $speed_tmp[0].'"';
            //$fuel[$i] = $fuel_tmp[0].'"'; 
            
            //$fix[$i] = $fix_tmp[0].'"';  
            //$ver[$i] = $ver_tmp[0].'"';             
            //$no_of_sat[$i] = $no_of_sat_tmp[0].'"'; 
            //$cellname[$i] = $cellname_tmp[0].'"'; 
            $distance[$i] = $distance_tmp[0].'"'; 
            //$io1[$i] = $io1_tmp[0].'"'; 
            //$io2[$i] = $io2_tmp[0].'"'; 
            //$io3[$i] = $io3_tmp[0].'"'; 
            //$io4[$i] = $io4_tmp[0].'"'; 
            //$io5[$i] = $io5_tmp[0].'"'; 
            //$io6[$i] = $io6_tmp[0].'"'; 
            //$io7[$i] = $io7_tmp[0].'"'; 
            //$sig_str[$i] = $sig_str_tmp[0].'"'; 
            //$sup_v[$i] = $sup_v_tmp[0].'"'; 
            //$speed_a[$i] = $speed_a_tmp[0].'"'; 
            //$geo_in_a[$i] = $geo_in_a_tmp[0].'"'; 
            //$geo_out_a[$i] = $geo_out_a_tmp[0].'"'; 
            //$stop_a[$i] = $stop_a_tmp[0].'"'; 
            //$move_a[$i] = $move_a_tmp[0].'"'; 
            //$lowv_a[$i] = $lowv_a_tmp[0].'"';   
           // echo "<br>dt=".$datetime[$i];                         
            $i++;                      
        }    // IF FORMAT 1 CLOSED    
                      
      }   // WHILE CLOSED

       ////////////// SORTING ALGO OK ///////////////////////////      
      // FORMAT 2 OPENS      
      if($format == 2)
      {
        	//echo "<br>In Format222";
			    for($x = 1; $x < $i; $x++) 
        	{
      	      $date_x=null;
              //$date_x = explode("=",$sts[$x]);
              //$date_x = explode("=",$sts[$x]);
              $date_x = explode("=",$datetime[$x]);
              $value = preg_replace('/"/', '', $date_x[1]);
  
              //$tmp_sts = $sts[$x];
              $tmp_datetime = $datetime[$x];
              //$tmp_msgtype = $msgtype[$x];
              $tmp_vehicleserial = $vehicleserial[$x];
      				//$tmp_vehiclename = $vehiclename[$x];
      				$tmp_lat = $lat[$x];
      				$tmp_lng = $lng[$x];
      				$tmp_alt = $alt[$x];
      				//$tmp_vehicletype = $vehicletype[$x];
      				$tmp_speed = $speed[$x];
      				//$tmp_fuel = $fuel[$x];
      				     				
              ////////////////////////////////////////       				
              $z = $x - 1;
              $done = false;
              while($done == false)
      		    {
                   $date_x=null;
                   //$date_x = explode("=",$sts[$z]);
                   $date_x = explode("=",$datetime[$z]);
      			       $date_tmp1 = preg_replace('/"/', '', $date_x[1]);
      
                  if ($date_tmp1 >$value)
      			      {
                      //$sts[$z + 1] = $sts[$z];
                      $datetime[$z + 1] = $datetime[$z];
                      //$msgtype[$z + 1] = $msgtype[$z];
              				$vehicleserial[$z + 1] = $vehicleserial[$z];
              				//$vehiclename[$z + 1] = $vehiclename[$z];
              				$lat[$z + 1] = $lat[$z];
              				$lng[$z + 1] = $lng[$z];
              				$alt[$z + 1] = $alt[$z];
              				//$vehicletype[$z + 1] = $vehicletype[$z];
              				$speed[$z + 1] = $speed[$z];
              				//$fuel[$z + 1] = $fuel[$z];
              				           				
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
      		    }
                
              //$sts[$z + 1] = $tmp_sts;
              $datetime[$z + 1] = $tmp_datetime;
              //$msgtype[$z + 1] = $tmp_msgtype;
              $vehicleserial[$z + 1] = $tmp_vehicleserial;
              //$vehiclename[$z + 1] = $tmp_vehiclename;
              $lat[$z + 1] = $tmp_lat;
              $lng[$z + 1] = $tmp_lng;
              $alt[$z + 1] = $tmp_alt;
              //$vehicletype[$z + 1] = $tmp_vehicletype;
              $speed[$z + 1] = $tmp_speed;
              //$fuel[$z + 1] = $tmp_fuel;                              
        	}            
         ///////////// SORTING ALGO 2 CLOSED /////////////////////
                           
         $fh = fopen($xml_original_tmp, 'a') or die("can't open file 5"); //append
         $linetowrite ="<t1>"; 
         fwrite($fh, $linetowrite); 
         //echo "<br>SORTED FILE".$i;
         
         for($y=0;$y<$i;$y++)
         {
            //echo "<br>A=".$datetime[$y];             
            //$linetowrite =  "\n"."<marker ".$sts[$y]." ".$vehicleserial[$y]." ".$vehiclename[$y]." ".$lat[$y]." ".$lng[$y]." ".$alt[$y]." ".$speed[$y]." ".$datetime[$y]." ".$fuel[$y]." ".$vehicletype[$y]."/>";
            $linetowrite =  "\n"."<marker ".$vehicleserial[$y]." ".$lat[$y]." ".$lng[$y]." ".$alt[$y]." ".$speed[$y]." ".$datetime[$y]."/>";
            fwrite($fh, $linetowrite);  
            //echo "<br>www".$linetowrite." ".$y;         
         }
       
      }// FORMAT 2 CLOSED      
      
            
      // FORMAT 1 OPENS      
      if($format == 1)
      {
        	//echo "<br>In Format111";
  			for($x = 1; $x < $i; $x++) 
        {
      	      $date_x=null;
              //$date_x = explode("=",$sts[$x]);
              $date_x = explode("=",$datetime[$x]);
              $value = preg_replace('/"/', '', $date_x[1]);
  
              //$tmp_sts = $sts[$x];
              $tmp_datetime = $datetime[$x];
              //$tmp_msgtype = $msgtype[$x];
              $tmp_vehicleserial = $vehicleserial[$x];
      				//$tmp_vehiclename = $vehiclename[$x];
      				$tmp_lat = $lat[$x];
      				$tmp_lng = $lng[$x];
      				$tmp_alt = $alt[$x];
      				//$tmp_vehicletype = $vehicletype[$x];
      				$tmp_speed = $speed[$x];
      				//$tmp_fuel = $fuel[$x];
      				     				
              ////////////      				
              //$tmp_fix = $fix[$x];   
              //$tmp_ver = $ver[$x];            
              //$tmp_no_of_sat = $no_of_sat[$x]; 
              //$tmp_cellname = $cellname[$x]; 
              $tmp_distance = $distance[$x]; 
              //$tmp_io1 = $io1[$x]; 
              //$tmp_io2 = $io2[$x]; 
              //$tmp_io3 = $io3[$x]; 
              //$tmp_io4 = $io4[$x]; 
              //$tmp_io5 = $io5[$x]; 
              //$tmp_io6 = $io6[$x]; 
              //$tmp_io7 = $io7[$x]; 
              //$tmp_sig_str = $sig_str[$x]; 
              //$tmp_sup_v = $sup_v[$x]; 
              //$tmp_speed_a = $speed_a[$x]; 
              //$tmp_geo_in_a = $geo_in_a[$x]; 
              //$tmp_geo_out_a = $geo_out_a[$x]; 
              //$tmp_stop_a = $stop_a[$x]; 
              //$tmp_move_a = $move_a[$x]; 
              //$tmp_lowv_a = $lowv_a[$x];    				     				
      				///////////      				
      				
              $z = $x - 1;
              $done = false;
              while($done == false)
      		    {
                   $date_x=null;
                   //$date_x = explode("=",$sts[$z]);
                   $date_x = explode("=",$datetime[$z]);
      			       $date_tmp1 = preg_replace('/"/', '', $date_x[1]);
      
                  if ($date_tmp1 >$value)
      			      {
                      //$sts[$z + 1] = $sts[$z];
                      $datetime[$z + 1] = $datetime[$z];
                      //$msgtype[$z + 1] = $msgtype[$z];
              				$vehicleserial[$z + 1] = $vehicleserial[$z];
              				//$vehiclename[$z + 1] = $vehiclename[$z];
              				$lat[$z + 1] = $lat[$z];
              				$lng[$z + 1] = $lng[$z];
              				$alt[$z + 1] = $alt[$z];
              				//$vehicletype[$z + 1] = $vehicletype[$z];
              				$speed[$z + 1] = $speed[$z];
              				//$fuel[$z + 1] = $fuel[$z];
              				           				
              				//////////////////
                      //$fix[$z + 1] = $fix[$z];    
                      //$ver[$z + 1] = $ver[$z];                       
                      //$no_of_sat[$z + 1] = $no_of_sat[$z];
                      //$cellname[$z + 1] = $cellname[$z];
                      $distance[$z + 1] = $distance[$z];
                      //$io1[$z + 1] = $io1[$z];
                      //$io2[$z + 1] = $io2[$z];
                      //$io3[$z + 1] = $io3[$z];
                      //$io4[$z + 1] = $io4[$z];
                      //$io5[$z + 1] = $io5[$z];
                      //$io6[$z + 1] = $io6[$z];
                      //$io7[$z + 1] = $io7[$z];                                                
                      //$sig_str[$z + 1] = $sig_str[$z];  
                      //$sup_v[$z + 1] = $sup_v[$z];
                      //$speed_a[$z + 1] = $speed_a[$z];  
                      //$geo_in_a[$z + 1] = $geo_in_a[$z];  
                      //$geo_out_a[$z + 1] = $geo_out_a[$z];  
                      //$stop_a[$z + 1] = $stop_a[$z]; 
                      //$move_a[$z + 1] = $move_a[$z];
                      //$lowv_a[$z + 1] = $lowv_a[$z];                            				
              				
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
      		    }
                
              //$sts[$z + 1] = $tmp_sts;
              $datetime[$z + 1] = $tmp_datetime;
              //$msgtype[$z + 1] = $tmp_msgtype;
              $vehicleserial[$z + 1] = $tmp_vehicleserial;
              //$vehiclename[$z + 1] = $tmp_vehiclename;
              $lat[$z + 1] = $tmp_lat;
              $lng[$z + 1] = $tmp_lng;
              $alt[$z + 1] = $tmp_alt;
              //$vehicletype[$z + 1] = $tmp_vehicletype;
              $speed[$z + 1] = $tmp_speed;
              //$fuel[$z + 1] = $tmp_fuel;
              
              //$fix[$z + 1] = $tmp_fix;   
              //$ver[$z + 1] = $tmp_ver;              
              //$no_of_sat[$z + 1] = $tmp_no_of_sat;
              //$cellname[$z + 1] = $tmp_cellname;
              //$io1[$z + 1] = $tmp_io1;
              //$io2[$z + 1] = $tmp_io2;
              //$io3[$z + 1] = $tmp_io3;
              //$io4[$z + 1] = $tmp_io4;
              //$io5[$z + 1] = $tmp_io5;
              //$io6[$z + 1] = $tmp_io6;
              //$io7[$z + 1] = $tmp_io7;
              //$sig_str[$z + 1] = $tmp_sig_str;
              //$sup_v[$z + 1] = $tmp_sup_v;
              //$speed_a[$z + 1] = $tmp_speed_a;
              //$geo_in_a[$z + 1] = $tmp_geo_in_a;
              //$geo_out_a[$z + 1] = $tmp_geo_out_a;     
              //$stop_a[$z + 1] = $tmp_stop_a;
              //$move_a[$z + 1] = $tmp_move_a;        
              //$lowv_a[$z + 1] = $tmp_lowv_a;                        
        	}            
         ///////////// SORTING ALGO 2 CLOSED /////////////////////
                  
         $fh = fopen($xml_original_tmp, 'a') or die("can't open file 5"); //append
         $linetowrite ="<t1>"; 
         fwrite($fh, $linetowrite); 
         //echo "<br>SORTED FILE".$i;
         
         //echo "<br>i=".$i;
         
         for($y=0;$y<$i;$y++)
         {
            //echo "<br>A=".$datetime[$y];             
            //$linetowrite =  "\n"."< marker ".$sts[$y]." ".$msgtype[$y]." ".$vehicleserial[$y]." ".$vehiclename[$y]." ".$lat[$y]." ".$lng[$y]." ".$alt[$y]." ".$speed[$y]." ".$datetime[$y]." ".$fuel[$y]." ".$vehicletype[$y]." ".$fix[$y]." ".$ver[$y]." ".$no_of_sat[$y]." ".$cellname[$y]." ".$distance[$y]." ".$io1[$y]." ".$io2[$y]." ".$io3[$y]." ".$io4[$y]." ".$io5[$y]." ".$io6[$y]." ".$io7[$y]." ".$sig_str[$y]." ".$sup_v[$y]." ".$speed_a[$y]." ".$geo_in_a[$y]." ".$geo_out_a[$y]." ".$stop_a[$y]." ".$move_a[$y]." ".$lowv_a[$y]."/>";
            //$linetowrite =  "\n"."<marker ".$vehicleserial[$y]." ".$vehiclename[$y]." ".$lat[$y]." ".$lng[$y]." ".$alt[$y]." ".$speed[$y]." ".$datetime[$y]." ".$fuel[$y]." ".$vehicletype[$y]." ".$fix[$y]."/>";
            $linetowrite =  "\n"."<marker ".$vehicleserial[$y]." ".$lat[$y]." ".$lng[$y]." ".$alt[$y]." ".$speed[$y]." ".$datetime[$y]."/>";
            fwrite($fh, $linetowrite);  
            //echo "<br>".$linetowrite." ".$y;         
         }
       
       }// FORMAT 1 CLOSED

    //}  // while closed
            
      $linetowrite ="\n</t1>"; 
      fwrite($fh, $linetowrite);
      fclose($fh);      
    //              
   }  // IF file CLOSED   //        
   
}     // FUNCTION CLOSED
                        