<!DOCTYPE html>
<html>
  <head>
  <script src="js/site.js" type="text/javascript"></script>
  
  <script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key="AIzaSyDoOnQ2JW-xK4qX9Zj665Cgv5XWbQc-KBw" type="text/javascript"></script>
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
      //alert("data="+data);
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
        if (status == google.maps.GeocoderStatus.OK) {
          if (results) {
            var foundAddress = false;
            for (var j=0; j<results.length; j++) {
              if ((results[j].types[0] == 'street_address') || (results[j].types[0] == 'route')) {
                addLocation(latLng, results[j]);
                foundAddress = true;
                break;
              }
            }
            if (!foundAddress) {
              addLocation(latLng, results[0]);
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
          $('#delay').html("Delay is " + delay.toString() + " ms");
          setTimeout("GeocodeNext()", delay);
        }
        else {
          $('#progress').html("Complete");
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
		
    function addLocation(latLng, result) {
			// add address components if selected
			var address = "\"" + result.formatted_address + "\"";
			//if ($("#addressComponents").is(":checked"))     //COMMENTED FOR TESTING
				//address = buildAddress(result);
			
			addResult(latLng, address, result.formatted_address);
		}
			
		function addResult(latLng, address, formattedAddress) {
      document.getElementById("geocodedPostcodes").value +=
        latLng.lat() + separator + latLng.lng() + separator + address + "\n";
        var marker = new google.maps.Marker({
          position: latLng,
          map: map,
          title:formattedAddress
      });
    }
    /* ]]> */
  </script>  
  </head>
  
<body> 
  <table>
    <tr>      
      <td>
        <!--<input type="submit" onclick="Geocode('26.34324,80.3434')" value="Reverse geocode" />-->
        <div id="progress"></div>
        <div id="delay"></div>
      </td>
      <td>
        <textarea id="geocodedPostcodes" cols="40" rows="20"></textarea>
      </td>
          
      <?php
         $point = '"';
         for($i=0;$i<50;$i++)
         {
            $lat = "26.34324".$i;
            $lng = "80.34324".$i;
            $coord = $lat.",".$lng; 
            
            if($i==0)
            {
              //echo "zero<br>" ;
              $point = $point.$coord;   
            }
            else
            {
              //echo "else<br>" ;
              $point = $point.":".$coord;   
            }
         }         
         $point = $point.'"';
         //echo $point;
         
         call_geocode($point);
         
         function call_geocode($point)
         {          
          echo '
            <script type="text/javascript">          
              Geocode('.$point.'); 
            </script>
          ';
         }          
      ?>      
      
    </tr>
  </table>



 <br />

 </div>
 </body>
 </html>
