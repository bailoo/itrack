<?php

function SortFile($xml_unsorted, $xml_original_tmp)
{
  //echo "<br>unsorted=".$xml_unsorted." <br>orgi=".$xml_original_tmp;  
  $fexist =1;
  $fix_tmp = 1;
  $xml = fopen($xml_unsorted, "r") or $fexist = 0;
  
  $count = count(file($xml_unsorted));
  //echo "<BR>COUNT======== $count lines in $xml";
  //$xml2 = '"'.$xml.'"';
  if($fexist)
  {
      $i=0;
      $format = 2;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
      		$DataValid = 0;
          $line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE
          //echo "line:".$line;   
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

            
          if( (strlen($line)>15) )
          {
            $DataValid = 1;
          }
          
          //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
          
          if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($format==2) && ($DataValid == 1) )
          {
              //echo "<br>In Format2";
			        $status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
              //echo "Status=".$status.'<BR>';
              //echo "test1".'<BR>';
              if($status==0)
              {
                continue;
              }
              //echo "test2".'<BR>';
              /*$status = preg_match('/vehiclename="[^"]+/', $line, $vehiclename_tmp);
              if($status==0)
              {
                continue;
              } */
              
              $status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
              if($status==0)
              {
                continue;
              }
              //echo "test6".'<BR>';
              $status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
              if($status==0)
              {
                continue;
              } 
              
              /*$status = preg_match('/alt="[^" ]+/', $line, $alt_tmp);
              if($status==0)
              {
                continue;
              }*/               
              
              $status = preg_match('/speed="[^" ]+/', $line, $speed_tmp);
              if($status==0)
              {
                continue;
              }                           
              //echo "test4".'<BR>';
              $status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
              if($status==0)
              {
                continue;
              }
              //echo "test5".$datetime_tmp[0].'<BR>';      
              $status = preg_match('/fuel="[^" ]+/', $line, $fuel_tmp);
              if($status==0)
              {
                continue;
              } 
              
              //echo "test7".'<BR>';
              $status = preg_match('/vehicletype="[^" ]+/', $line, $vehicletype_tmp); 
              if($status==0)
              {
                continue;
              }                          
          }
          
          else if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($format==1) && ($DataValid == 1) )
          {
              //echo "<br>In Format1";
			        //echo "<br>IN GET ATTRIBUTES";             
              $status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
              //echo "Status=".$status.'<BR>';
              //echo "test1".'<BR>';
              if($status==0)
              {
                continue;
              }
              //echo "test2".'<BR>';
              /*$status = preg_match('/vehiclename="[^"]+/', $line, $vehiclename_tmp);
              if($status==0)
              {
                continue;
              } */
              //echo "test4".'<BR>';
              $status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
              if($status==0)
              {
                continue;
              }
              //echo "test5".$datetime_tmp[0].'<BR>';
              $status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
              if($status==0)
              {
                continue;
              }
              //echo "test6".'<BR>';
              $status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
              if($status==0)
              {
                continue;
              }
              
              //echo "test8".'<BR>';
              $status = preg_match('/cellname="[^"]+/', $line, $cellname_tmp);
              if($status==0)
              {
                continue;
              }
             /* 
              //echo "test8".'<BR>';
              $status = preg_match('/mobilestatus="[^" ]+/', $line, $mobilestatus_tmp);
              if($status==0)
              {
                continue;
              }
              
              //echo "test9".'<BR>';
              $status = preg_match('/cellid="[^" ]+/', $line, $cellid_tmp);
              if($status==0)
              {
                continue;
              }
              
              //echo "test10".'<BR>';
              $status = preg_match('/mcc="[^" ]+/', $line, $mcc_tmp);
              if($status==0)
              {
                continue;
              }
              
              //echo "test11".'<BR>';
              $status = preg_match('/mnc="[^" ]+/', $line, $mnc_tmp);
              if($status==0)
              {
                continue;
              }
              
              //echo "test12".'<BR>';
              $status = preg_match('/lac="[^" ]+/', $line, $lac_tmp);
              if($status==0)
              {
                continue;
              } 
              */    
              //echo "test6".'<BR>';
              $status = preg_match('/alt="[^" ]+/', $line, $alt_tmp);
              if($status==0)
              {
                continue;
              }              
              //echo "test7".'<BR>';
              $status = preg_match('/vehicletype="[^" ]+/', $line, $vehicletype_tmp); 
              if($status==0)
              {
                continue;
              }  
              //echo "test8".'<BR>';           
              $status = preg_match('/speed="[^" ]+/', $line, $speed_tmp);
              if($status==0)
              {
                continue;
              }
              //echo "test9".'<BR>';
              $status = preg_match('/io8="[^" ]+/', $line, $fuel_tmp);
              if($status==0)
              {
                continue;
              } 
              
              //////////////////////////////////////////////////////////////////              
              $status = preg_match('/fix="[^" ]+/', $line, $fix_tmp);
              if($status==0)
              {
                continue;
              }   
              
              $status = preg_match('/RFID="[^" ]+/', $line, $rfid_tmp);
              if($status==0)
              {
                //continue;
                $rfid_tmp='RFID="0"';
              }
              
                                                       
               
              /*$status = preg_match('/no_of_sat="[^" ]+/', $line, $no_of_sat_tmp);
              if($status==0)
              {
                continue;
              } 
              
              $status = preg_match('/cellname="[^" ]+/', $line, $cellname_tmp);
              if($status==0)
              {
                //continue;
                $cellname_tmp[0] ='cellname="-';
              }    
              
              $status = preg_match('/distance="[^" ]+/', $line, $distance_tmp);
              if($status==0)
              {
                continue;
              }    
              
              $status = preg_match('/io1="[^" ]+/', $line, $io1_tmp);
              if($status==0)
              {
                continue;
              }    
              
              $status = preg_match('/io2="[^" ]+/', $line, $io2_tmp);
              if($status==0)
              {
                continue;
              }    
              
              $status = preg_match('/io3="[^" ]+/', $line, $io3_tmp);
              if($status==0)
              {
                continue;
              }   
              
              $status = preg_match('/io4="[^" ]+/', $line, $io4_tmp);
              if($status==0)
              {
                continue;
              }             
              
              $status = preg_match('/io5="[^" ]+/', $line, $io5_tmp);
              if($status==0)
              {
                continue;
              }     
              
              $status = preg_match('/io6="[^" ]+/', $line, $io6_tmp);
              if($status==0)
              {
                continue;
              }     
              
              $status = preg_match('/io7="[^" ]+/', $line, $io7_tmp);
              if($status==0)
              {
                continue;
              }        
              
              $status = preg_match('/sig_str="[^" ]+/', $line, $sig_str_tmp);
              if($status==0)
              {
                continue;
              }   
              
              $status = preg_match('/sup_v="[^" ]+/', $line, $sup_v_tmp);
              if($status==0)
              {
                continue;
              }           
              
              $status = preg_match('/speed_a="[^" ]+/', $line, $speed_a_tmp);
              if($status==0)
              {
                continue;
              }   
              
              $status = preg_match('/geo_in_a="[^" ]+/', $line, $geo_in_a_tmp);
              if($status==0)
              {
                continue;
              } 
              
              $status = preg_match('/geo_out_a="[^" ]+/', $line, $geo_out_a_tmp);
              if($status==0)
              {
                continue;
              } 
              
              $status = preg_match('/stop_a="[^" ]+/', $line, $stop_a_tmp);
              if($status==0)
              {
                continue;
              }     
              
              $status = preg_match('/move_a="[^" ]+/', $line, $move_a_tmp);
              if($status==0)
              {
                continue;
              }     
              
              $status = preg_match('/lowv_a="[^" ]+/', $line, $lowv_a_tmp);
              if($status==0)
              {
                continue;
              } */  
          } // FORMAT 1 CLOSED                                                                                                                                                                                                                                                                        
               
              //echo "test3".'<BR>';           
                             
         if( ($format == 2) && ($DataValid == 1))
         {
            //echo "<br>In Format22";
			      $datetime[$i] = $datetime_tmp[0].'"';                // Store Name with Value
            $vehicleserial[$i] = $vehicleserial_tmp[0].'"';
            //$vehiclename[$i] = $vehiclename_tmp[0].'"';
            $lat[$i] = $lat_tmp[0].'"';
            $lng[$i] = $lng_tmp[0].'"';
            //$alt[$i] = $alt_tmp[0].'"';
            $alt[$i] = 'alt="-"';
            $speed[$i] = $speed_tmp[0].'"';
            $fuel[$i] = $fuel_tmp[0].'"'; 
            $vehicletype[$i] = $vehicletype_tmp[0].'"';         
			
            //echo "<br>rec1=".$datetime[$i].",".$vehicleserial[$i].",".$vehiclename[$i].",".$lat[$i].",".$lng[$i].",".$speed[$i].",".$fuel[$i].",".$vehicletype[$i];
                          
            $i++;                                     
         }   // IF FORMAT 2 CLOSED
         else if( ($format == 1) && ($DataValid == 1))
         {   
            //echo "<br>In Format11";
			      $datetime[$i] = $datetime_tmp[0].'"';                // Store Name with Value
            $vehicleserial[$i] = $vehicleserial_tmp[0].'"';
            //$vehiclename[$i] = $vehiclename_tmp[0].'"';
            
            $lat[$i] = $lat_tmp[0].'"';
            $lng[$i] = $lng_tmp[0].'"';
            $cellname[$i] = $cellname_tmp[0].'"';
            /*$mobilestatus[$i] = $mobilestatus_tmp[0].'"';
            $cellid[$i] = $cellid_tmp[0].'"';
            $mcc[$i] = $mcc_tmp[0].'"';
            $mnc[$i] = $mnc_tmp[0].'"';
            $lac[$i] = $lac_tmp[0].'"';
            */
            $alt[$i] = $alt_tmp[0].'"';
            $vehicletype[$i] = $vehicletype_tmp[0].'"';
            $speed[$i] = $speed_tmp[0].'"';
            $fuel[$i] = $fuel_tmp[0].'"'; 
            
            $fix[$i] = $fix_tmp[0].'"';    
            $rfid[$i] = $rfid_tmp[0].'"';    
                     
            /*$no_of_sat[$i] = $no_of_sat_tmp[0].'"'; 
            $cellname[$i] = $cellname_tmp[0].'"'; 
            $distance[$i] = $distance_tmp[0].'"'; 
            $io1[$i] = $io1_tmp[0].'"'; 
            $io2[$i] = $io2_tmp[0].'"'; 
            $io3[$i] = $io3_tmp[0].'"'; 
            $io4[$i] = $io4_tmp[0].'"'; 
            $io5[$i] = $io5_tmp[0].'"'; 
            $io6[$i] = $io6_tmp[0].'"'; 
            $io7[$i] = $io7_tmp[0].'"'; 
            $sig_str[$i] = $sig_str_tmp[0].'"'; 
            $sup_v[$i] = $sup_v_tmp[0].'"'; 
            $speed_a[$i] = $speed_a_tmp[0].'"'; 
            $geo_in_a[$i] = $geo_in_a_tmp[0].'"'; 
            $geo_out_a[$i] = $geo_out_a_tmp[0].'"'; 
            $stop_a[$i] = $stop_a_tmp[0].'"'; 
            $move_a[$i] = $move_a_tmp[0].'"'; 
            $lowv_a[$i] = $lowv_a_tmp[0].'"';   */
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
              $date_x = explode("=",$datetime[$x]);
              $value = preg_replace('/"/', '', $date_x[1]);
  
              $tmp_datetime = $datetime[$x];
              $tmp_vehicleserial = $vehicleserial[$x];
      				//$tmp_vehiclename = $vehiclename[$x];
      				$tmp_lat = $lat[$x];
      				$tmp_lng = $lng[$x];
      				$tmp_alt = $alt[$x];
      				$tmp_vehicletype = $vehicletype[$x];
      				$tmp_speed = $speed[$x];
      				$tmp_fuel = $fuel[$x];
      				     				
              ////////////////////////////////////////       				
              $z = $x - 1;
              $done = false;
              while($done == false)
      		    {
                   $date_x=null;
                   $date_x = explode("=",$datetime[$z]);
      			       $date_tmp1 = preg_replace('/"/', '', $date_x[1]);
      
                  if ($date_tmp1 >$value)
      			      {
                      $datetime[$z + 1] = $datetime[$z];
              				$vehicleserial[$z + 1] = $vehicleserial[$z];
              				//$vehiclename[$z + 1] = $vehiclename[$z];
              				$lat[$z + 1] = $lat[$z];
              				$lng[$z + 1] = $lng[$z];
              				$alt[$z + 1] = $alt[$z];
              				$vehicletype[$z + 1] = $vehicletype[$z];
              				$speed[$z + 1] = $speed[$z];
              				$fuel[$z + 1] = $fuel[$z];
              				           				
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
                
              $datetime[$z + 1] = $tmp_datetime;
              $vehicleserial[$z + 1] = $tmp_vehicleserial;
              //$vehiclename[$z + 1] = $tmp_vehiclename;
              $lat[$z + 1] = $tmp_lat;
              $lng[$z + 1] = $tmp_lng;
              $alt[$z + 1] = $tmp_alt;
              $vehicletype[$z + 1] = $tmp_vehicletype;
              $speed[$z + 1] = $tmp_speed;
              $fuel[$z + 1] = $tmp_fuel;                              
        	}            
         ///////////// SORTING ALGO 2 CLOSED /////////////////////
                           
         $fh = fopen($xml_original_tmp, 'a') or die("can't open file 5"); //append
         $linetowrite ="<t1>"; 
         fwrite($fh, $linetowrite); 
         //echo "<br>SORTED FILE".$i;
         
         for($y=0;$y<$i;$y++)
         {
            //echo "<br>A=".$datetime[$y];             
            //$linetowrite =  "\n"."<marker ".$vehicleserial[$y]." ".$vehiclename[$y]." ".$lat[$y]." ".$lng[$y]." ".$alt[$y]." ".$speed[$y]." ".$datetime[$y]." ".$fuel[$y]." ".$vehicletype[$y]."/>";
            $linetowrite =  "\n"."<marker ".$vehicleserial[$y]." ".$lat[$y]." ".$lng[$y]." ".$alt[$y]." ".$speed[$y]." ".$datetime[$y]." ".$fuel[$y]." ".$vehicletype[$y]."/>";
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
              $date_x = explode("=",$datetime[$x]);
              $value = preg_replace('/"/', '', $date_x[1]);
  
              $tmp_datetime = $datetime[$x];
              $tmp_vehicleserial = $vehicleserial[$x];
      				//$tmp_vehiclename = $vehiclename[$x];
      				              
      				$tmp_lat = $lat[$x];
      				$tmp_lng = $lng[$x]; 
      				$tmp_cellname = $cellname[$x];
      				/*
      				$tmp_mobilestatus = $mobilestatus[$x];
      				$tmp_cellid = $cellid[$x];
      				$tmp_mcc = $mcc[$x];
      				$tmp_mnc = $mnc[$x];
      				$tmp_lac = $lac[$x];
      				 */
      				$tmp_alt = $alt[$x];
      				$tmp_vehicletype = $vehicletype[$x];
      				$tmp_speed = $speed[$x];
      				$tmp_fuel = $fuel[$x];
      				     				
              ////////////
      				
              $tmp_fix = $fix[$x];  
              $tmp_rfid = $rfid[$x];   
         
              /*$tmp_no_of_sat = $no_of_sat[$x]; 
              $tmp_cellname = $cellname[$x]; 
              $tmp_distance = $distance[$x]; 
              $tmp_io1 = $io1[$x]; 
              $tmp_io2 = $io2[$x]; 
              $tmp_io3 = $io3[$x]; 
              $tmp_io4 = $io4[$x]; 
              $tmp_io5 = $io5[$x]; 
              $tmp_io6 = $io6[$x]; 
              $tmp_io7 = $io7[$x]; 
              $tmp_sig_str = $sig_str[$x]; 
              $tmp_sup_v = $sup_v[$x]; 
              $tmp_speed_a = $speed_a[$x]; 
              $tmp_geo_in_a = $geo_in_a[$x]; 
              $tmp_geo_out_a = $geo_out_a[$x]; 
              $tmp_stop_a = $stop_a[$x]; 
              $tmp_move_a = $move_a[$x]; 
              $tmp_lowv_a = $lowv_a[$x]; */      				     				
      				///////////      				
      				
              $z = $x - 1;
              $done = false;
              while($done == false)
      		    {
                   $date_x=null;
                   $date_x = explode("=",$datetime[$z]);
      			       $date_tmp1 = preg_replace('/"/', '', $date_x[1]);
      
                  if ($date_tmp1 >$value)
      			      {
                      $datetime[$z + 1] = $datetime[$z];
              				$vehicleserial[$z + 1] = $vehicleserial[$z];
              				//$vehiclename[$z + 1] = $vehiclename[$z];
              				$lat[$z + 1] = $lat[$z];
              				$lng[$z + 1] = $lng[$z]; 
              				$cellname[$z + 1] = $cellname[$z];
              				 /*
              				$mobilestatus[$z + 1] = $mobilestatus[$z];
                      $cellid[$z + 1] = $cellid[$z];
                      $mcc[$z + 1] = $mcc[$z];
                      $mnc[$z + 1] = $mnc[$z];
                      $lac[$z + 1] = $lac[$z];
                       */
              				$alt[$z + 1] = $alt[$z];
              				$vehicletype[$z + 1] = $vehicletype[$z];
              				$speed[$z + 1] = $speed[$z];
              				$fuel[$z + 1] = $fuel[$z];
              				           				
              				//////////////////
                      $fix[$z + 1] = $fix[$z];     
                      $rfid[$z + 1] = $rfid[$z];                         
                      /*$no_of_sat[$z + 1] = $no_of_sat[$z];
                      $cellname[$z + 1] = $cellname[$z];
                      $distance[$z + 1] = $distance[$z];
                      $io1[$z + 1] = $io1[$z];
                      $io2[$z + 1] = $io2[$z];
                      $io3[$z + 1] = $io3[$z];
                      $io4[$z + 1] = $io4[$z];
                      $io5[$z + 1] = $io5[$z];
                      $io6[$z + 1] = $io6[$z];
                      $io7[$z + 1] = $io7[$z];                                                
                      $sig_str[$z + 1] = $sig_str[$z];  
                      $sup_v[$z + 1] = $sup_v[$z];
                      $speed_a[$z + 1] = $speed_a[$z];  
                      $geo_in_a[$z + 1] = $geo_in_a[$z];  
                      $geo_out_a[$z + 1] = $geo_out_a[$z];  
                      $stop_a[$z + 1] = $stop_a[$z]; 
                      $move_a[$z + 1] = $move_a[$z];
                      $lowv_a[$z + 1] = $lowv_a[$z];  */                          				
              				
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
                
              $datetime[$z + 1] = $tmp_datetime;
              $vehicleserial[$z + 1] = $tmp_vehicleserial;
              //$vehiclename[$z + 1] = $tmp_vehiclename;
              $lat[$z + 1] = $tmp_lat;
              $lng[$z + 1] = $tmp_lng;
              $cellname[$z + 1] = $tmp_cellname;
              /*
              $mobilestatus[$z + 1] = $tmp_mobilestatus;
              $cellid[$z + 1] = $tmp_cellid;
              $mcc[$z + 1] = $tmp_mcc;
              $mnc[$z + 1] = $tmp_mnc;
              $lac[$z + 1] = $tmp_lac;
              */        
              $alt[$z + 1] = $tmp_alt;
              $vehicletype[$z + 1] = $tmp_vehicletype;
              $speed[$z + 1] = $tmp_speed;
              $fuel[$z + 1] = $tmp_fuel;
              
              $fix[$z + 1] = $tmp_fix;      
              $rfid[$z + 1] = $tmp_rfid;              
               /*$no_of_sat[$z + 1] = $tmp_no_of_sat;
              $cellname[$z + 1] = $tmp_cellname;
              $io1[$z + 1] = $tmp_io1;
              $io2[$z + 1] = $tmp_io2;
              $io3[$z + 1] = $tmp_io3;
              $io4[$z + 1] = $tmp_io4;
              $io5[$z + 1] = $tmp_io5;
              $io6[$z + 1] = $tmp_io6;
              $io7[$z + 1] = $tmp_io7;
              $sig_str[$z + 1] = $tmp_sig_str;
              $sup_v[$z + 1] = $tmp_sup_v;
              $speed_a[$z + 1] = $tmp_speed_a;
              $geo_in_a[$z + 1] = $tmp_geo_in_a;
              $geo_out_a[$z + 1] = $tmp_geo_out_a;     
              $stop_a[$z + 1] = $tmp_stop_a;
              $move_a[$z + 1] = $tmp_move_a;        
              $lowv_a[$z + 1] = $tmp_lowv_a;  */                      
        	}            
         ///////////// SORTING ALGO 2 CLOSED /////////////////////
                  
         $fh = fopen($xml_original_tmp, 'a') or die("can't open file 5"); //append
         $linetowrite ="<t1>"; 
         fwrite($fh, $linetowrite); 
         //echo "<br>SORTED FILE".$i;
         
         for($y=0;$y<$i;$y++)
         {
            //echo "<br>A=".$datetime[$y];
                                    
            //$linetowrite =  "\n"."<marker ".$vehicleserial[$y]." ".$vehiclename[$y]." ".$lat[$y]." ".$lng[$y]." ".$alt[$y]." ".$speed[$y]." ".$datetime[$y]." ".$fuel[$y]." ".$vehicletype[$y]." ".$fix[$y]."/>";
            //$linetowrite =  "\n"."<marker ".$vehicleserial[$y]." ".$lat[$y]." ".$lng[$y]." ".$mobilestatus[$y]." ".$cellid[$y]." ".$mcc[$y]." ".$mnc[$y]." ".$lac[$y]." ".$alt[$y]." ".$speed[$y]." ".$datetime[$y]." ".$fuel[$y]." ".$vehicletype[$y]." ".$fix[$y]." ".$rfid[$y]."/>";
            $linetowrite =  "\n"."<marker ".$vehicleserial[$y]." ".$lat[$y]." ".$lng[$y]." ".$cellname[$y]." ".$alt[$y]." ".$speed[$y]." ".$datetime[$y]." ".$fuel[$y]." ".$vehicletype[$y]." ".$fix[$y]." ".$rfid[$y]."/>";
            fwrite($fh, $linetowrite);  
           //  echo "<br>".$linetowrite." ".$y;         
         }
       
       }// FORMAT 1 CLOSED

    //}  // while closed
            
       $linetowrite ="\n</t1>"; 
       fwrite($fh, $linetowrite);
       fclose($fh);      
    //              
   }  // IF file CLOSED   //        
   
}     // FUNCTION CLOSED                