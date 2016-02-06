<?php
class class_polyline_edge{
	//===========First function called=======//  
        
	function get_polyline($polyline_coord,$chk_latlng_array,$data_date_array,$polyline_name){
		$flag_edge=0;		
		$data_value=array();
		$d=0;
		foreach($chk_latlng_array as $chk_latlng)
		{
			$data_value[]=$this->get_perpendicular_distance($polyline_coord,$chk_latlng,str_replace(':','-',$data_date_array[$d]),$polyline_name);
			$d++;
		}
		//print_r($data_value);
		$final_data_value=array();
		$last_prev_date=array();
		$p=0;
		foreach($data_value as $d_val)
		{
			$d_val1=explode(":",$d_val);			
			if($d_val1[3]=="0" && $flag_edge==0)
			{
				for($m=$p;$m<=sizeof($data_value);$m++)
				{
					if($m<sizeof($data_value)-1)
					{
						$last_prev_date=explode(":",$data_value[$m+1]);	
						//echo $last_prev_date[4]."/";
						if($last_prev_date[3]=="1")//checking on edge
						{
							$last_prev_date=explode(":",$data_value[$m]);
							//echo "break";
							break;
						}
					}
				}
				
				if($last_prev_date[4]=="")
				{
					$last_prev_date=explode(":",$data_value[$p]);
				}
				
				$lat_lng_loc=explode(",",$d_val1[2]);
				$final_data_value[]=$d_val.":".$last_prev_date[4].":".$this->getlocation($lat_lng_loc[0],$lat_lng_loc[1]);
				$flag_edge=1;				
			}
			else if($d_val1[3]=="1" && $flag_edge==1)
			{
				$last_prev_date=explode(":",$data_value[$p+1]);
				$lat_lng_loc=explode(",",$d_val1[2]);
				$final_data_value[]=$d_val.":".$last_prev_date[4].":".$this->getlocation($lat_lng_loc[0],$lat_lng_loc[1]);
				$flag_edge=0;				
			}
			$p++;
		}		
		return $final_data_value;
	}
	
	public function get_perpendicular_distance($polyline_coord,$chk_latlng,$data_date,$polyline_name){
		
		$leg_step1=array();
		$leg_step2=array();
		$off_point=array();		
		$off_point=explode(",",$chk_latlng);
		$leg_total=sizeof($polyline_coord);
		$l=0;
		$per_distance=0.0; $min_per_distance=0.0;
		foreach($polyline_coord as $poly_coord)
		{
			if($l<$leg_total)
			{
				$leg_step1=explode("," ,$polyline_coord[$l]);
				$leg_step2=explode("," ,$polyline_coord[$l+1]);
				
				$a['lat'] = $leg_step1[0];
				$a['lon'] = $leg_step1[1];
				$b['lat'] = $leg_step2[0];
				$b['lon'] = $leg_step2[1];
				
				// point
				$c['lat'] = $off_point[0];
				$c['lon'] = $off_point[1];
				$per_distance=$this->get_geo_distance_point_to_segment($a, $b, $c);
				
				if($l==0)
				{
					$min_per_distance=$per_distance;
					$route1latlng=$a['lat'].",".$a['lon'];
					$route2latlng=$b['lat'].",".$b['lon'];
				}
				else
				{
					if($per_distance < $min_per_distance)
					{
						$min_per_distance=$per_distance;
						$route1latlng=$a['lat'].",".$a['lon'];
						$route2latlng=$b['lat'].",".$b['lon'];
					}					
				}
				if($per_distance*1000 < 1000) //km to m and chk within 100 m
				{
					
					//$perpendicular_distance="Vehicle on Edge [ ".round($per_distance*1000,2) ." ] meter from Route:".$route1latlng."->".$route2latlng.":".$c['lat'].",".$c['lon'].":1:".$data_date;
					return null;
                                        //break;
				}
				else
				{
					
					$perpendicular_distance="Vehicle Deviated [ ".round($min_per_distance,2) ." ] Km from Route-".$polyline_name.":".$route1latlng."->".$route2latlng.":".$c['lat'].",".$c['lon'].":0:".$data_date;
				}
			}
			$l++;
		}
		
		//return $perpendicular_distance."Location=".$this->getlocation($off_point[0],$off_point[1]);
		return $perpendicular_distance;
	}
	
