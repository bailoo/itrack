<?php
$lat = $_REQUEST['lat'];
$lng = $_REQUEST['lng'];
//echo "lat=".$lat." ,lng=".$lng;
?>

<html>
<head>
  <script src="location_js/site.js" type="text/javascript"></script>
  
  <script src="http://www.google.com/uds/api?file=uds.js&amp;v=2.0&amp;key="AIzaSyDoOnQ2JW-xK4qX9Zj665Cgv5XWbQc-KBw" type="text/javascript"></script>
  <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
  <script type="text/javascript">
    /* <![CDATA[ */

    var map;
    window.onload = function() {
      var latlng = new google.maps.LatLng(54.559322587438636, -4.1748046875);
      var options = {
        zoom: 6,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };

      map = new google.maps.Map(document.getElementById("map"), options);
    }

    var splitData;
    var i=0;

    function Geocode(data)
    {
      alert("data="+data);
      // split data
      i=0;
      document.getElementById("geocodedPostcodes").value = "";
      //var data = document.getElementById("postcodes").value;
      //var data = <?php echo "'".$point."'"; ?>;
      //alert(data);
      splitData = data.split(":");
      GeocodeNext();        
    }

    var separator = ",";
    var delay = 0;
    function GeocodeNext(){
      $('#progress').html((i+1).toString() + " of " + splitData.length);

      var geocoder = new google.maps.Geocoder();
      var splitLatLng = splitData[i].replace("\r", "").split(",");
      // if no commas, try tab
      if (splitLatLng.length == 1) {
        splitLatLng = splitData[i].replace("\r", "").split("\t");
        separator = "\t";
      }
			else
				separator = ",";
      var latLng = new google.maps.LatLng(splitLatLng[0], splitLatLng[1]);
      geocoder.geocode({'latLng': latLng}, function(results, status) {
        alert(status);
        if (status == google.maps.GeocoderStatus.OK) {
          if ("res="+results) {
            var foundAddress = false;
            var user_lat = "";
            var user_lng = "";
            var google_lat = "";
            var google_lng = "";
            var distance = "";
			//getAddressComponent(result, "country");			
			alert("len="+results.length);
			for (var j=0; j<results.length; j++) {            
			  if ((results[j].types[0] == 'street_address') || (results[j].types[0] == 'route')) {
				
				//alert(results[j].geometry.location.lat());
				user_lat = latLng.lat();
				user_lng = latLng.lng();
				google_lat = results[j].geometry.location.lat();
				google_lng = results[j].geometry.location.lng();
    		
        var distance = calculate_distance(user_lat, google_lat, user_lng, google_lng); 
    		//alert("user_lat="+user_lat+" ,user_lng="+user_lng+" ,google_lat="+google_lat+" ,google_lng="+google_lng+" ,dist="+distance);
    		//var address2 = distance+" km from "+address1;					
				
				addLocation(latLng, results[j], distance);
                foundAddress = true;
                break;
              }
            }
            if (!foundAddress) {
              addLocation(latLng, results[0], distance);
            }
          }
        }
        else {
          var errorMsg = "Unknown error";
          switch (status) {
            case google.maps.GeocoderStatus.ZERO_RESULTS : errorMsg = "No results"; break;
            case google.maps.GeocoderStatus.OVER_QUERY_LIMIT : errorMsg = "Over query limit"; break;
            case google.maps.GeocoderStatus.REQUEST_DENIED : errorMsg = "Request denied"; break;
            case google.maps.GeocoderStatus.INVALID_REQUEST : errorMsg = "Invalid request"; break;
          }
          if (status != google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
            addResult(latLng, errorMsg, errorMsg);
          }
        }
        if (i < splitData.length-1) {
          if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
            delay += 100;
          }
          else {
            i++;
						if (delay > 0)
							delay -= 100;
          }
          //$('#delay').html("Delay is " + delay.toString() + " ms");
          //$('#delay').html("Delay is " + delay.toString() + " ms");
          setTimeout("GeocodeNext()", delay);
        }
        else {          
          //$('#progress').html("Complete");
          document.forms[0].submit();	
        }
      });
    }

		function getAddressComponent(result, component) {
			for (var i=0; i<result.address_components.length; i++) {
				var comp = result.address_components[i];
				for (j=0; j<comp.types.length; j++) {
					if (comp.types[j] == component)
						return comp.long_name;
				}
			}
			
			return "";
		}
		
		function buildAddress(result) {
			return getAddressComponent(result, "street_number") + separator + 
				getAddressComponent(result, "route") + separator +
				getAddressComponent(result, "postal_town") + separator +
				getAddressComponent(result, "administrative_area_level_2") + separator +
				getAddressComponent(result, "postal_code") + separator +
				getAddressComponent(result, "country");
				;
		}
		
    function addLocation(latLng, result, distance) {
			// add address components if selected
			var address = "\"" + result.formatted_address + "\"";
			//alert(address);
			//if ($("#addressComponents").is(":checked"))     //COMMENTED FOR TESTING
				//address = buildAddress(result);
			
			addResult(latLng, address, result.formatted_address, distance);
		}
			
		function addResult(latLng, address, formattedAddress, distance) {
      //alert("k");
      //document.getElementById("geocodedPostcodes").value +=
        //latLng.lat() + separator + latLng.lng() + separator + address + ":";
        alert(address);
        document.getElementById("geocodedPostcodes").value += distance + " km from " + address + ":";             
        /*var marker = new google.maps.Marker({
          position: latLng,
          map: map,
          title:formattedAddress
      }); */
    }
    
    function calculate_distance(lat1, lat2, lon1, lon2) 
    {
    	lat1 = (lat1/180)*Math.PI;
    	lon1 = (lon1/180)*Math.PI;
    	lat2 = (lat2/180)*Math.PI;
    	lon2 = (lon2/180)*Math.PI;
    	
    	var delta_lat = lat2 - lat1;
    	var delta_lon = lon2 - lon1;
    	var temp = Math.pow(Math.sin(delta_lat/2.0),2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(delta_lon/2.0),2);
    	
    	var distance = 3956 * 2 * Math.atan2(Math.sqrt(temp),Math.sqrt(1-temp));
    
    	distance = distance*1.609344;
    	distance=Math.round(distance*100)/100;
    	return distance;
    } 
    /* ]]> */
  </script>  

</head>

<body>

<?php        
   
echo'
  <form method="post" action="location_result.php" target="_self">
    
  <textarea id="geocodedPostcodes" name="geocodedPostcodes" cols="40" rows="20" style="visibility:hidden;"></textarea>
  ';
 
  //$lat = "26.45445";
  //$lng = "80.343243";
  //$point = $lat.",".$lng.":".$lat.",".$lng; 
  $point = '"';
  $coord = $lat.",".$lng;
  $point = $point.$coord;  
  $point = $point.'"';
          
  call_geocode($point);
  
  function call_geocode($point)
  {          
    echo '
    <script type="text/javascript">          
      Geocode('.$point.'); 
    </script>
    ';
  }          
    
  echo'   
  </form>

	<script type="text/javascript" language="javascript">
		//document.forms[0].submit();	
	</script>
	
</body>
</html>   
';
 
?>
