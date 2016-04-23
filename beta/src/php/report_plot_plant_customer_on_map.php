<html>  
    <head>      
        <?php
        // include('main_google_key.php');
        /*echo '<style>
        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 300px;
        }
        #pac-input:focus {
            border-color: #4d90fe;
        }
        .pac-container {
          font-family: Roboto;
        }
        </style>';*/
        echo '<style>
        .tooltip {
            position: relative;
            display: inline-block;
            border-bottom: 1px dotted black;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 125px;
            height: 40px;
            background-color: black;
            color: #fff;
            text-align: left;
            border-radius: 2px;
            padding: 2px 0;

            /* Position the tooltip */
            position: absolute;
            z-index: 1;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
        }
        </style>';
       
        include('route_map_js_css.php');
        include('route_common_js_css.php');
        include('util_calculate_distance_js.php');
        //echo"<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>";
        //echo"<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places'></script>";
        echo "<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>";
        echo '<script type="text/javascript" src="src/js/jquery-1.3.2"></script>';
        echo '<script type="text/javascript" src="src/js/markerwithlabel.js"></script>';

        //include('customerPlantGoogleMapApi.php');
        //include('main_frame_part1.php');          
        ?>  

        <script type="text/javascript">
            var gmarkers = [];
            function initialize(customers_json, plants_json) {
            //alert(customers_json);
            var customerArr= JSON.parse(customers_json);
            var plantArr= JSON.parse(plants_json);

            //alert(customerArr);
            var mapOptions = {
                    zoom: 10,
                    center: new google.maps.LatLng(20.5937, 78.9629),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
            }                                                         
            var map = new google.maps.Map(document.getElementById("googlemap"), mapOptions);

            /*var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
              searchBox.setBounds(map.getBounds());
            }); */ 
            /*var locations = [
                ['New York', 40.714364, -74.005972, 'http://www.google.com/intl/en_us/mapfiles/ms/micons/green-dot.png'],
            ['Hudson River, near Holland Tunnel<br>(New York)', 40.724364, -74.015972, 'http://www.google.com/intl/en_us/mapfiles/ms/micons/yellow-dot.png']
            ];*/
            //alert("K");

            var marker, i;
            var customer_plant_list = '';
            var infowindow = new google.maps.InfoWindow();


            google.maps.event.addListener(map, 'click', function() {
                infowindow.close();
            });
            i=0;
            
            var latlngbounds = new google.maps.LatLngBounds();
            //###### CUSTOMER DATA
            for (var key in customerArr)
            {
                    //alert(key + ' --> ' + customerArr[key]);
                    var splitCustomerData=customerArr[key].split('^');
                    //var position;
                    var lat = splitCustomerData[0];
                    var lng = splitCustomerData[1];
                    var stationName = splitCustomerData[2];
                    var type = splitCustomerData[3];
                    var icon = splitCustomerData[4];
                //alert("TYPE="+type);
                //for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    //position: new google.maps.LatLng(customerArr[i][1], customerArr[i][2]),
                    position: new google.maps.LatLng(lat, lng),                    
                    map: map,
                    //icon: 'http://www.google.com/intl/en_us/mapfiles/ms/micons/green-dot.png'
                    icon: icon
                });
                
                var latlngobj = new google.maps.LatLng(splitCustomerData[0], splitCustomerData[1]);						
		latlngbounds.extend(latlngobj);                

                gmarkers.push(marker);
                
                //#### URL STRING 
                var color = '#000000';
                
                if(type == 'MS') {
                   color = '#269900';
                } else if (type == 'KISOK') {
                   color = '#990099';
                } else if (type == 'INSTITUTION') {
                   color = '#000099'; 
                } else if (type == 'FS') {
                   color = '#990000'; 
                } else if (type == 'DISTRIBUTOR') {
                   color = '#007399'; 
                }
                
                customer_plant_list += "<a href=\"javascript:google.maps.event.trigger(gmarkers["+i+"],'click');\"><font color="+color+"><strong>"+key+"</strong></font>&nbsp;(<font color=#000000>"+stationName+"</font>)</a><br>";

                google.maps.event.addListener(marker, 'click', (function(marker,key,lat,lng,stationName) {
                    return function() {

                    var station_no_tag = 'CustomerNo';
                    var station_name_tag = 'CustomerName';
                                     
                        //infowindow.setContent(locations[i][0]);
                    var contentString='<table border=\"0\">'+                                               
                                               '<tr>'+										
                                                    '<td><strong>'+station_no_tag+'</strong></td>'+
                                                    '<td><strong>:</strong></td>'+
                                                    '<td><font color=red><strong>'+key+'</strong></font></td>'+
                                               '</tr>'+	
                                                    '<tr><strong>'+										
                                                    '<td><strong>'+station_name_tag+'</strong></td>'+
                                                    '<td><strong>:</strong></td>'+
                                                    '<td><font color=blue><strong>'+stationName+'</strong></font></td>'+
                                               '</tr>'+
                                                '<tr>'+
                                                    '<td><strong>Latitude</strong></td>'+
                                                    '<td><strong>:</strong></td>'+
                                                    '<td><font color=blue><strong>'+lat+'</strong></font></td>'+
                                               '</tr>'+
                                               '<tr>'+
                                                    '<td><strong>Longitude</strong></td>'+
                                                    '<td>:</strong></td>'+
                                                    '<td><font color=blue><strong>'+lng+'</strong></font></td>'+
                                               '</tr>'+                                               
                                        '</table>';		

                        infowindow.setContent(contentString);                  
                        infowindow.open(map, marker);                   
                    }
                })(marker, key,lat,lng,stationName));

                 i++;
            }
            
            
            //###### PLANT DATA
            for (var key in plantArr)
            {
                    //alert(key + ' --> ' + plantArr[key]);
                    var splitPlantData=plantArr[key].split('^');
                    //var position;
                    var lat = splitPlantData[0];
                    var lng = splitPlantData[1];
                    var plantName = splitPlantData[2];
                    var type = splitPlantData[3];
                    var icon = splitPlantData[4];

                //for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    //position: new google.maps.LatLng(customerArr[i][1], customerArr[i][2]),
                    position: new google.maps.LatLng(lat, lng),
                    map: map,
                    //icon: 'http://www.google.com/intl/en_us/mapfiles/ms/micons/green-dot.png'
                    icon: icon
                });

                var latlngobj = new google.maps.LatLng(splitCustomerData[0], splitCustomerData[1]);						
		latlngbounds.extend(latlngobj);
                
                gmarkers.push(marker);
                
                //#### URL STRING 
                var color = '#ff0000';                
                
                customer_plant_list += "<a href=\"javascript:google.maps.event.trigger(gmarkers["+i+"],'click');\"><font color="+color+"><strong>"+key+"</strong></font>&nbsp;(<font color=#000000>"+plantName+"</font>)</a><br>";

                google.maps.event.addListener(marker, 'click', (function(marker,key,lat,lng,plantName) {
                    return function() {

                    var plant_no_tag = 'PlantNo';
                    var plant_name_tag = 'PlantName';				                   
                        //infowindow.setContent(locations[i][0]);
                    var contentString='<table border=\"0\">'+
                                               '<tr>'+										
                                                    '<td><strong>'+plant_no_tag+'</strong></td>'+
                                                    '<td><strong>:</strong></td>'+
                                                    '<td><font color=red><strong>'+key+'</strong></font></td>'+
                                               '</tr>'+	
                                                    '<tr>'+										
                                                    '<td><strong>'+plant_name_tag+'</strong></td>'+
                                                    '<td><strong>:</strong></td>'+
                                                    '<td><font color=blue><strong>'+plantName+'</strong></font></td>'+
                                               '</tr>'+
                                               '<tr>'+
                                                    '<td><strong>Latitude</strong></td>'+
                                                    '<td><strong>:</strong></td>'+
                                                    '<td><font color=blue><strong>'+lat+'</strong></font></td>'+
                                               '</tr>'+
                                               '<tr>'+
                                                    '<td><strong>Longitude</strong></td>'+
                                                    '<td><strong>:</strong></td>'+
                                                    '<td><font color=blue><strong>'+lng+'</strong></font></td>'+
                                               '</tr>'+                                               
                                        '</table>';		

                        infowindow.setContent(contentString);                  
                        infowindow.open(map, marker);                   
                    }
                })(marker, key,lat,lng,plantName));

                 i++;
            }            
            
            map.setCenter(latlngbounds.getCenter());					
            /*if(latarr.length>0 && latarr.length<2)
            {
                map.setZoom(10);
            }
            else
            {*/
                map.fitBounds(latlngbounds);
            //}            
            //####### PLOT URL STRING
            //alert(customer_plant_list);
            document.getElementById('customer_plant_id').innerHTML = customer_plant_list;            
        }
        google.maps.event.addDomListener(window, 'load', initialize);
        //#### GOOGLE MAP CLOSED
    
        function ChangeText(oFileInput, sTargetID) {
            document.getElementById(sTargetID).value = oFileInput.value;
        }

        function show_hide_option(type) {
            var id = type;
            var row = type + "_row";
            //alert(id+" ,"+row);
            if (document.getElementById(id).checked) {
                document.getElementById(row).style.display = '';
            } else {
                document.getElementById(row).style.display = 'none';
            }
        }

        function selectUnselectAll() {
            if (document.forms[0].All.checked) {
                document.forms[0].MS.checked = true;
                document.forms[0].KISOK.checked = true;
                document.forms[0].INSTITUTION.checked = true;
                document.forms[0].FS.checked = true;
                document.forms[0].DISTRIBUTOR.checked = true;
                document.forms[0].PLANTS.checked = true;
            } else if (!document.forms[0].All.checked) {
                document.forms[0].MS.checked = false;
                document.forms[0].KISOK.checked = false;
                document.forms[0].INSTITUTION.checked = false;
                document.forms[0].FS.checked = false;
                document.forms[0].DISTRIBUTOR.checked = false;
                document.forms[0].PLANTS.checked = false;
            }
        }

        /*function show_customer_plant_list() {
         var request = $.ajax({

         url: "src/php/show_transporters.php",
         type: "POST",
         data: "city_id_source="+city_id_source+"&city_id_dest="+city_id_dest+"&city_source="+city_source+"&city_dest="+city_dest+"&door_close="+door_close+"&refrigerated="+refrigerated+"&date1="+date1+"&category_id="+category_id+"&category="+category+"&item="+item+"&capacity="+capacity+"&vehicle_count="+vehicle_count,
         dataType: "html"
         });

         request.done(function(msg) {
         $("#transporter_box").html(msg);
         });

         request.fail(function(jqXHR, textStatus) {
         alert("Request failed: " + textStatus);
         });
         }*/
       </script>  
    </head>
    <body class="body_part" topmargin="0"  onload="javascript:resize('home')"  onresize="javascript:resize('home')">

        <?php
        $flag_station = 0;
        $flag_visit_track = 0;

        for ($k = 0; $k < $size_feature_session; $k++) {
            //$feature_id_session[$k];
            if ($feature_name_session[$k] == "station") {
                $flag_station = 1;
                break;
            }
            //echo "<br>feature_name=".$feature_name_session[$k];
        }
        for ($k = 0; $k < $size_feature_session; $k++) {
            //$feature_id_session[$k];
            if ($feature_name_session[$k] == "visit_track") {
                $flag_visit_track = 1;
                break;
            }
            //echo "<br>feature_name=".$feature_name_session[$k];
        }

        //$flag_station = 1;

        if ($flag_station) {
            echo '<input type="hidden" id="station_flag_map" value="1"/>';
        } else {
            echo '<input type="hidden" id="station_flag_map" value="0"/>';
        }

        if ($flag_visit_track) {
            echo '<input type="hidden" id="schedule_location_flag" value="1"/>';
        } else {
            echo '<input type="hidden" id="schedule_location_flag" value="0"/>';
        }
        $flag_chilling_plant = 0;
        for ($k = 0; $k < $size_feature_session; $k++) {
            //echo $feature_name_session[$k]."<br>";
            if ($feature_name_session[$k] == "chilling_plant") {
                $flag_chilling_plant = 1;
            }
        }
        //echo "cPlant=".$flag_chilling_plant."<br>";
        echo '<input type="hidden" id="customer_list"/>';

        include('main_frame_part2.php');
        include('plant_customer_module_frame_header.php');
        //include('module_frame_header.php');
        include('main_frame_part3_plant.php');
        //include('route_module_home_menu.php');
        //include('main_frame_part4.php');
        include('module_customer_plant_home_body.php');
        include('module_filter_customer_plant.php');
        echo '<div id="map_home"/>';
        include('main_frame_part5.php');
                
        echo '<script type="text/javascript">
            initialize('.$cArr.','.$pArr.');
        </script>';

        ?>
    </body>

</html>