<?php

function get_location_cellname($cellname,&$cell_lat,&$cell_lng)
{ 
  $cell_parameters=explode(',',$cellname);
  $mnc=explode(' ',$cell_parameters[1]);
  $cell_key = "f169a48899a67db300b413fc0bec069f";
  
  //http://www.opencellid.org/cell/get?key=f169a48899a67db300b413fc0bec069f&mnc=1&mcc=2&lac=200&cellid=234
  // echo "<br>".$cellname;
  $Request = "http://www.opencellid.org/cell/get?key=$cell_key&mnc=$mnc[0]&mcc=$cell_parameters[0]&lac=$cell_parameters[3]&cellid=$cell_parameters[2]";
  //echo"<br>".$Request; 
	$page = file_get_contents($Request);
	$xml = new SimpleXMLElement($page);	
  //$new = htmlspecialchars($page);
  //echo "<br>".$new;	
	
  
  foreach($xml->attributes() as $a => $b)
  {
  //echo $a,'="',$b,"\"</br>";
      
      if($a=="stat"){
        if($b=="ok"){
          //echo $a,'="',$b,"\"</br>";
          foreach($xml->cell[0]->attributes() as $x => $y){
            //echo $a,'="',$b,"\"</br>";
                if($x=="lat"){
                  $cell_lat=round(floatval($y),5);
                  //echo $x,'="',$y,"\"</br>";
                  //echo "<br>celllat=".$cell_lat;
                }
                if($x=="lon"){
                  $cell_lng=round(floatval($y),5);
                  //echo $x,'="',$y,"\"</br>";
                }
            }	
        
        }// second if
      }  // first if
  } // first for each
  					
}

?>