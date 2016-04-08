<?php
  //######## READ CSV #########//
	require_once "simplexlsx.class.php";
	
  // Numbers of days between January 1, 1900 and 1970 (including 19 leap years)
  define("MIN_DATES_DIFF", 25569);
    
  // Numbers of second in a day:
  define("SEC_IN_DAY", 86400);   	
	
	//$xlsx = new SimpleXLSX( $_FILES['file']['tmp_name'] );
	//$xlsx = new SimpleXLSX( "Book1.xlsx" );
	$xlsx = new SimpleXLSX($path);
	
	//echo '<h1>Parsing Result</h1><br>'.$path;
	//echo '<table border="1" cellpadding="3" style="border-collapse: collapse">';
	
	list($cols,) = $xlsx->dimension();
	/*
	foreach( $xlsx->rows() as $k => $r) {
//		if ($k == 0) continue; // skip first row
		echo '<tr>';
		for( $i = 0; $i < $cols; $i++)
			echo '<td>'.( (isset($r[$i])) ? $r[$i] : '&nbsp;' ).'</td>';
		echo '</tr>';
	}
	echo '</table>';
//} */

  list($cols,) = $xlsx->dimension();

  /*
  foreach( $xlsx->rows() as $k => $r) 
  {
    //echo "<br>Row:".$k."<br>";
    
    for( $i = 0; $i < $cols; $i++)
      echo "<br>".$r[$i];
  }
*/  
////////	
  
  
  $row = 1;
	$i=0;
	//$title = "";
	//if (($handle = fopen($path, "r")) !== FALSE) 
	//{
	  //while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
	  foreach( $xlsx->rows() as $k => $data) 
	  {
		  //echo "row=".$i;
      //$num = count($data);		  
		  $record ="";
		  $date1 ="";
		  $time1 ="";
		  $date2 ="";
		  $time2 ="";
		  $doctype1 ="";
		  $plant1 ="";
		  $route1 ="";
		  $vname1 = "";
		  $vendor_name1="";
		  $customer_no1 = "";
		  //$qty1 ="";
		  //$unit1 ="";          
			  
		  //echo "<p> $num fields in line $row: <br /></p>\n";
		  $row++;
		  if($i==0)
		  {
  			//for ($c=0; $c < $num; $c++) 
  			for( $c = 0; $c < $cols; $c++)
  			{
  				//echo "In Zero=".$data[$c] . "<br />\n";
  				$record = $data[$c];
  				
          if($c==0)
  				  $stringData = $stringData.$record;
  				else if($c<=9)
  				  $stringData = $stringData.",".$record;
  			}            
		  }
		  else
		  {
  			//for ($c=0; $c < $num; $c++)
        for( $c = 0; $c < $cols; $c++) 
  			{            
  				//echo "In Else=".$data[$c] . "<br />\n";
  				$record = $data[$c];
  				
  				//echo "<br>record=".$record;               
  				if($c==0)
  				{  				  
            //$date_decimal = 41191;
            $date_decimal = $record;
            $timestamp = ($date_decimal - 25569) * 86400;            
            $datef = excel2_date($timestamp);
  				  
  				  $date1 = $datef;
  				  
  				  //echo "<br>c0=".$date1;
  				}
  				else if($c==1)
  				{
            //$time_float = 0.5833333333;
            $timef = excel2_time($record);                        				  
            $time1 = $timef;
            
  				  //echo "<br>c1=".$time1;
  				}
  				else if($c==2)
  				{
            $date_decimal = $record;
            $timestamp = ($date_decimal - 25569) * 86400;            
            $datef = excel2_date($timestamp);
  				  
  				  $date2 = $datef;
  				  
  				  //echo "<br>c0=".$date2;
  				}
  				else if($c==3)
  				{
            //$time_float = 0.5833333333;
            $timef = excel2_time($record);                        				  
            $time2 = $timef;
            
  				  //echo "<br>c3=".$time2;
  				}                                
  				else if($c==4)
  				{
  				  $doctype1 = $record;
  				  //echo "<br>c4=".$record;
  				}
  				else if($c==5)
  				{
  				  $plant1 = $record;
  				  //echo "<br>c5=".$record;
  				}
  				else if($c==6)
  				{
  				  $route1 = $record;
  				  //echo "<br>c6=".$record;
  				}
  				else if($c==7)
  				{
  				  $vname_tmp1 = $record;
  				  //echo "<br>c7=".$record;
  				  //echo "<br>vname=".$vname1;
  				}
  				else if($c==8)
  				{
  				  $vendor_name1 = $record;
  				  //echo "<br>c8=".$record;
  				}                     
  				else if($c==9)
  				{
  				  $customer_no1 = intval($record);
  				  //echo "<br>c9=".$record;
  				  //echo "<br>customer_no1=".$customer_no1;
  				}  							
  				/*else if($c==10)
  				{
  				  $qty1 = $record;
  				}
  				else if($c==11)
  				{
  				  $unit1 = $record;
  				}*/                                
  			}
  			//echo "<br>";
		  }
		  $i++;

		  if($customer_no1!="")
		  {  
  			///////////********MAKE INDIVIDUAL START DATE AND END DATE*******//////////              
  			//FIRST DATE        
        $pos1 = strrpos($date1, "-");
        $pos2 = strrpos($date1, "/");
        
        if($pos1)
        {
         $datetmp_a1 = explode('-',$date1);          
        }
        else if($pos2)
        {
         $datetmp_a1 = explode('/',$date1);         
        }                  
        $pos1 = false; 
        $pos2 = false;
          
        $year1 = $datetmp_a1[2];
  			$tmp_month1 = intval($datetmp_a1[1]);
  			$tmp_day1 = intval($datetmp_a1[0]);       			
        $time_a1 = explode(':',$time1);
        $tmp_hr1 = intval($time_a1[0]);
        $tmp_min1 = intval($time_a1[1]);
        $tmp_sec1 = intval($time_a1[2]);
        
        if($tmp_month1 < 10)
        {
          $tmp_month1 = "0".$tmp_month1;
        }
        if($tmp_day1 < 10)
        {
          $tmp_day1 = "0".$tmp_day1;
        }
        if($tmp_hr1 < 10)
        {
          $tmp_hr1 = "0".$tmp_hr1;
        }
        if($tmp_min1 < 10)
        {
          $tmp_min1 = "0".$tmp_min1;
        }
        if($tmp_sec1 < 10)
        {
          $tmp_sec1 = "0".$tmp_sec1;
        }
        
        $date_str_display1 = $tmp_day1."-".$tmp_month1."-".$year1;
        $date_str1 = $year1."-".$tmp_month1."-".$tmp_day1;        
        $time_str1 = $tmp_hr1.":".$tmp_min1.":".$tmp_sec1;
                                                 			                
        $date1_tmp = $date_str1." ".$time_str1;
  			/////////////////////////////////////////////////////////////
  			
        //SECOND DATE        
        $pos1 = strrpos($date2, "-");
        $pos2 = strrpos($date2, "/");
        
        if($pos1)
        {
         $datetmp_a2 = explode('-',$date2);        
        }
        else if($pos2)
        {
         $datetmp_a2 = explode('/',$date2);      
        }                  
        $pos1 = false; 
        $pos2 = false;

        
		    $year2 = $datetmp_a2[2];
  			$tmp_month2 = intval($datetmp_a2[1]);
  			$tmp_day2 = intval($datetmp_a2[0]);       			
        $time_a2 = explode(':',$time2);
        $tmp_hr2 = intval($time_a2[0]);
        $tmp_min2 = intval($time_a2[1]);
        $tmp_sec2 = intval($time_a2[2]);
        
        if($tmp_month2 < 10)
        {
          $tmp_month2 = "0".$tmp_month2;
        }
        if($tmp_day2 < 10)
        {
          $tmp_day2 = "0".$tmp_day2;
        }
        if($tmp_hr2 < 10)
        {
          $tmp_hr2 = "0".$tmp_hr2;
        }
        if($tmp_min2 < 10)
        {
          $tmp_min2 = "0".$tmp_min2;
        }
        if($tmp_sec2 < 10)
        {
          $tmp_sec2 = "0".$tmp_sec2;
        }
        
        $date_str_display2 = $tmp_day2."-".$tmp_month2."-".$year2;
        $date_str2 = $year2."-".$tmp_month2."-".$tmp_day2;
        $time_str2 = $tmp_hr2.":".$tmp_min2.":".$tmp_sec2;
                                                 			                
        $date2_tmp = $date_str2." ".$time_str2;
  			///////////////////////////////////////////////////////////////////// 			
        
  			$date1_csv[] = $date_str_display1;
  			$time1_csv[] = $time_str1;
  
  			$date2_csv[] = $date_str_display2;
  			$time2_csv[] = $time_str2;
              
        $input_date1[] = $date1_tmp;
  			$input_date2[] = $date2_tmp;
  			//echo "<br>input date1=".$date1_tmp." ,input date2=".$date2_tmp;              
  			///////////////********************************************////////////////          	  
  			$doctype[] = $doctype1;
  			$plant[] = $plant1;
  			$route[] = $route1;
  			$vname[] = $vname_tmp1;
  			$vendor_name[] = $vendor_name1;
  			$customer_no[] = $customer_no1;
  			//echo "<br>customer_no1=".$customer_no1."<br>";              
  			//$qty[] = $qty1;
  			//$unit[] = $unit1;      	  
			}                    
	  }
	  //fclose($handle);
	//}     
  
  
  
function excel2_date($timestamp)
{
  return date('Y-m-d',excel2timestamp(41191));
}

function excel2_time($dec)
{
  $spd = 86400;            
  //$dec = 0.5833333333;
  //$dec = $time2;
  $secs = $spd * $dec;
  $time_f = strtotime('midnight') + $secs;
  return date ('H:i:s',$time_f);

}

function excel2timestamp($excelDate)
{
   if ($excelDate <= MIN_DATES_DIFF)
      return 0;
 
   return  ($excelDate - MIN_DATES_DIFF) * SEC_IN_DAY;
}          
?>
