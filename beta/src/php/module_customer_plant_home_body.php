<?php

/*$options = array(
    'center' => true,
    'navigationControl' => true,
    'mapTypeControl' => true,
    'scaleControl' => true,
    'scrollwheel' => true,
    'zoom' => 5,
    'type' => 'R', // Roadmap, Satellite, Hybrid, Terrain
    'div' => array('height' => '100%', 'width' => '100%'), //$user_height1'div'=> array('height'=>'613', 'width'=>'auto'),
    'position' => 'relative',
    'id' => 'map_canvas',
    'chillingPlant' => $flag_chilling_plant,
    'lat' => 22.755920681486,
    'lng' => 78.2666015625,
    'style' => ''
);*/
//echo "3";
$customer_no = array();
$lat = array();
$lng = array();
$customer_name = array();
$data1 = array();
$data2 = array();

//echo'<input id="pac-input" class="controls" type="text" placeholder="Search Box">';
//$googleMapthisapi = new GoogleMapHelper();

//echo "<br>FILTER ON";
$filter_flag = $_POST['filter_flag'];
$ALL = $_POST['All'];
$MS = $_POST['MS'];
$KISOK = $_POST['KISOK'];
$INSTITUTION = $_POST['INSTITUTION'];
$FS = $_POST['FS'];
$DISTRIBUTOR = $_POST['DISTRIBUTOR'];
$PLANTS = $_POST['PLANTS'];
$GEOFENCE = $_POST['GEOFENCE'];

if ($filter_flag == '') {
    //echo "<br>CUSTOMER All";
    //$cArr = json_encode($_SESSION['uniqueCustomerArrNew']);
    $All = 'on';
    $MS = 'on';
    $KISOK = 'on';
    $INSTITUTION = 'on';
    $FS = 'on';
    $DISTRIBUTOR = 'on';
    $PLANTS = 'on';
} 

//####### GET CUSTOMER DETAIL ##########
//#######################################
$cArr = json_encode($_SESSION['uniqueCustomerArrNew']);

$customerArr1 = json_decode($cArr, true);
$customerArr = json_decode($customerArr1, true);
//echo "size=".sizeof($cArr);
//print_r($customerArr);
foreach ($customerArr as $data_key => $data_value) {

    //echo "<br>DATA=".$data_value;
    $splitCustomerData = explode('^', $data_value);

    $lat_tmp = $splitCustomerData[0];
    $lng_tmp = $splitCustomerData[1];
    $customerName = $splitCustomerData[2];
    //echo "<br>CustomerNo:".$data_key." ,Lat=".$lat_tmp." ,Lng=".$lng_tmp." ,CustomerName=".$customerName;

    //echo "<br>DataValue=".$data_value;
    $tmp_customer = explode('@', $data_key);
    $customer_key = intval(str_replace(' ', '', $tmp_customer[0]));
    //echo $customer_key."<br>";
    //if (strpos($customerName, $customer_option) !== false) {
    if( ($MS == 'on') && ( ($customer_key > 1 && $customer_key < 1999) || 
            ($customer_key > 17001 && $customer_key < 19999) ) )
    {           
        $type ='MS';
        $icon = 'images/customer_plant_on_map/ms.png';
        $str = $lat_tmp . '^' . $lng_tmp . '^' . $customerName . '^'.$type.'^'.$icon;
        $data1[$data_key] = $str;
    }

    else if ( ($KISOK == 'on') && ($customer_key > 14001 && $customer_key < 15999) )            
    {                   
        //echo "<br>C=".$customer_key."<br>";
        $type ='KISOK';
        $icon = 'images/customer_plant_on_map/kisok.png';
        $str = $lat_tmp . '^' . $lng_tmp . '^' . $customerName . '^'.$type.'^'.$icon;
        $data1[$data_key] = $str;
    }   

    else if ( ($INSTITUTION == 'on') && (($customer_key > 1000001 && $customer_key < 1999999) || 
            ($customer_key > 5000001 && $customer_key < 5999999) ) )
    {            
        $type ='INSTITUTION';
        $icon = 'images/customer_plant_on_map/institution.png';
        $str = $lat_tmp . '^' . $lng_tmp . '^' . $customerName . '^'.$type.'^'.$icon;
        $data1[$data_key] = $str;
    }   

    else if ( ($FS == 'on') && ( ($customer_key > 12001 && $customer_key < 13999) || 
            ($customer_key > 16001 && $customer_key < 16999) ) )
    {            
        $type ='FS';
        $icon = 'images/customer_plant_on_map/fs.png';
        $str = $lat_tmp . '^' . $lng_tmp . '^' . $customerName . '^'.$type.'^'.$icon;
        $data1[$data_key] = $str;
    }   

    else if ( ($DISTRIBUTOR == 'on') && ($customer_key > 70001 && $customer_key < 77999) )
    {           
        $type ='DISTRIBUTOR';
        $icon = 'images/customer_plant_on_map/distributor.png';
        $str = $lat_tmp . '^' . $lng_tmp . '^' . $customerName . '^'.$type.'^'.$icon;
        $data1[$data_key] = $str;
    }           

} //## FOREACH CLOSED

