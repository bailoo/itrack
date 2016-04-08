<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');   
	$state1 = $_POST['state'];

	$DEBUG=0;
	echo "showcity##";
   	
  $query = "SELECT DISTINCT city FROM transporters WHERE state='$state1' and status=1";  
  $result = mysql_query($query, $DbConnection);                       
  //echo $query;
  echo'
  &nbsp;Select City : 
  <select name="city" id="city">';
    
    while($row = mysql_fetch_object($result))
    {
      $city = $row->city;  
      echo '<option value="'.$city.'">'.$city.'</option>';  
    }                                                   
  
   echo'</select>
   
   &nbsp;<input type="button" value="Go>>" onclick="javascript:show_transporter_info();">
   ';
   
?>
