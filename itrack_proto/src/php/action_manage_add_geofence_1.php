<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
	
	$flag=0;
  $geo_name1 = $_POST['geo_name'];
  $geo_coord1 = $_POST['geo_coord'];

  date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
	$date=date("Y-m-d H:i:s");

	$query ="SELECT geofencing_name,coord from geofencing_info where operator_id='$account_id' and status=1";
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	echo"numrow=".$numrows;
	if($numrows==0)
	{
      $query="INSERT INTO geofencing_info(geofencing_name,coord,operator_id,create_date,status) VALUES('$geo_name1','$geo_coord1','$account_id','$date','1')";      
    	echo "query1=".$query."<br>";
      $result1=mysql_query($query,$DbConnection);       	  
      if($result1)
      {
        $flag=1;
      }
  } 
	
  if($flag==1)
	{
		echo"<center><br><br><FONT color=\"blue\" size=\"2\"><strong>Geofence Detail Added Successfully<br>
            <a href='javascript:manage_add_geofence()'> Back </a></strong></font></center>";
		
	}					
	else if($flag==0)
	{
		echo"<center><br><br><FONT color=\"blue\" size=\"2\"><strong>Specify Another Geofence name, given name already exists!
		<br>
            <a href='javascript:manage_add_geofence()'> Back </a></strong></font></center>";
	} 
?>
        