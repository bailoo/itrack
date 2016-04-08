<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $geo_id_1=$_POST['geo_id'];
  $route_id_1=$_POST['route_id'];
  $landmark_id_1=$_POST['landmark_id'];
  //echo "route_id=". $route_id_1;;
  
  if($geo_id_1!="")
  {
    $query="SELECT geo_name,geo_coord FROM geofence WHERE geo_id='$geo_id_1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $geo_name1=$row->geo_name;
    $coord_1=$row->geo_coord;
    $coord_1 = base64_decode($coord_1);
   
    echo "manage_geo_coord##".$geo_name1."##".$coord_1;
  }
  else if($route_id_1!="")
  {
    $query="SELECT route_name,route_coord FROM route WHERE route_id='$route_id_1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $route_name=$row->route_name;
    $coord_1=$row->route_coord;     
    $coord_1 = base64_decode($coord_1);
   
    echo "manage_route_coord##".$route_name."##".$coord_1;
  }
  else if($landmark_id_1!="")
  {
    $query="SELECT landmark_name,landmark_coord,zoom_level FROM landmark WHERE landmark_id='$landmark_id_1' AND status='1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $landmark_name=$row->landmark_name;
    $coord_1=$row->landmark_coord;
    $zoom_level=$row->zoom_level;     
    $coord_1 = base64_decode($coord_1);
   
    echo "manage_landmark_coord##".$landmark_name."##".$coord_1."##".$zoom_level;
  }
?>

        