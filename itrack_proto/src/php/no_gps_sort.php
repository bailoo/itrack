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
          $line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE
          //echo "line:".$line;   
          //echo '<textarea>'.$line.'</textarea>';                                      
          //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
          
          if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && (strlen($line)>10) )
          {
              //echo "<br>In Format2";
			        $status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
              //echo "Status=".$status.'<BR>';
              //echo "test1".'<BR>';
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
              
              $datetime[$i] = $datetime_tmp[0].'"';                // Store Name with Value
              $line_array[$i] = $line;    
          }
          $i++;                      
      }   // WHILE CLOSED

       ////////////// SORTING ALGO OK ///////////////////////////

	    for($x = 1; $x < $i; $x++) 
    	{
  	      $date_x=null;
          $date_x = explode("=",$datetime[$x]);
          $value = preg_replace('/"/', '', $date_x[1]);

          $tmp_datetime = $datetime[$x];
          $tmp_line_array = $line_array[$x];      				
  				
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
          				$line_array[$z + 1] = $line_array[$z];              				
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
          $line_array[$z + 1] = $tmp_line_array;                                   
    	}            
         ///////////// SORTING ALGO 2 CLOSED /////////////////////
                  
     //echo "<br>SORT<br>";
     $fh = fopen($xml_original_tmp, 'a') or die("can't open file 5"); //append
     $linetowrite ="<t1>\n"; 
     fwrite($fh, $linetowrite); 
     //echo "<br>SORTED FILE".$i;
     
     for($y=0;$y<$i;$y++)
     {
        //echo "<br>A=".$datetime[$y];                         
        //$linetowrite =  "\n"."<marker ".$vehicleserial[$y]." ".$vehiclename[$y]." ".$lat[$y]." ".$lng[$y]." ".$alt[$y]." ".$speed[$y]." ".$datetime[$y]." ".$fuel[$y]." ".$vehicletype[$y]." ".$fix[$y]."/>";        
        $linetowrite =  $line_array[$y];
        if(strlen($linetowrite)>10)
        {
          fwrite($fh, $linetowrite); 
        } 
       //  echo "<br>".$linetowrite." ".$y;         
     }
            
     $linetowrite ="</t1>"; 
     fwrite($fh, $linetowrite);
     fclose($fh);      
    //              
   }  // IF file CLOSED   //        
   
}     // FUNCTION CLOSED                