<?php    
$options = array(
    'center' => true,
	'navigationControl' => true,
	'mapTypeControl' => true,
	'scaleControl' => true,
	'scrollwheel' => true,
    'zoom' => 5,	
    'type' => 'R', // Roadmap, Satellite, Hybrid, Terrain
    'div'=> array('height'=>'85%', 'width'=>'100%'),//$user_height1'div'=> array('height'=>'613', 'width'=>'auto'),
    'position'=>'relative',
    'id' => 'map_canvas',
	'chillingPlant' => $flag_chilling_plant,
    'lat'=> 22.755920681486,
    'lng'=> 78.2666015625,
	'style' => ''
);
//echo "3";
/*if (file_exists('C:\\xampp/htdocs/itrack_test_analytics/beta/src/php/googleMapApi_cellid.php')) { echo "TRUE"; }else{
    echo "false";
}*/
echo'<span style="position:absolute; right:50px; top:87%;z-index:99;">';
//include('module_speed_symbol.php');
echo'</span>';
echo'<a href="#menu-toggle" class="btn btn-default" id="menu-toggle" style="position:absolute; left:0px; top:40%;z-index:99;"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span></a>';
echo'<input id="pac-input" class="controls" type="text" placeholder="Search Box">';
$googleMapthisapi=new GoogleMapHelper();
echo $googleMapthisapi->map($options);
echo'<p id="prepage" style="position:absolute; font-family:arial; font-size:16; left:40%; top:220px; layer-background-color:#e5e3df; height:10%; width:20%; visibility:hidden"><img src="images/load_data.gif">';		
echo '<div id="dummy_div" style=""/>';


?>