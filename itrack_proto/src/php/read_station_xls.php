<?php
//######## READ XLS #########// 
require_once 'simplexls.class.php';

//$data = new Spreadsheet_Excel_Reader("Book1.xls"); 
$data = new Spreadsheet_Excel_Reader($path);
//echo "data=".$data;
//$arr = array();

$sheet = 0;
/*for($row=1;$row<=$data->rowcount($sheet_index=0);$row++)
{ 
      //echo "<br>Row:".$row."<br>";
      for($col=1;$col<=$data->colcount($sheet_index=0);$col++) 
      {
            $arr[$row][$col] = $data->val($row,$col,$sheet);
            echo "<br>".$arr[$row][$col];
      }
} */               
///  
  
	//$row = 1;
	 $i=0;
	//$title = "";
	//if (($handle = fopen($path, "r")) !== FALSE) 
	//{
	  //while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
	  //{	  
    for($row=1;$row<=$data->rowcount($sheet_index=0);$row++)
    {	  
		  //$num = count($data);
		  //echo "<br>row".$row;
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
		  //$row++;
		  if($i==0)
		  {
  			//for ($c=0; $c < $num; $c++) 
  			//{  			
          for($c=1;$c<=$data->colcount($sheet_index=0);$c++) 
          {  			
    				//echo "col=".$c;
            //echo "In Zero=".$data[$c] . "<br />\n";
    				//$record = $data[$c];
            //$arr[$row][$col] = $data->val($row,$col,$sheet);
            $record = $data->val($row,$c,$sheet);
    				
    				if($c==1)
    				  $stringData = $stringData.$record;
    				else if($c<=10)
    				  $stringData = $stringData.",".$record;
  			  }            
		  }
		  else
		  {
  			//for ($c=0; $c < $num; $c++) 
  			//{ 
        for($c=1;$c<=$data->colcount($sheet_index=0);$c++) 
        {  			                     				
  				//$record = $data[$c];
  				$record = $data->val($row,$c,$sheet);
  				//echo "In Else=".$record . "<br />\n";
  				//echo "<br>record=".$record;               
  				if($c==1)
  				{
  				  $date1 = $record;
  				  //echo "<br>c1=".$record;
  				}
  				else if($c==2)
  				{
  				  $time1 = $record;
  				  //echo "<br>c2=".$record;
  				}
  				else if($c==3)
  				{
  				  $date2 = $record;
  				  //echo "<br>c3=".$record;
  				}
  				else if($c==4)
  				{
  				  $time2 = $record;
  				 //echo "<br>c4=".$record;
  				}                                
  				else if($c==5)
  				{
  				  $doctype1 = $record;
  				  //echo "<br>c5=".$record;
  				}
  				else if($c==6)
  				{
  				  $plant1 = $record;
  				  //echo "<br>c6=".$record;
  				}
  				else if($c==7)
  				{
  				  $route1 = $record;
  				  //echo "<br>c7=".$record;
  				}
  				else if($c==8)
  				{
  				  $vname_tmp1 = $record;
  				  //echo "<br>c8=".$record;
  				  //echo "<br>vname=".$vname1;
  				}
  				else if($c==9)
  				{
  				  $vendor_name1 = $record;
  				  //echo "<br>c9=".$record;
  				}                     
  				else if($c==10)
  				{
  				  $customer_no1 = intval($record);
  				  //echo "<br>c10=".$record;
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
		  }
		  $i++;

		  //echo "<br>CUSTN=".$customer_no1;
		  
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
        //echo  "<br>doctype=".$doctype1." ,plant1=".$plant1." ,route1=".$route1." ,vname_tmp1=".$vname_tmp1." ,customer_no1=".$customer_no1;   	  
			}                    
	  }
	 // fclose($handle);
	//}               
?>
