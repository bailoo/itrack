<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

$landmark_name1 = $_GET['station_name'];
$customer_no1 = $_GET['customer_no'];
$radius_distance1 = $_GET['radius'];
$landmark_point1 = $_GET['landmark_point'];
//$feature_id = $_GET['map_feature_id'];
$landmark_type1 = $_GET['landmark_type'];

$zoom_level = 5;

//date_default_timezone_set('Asia/Calcutta');
$date=date("Y-m-d H:i:s");		
			

$query = "SELECT station_coord FROM station WHERE station_coord='$landmark_point1' AND user_account_id='$account_id' AND status=1";
//echo "q1=".$query;
$result = mysql_query($query,$DbConnection);
$numrows = mysql_num_rows($result);

if($numrows >0)
{
  $message = "Sorry! This Station is Already Added";
}
else
{  
  $query_select = "SELECT customer_no FROM station WHERE customer_no='$customer_no1' AND user_account_id='$account_id' AND status=1";
  $result = mysql_query($query_select,$DbConnection);
  $numrows2 = mysql_num_rows($result);
  //echo "<br>q2=".$query_select;
  
  if($numrows2 >0)
  {
    $message = "Sorry! Customer Number Already Exists";
  }
  else
  {
    $query ="select Max(sno)+1 as serial_no from station";  ///// for auto increament of landmark_id ///////////   
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $max_no= $row->serial_no;
    if($max_no==""){$max_no=1;}
    
    $query_string1="INSERT INTO station(user_account_id,station_id,station_name,customer_no,distance_variable,station_coord,type,status,create_id,create_date) VALUES".
                    "('$account_id','$max_no','$landmark_name1','$customer_no1','$radius_distance1','$landmark_point1',$landmark_type1,1,'$account_id','$date');";
        
    //echo "q2=".$query_string1;
    $result = mysql_query($query_string1,$DbConnection);
  
    $message = "Station Added Successfully";
  }
}

echo $message;
//echo "Q=".$Query1;

?>