$cArr1 = json_encode($data1);
$cArr = json_encode($cArr1);
//$tmp = json_decode($cArr,true);
//print_r($tmp);
//##### CUSTOMER DETAIL CLOSED #######


//####### GET PLANT DETAIL ##########
//#######################################
if ($PLANTS == 'on') {
    //echo "<br>PLANT All";
    $pArr = json_encode($_SESSION['uniquePlantArrNew']);

    $plantArr1 = json_decode($pArr, true);
    $plantArr = json_decode($plantArr1, true);
    //echo "size=".sizeof($cArr);
    //print_r($customerArr);
    foreach ($plantArr as $data_key => $data_value) {

        //echo "<br>DATA=".$data_value;
        $splitPlantData = explode('^', $data_value);

        $lat_tmp = $splitPlantData[0];
        $lng_tmp = $splitPlantData[1];
        $plantName = $splitPlantData[2];
        //echo "<br>CustomerNo:".$data_key." ,Lat=".$lat_tmp." ,Lng=".$lng_tmp." ,CustomerName=".$customerName;

        $tmp_plant = explode('@', $data_key);
        $plant_key = intval(str_replace(' ', '', $tmp_plant[0]));
           
        $type ='PLANTS';
        $icon = 'images/customer_plant_on_map/plants.png';
        $str = $lat_tmp . '^' . $lng_tmp . '^' . $plantName . '^'.$type.'^'.$icon;
        $data2[$data_key] = $str;
        
    } //## FOREACH CLOSED

    $pArr1 = json_encode($data2);
    $pArr = json_encode($pArr1);
    //$tmp = json_decode($cArr,true);
    //print_r($tmp);
}       
//##### PLANT DETAIL CLOSED #######   

//echo "ONE<br>PARR=";
//print_r($pArr);
//echo "TWO<br>";
if ($cArr == "") {
    $data1['0'] = '^^^^';
    $cArr1 = json_encode($data1);
    $cArr = json_encode($cArr1);
}
if ($pArr == "") {
    $data2['0'] = '^^^^';
    $pArr1 = json_encode($data2);
    $pArr = json_encode($pArr1);
}
//print_r($pArr);
//echo $googleMapthisapi->map($options, $cArr, $pArr);

//##### MAP OBJECT ########
echo '<div id="googlemap" style="width: 100%; height: 100%;"></div>';

echo'<p id="prepage" style="position:absolute; font-family:arial; font-size:16; left:48%; top:220px; layer-background-color:#e5e3df; height:10%; width:20%; visibility:hidden"><img src="images/load_data.gif">';

echo '<div id="dummy_div" style="display:none;"/>';

//echo '<div id="map_home"/>';	
?>