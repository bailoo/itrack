var map;
var currentDate = new Date;

///// First Marker  ////
var startIcon= new GIcon();
startIcon.image = 'images/start_marker.png';
startIcon.iconSize= new GSize(20, 34);
//baseIcon.shadowSize= newGSize(37, 34);
startIcon.iconAnchor= new GPoint(9, 34);
startIcon.infoWindowAnchor= new GPoint(5, 1);

/// Last Marker ///////
var stopIcon= new GIcon();
stopIcon.image = 'images/stop_marker.png';
stopIcon.iconSize= new GSize(20, 34);
//baseIcon.shadowSize= newGSize(37, 34);
stopIcon.iconAnchor= new GPoint(9, 34);
stopIcon.infoWindowAnchor= new GPoint(5, 1);


///////////////////// light vehicles icons ////////////////
var lvIcon1= new GIcon();
lvIcon1.image = 'images/light_v1.png';
lvIcon1.iconSize= new GSize(28, 25);
//baseIcon.shadowSize= newGSize(37, 34);
lvIcon1.iconAnchor= new GPoint(9, 34);
lvIcon1.infoWindowAnchor= new GPoint(5, 1);

var lvIcon2= new GIcon();
lvIcon2.image = 'images/light_v2.png';
lvIcon2.iconSize= new GSize(28, 25);
//baseIcon.shadowSize= newGSize(37, 34);
lvIcon2.iconAnchor= new GPoint(9, 34);
lvIcon2.infoWindowAnchor= new GPoint(5, 1);

var lvIcon3= new GIcon();
lvIcon3.image = 'images/light_v3.png';
lvIcon3.iconSize= new GSize(28, 25);
//baseIcon.shadowSize= newGSize(37, 34);
lvIcon3.iconAnchor= new GPoint(9, 34);
lvIcon3.infoWindowAnchor= new GPoint(5, 1);

////// heavy vehicles icons //////////////////
var hvIcon1= new GIcon();
hvIcon1.image = 'images/heavy_v1.png';
hvIcon1.iconSize= new GSize(28, 25);
//baseIcon.shadowSize= newGSize(37, 34);
hvIcon1.iconAnchor= new GPoint(9, 34);
hvIcon1.infoWindowAnchor= new GPoint(5, 1);

var hvIcon2= new GIcon();
hvIcon2.image = 'images/heavy_v2.png';
hvIcon2.iconSize= new GSize(28, 25);
//baseIcon.shadowSize= newGSize(37, 34);
hvIcon2.iconAnchor= new GPoint(9, 34);
hvIcon2.infoWindowAnchor= new GPoint(5, 1);

var hvIcon3= new GIcon();
hvIcon3.image = 'images/heavy_v3.png';
hvIcon3.iconSize= new GSize(28, 25);
//baseIcon.shadowSize= newGSize(37, 34);
hvIcon3.iconAnchor= new GPoint(9, 34);
hvIcon3.infoWindowAnchor= new GPoint(5, 1);

////////////////////////////////////////////

var arrowIcon = new GIcon();
arrowIcon.iconSize = new GSize(24,24);
arrowIcon.iconAnchor = new GPoint(12,12);
arrowIcon.infoWindowAnchor = new GPoint(0,0);

var degreesPerRadian = 180.0 / Math.PI;

var lnmark= new GIcon();
lnmark.image = 'images/landmark.png';
lnmark.iconSize= new GSize(10, 10);
lnmark.iconAnchor= new GPoint(9, 34);
lnmark.infoWindowAnchor= new GPoint(5, 1);

var iconYellow = new GIcon();    
iconYellow.image = 'images/yellow_Marker1.png';
iconYellow.iconSize = new GSize(12, 20);
iconYellow.iconAnchor = new GPoint(6, 20);
iconYellow.infoWindowAnchor = new GPoint(5, 1);  

var iconRed = new GIcon(); 
iconRed.image = 'images/red_Marker1.png';
iconRed.iconSize = new GSize(12, 20);
iconRed.iconAnchor = new GPoint(6, 20);
iconRed.infoWindowAnchor = new GPoint(5, 1);

var iconGreen = new GIcon(); 
iconGreen.image = 'images/green_Marker1.png';
iconGreen.iconSize = new GSize(12, 20);
iconGreen.iconAnchor = new GPoint(6, 20);
iconGreen.infoWindowAnchor = new GPoint(5, 1);

var iconCurrent = new GIcon(); 
iconCurrent.image = 'images/current_Marker.png';
iconCurrent.iconSize = new GSize(17, 28);
iconCurrent.iconAnchor = new GPoint(8, 25);
iconCurrent.infoWindowAnchor = new GPoint(5, 1);

var add;

var geocoder = null;
var address = null;

var rg;
var lm;
lm=0;
var a;
//alert("lm="+lm);

var ge;
//google.load("maps", "2.x");