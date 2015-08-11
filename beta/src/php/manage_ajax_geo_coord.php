<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $geo_id_1=$_POST['geo_id'];
  $visit_area_id_1=$_POST['visit_area_id'];
  $station_id_1=$_POST['station_id'];
  $route_id_1=$_POST['route_id'];
  $landmark_id_1=$_POST['landmark_id'];
  $location_id_1=$_POST['location_id'];
  //echo "route_id=". $route_id_1;;
  
  if($station_id_1!="")
  {
    $query="SELECT station_name,customer_no,station_coord,distance_variable,type FROM station WHERE station_id='$station_id_1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $geo_name1=$row->station_name;
    $customer_no1=$row->customer_no;
    $coord_1=$row->station_coord;
    $distance_variable1 =$row->distance_variable;
    $type1 =$row->type;
    $type1 = trim($type1);
    if($type1 == "0")
    {
      $station_type = "Customer";
    }    
    else if($type1 == "1")
    {
      $station_type = "Plant";
    }
    else if($type1 == "2")
    {
      $station_type = "Chilling Plant";
    }
    echo "manage_station_coord##".$geo_name1."##".$customer_no1."##".$coord_1."##".$distance_variable1."##".$station_type;
  }  
  
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
  if($visit_area_id_1!="")
  {
    $query="SELECT visit_area_name,visit_area_coord FROM visit_area WHERE visit_area_id='$visit_area_id_1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $geo_name1=$row->visit_area_name;
    $coord_1=$row->visit_area_coord;
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
    $query="SELECT landmark_name,landmark_coord,zoom_level,distance_variable FROM landmark WHERE landmark_id='$landmark_id_1' AND status='1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $landmark_name=$row->landmark_name;
    $coord_1=$row->landmark_coord;
    $zoom_level=$row->zoom_level; 
	$distance_variable=$row->distance_variable;
    //$coord_1 = base64_decode($coord_1);
   
    echo "manage_landmark_coord##".$landmark_name."##".$coord_1."##".$zoom_level."##".$distance_variable;
  }
  else if($location_id_1!="")
  {
    $query="SELECT location_name,geo_point FROM schedule_location WHERE location_id='$location_id_1' AND status='1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $location_name=$row->location_name;
    $coord_1=$row->geo_point;   
    echo "manage_location_coord##".$location_name."##".$coord_1;
  }
?>

        