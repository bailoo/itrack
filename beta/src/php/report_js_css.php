<link rel="stylesheet" href="src/css/search_style.css?<?php echo time();?>">
<link rel="stylesheet" href="src/css/simpletree.css?<?php echo time();?>">
<script language="javascript" src="src/js/simpletreemenu.js?<?php echo time();?>"></script>       
<script language="javascript" src="src/js/report.js?<?php echo time();?>"></script>


<script type="text/javascript" src="src/js/calculate_distance.js"></script>
		<link rel="stylesheet" type="text/css" href="gm_minimap_in_infowindow_files/mapStyle.css">	
		<style type="text/css">
		@import url("http://www.google.com/uds/css/gsearch.css");
		@import url("http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css");
		}
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
		<script type="text/javascript" src="src/js/report_shortmap.js?<?php echo time();?>"></script>
		<?php		
			//echo '<script type="text/javascript" src="../js/newwindow_shortmap.js"></script>';
			if($account_id == "230" || $account_id == "231" || $account_id == "232" || $account_id == "265" || $account_id == "322" || $account_id == "419" || $account_id == "420" || $account_id == "421" || $account_id == "422"|| $account_id == "423"|| $account_id == "424"|| $account_id == "425"|| $account_id == "426"|| $account_id == "427"|| $account_id == "428"|| $account_id == "429")  
			{
				echo'<script type="text/javascript" src="../js/newwindow_mapstation.js"></script>  
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
				//alert(vname+" "+date1+" "+lat+" "+lng);	
				//test2(vname,datetime,lat,lng);			
				document.getElementById("window").style.display = '';
				load_vehicle_on_mini_map(vname,date1,date2,lat,lng,type);							
			}
			/*function test()
			{
				alert("test function");
			}*/
	  
			/*function map_window(vname,datefrom,dateto,lat,lng)
			{
				//alert(vname+" "+datetime+" "+lat+" "+lng);	
				//test2(vname,datetime,lat,lng);			
				document.getElementById("window").style.display = '';
				load_vehicle_on_map(vname,datefrom,dateto,lat,lng);							
			}*/  		
		</script>
		<link rel="StyleSheet" href="../css/newwindow.css">	
		<script language="javascript" src="src/js/drag.js"></script>   
		<script language="javascript" src="src/js/report.js"></script>	