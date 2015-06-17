<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<meta charset="utf-8">
<title>Google Maps JavaScript API v2 Example: Reverse Geocoding</title>
<link href="/maps/documentation/javascript/examples/default.css" rel="stylesheet">

<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=AIzaSyA-kOZyS1aA1dcgedX-GFniccAOPIAOv2c" type="text/javascript"></script>
<!--<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>-->
<script type="text/javascript" src="../../src/js/reverse_geo.js"></script>

</head>

<body>

<textarea id="tmp_location"></textarea>
<textarea id="test_str"></textarea>
<input type="text" id="status"/>

<?php
  $lat = "26.8076";
  $lng = "80.4543";
  
  for($i=0;$i<100;$i++)
  {
    $lat = "26.807".$i;
    $lng = "80.454".$i;
    //echo "hello".$i."\n";  
    get_js_location($i,$lat,$lng);
    //usleep(1000000);  //1 sec
    usleep(1000000);    //1 milli sec
    ob_flush();
    flush();
  }
  usleep(2000000);    //1 milli sec
  ob_flush();
  flush();
  print_js_location();
   
  
  function print_js_location()
  {
     echo '<script type="text/javascript">     
      alert(document.getElementById("tmp_location").value);   
    </script>';  
  }
  function get_js_location($i,$lat,$lng)
  {
    
    echo '<script type="text/javascript">     
      document.getElementById("test_str").value = null;
      codeLatLng('.$lat.','.$lng.','.$i.');
    </script>';    
  }
?>
</body>

</html>