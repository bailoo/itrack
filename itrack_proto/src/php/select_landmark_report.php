<?php

  function get_landmark($lt1,$lng1,&$landmark)
  {
    global $DbConnection;
    global $account_id;
    
    $query = "SELECT * FROM landmark WHERE account_id='$account_id' AND status=1";
    //echo $query;
    $result = mysql_query($query,$DbConnection);
    
    $placename1 ="";
    $i=0;
    while($row=mysql_fetch_object($result))
    {
    	$landmark_name=$row->landmark_name;
    	$coord = $row->landmark_coord;
    	
    	$coord1 = explode(',',$coord);
    	$lat2= $coord1[0];
    	$lng2= $coord1[1];
  
  		//echo "lat1=".$lat1."long1=".$lon1."lat2=".$lat2."long2=".$lon2;
  		calculate_distance($lt1,$lat2,$lng1,$lng2,$distance);
  		$distance1=round($distance,2);
  		
  		//echo "<br>dist=".$distance1." ,".$landmark;
      if($i==0)
  		{
  		  $lowest_dist = $distance1;
  		  $placename1 = $landmark_name;
      }
      else
      {
        if($distance1 < $lowest_dist)
        {
          $lowest_dist = $distance1;
          $placename1 = $landmark_name;
        } 
      }
  	
      $i++;	
  		//echo "<br>L1:".$distance1." #".$lowest_dist." #".$placename1;
      //echo "dist=".$distance;
  		//echo "lat1=".$lat1."long1=".$lon1."distance=".$distance."lat2=".$lat2."long2=".$lon2;;
  	}
    
  	//echo "<br>L1:".$distance1." #".$lowest_dist." #".$placename1;
    //if(($lowest_dist <=10) && ($placename1!=""))
        $query1 = "SELECT distance_variable FROM account_detail WHERE account_id='$account_id'";
        $result1 = mysql_query($query1,$DbConnection);
        $Row1=mysql_fetch_object($result1);
        $distance_variable1=$Row1->distance_variable;

    //if(($lowest_dist <=1) && ($placename1!=""))
	$landmark="";
	if($lowest_dist!="" && $distance_variable1!="")
	{
		if(($lowest_dist <=$distance_variable1) && ($placename1!=""))
  		{
	  		$landmark = $lowest_dist." km from ".$placename1;	 
  			//echo "<br>place=".$landmark;
  		}
	}        
	//echo $landmark;	
  }
	
?>
