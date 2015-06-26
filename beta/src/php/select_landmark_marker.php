<?php
    //include_once('util_session_variable.php');
    include_once('util_php_mysql_connectivity.php');
    include_once('calculate_distance.php');

    $content = $_GET['content'];

    $string = explode(",", $content);
    $lat1 = $string[0];
    $lng1 = $string[1];
    
    //echo "lat=".$lat1."lng1=".$lng1."<br>";

	//echo "<br>place=".$placename[$i]."<br>";

  $query = "SELECT * FROM landmark WHERE account_id='$account_id' AND status=1";
  $result = mysql_query($query,$DbConnection);
  
  $placename1 ="";
  $i=0;
  while($row=mysql_fetch_object($result))
  {
  	$landmark=$row->landmark_name;
  	$coord = $row->landmark_coord;
  	
  	$coord1 = explode(',',$coord);
  	$lat2= $coord1[0];
  	$lng2= $coord1[1];

		//echo "lat1=".$lat1."long1=".$lon1."lat2=".$lat2."long2=".$lon2;
		calculate_distance($lat1,$lat2,$lng1,$lng2,$distance);
		$distance1=round($distance,2);
		
		if($i==0)
		{
		  $lowest_dist = $distance1;
		  $placename1 = $landmark;
    }
    else
    {
      if($distance1 < $lowest_dist)
      {
        $lowest_dist = $distance1;
        $placename1 = $landmark;
      } 
    }
	
    $i++;	
		//echo "dist=".$distance;
		//echo "lat1=".$lat1."long1=".$lon1."distance=".$distance."lat2=".$lat2."long2=".$lon2;;
	}
	$query1 = "SELECT distance_variable FROM account_detail WHERE account_id='$account_id'";
	$result1 = mysql_query($query1,$DbConnection); 	
	$Row1=mysql_fetch_object($result1);
	$distance_variable1=$Row1->distance_variable;
	$placename2="";
	if(($lowest_dist <=$distance_variable1) && ($placename1!=""))
	{
		$placename2 = $lowest_dist." km from ".$placename1;	 
		//echo "<br>place=".$placename2;
	}
    
	echo $placename2;	
	//$distance1=round($distance,4);*/
	
?>
