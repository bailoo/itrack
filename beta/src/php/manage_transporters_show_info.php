<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');   
	$city1 = $_POST['city'];

	$DEBUG=0;
	 	
  $query = "SELECT * FROM transporters WHERE city='$city1' and status=1";  
  //echo "<br>".$query;
  $result = mysql_query($query, $DbConnection);   
  $i=0;
  while($row = mysql_fetch_object($result))
  {
     $transporter_id[$i] = $row->transporter_id;
     $name[$i] = $row->name;
     $state[$i] = $row->state;     
     $address1[$i] = $row->address1;
     $address2[$i] = $row->address2;
     //echo "<br>name=".$name[$i]." ,state=".$state[$i];
     $i++;
  }                    
  
  //   $name[$j]
  //   $state[$j]
  //   $city[$j]
  //   $address1[$j] 
  //  $address2[$j]
     
  echo'<div style="height:350px;overflow:auto;">';
  for($j=0;$j<$i;$j++)
  {
    $address = $address1[$j].','.$address2[$j];
    //color: #FF9900;
    echo'      
    <br><br><br><table width="70%">
    <tr rules="all" style="background-color:#E2E2E2"><td colspan="2"><strong>'.$name[$j].'</strong></td></tr>
    <tr><td><div>'.$address.', '.$city1.' ,'.$state[$j].'</div></td><td align="right"><a href="javascript:book_vehicle('.$transporter_id[$j].');"><font color="#333366">Book Now</font></a></td></tr>
    <tr><td height="10"></td></tr>
    <tr><td><a href="#"><font color="#FF9900">more</font></a></td></tr>
    <tr><td height="40"></td></tr>
    </table>
    <br>
    ';
  }
  echo'</div>';
?>
