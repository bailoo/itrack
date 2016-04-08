
<style>
   #map-canvas {
    height: 100%;
    margin: 0px;
    padding: 0px
  }
</style>
  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>


// If you're adding a number of markers, you may want to
// drop them on the map consecutively rather than all at once.
// This example shows how to use setTimeout() to space
// your markers' animation.

var india = new google.maps.LatLng(23.2599333, 77.41261499999996);

var neighborhoods = [
  new google.maps.LatLng(25.61046, 85.14166699999998), //patna
  new google.maps.LatLng(19.8133822, 85.83146549999992), //puri
  new google.maps.LatLng(17.385044, 78.486671),//hydrabad
  new google.maps.LatLng(23.2599333, 77.41261499999996) //bhopal
];

var markers = [];
var iterator = 0;

var map;

function initialize() {
  var mapOptions = {
    zoom: 5,
    center: india
  };

  map = new google.maps.Map(document.getElementById('map-canvas'),
          mapOptions);
}

function drop() {
  for (var i = 0; i < neighborhoods.length; i++) {
    setTimeout(function() {
      addMarker();
    }, i * 200);
  }
}

function addMarker() {
  markers.push(new google.maps.Marker({
    position: neighborhoods[iterator],
    map: map,
    draggable: false,
    animation: google.maps.Animation.DROP
  }));
  iterator++;
}

google.maps.event.addDomListener(window, 'load', initialize);



    </script>
