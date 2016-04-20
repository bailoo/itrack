<?php 
 
	$options = array(
    'center' => true,
	'navigationControl' => true,
	'mapTypeControl' => true,
	'scaleControl' => true,
	'scrollwheel' => true,
    'zoom' => 5,	
    'type' => 'R', // Roadmap, Satellite, Hybrid, Terrain
    'div'=> array('height'=>'100%', 'width'=>'100%'),//$user_height1'div'=> array('height'=>'613', 'width'=>'auto'),
    'position'=>'relative',
    'id' => 'map_canvas',
	'chillingPlant' => $flag_chilling_plant,
    'lat'=> 22.755920681486,
    'lng'=> 78.2666015625,
	'style' => ''
);
//echo "3";
$customer_no = array();
$lat = array();
$lng = array();
$customer_name = array();
$data = array();

echo'<input id="pac-input" class="controls" type="text" placeholder="Search Box">';
$googleMapthisapi=new GoogleMapHelper();

$filter_mode = $_POST['filter_mode'];
if($filter_mode ==1) {
	//echo "<br>FILTER ON";
	$customer = $_POST['customer'];
	$plant = $_POST['plant'];
	
	if($customer=="on") {
		
		$customer_option = $_POST['customer_option'];
		if($customer_option=="All") {
			//echo "<br>CUSTOMER All";
			$cArr=json_encode($_SESSION['uniqueCustomerArrNew']);
		} else {
			//echo "<br>CUSTOMER SELECTED";
			$cArr=json_encode($_SESSION['uniqueCustomerArrNew']);

			$customerArr1 = json_decode($cArr,true);
			$customerArr = json_decode($customerArr1,true);
			//echo "size=".sizeof($cArr);
			//print_r($customerArr);
			foreach($customerArr as $data_key=>$data_value) {
				
				//echo "<br>DATA=".$data_value;
				$splitCustomerData=explode('^',$data_value);

				$lat_tmp=$splitCustomerData[0];
				$lng_tmp=$splitCustomerData[1];
				$customerName=$splitCustomerData[2];
				//echo "<br>CustomerNo:".$data_key." ,Lat=".$lat_tmp." ,Lng=".$lng_tmp." ,CustomerName=".$customerName;
				
				if (strpos($customerName, $customer_option) !== false) {
					//echo 'true';
					$customer_no[] = $data_key;
					$lat[] = $lat_tmp;
					$lng[] = $lng_tmp;
					$customer_name[] = $customerName;
					$str = $lat_tmp.'^'.$lng_tmp.'^'.$customerName.'^0';
					$data[$data_key] = $str;						
				}				
					
			} //## FOREACH CLOSED
			
			$cArr1=json_encode($data);
			$cArr=json_encode($cArr1);
			//$tmp = json_decode($cArr,true);
			//print_r($tmp);		
		}
	}
	
	if($plant=="on") {
		
		$plant_option = $_POST['plant_option'];
		if($plant_option =="All") {
			//echo "<br>PLANT All";
			$pArr= json_encode($_SESSION['uniquePlantArrNew']);
		}
	}	
	
} else {
	$cArr=json_encode($_SESSION['uniqueCustomerArrNew']);
	$pArr= json_encode($_SESSION['uniquePlantArrNew']);
}

//echo "ONE<br>PARR=";
//print_r($pArr);
//echo "TWO<br>";
if($cArr=="") {
	$data1['0'] = '^^^^';
	$cArr1=json_encode($data1);
	$cArr=json_encode($cArr1);
}
if($pArr=="") {
	$data2['0'] = '^^^^';
	$pArr1=json_encode($data2);
	$pArr=json_encode($pArr1);
}
//print_r($pArr);
echo $googleMapthisapi->map($options,$cArr,$pArr);
echo'<p id="prepage" style="position:absolute; font-family:arial; font-size:16; left:48%; top:220px; layer-background-color:#e5e3df; height:10%; width:20%; visibility:hidden"><img src="images/load_data.gif">';		


echo '<div id="dummy_div" style="display:none;"/>';
//echo '<div id="map_home"/>';	



?>