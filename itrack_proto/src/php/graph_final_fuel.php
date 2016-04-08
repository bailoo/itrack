<?php
include ("jpgraph/src/jpgraph.php");
include ("jpgraph/src/jpgraph_line.php");

include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once("get_all_dates_between.php");
include_once("get_io.php");
include_once("util.fuel_calibration.php");
include_once("sort_xml.php");

$width=$_GET['width'];
$height=$_GET['height'];
$day=$_GET['day'];
$month=$_GET['month'];
$year=$_GET['year'];
$vserial=$_GET['vserial'];
$vsize = sizeof($vserial);

$query = "SELECT vehicle_name FROM vehicle WHERE ".
" vehicle_id=(SELECT vehicle_id FROM vehicle_assignment ".
"WHERE device_imei_no='$vserial' AND status=1) AND status=1";
//echo $query;
$result = mysql_query($query, $DbConnection);
$row = mysql_fetch_object($result);
$vname = $row->vehicle_name;
        
$xmin = $_GET['xmin'];
$xmax = $_GET['xmax'];

$ymin = $_GET['ymin'];
$ymax = $_GET['ymax'];
	
if($xmin=="" && $xmax=="")
{
	$xmin = 0;
	$xmax = 24;
}

if($ymin=="" && $ymax=="")
{
	$ymin = 0;
	$ymax = 150;
}
//echo "<br>day=".$day." month=".$month." year=".$year." xmin=".$xmin." xmax=".$xmax." ymin=" .$ymin. " ymax=" .$ymax;
///////////// SPEED GRAPH LOGIC USING XML /////////////////////////////// 
$maxPoints = 1000;
$file_exist = 0;
//echo "<br>day=".$day." month=".$month." year=".$year." xmin=".$xmin." xmax=".$xmax." ymin=" .$ymin. " ymax=" .$ymax." height=".$height." width=".$width;

get_fuel_detail_xml($vserial, $vname, $year, $month, $day, $xmin, $xmax, $ymin, $ymax, $height, $width);
  
