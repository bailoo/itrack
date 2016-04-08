<?php

//get_calibrated_fuel_level(25);
function get_calibrated_fuel_level($adc_count, $calibfile)
{  
  //$xml_file = "fuel_level_adc1.csv";  
  $xml_file = $calibfile;  
  $xml = @fopen($xml_file, "r") or $fexist = 0;  

  $i =0;  

  if (file_exists($xml_file)) 
  {  
    while(!feof($xml))          // WHILE LINE != NULL
  	{
  		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
  		//echo $line;		
  	  $str = explode(',',$line);
  	  $fuel_level[$i] = $str[0];
  	  $adc_value[$i] = $str[1];
  	  $i++;
  	}
  } // if file_exist closed	
	
	//echo "i=".$i;
  $j=0;
  while($j<$i)
	{
    //echo "j=".$j;
    if($adc_count == $adc_value[$j])
    {
      //echo "<br>if";
      $final_fuel_level = $fuel_level[$j];      
      break;
    }
    else
    {
       //echo "<br>elseA";
       if( ($adc_count >$adc_value[$j]) && ($adc_count <$adc_value[$j+1]) )
       //if( ($adc_count <$adc_value[$j]) && ($adc_count <$adc_value[$j+1]) )
       {          
          //echo "<br>elseB";
          $x = $adc_count;
          $x1 = $adc_value[$j]; 
          $x2 = $adc_value[$j+1]; 
          $y1 = $fuel_level[$j];
          $y2 = $fuel_level[$j+1]; 
          //echo "<br>x1=".$x1." ,x2=".$x2." ,y1=".$y1." ,y2=".$y2;
          
          $y = ($y2 - ( (($y2-$y1)/($x2-$x1)) * ($x2-$x) ));  // FORMULA TO GET FUEL LEVEL
          
          $final_fuel_level = $y;     
          break;               
       }
    }
    $j++;    
  }  // while closed  
  //echo "<br>final_fuel_level=".$final_fuel_level;		
fclose($xml);       

return  $final_fuel_level;    		
}  // function closed
?>