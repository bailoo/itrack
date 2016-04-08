var map;
var currentDate = new Date;

var lnmark= new GIcon();
lnmark.image = 'images/landmark.png';
lnmark.iconSize= new GSize(10, 10);
//baseIcon.shadowSize= newGSize(37, 34);
lnmark.iconAnchor= new GPoint(2, 2);
lnmark.infoWindowAnchor= new GPoint(5, 1);

var baseIcon= new GIcon();
baseIcon.image = 'truck.png';
baseIcon.iconSize= new GSize(28, 25);
//baseIcon.shadowSize= newGSize(37, 34);
baseIcon.iconAnchor= new GPoint(9, 34);
baseIcon.infoWindowAnchor= new GPoint(5, 1);


var arrowIcon = new GIcon();
arrowIcon.iconSize = new GSize(24,24);
//arrowIcon.shadowSize = new GSize(1,1);
arrowIcon.iconAnchor = new GPoint(12,12);
arrowIcon.infoWindowAnchor = new GPoint(0,0);

var degreesPerRadian = 180.0 / Math.PI;


var iconYellow = new GIcon();    
iconYellow.image = 'yellow_Marker1.png';
//iconBlue.shadow = '';
iconYellow.iconSize = new GSize(12, 20);
//iconBlue.shadowSize = new GSize(22, 20);
iconYellow.iconAnchor = new GPoint(6, 20);
iconYellow.infoWindowAnchor = new GPoint(5, 1);  


var iconRed = new GIcon(); 
iconRed.image = 'red_Marker1.png';
//iconRed.shadow = '';
iconRed.iconSize = new GSize(12, 20);
//iconRed.shadowSize = new GSize(22, 20);
iconRed.iconAnchor = new GPoint(6, 20);
iconRed.infoWindowAnchor = new GPoint(5, 1);


var iconGreen = new GIcon(); 
iconGreen.image = 'green_Marker1.png';
// iconRed.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
iconGreen.iconSize = new GSize(12, 20);
//iconGreen.shadowSize = new GSize(22, 20);
iconGreen.iconAnchor = new GPoint(6, 20);
iconGreen.infoWindowAnchor = new GPoint(5, 1);


var iconCurrent = new GIcon(); 
iconCurrent.image = 'current_Marker.png';
//iconRed.shadow = '';
iconCurrent.iconSize = new GSize(17, 28);
//iconRed.shadowSize = new GSize(22, 20);
iconCurrent.iconAnchor = new GPoint(8, 25);
iconCurrent.infoWindowAnchor = new GPoint(5, 1);