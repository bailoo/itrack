<?php
   //?lat=26.45832&lng=80.4416
   //include_once('../../SessionVariable.php');
   include_once("PhpMysqlConnectivity.php");
   include_once("get_location.php");
   //include("calculate_distance.php");
   
   $lat = $_REQUEST['lat'];
   $lng = $_REQUEST['lng'];
   //$vehiclename = $_REQUEST['vname'];
   //$datetime = $_REQUEST['datetime'];
   
   $vehiclename = "testvehicle";
   $datetime = "2012-05-10 14:00:00";   
 

function calculate_mileage($lat1, $lat2, $lon1, $lon2, &$distance) 
  {	
  	$lat1 = deg2rad($lat1);
  	$lon1 = deg2rad($lon1);
  
  	$lat2 = deg2rad($lat2);
  	$lon2 = deg2rad($lon2);
  	
  	$delta_lat = $lat2 - $lat1;
  	$delta_lon = $lon2 - $lon1;
  	
  	$temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
  	$distance = 3956 * 2 * atan2(sqrt($temp),sqrt(1-$temp));
  	
  	$distance = $distance*1.609344;	
  }  

?>

<html><head><title>Innovative Tracking Solutions Pvt.Ltd.</title>


<meta http-equiv="Content-Type" content="text/html; charset=">
<meta name="keywords" content="vehicle tracking, gps tracking, gps vehcile tracking, surveillance, gps, tracking, tracking device,tracing,latitude and longitude,gsm modem, car tracking, analyzer,path finder, path locater,way finder,plotter,gps technology">
<meta name="description" content="">

<script type="text/javascript" src="js/mobile.js"></script>

<link rel="shortcut icon" href="images/iesicon.ico">

<link href="http://code.google.com/apis/maps/documentation/javascript/examples/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="http://code.google.com/apis/gears/gears_init.js"></script>

<style type="text/css">
<!--
td {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px}
.white {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #FFFFFF;
}
a:link {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FFFFFF;
	text-decoration: none;
}
a:visited {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FFFFFF;
	text-decoration: none;
}
a:hover {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FFFFFF;
	text-decoration: underline;
}
a:active {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FFFFFF;
	text-decoration: none;
}
-->
</style>


