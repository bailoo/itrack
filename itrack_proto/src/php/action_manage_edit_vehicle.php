<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
?>

<?php
  $DEBUG=1;	
	$flag=0;
	$result_response=1;
	
  $post_vehicle_name=$_POST['vehicle_name'];
  $post_vehicle_number=$_POST['vehicle_number'];
  $post_max_speed=$_POST['max_speed'];
  $post_vehicle_tag=$_POST['vehicle_tag'];
  $post_vehicle_type=$_POST['vehicle_type'];
  
	date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
	$date=date("Y-m-d H:i:s");
  
  if($DEBUG)
  {  
    echo "Vehicle Name = ".$post_vehicle_name." (Length: ".strlen($post_vehicle_name).") <br>";
    echo "Vehicle Number = ".$post_vehicle_number." (Length: ".strlen($post_vehicle_number).") <br>";
    echo "MAX Speed = ".$post_max_speed." (Length: ".strlen($post_max_speed).") <br>";
    echo "Vehicle Tag = ".$post_vehicle_tag." (Length: ".strlen($post_vehicle_tag).") <br>";
    echo "Vehicle Type = ".$post_vehicle_type." (Length: ".strlen($post_vehicle_type).") <br>";
  }
  
  if (strlen($post_vehicle_name)==0 || strlen($post_vehicle_number)==0 || strlen($post_max_speed)==0 || strlen($post_vehicle_tag)==0)
  {
    $ERROR = "Empty Input!";
    $flag = -1;
  }
  else
  {
    $query = "UPDATE vehicle SET vehicle_name='$post_vehicle_name',vehicle_type='$post_vehicle_type',vehicle_tag='$post_vehicle_tag',vehicle_number='$post_vehicle_number',max_speed='$post_max_speed',edit_id='$account_id',edit_date='$date'";
    $result = mysql_query($query,$DbConnection);
  }
  
  if($flag==1)
	{
		echo "<center><br><br><FONT color=\"green\"><strong>Vehicle Detail Added Successfully!</strong></font></center>";
	}
  else if($flag==-1)
  {
    echo "<center><br><br><FONT color=\"red\"><strong>".$ERROR."</strong></font></center>";
  }					
	else if($flag==0)
	{
		echo "<center><br><br><FONT color=\"red\"><strong>Unable to add Vehicle Detail Due to Some Server Problem!</strong></font></center>";
	}
	else
	{
    echo "<center><br><br><FONT color=\"red\"><strong>Sorry! Unable to process request.</strong></font></center>";
  }

?>
        