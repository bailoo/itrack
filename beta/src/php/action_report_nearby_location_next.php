<?php
include_once('util_session_variable.php');
set_time_limit(300);
$j=-1;    
$google_place=explode(":",$geocodedPostcodes);
//echo "geocodedPostcodes=".$geocodedPostcodes."<br>";
$imei=explode(":",$imei_prev);
//echo "imei_prev=".$imei_prev."<br>";
$vname=explode(":",$vname_prev);
$distanceArr=explode(":",$distance_prev);
$lat=explode(":",$lat_prev);
$lng=explode(":",$lng_prev);
//echo "distanceStr=".$distance_prev."<br>";
$vsize = sizeof($imei); 
//echo "vSize=".$vsize."<br>";
?>
<html>
	<title> Halt Report </title>
	 <head> 
		<script type="text/javascript" src="../js/calculate_distance.js"></script>
		<link rel="stylesheet" type="text/css" href="gm_minimap_in_infowindow_files/mapStyle.css">	
		<style type="text/css">
		@import url("http://www.google.com/uds/css/gsearch.css");
		@import url("http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css");
	
		</style>
		<?php
		include_once("main_google_key.php");
		?>	
		
		<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0" type="text/javascript"></script>
		<script src="http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js" type="text/javascript"></script> 
		<script src="./src/js/dragzoom/gzoom.js" type="text/javascript"></script>	
		<script type="text/javascript">
			var lnmark= new GIcon();
			lnmark.image = 'landmark.png';
			lnmark.iconSize= new GSize(10, 10);
			lnmark.iconAnchor= new GPoint(9, 34);
			lnmark.infoWindowAnchor= new GPoint(5, 1);
		</script>
		<script type="text/javascript" src="../js/newwindow_shortmap.js?<?php echo time();?>"></script>
		<?php		
		//echo '<script type="text/javascript" src="../js/newwindow_shortmap.js"></script>';
		if($account_id == "230" || $account_id == "231" || $account_id == "232" || $account_id == "265" || $account_id == "322" || $account_id == "419" || $account_id == "420" || $account_id == "421" || $account_id == "422"|| $account_id == "423"|| $account_id == "424"|| $account_id == "425"|| $account_id == "426"|| $account_id == "427"|| $account_id == "428"|| $account_id == "429")  
		{
			echo'
			<script type="text/javascript" src="../js/newwindow_mapstation.js"></script>  
			<script type="text/javascript" language="javascript">	
			function map_window_station_prev(customer,station_id,station_name,lat,lng)
			{
				//test2(vname,datetime,lat,lng);			
				document.getElementById("window").style.display = "";
				load_stations(customer,station_id,station_name,lat,lng);							
			}
			</script>';
		}

		?>
		
		<script type="text/javascript">
			//function MapWindow(vname,datetime,lat,lng)
			function map_window(vname,date1,date2,lat,lng,type)
			{
				//alert(vname+" "+datetime+" "+lat+" "+lng);	
				//test2(vname,datetime,lat,lng);			
				document.getElementById("window").style.display = '';
				load_vehicle_on_mini_map(vname,date1,date2,lat,lng,type);							
			}
	  
			/*function map_window(vname,datefrom,dateto,lat,lng)
			{
				//alert(vname+" "+datetime+" "+lat+" "+lng);	
				//test2(vname,datetime,lat,lng);			
				document.getElementById("window").style.display = '';
				load_vehicle_on_map(vname,datefrom,dateto,lat,lng);							
			}*/  		
		</script>
		<link rel="StyleSheet" href="../css/newwindow.css">	
		<script language="javascript" src="../js/drag.js"></script>   
		<script language="javascript" src="../js/report.js"></script>		
	 </head>
<body>
    <?php


//echo "distance=".$distance."<br>";
echo'<link rel="StyleSheet" href="../css/menu.css">';
echo"<br>
        <center>
            <b>Near By Vehicle
        </center>
    <br>";
echo'<div style="height:500px;overflow:auto">
    <table width=100%>
        <tr>
            <td align="center">
                <table border=1 width="70%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3 class="menu">	
                    <tr style="background-color:lightgrey">
                        <td class="text">
                        <b>Serial
                        </td>
                        <td class="text">
                        <b>Vehicle Name
                        </td>
                        <td class="text">
                        <b>Distance
                        </td>
                        <td class="text">
                        <b>Address
                        </td>
                    </tr>';
$serial=0;
if(count($distanceArr)==0)
{
    echo "<tr>
                <td colspan='4' align='center' class='text'>No Data Found</td>
        </tr>";
}
else
{
    //echo "in if";
    for($i=0;$i<$vsize-1;$i++)
    {
        $serial++;
        echo "<tr>
                    <td class='text'>".$serial."</td>
                    <td class='text'>".$vname[$i]."</td>
                    <td class='text'>".round($distanceArr[$i],2)."</td>";
                    if($google_place[$i]=="")
                    {
                    echo"<td class='text'>-</td>";
                    }
                    else
                    {
                        echo'<td class="text">'.$google_place[$i].'
                               <a href="javascript:map_window(\''.$vname[$i].'\',\'\',\'\',\''.$lat[$i].'\',\''.$lng[$i].'\',\'\');">
                                    <font color="green">
                                        &nbsp;(Show on map)
                                    </font>
                                </a>
                            </td>';
                    }
            echo"</tr>";
    }
}
    echo"</table>
        </td>
        </tr>
        </table>
</div>";
    include("map_window/floating_map_window.php"); 

?>								