<style type="text/css">
<!--
A:link {text-decoration:none}
A:visited {text-decoration:none}
A:hover{text-decoration:underline;}
.first {color: #63639C }
.style1 {color: #C8C8C8}
.style2 {color: #800000}
-->
</style>

<script type="text/javascript">


if (document.addEventListener) 
{
  //document.addEventListener("DOMContentLoaded", init, false);
}


</script>

<script type="text/javascript">
function validate_form(obj)
{
	if(obj.Login.value == "")
	{
		alert("Please Enter Login ID");
		obj.Login.focus();
		return false;
	}
	if(obj.Password.value == "")
	{
		alert("Please Enter Password");
		obj.Password.focus();
		return false;
	}
	return true;
}
</script>



</head>

<body>

<div id="mainDiv">

<div>
	<img src="images/vts_mobile.png">
</div>
<div>
	<img src="images/title_bar.png" height="12" width="350">
</div>

  <div align="right"> 
                
  <?php
  if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == false))
  echo '<br>';
  ?>																
  							
  </div>
 
  <form name="myform" method = "post" action ="Login.php" onSubmit="javascript:return validate_form(myform)">
 
 <?php
	  
	  echo '<div style="width:100%"><b> VEHICLE GPS LOCATION :</b> </div>';

	  echo '
    <table border=1 rules=all bordercolor="#e5ecf5" cellspacing=0 cellpadding=3>
				<tr bgcolor="#151B8D">
					<td class="text" align="left" >
						<font color="white">SNo</font>
						<font color="red"><b>|</b></font>
						<font color="white">VehicleName</font>
						<font color="red"><b>|</b></font>
						<font color="white">DateTime</font>
						<font color="red"><b>|</b></font>
						<font color="white">Location</font>
					</font></b></td>
				</tr>
    ';

  $img_url_for_marker="http://maps.google.com/maps/api/staticmap?center=".$lat.",".$lng."&zoom=8&size=512x512&maptype=roadmap";

  //echo'vehicleserial : '.sizeof($vehicleserial);
  $alt="-";
  
  get_location($lat,$lng,$alt,&$placename1,$DbConnection);
  $placename=$placename1;
  
  //echo "lat=".$lat[$i];
  //echo "lng=".$lng[$i];
  $j=1;
  
  $bgclr="";
  if($j%2 == 0)
  	$bgclr="#F9B7FF";
  else
  	$bgclr="#E6A9EC";
  
  $img_url_for_marker=$img_url_for_marker."&markers=color:blue|label:".$j."|".$lat.",".$lng;

	echo '
		<tr bgcolor="'.$bgclr.'">
			<td class="text" align="left">
			'.$j.'<font color="red"><b>|</b></font>'.$vehiclename.'<font color="red"><b>|</b></font>'.$datetime.'<font color="red"><b>|</b></font>'.$placename.'
			</td>
		</tr>
		';


$img_url_for_marker=$img_url_for_marker."&sensor=false";


echo'

</table>
';
	if($b_type=="android"){
		echo'<div id="map_canvas"></div>';
	}
	else{
	echo'
	<image src="'.$img_url_for_marker.'" /> ';  
  }		  
		 
//}//********************************** Last position Report closed *****************

  ?>

  <input type="hidden" value="<?php echo $startdate ?>" name="startdate" id="startdate">
  <input type="hidden" value="<?php echo $enddate ?>" name="enddate" id="enddate">

</form>
		
	<table>
	<tbody>
		  <tr valign="top">  		
			<td width="350" height="13" background="../../images/bottomstrip.png"> 	
			  <div align="center"><font color="#FFFFFF">&#169;copyright 
				Innovative Embedded Systems Pvt. Ltd. <br>
				All Rights Reserved</font> </div>
			</td>
			 </tr>
    </tbody>
  </table>	                        
 
</div>
<?php
mysql_close($DbConnection);

IF($mobile_browser > 0){
	echo "<script type='text/javascript'>	
			mainDiv.style.width = '100%';    
			mainDiv.style.height = '100%'; 
		</script>";
}
ELSE{
	echo "<script type='text/javascript'>			
			detectBrowser();		
		  </script>";
}


if($b_type=="android"){
$k=0;
echo'
<script type="text/javascript">
//alert("Hi 1");
var geocoder;
var initialLocation;	
var latlng = new google.maps.LatLng('.substr($lat[0],0,-1).','.substr($lng[0],0,-1).');
var map;
var infowindow = new google.maps.InfoWindow();
var contentString="";
var marker;
var elevator;
var polyline;
//var path=new Array(); 

Plot_Marker();

function loadXML(xmlFile)
{
	var xmlhttp=false;
	var status = false;
	var xmlDoc=null;
	var newurl=0;
	
	
	if (!xmlhttp && typeof XMLHttpRequest!=\'undefined\') {
		try {
			xmlhttp = new XMLHttpRequest();
		} catch (e) {
			xmlhttp=false;
		}
	}
	if (!xmlhttp && window.createRequest) {
		try {
			xmlhttp = window.createRequest();
		} catch (e) {
			xmlhttp=false;
		}
	}
	
	
	xmlFile=xmlFile+"?newurl="+newurl; 
	xmlhttp.open("GET",xmlFile,false);
	xmlhttp.send(null);
	var finalStr = xmlhttp.responseText
	//var finalStr = modify_xml_text(txtStr)	 
		//alert(txtStr.length);	  
		//alert(finalStr.length);	
	if (window.DOMParser)
	{
		//alert(finalStr);
		parser=new DOMParser();
		xmlDoc=parser.parseFromString(finalStr,"text/xml");
	}
	else // Internet Explorer
	{
		try
		{
      xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
  		xmlDoc.async="false";
  		status = xmlDoc.loadXML(finalStr);
  	}
  	catch(e1)
  	{
  	 //alert("T1");
  	 xmlDoc=null;
    }
    if(status==false)
	  {
	   xmlDoc=null;
    }
	} 
	
	  //alert("xmlDoc="+xmlDoc);
	  return xmlDoc;
};




//myCars[0]="Saab";

function Plot_Marker(){
		
	geocoder = new google.maps.Geocoder();
	var myOptions = {
    zoom: 10,
	center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	
	// Create an ElevationService.
    //elevator = new google.maps.ElevationService();

	var xmlObj = null;
	//alert("hello1"+xmlObj+"z");    

	xmlObj = loadXML("'.$xmltowrite.'");

	//alert("hello1"+xmlObj+"z");    

	if(xmlObj!=null)	
	{ 
	  //alert("hello1"+xmlObj+"z");                                        
	  xml_data = xmlObj.documentElement.getElementsByTagName("marker");
	  //alert("marker :"+xml_data);
	  for (var k = 0; k < xml_data.length; k++) 
		{																													
			//alert("t11111111==="+t1[i].getAttribute("datetime"));						
			lat_tmp = xml_data[k].getAttribute("lat");
			lng_tmp = xml_data[k].getAttribute("lng");
			
			//alert(lat_tmp.substring(0, 7)+","+lng_tmp.substring(0, 7));
			
			latlng = new google.maps.LatLng(lat_tmp.substring(0, 7),lng_tmp.substring(0, 7));
			//path[k]=latlng;
			//alert(latlng);
			marker = new google.maps.Marker({
				position: latlng, 
				map: map,
				title:k+""
			}); 
		}

	// Draw the path, using the Visualization API and the Elevation service.
   // drawPath();			
	}

}

</script>';
//}//for close
}
?>


</body>
</html>