function get_fuel_detail_xml($vserial, $vname, $year, $month, $day, $xmin, $xmax, $ymin, $ymax, $height, $width)
{
  //if($day<=9)
    //$day = "0".$day;

  $date = $year."-".$month."-".$day;

	$fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;
	$linetowrite="";
	$firstdata_flag =0;

	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
    
  //if($date == $current_date)
	//{	
  $xml_current = "../../../xml_vts/xml_data/".$date."/".$vserial.".xml";	
  		
  if (file_exists($xml_current))      
  {  		
		//echo "in else";
		$xml_file = $xml_current;
		$CurrentFile = 1;
	}		
	else
	{
		$xml_file = "../../../sorted_xml_data/".$date."/".$vserial.".xml";
		$CurrentFile = 0;
	}		
	//echo "<br>xml in Speed graph =".$xml_file;	
  	
  if (file_exists($xml_file)) 
	{				  
    //////// GET VOLTAGE TANK, CAPACITY //////     
    global $DbConnection;
    $io = get_io($vserial,'fuel');
      
    //$current_datetime1 = date("Y_m_d_H_i_s");
    $t=time();
    //$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vserial."_".$current_datetime1.".xml";
    $xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vserial."_".$t.".xml";
								      
    if($CurrentFile == 0)
		{
			//echo "<br>ONE<br>";
      copy($xml_file,$xml_original_tmp);
		}
		else
		{
			//echo "<br>TWO<br>";
      //$xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$vserial."_".$current_datetime1."_unsorted.xml";
			$xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$vserial."_".$t."_unsorted.xml";
      //echo  "<br>".$xml_file." <br>".$xml_unsorted."<br><br>";
			        
      copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
      SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
			unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
		}        
    
    $total_lines = count(file($xml_original_tmp));
    //echo "<br>Total lines orig=".$total_lines;
    
    $xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
    $logcnt=0;
    $DataComplete=false;
                
    $vehicleserial_tmp=null;
    $format =2;
    $i = 0;
    $tmp = 20.000000;
                
    
    if (file_exists($xml_original_tmp)) 
    {    
      while(!feof($xml))          // WHILE LINE != NULL
  		{
  			$DataValid = 0;
        
  			$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE	
        //echo "line<br />";		
  			
  			if(strlen($line)>20)
  			{
  			  $linetmp =  $line;
        }
  
  			if(strpos($line,'fix="1"'))     // RETURN FALSE IF NOT FOUND
  			{
  				$format = 1;
          $fix_tmp = 1;
  			}
              
  			else if(strpos($line,'fix="0"'))
  			{
  			  $format = 1;
  				$fix_tmp = 0;
  			}
  			
        if( (preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
        { 
          $lat_value = explode('=',$lat_match[0]);
          $lng_value = explode('=',$lng_match[0]);
          //echo " lat_value=".$lat_value[1];         
          if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
          {
            $DataValid = 1;
          }
        }
        //if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
  			{
  				//preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
  				$status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
  				$datetime_tmp1 = explode("=",$datetime_tmp[0]);
          $datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
  				//echo "<br>str3tmp[0]=".$str3tmp[0];
  				$xml_date = $datetime;
  			}				
        //echo "Final0=".$xml_date." datavalid=".$DataValid;
        
        if($xml_date!=null)
  			{				  
          //echo "<br>".$xml_date.",".$startdate.",".$enddate.",".$DataValid;
          //$lat = $lat_value[1] ;
  				//$lng = $lng_value[1];
  				
  				if($DataValid==1)
  				{							           	
            //echo "<br>One";             
            $status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
            if($status==0)
            {
              continue;
            }                
              
            $status = preg_match('/'.$io.'="[^" ]+/', $line, $fuelcount_tmp);  
                        
            //echo "<br>status=".$status;
            if($status==0)
            {
              continue;
            }
            
            $fuel_tmp1 = explode("=",$fuelcount_tmp[0]);  
            $fuel = preg_replace('/"/', '', $fuel_tmp1[1]);                     
    				
    				$fuel_level = get_calibrated_fuel_level($fuel);
                                  					
  					//$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
            //$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);          
            //$vehiclename_tmp1 = explode("=",$vehiclename_tmp[0]);
            //$vname = preg_replace('/"/', '', $vehiclename_tmp1[1]);							
  					
            //$datetime = $datetime_tmp[0];	
            $tmphr = substr($datetime,11,2);
            $tmpmin = substr($datetime,14,2);
            $datax[$i] = $tmphr+ ($tmpmin/60); //get time
            $datay[$i] = $fuel_level;   
             
            $i++;
            //echo "<br>vname=".$vname." ,datax[0]=".$datax[$i]." ,datay[0]=".$datay[$i];        
   
  				} // if DataValid 1
  			}   // if xml_date!null closed
      }   // while closed
    }  // if original_tmp exist closed
		            
   fclose($xml);            
	 unlink($xml_original_tmp);
	} // if (file_exists closed

///////// PLOT GRAPH  ////////////////   
  /*$datax[0]=10.2666666667;
  $datay[0]=20.000000;
  $datax[1]=11.2666666667;
  $datay[1]=30.000000;
  $datax[2]=12.2833333333;
  $datay[2]=80.000000;
  $datax[3]=13.2833333333;
  $datay[3]=80.000000;
  $datax[4]=14.2833333333;
  $datay[4]=70.000000;
  $datax[5]=15.4166666667;
  $datay[5]=80.000000;
  $datax[6]=16.4333333333;
  $datay[6]=70.000000;
  $datax[7]=17.45;
  $datay[7]=60.000000;
  $datax[8]=17.4666666667;
  $datay[8]=60.852000;  */
  
  /*for($j=0;$j<$i;$j++)
    echo "<br>vname=".$vname." ,datax=".$datax[$j]." ,datay=".$datay[$j];
    
  echo "<br>day=".$day." month=".$month." year=".$year." xmin=".$xmin." xmax=".$xmax." ymin=" .$ymin. " ymax=" .$ymax." height=".$height." width=".$width;
  */
  
  $w=$width-270;
  $h=$height-120;
  
  // A nice graph with anti-aliasing
  $graph = new Graph(680,410);
  $graph->SetScale("textlin");
  $graph->SetFrame(false);
  $graph->img->SetMargin(40,30,30,10); 	
  //$graph->SetBackgroundImage("car.png",BGIMG_COPY);
  $graph->SetBackgroundImage('../../images/car.png');
  //$graph->SetAlphaBlending();
  //$graph->xaxis->SetLabelFormatCallback('TimeCallback');
  $graph->tabtitle->Set("$vname: $day-$month-$year");
  $graph->yaxis->scale->SetGrace(20);
  $graph->img->SetAntiAliasing("white");
  $graph->SetScale("textlin");
  //$graph->SetShadow();
  //$graph->title->Set("Background image");
  //$graph->xaxis->SetTickLabels($datax);
  //$xmin = 0;
  //$xmax = 24;
  //$ymin = 0;
  //$ymax = 150;
  $graph->SetScale('intlin',$ymin,$ymax,$xmin,$xmax);
  $graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
  //$graph->xaxis->SetTextLabelInterval(0); 
  //$graph->xaxis->SetLabelSide(SIDE_UP); 
  //$graph->yaxis->HideTicks(true,false);
  $graph->xaxis->title->Set("Time(hh:mm)");
  $graph->yaxis->title->Set("Fuel level(litres)");
  // Use built in font
  $graph->title->SetFont(FF_FONT1,FS_BOLD);
  // Slightly adjust the legend from it's default position in the
  // top right corner. 
  $graph->legend->Pos(0.05,0.5,"right","center");
  // Create the line
  $p = new LinePlot($datay,$datax);
  $p->mark->SetType(MARK_STAR);
  $p->mark->SetFillColor("red");
  $p->mark->SetWidth(4);
  $p->SetColor("red");
  $p->SetCenter();
  //$p->value->SetFormat("%0.2f");
  $graph->Add($p);
  // Output line
  $graph->Stroke();   
} // FUNCTION CLOSED

?>