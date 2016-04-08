<?php   
  include_once("get_location.php");
  include_once("calculate_distance.php");
  
  $type = $_POST['type'];
  $time1 = $_POST['time1'];
  $time2 = $_POST['time2'];    
  $lat1 = $_POST['lat1'];
  $lng1 = $_POST['lng1'];
  $lat2 = $_POST['lat2'];
  $lng2 = $_POST['lng2']; 
  
  $diff = $_POST['diff']; 
  $speed1 = $_POST['speed1'];
  $speed2 = $_POST['speed2'];
  
  echo 'datagap##'.$lat1.':'.$lng1.':'.$lat2.':'.$lng2.'##';
  
	$DbConnection=null;
	$alt=null;
  
  $data_valid1 = 0;
  $data_valid2 = 0;
    
  
  if($lat1!="-" && $lng1!="-" && $lat2!="-" && $lng2!="-")
  {
    get_location($lat1,$lng1,$alt,&$location1,$DbConnection);
    get_location($lat2,$lng2,$alt,&$location2,$DbConnection);
    
    calculate_distance($lat1, $lat2, $lng1, $lng2, &$distance);
    //echo'<a href="#" onclick="javascript:load_map('.$lat1.','.$lng1.','.$lat2.','.$lng2.');">get map</a>'; 
  }
  else
  {
    if( ($lat1=="-" || $lng1=="-") || ($lat1=="" || $lng1=="") ) 
      $location1 = "No GPS found";
    if( ($lat2=="-" || $lng2=="-") || ($lat2=="" || $lng2=="") )  
      $location2 = "No GPS found";
  }
      
  echo'
  
  <div align="right" Floating Menu <a href="#" onclick="document.getElementById(\'floating_div\').style.display=\'none\'">Close</a></div>    
	<center>
	
	<table width = 100% border=1 cellpadding=2 rules=all bordercolor=black>
    <tr valign="top">
      <td align="center"><span class="report_heading">'.$type.'</span>     
          
          <table border=1 cellpadding=2 style="border:thin;" rules=all bordercolor=lightblue>          
            <tr>			   
        			<td class="textname" align="left" width="4%">TimeFrom</td> <td class="textname">&nbsp;:&nbsp;</td> <td class="textvalue" align="left">'.$time1.'</td>
        		</tr>
        		
        		<tr>			   
        			<td class="textname" align="left" width="4%">TimeTo</td> <td class="textname">&nbsp;:&nbsp;</td> <td class="textvalue" align="left">'.$time2.'</td>
        		</tr>
        		
        		<tr>			   
        			<td class="textname" align="left" width="4%">LocationFrom</td> <td class="textname">&nbsp;:&nbsp;</td> <td class="textvalue" align="left">'.$location1.'</td>
        		</tr>
        		
        		<tr>			   
        			<td class="textname" align="left" width="4%">LocationTo</td> <td class="textname">&nbsp;:&nbsp;</td> <td class="textvalue" align="left">'.$location2.'</td>
        		</tr>
        		
        		<tr>			   
        			<td class="textname" align="left" width="4%">Difference(H:m:s)</td> <td class="textname">&nbsp;:&nbsp;</td> <td class="textvalue" align="left">'.$diff.'</td>
        		</tr>
        		
        		<tr>			   
        			<td class="textname" align="left" width="4%">Speed1</td> <td class="textname">&nbsp;:&nbsp;</td> <td class="textvalue" align="left">'.round($speed1,2).'</td>
        		</tr>
        		
        		<tr>			   
        			<td class="textname" align="left" width="4%">Speed2</td> <td class="textname">&nbsp;:&nbsp;</td> <td class="textvalue" align="left">'.round($speed2,2).'</td>
        		</tr>        		
        		
            <tr>			   
        			<td class="textname" align="left" width="4%">Distance</td> <td class="textname">&nbsp;:&nbsp;</td> <td class="textvalue" align="left">'.round($distance,2).'</td>
        		</tr>
      		
      		</table>
    </td>
    
	  <td align="center"></a>
          <span class="report_heading">POSITION ON MAP</span>		
          <!--<div id="map_canvas" style="width: 40%; height: 45%">MAP</div>-->
         <div id="map_canvas" style="height:210px;width:400px;"></div>

    </td>
  </tr>
</table>  
  ';	 
?>