	/*public function get_perpendicular_distance($polyline_coord,$chk_latlng){
		global $flag_edge;
		$leg_step1=array();
		$leg_step2=array();
		$off_point=array();		
		$off_point=explode(",",$chk_latlng);
		$leg_total=sizeof($polyline_coord);
		$l=0;
		$per_distance=0.0; $min_per_distance=0.0;
		foreach($polyline_coord as $poly_coord)
		{
			if($l<$leg_total)
			{
				$leg_step1=explode("," ,$polyline_coord[$l]);
				$leg_step2=explode("," ,$polyline_coord[$l+1]);
				
				$a['lat'] = $leg_step1[0];
				$a['lon'] = $leg_step1[1];
				$b['lat'] = $leg_step2[0];
				$b['lon'] = $leg_step2[1];
				
				// point
				$c['lat'] = $off_point[0];
				$c['lon'] = $off_point[1];
				$per_distance=$this->get_geo_distance_point_to_segment($a, $b, $c);
				
				if($l==0)
				{
					$min_per_distance=$per_distance;
					$route1latlng=$a['lat'].",".$a['lon'];
					$route2latlng=$b['lat'].",".$b['lon'];
				}
				else
				{
					if($per_distance < $min_per_distance)
					{
						$min_per_distance=$per_distance;
						$route1latlng=$a['lat'].",".$a['lon'];
						$route2latlng=$b['lat'].",".$b['lon'];
					}					
				}
				if($per_distance*1000 < 100) //km to m and chk within 100 m
				{
					$perpendicular_distance="Vehicle on Edge [ ".round($per_distance*1000,2) ." ] meter from Route:".$route1latlng."->".$route2latlng.":".$c['lat'].",".$c['lon'];
					break;
				}
				else
				{	
					$perpendicular_distance="Vehicle Not on Edge [ ".round($min_per_distance,2) ." ] Km from Route:".$route1latlng."->".$route2latlng.":".$c['lat'].",".$c['lon'];
				}
			}
			$l++;
		}
		
		//return $perpendicular_distance."Location=".$this->getlocation($off_point[0],$off_point[1]);
		return $perpendicular_distance;
	}
	*/
	public function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'Km') {
		 $theta = $longitude1 - $longitude2;
		 $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
		 $distance = acos($distance);
		 $distance = rad2deg($distance);
		 $distance = $distance * 60 * 1.1515; switch($unit) {
			  case 'Mi': break; case 'Km' : $distance = $distance * 1.609344;
		 }
		 return (round($distance,2));
	}
	
	// distance between two points in kilometres
	// 3958      - Earth's radius (miles)
	// 3.1415926 - PI
	// 57.29578  - Number of degrees/radian (for conversion)
	// 1.609344  - meters = 0.001 mile
	public function get_geo_distance_point_to_point($lat1, $lon1, $lat2, $lon2)
	{
		return 	(3958 * 3.1415926 * sqrt(($lat2 - $lat1) * ($lat2 - $lat1) 
				+ cos($lat2 / 57.29578) * cos($lat1 / 57.29578) * ($lon2 - $lon1) * ($lon2 - $lon1)) / 180) * 1.609344;
	}
	// get height from triangle where A or B are not obtuse
	public function get_height_from_base_triangle($ab, $ac, $bc)
	{
		// find $s (semiperimeter) for Heron's formula
		$s = ($ab + $ac + $bc) / 2;
		
		// Heron's formula - area of a triangle
		$area = sqrt($s * ($s - $ab) * ($s - $ac) * ($s - $bc));
		
		// find the height of a triangle - ie - distance from point to line segment
		$height = $area / (.5 * $ab);
		
		return $area;
	}
	// returns angles of a triangle from the sides
	public function get_angles_from_sides($ab, $bc, $ac)
	{
		$a = $bc;
		$b = $ac;
		$c = $ab;
		
		$angle['a'] = rad2deg(acos((pow($b,2) + pow($c,2) - pow($a,2)) / (2 * $b * $c)));
		$angle['b'] = rad2deg(acos((pow($c,2) + pow($a,2) - pow($b,2)) / (2 * $c * $a)));
		$angle['c'] = rad2deg(acos((pow($a,2) + pow($b,2) - pow($c,2)) / (2 * $a * $b)));
		
		return $angle;			
	}
	// $a, $b, $c lat lon array of line segments ($a and $b) and the off point ($c)
	public function get_geo_distance_point_to_segment($a, $b, $c)
	{
		$ab = $this->get_geo_distance_point_to_point($a['lat'], $a['lon'], $b['lat'], $b['lon']); // base or line segment
		$ac = $this->get_geo_distance_point_to_point($a['lat'], $a['lon'], $c['lat'], $c['lon']);
		$bc = $this->get_geo_distance_point_to_point($b['lat'], $b['lon'], $c['lat'], $c['lon']);
		//return $ab;
		
		$angle = $this->get_angles_from_sides($ab, $bc, $ac);
		
		if($ab + $ac == $bc) // then points are collinear - point is on the line segment
		{
			return 0;
		}
		else if($angle['a'] <= 90 && $angle['b'] <= 90) // A or B are not obtuse - return height as distance
		{
			return $this->get_height_from_base_triangle($ab, $ac, $bc);
		}
		else // A or B are obtuse - return smallest side as distance
		{
			return ($ac > $bc) ? $bc : $ac;
		}

	}
	/*
		// line segment
		$a['lat'] = 37.083667;
		$a['lon'] = -1.84948;
		$b['lat'] = 37.069149;
		$b['lon'] = -1.849823;
		
		// point
		$c['lat'] = 37.14;
		$c['lon'] = -1.85;

		echo get_geo_distance_point_to_segment($a, $b, $c);
	*/
	
	
	function getlocation($lat,$lng)
	{
		/*$endpoint="http://maps.googleapis.com/maps/api/geocode/json?latlng=".trim($lat).",".trim($lng)."&sensor=false";
 
		$raw=@file_get_contents($endpoint);
		$json_data=json_decode($raw);
 
		if ($json_data->status=="OK") 
		{
        		//$fAddress=explode(",", $json_data->results[count($json_data->results)-2]->formatted_address);  //this is human readable address --> getting province name
	         	$fAddress= $json_data->results[0]->formatted_address;
			
		}
		else
		{
			$fAddress="-";
		}
		return $fAddress;*/
            header('Access-Control-Allow-Origin: *');

                $latitude=$lat;
                $longitude=$lng;


                $curl_handle=curl_init();
                curl_setopt($curl_handle, CURLOPT_URL,'http://nominatim.openstreetmap.org/reverse?format=json&lat='.$latitude.'&lon='.$longitude.'&zoom=18&addressdetails=1');
                curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
                $json = curl_exec($curl_handle);
                curl_close($curl_handle);
                $data = json_decode($json, true);
                //print_r($data);
                $address=$data['display_name'];
                $lat_local=$data['lat'];
                $lon_local=$data['lon'];
                $lat_local = round(floatval($lat_local),4);
                $lon_local = round(floatval($lon_local),4);
                $distance="";
                calculate_report_distance($latitude,$lat_local,$longitude,$lon_local,$distance);
                $placename=round($distance,2)." km from ".$address;
                return $placename;


	}
        
         function calculate_report_distance($lat1, $lat2, $lon1, $lon2, &$distance) 
        {
                $lat1 = deg2rad($lat1);
                $lon1 = deg2rad($lon1);

                $lat2 = deg2rad($lat2);
                $lon2 = deg2rad($lon2);

                $delta_lat = $lat2 - $lat1;
                $delta_lon = $lon2 - $lon1;

                $temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
                $distance = 6378.1  * 2 * atan2(sqrt($temp),sqrt(1-$temp));
        }
	
}
?>
