<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

$landmark_name1 = $_GET['landmark_name'];
$landmark_point1 = $_GET['landmark_point'];
//$feature_id = $_GET['map_feature_id'];
$landmark_type1 = $_GET['landmark_type'];

$zoom_level = 5;

//date_default_timezone_set('Asia/Calcutta');
$date=date("Y-m-d H:i:s");		
			

$query = "SELECT landmark_coord FROM landmark WHERE landmark_coord='$landmark_point1' AND account_id='$account_id' AND status=1";
//echo "q1=".$query;
$result = mysql_query($query,$DbConnection);
$numrows = mysql_num_rows($result);

if($numrows >0)
{
  $message = "Sorry! This Landmark is Already Added";
}
else
{
  $query ="select Max(sno)+1 as serial_no from landmark";  ///// for auto increament of landmark_id ///////////   
  $result=mysql_query($query,$DbConnection);
  $row=mysql_fetch_object($result);
  $max_no= $row->serial_no;
  if($max_no==""){$max_no=1;}
  
  $query_string1="INSERT INTO landmark(account_id,landmark_id,landmark_name,landmark_coord,zoom_level,status,create_id,create_date) VALUES".
                  "('$account_id','$max_no','$landmark_name1','$landmark_point1','$zoom_level','1','$account_id','$date');";
  
  //echo "q2=".$query_string1;
  $result = mysql_query($query_string1,$DbConnection);    
  $message = "Landmark Added Successfully";
}

echo $message;
//echo "Q=".$Query1;

?>
