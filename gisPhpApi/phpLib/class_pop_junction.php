<?php
class class_pop_junction{
	
	function get_junction_by_name($name_loc){
		//============This will return from Solr Apache=============================
		$junction_info=array();
		
		//$name_loc="Gooba Garden Road";
		$name_loc = preg_replace('/\s+/', '%20', $name_loc);
		//echo $name_loc;
		$url="http://52.74.144.159:8080/fulltext/fulltextsearch?q=$name_loc&placetype=road&format=JSON&__checkbox_indent=true&from=1&to=1";

		$json = file_get_contents($url);
		$data = json_decode($json, TRUE);
		//print_r($data);

		$feature_id=$data['response']['docs'][0]['feature_id'];
		$name=$data['response']['docs'][0]['name'];
		$lat=$data['response']['docs'][0]['lat'];
		$lng=$data['response']['docs'][0]['lng'];
		//$feature_code=$data['response']['docs'][0]['feature_code'];
		//$placetype=$data['response']['docs'][0]['placetype'];//ROAD
		//echo "CHURAHA INFO, FeatureID=".$feature_id.":Name=".$name.":Lat=".$lat.":Lng=".$lng.":FeatureCode=".$feature_code;
		$location_name=$name;		
		$junction_info[]=array('code'=>$feature_id,'location'=>$location_name,'lat'=>$lat,'lng'=>$lng);
		
		return $junction_info;
	}
	function  get_junction_by_latlng($lat,$lng)
	{
		//============This will return from pOSTGRES sql=============================
		$junction_info=array();
		$url="http://52.74.144.159:8080/geoloc/geolocsearch?lat=$lat&lng=$lng&placetype=road&format=JSON&distance=true&from=1&to=1";
		$json = file_get_contents($url);
		$data = json_decode($json, TRUE);
		//print_r($data);
		
		$feature_id=$data['result'][0]['featureId'];
		$name=$data['result'][0]['name'];
		$lat=$data['result'][0]['lat'];
		$lng=$data['result'][0]['lng'];
		$feature_code=$data['result'][0]['featureCode'];
		//$distance=$data['result'][0]['distance'];
		//$placetype=$data['result'][0]['placetype'];//ROAD
		//echo "CHURAHA INFO, FeatureID=".$feature_id.":Name=".$name.":Lat=".$lat.":Lng=".$lng.":FeatureCode=".$feature_code;
		$location_name=$name;		
		
		$junction_info[]=array('code'=>$feature_id,'location'=>$name,'lat'=>$lat,'lng'=>$lng);
		return $junction_info;
	}
	
	function  get_junction_near_by_latlng($lat,$lng,$radius)
	{
		//============This will return from pOSTGRES sql=============================
		$junction_info=array();
		$url="http://52.74.144.159:8080/geoloc/geolocsearch?lat=$lat&lng=$lng&placetype=road&format=JSON&distance=true&radius=$radius&from=1&to=1";
		$json = file_get_contents($url);
		$data = json_decode($json, TRUE);
		//print_r($data);
		
		$feature_id=$data['result'][0]['featureId'];
		$name=$data['result'][0]['name'];
		$lat=$data['result'][0]['lat'];
		$lng=$data['result'][0]['lng'];
		$feature_code=$data['result'][0]['featureCode'];
		$distance=$data['result'][0]['distance'];
		//$placetype=$data['result'][0]['placetype'];//ROAD
		//echo "CHURAHA INFO, FeatureID=".$feature_id.":Name=".$name.":Lat=".$lat.":Lng=".$lng.":FeatureCode=".$feature_code;
		$location_name=$name;		
		
		$junction_info[]=array('code'=>$feature_id,'location'=>$name,'lat'=>$lat,'lng'=>$lng,'distance'=>$distance);
		return $junction_info;
	}
	
	function get_junction_by_code($code)
	{
		//============This will return from pOSTGRES sql=============================
		$host        = "host=127.0.0.1";
		$port        = "port=5432";
		$dbname      = "dbname=gisgraphy";
		$credentials = "user=postgres password=neon04$";
		$db_connection = pg_connect( "$host $port $dbname $credentials"  );
		if(!$db_connection){
		  echo "Error : Unable to open database\n";
		} else {
		  //echo "Opened database successfully\n";
		}
		$junction_info=array();
		//$code="100220619";
		$query="SELECT name,astext(location) as lnglat,featurecode FROM road where featureid=$code ";
		//echo $query;
		$result1 = pg_query($db_connection, $query);		
		$row1=pg_fetch_object($result1);
		$name=$row1->name;
		$lnglat=explode(" ",$row1->lnglat);
		$lng_tmp=explode("(",$lnglat[0]);
		$lat_tmp=explode(")",$lnglat[1]);
		//$featurecode=$row1->featurecode;
		$location_name=$name;
		//return "Name=".$name.":Lng=".$lng_tmp[1].":Lat=".$lat_tmp[0];
		$junction_info[]=array('code'=>$code,'location'=>$name,'lat'=>$lat_tmp[0],'lng'=>$lng_tmp[1]);
		return $junction_info;
	}
	
	function  get_junction_by_near_latlng_pgis($lat,$lng)
	{
		//============This will return from pOSTGRES sql=============================
		$host        = "host=127.0.0.1";
		$port        = "port=5432";
		$dbname      = "dbname=gisgraphy";
		$credentials = "user=postgres password=neon04$";
		$db_connection = pg_connect( "$host $port $dbname $credentials"  );
		if(!$db_connection){
		  echo "Error : Unable to open database\n";
		} else {
		  //echo "Opened database successfully\n";
		}
		
		$junction_info=array();
		$query="SELECT id, name, astext(location) as lnglat,featureid, CAST (st_distance_sphere(location, st_setsrid(st_makepoint($lng,$lat),4326)) AS INT) AS d FROM road ORDER BY location <-> st_setsrid(st_makepoint($lng,$lat), 4326)  LIMIT 1";
		//echo $query;
		$result1 = pg_query($db_connection, $query);		
		$row1=pg_fetch_object($result1);
		$name=$row1->name;
		$lnglat=explode(" ",$row1->lnglat);
		$lng_tmp=explode("(",$lnglat[0]);
		$lat_tmp=explode(")",$lnglat[1]);
		$featureid=$row1->featureid;
		$feature_id=$name;		
		
		$junction_info[]=array('code'=>$featureid,'location'=>$name,'lat'=>$lat,'lng'=>$lng);
		return $junction_info;
	}
	
}
?>