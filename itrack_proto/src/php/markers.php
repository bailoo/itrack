<script type="text/javascript">
var map;
var currentDate = new Date;
var label_type1= "<?php echo $report_type; ?>";
//alert("label_type1="+label_type1);
///// First Marker  ////
if(label_type1!="Person")
{
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
  
  var hvIcon4= new GIcon();
  hvIcon4.image = 'images/bus.png';
  hvIcon4.iconSize= new GSize(23, 20);
  //baseIcon.shadowSize= newGSize(37, 34);
  hvIcon4.iconAnchor= new GPoint(9, 34);
  hvIcon4.infoWindowAnchor= new GPoint(5, 1);
  
  var hvIcon5= new GIcon();
  hvIcon5.image = 'images/motorbike.png';
  hvIcon5.iconSize= new GSize(18, 26);
  //baseIcon.shadowSize= newGSize(37, 34);
  hvIcon5.iconAnchor= new GPoint(9, 34);
  hvIcon5.infoWindowAnchor= new GPoint(5, 1);    
  
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
  
  var station_icon= new GIcon();
  station_icon.image = 'images/station.png';
  station_icon.iconSize= new GSize(8, 8);
  station_icon.iconAnchor= new GPoint(7, 30);
  station_icon.infoWindowAnchor= new GPoint(5, 1);
  
  
  var iconYellow = new GIcon();    
  iconYellow.image = 'images/yellow_Marker1.png';
  iconYellow.iconSize = new GSize(12, 20);
  iconYellow.iconAnchor = new GPoint(6, 20);
  iconYellow.infoWindowAnchor = new GPoint(5, 1);  

  var iconRed = new GIcon(); 
  if(label_type1=="Person")
  {
  	iconRed.image = 'images/yellow_Marker1.png';
  }
  else
  {
  	iconRed.image = 'images/red_Marker1.png';
  }
  iconRed.iconSize = new GSize(12, 20);
  iconRed.iconAnchor = new GPoint(6, 20);
  iconRed.infoWindowAnchor = new GPoint(5, 1);
  
  var iconGreen = new GIcon(); 
  iconGreen.image = 'images/green_Marker1.png';
  iconGreen.iconSize = new GSize(12, 20);
  iconGreen.iconAnchor = new GPoint(6, 20);
  iconGreen.infoWindowAnchor = new GPoint(5, 1);
  
  var iconDot = new GIcon(); 
  iconDot.image = 'images/iconDot.gif';
  iconDot.iconSize = new GSize(0, 0);
  iconDot.iconAnchor = new GPoint(2, 18);
  iconDot.infoWindowAnchor = new GPoint(1, 1);
  
  var iconCurrent = new GIcon(); 
  iconCurrent.image = 'images/current_Marker.png';
  iconCurrent.iconSize = new GSize(17, 28);
  iconCurrent.iconAnchor = new GPoint(8, 25);
  iconCurrent.infoWindowAnchor = new GPoint(5, 1);
}
else
{
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
  lvIcon1.image = 'images/person_1.png';
  lvIcon1.iconSize= new GSize(28, 25);
  //baseIcon.shadowSize= newGSize(37, 34);
  lvIcon1.iconAnchor= new GPoint(9, 34);
  lvIcon1.infoWindowAnchor= new GPoint(5, 1);
  
  var lvIcon2= new GIcon();
  lvIcon2.image = 'images/person_1.png';
  lvIcon2.iconSize= new GSize(28, 25);
  //baseIcon.shadowSize= newGSize(37, 34);
  lvIcon2.iconAnchor= new GPoint(9, 34);
  lvIcon2.infoWindowAnchor= new GPoint(5, 1);
  
  var lvIcon3= new GIcon();
  lvIcon3.image = 'images/person_1.png';
  lvIcon3.iconSize= new GSize(28, 25);
  //baseIcon.shadowSize= newGSize(37, 34);
  lvIcon3.iconAnchor= new GPoint(9, 34);
  lvIcon3.infoWindowAnchor= new GPoint(5, 1);
  
  ////// heavy vehicles icons //////////////////
  var hvIcon1= new GIcon();
  hvIcon1.image = 'images/person_1.png';
  hvIcon1.iconSize= new GSize(28, 25);
  //baseIcon.shadowSize= newGSize(37, 34);
  hvIcon1.iconAnchor= new GPoint(9, 34);
  hvIcon1.infoWindowAnchor= new GPoint(5, 1);
  
  var hvIcon2= new GIcon();
  hvIcon2.image = 'images/person_1.png';
  hvIcon2.iconSize= new GSize(28, 25);
  //baseIcon.shadowSize= newGSize(37, 34);
  hvIcon2.iconAnchor= new GPoint(9, 34);
  hvIcon2.infoWindowAnchor= new GPoint(5, 1);
  
  var hvIcon3= new GIcon();
  hvIcon3.image = 'images/person_1.png';
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
  iconYellow.image = 'images/person_1.png';
  iconYellow.iconSize = new GSize(12, 20);
  iconYellow.iconAnchor = new GPoint(6, 20);
  iconYellow.infoWindowAnchor = new GPoint(5, 1);  
  
  var iconRed = new GIcon(); 
  if(label_type1=="Person")
  {
  	iconRed.image = 'images/person_1.png';
  }
  else
  {
  	iconRed.image = 'images/person_1.png';
  }
  iconRed.iconSize = new GSize(12, 20);
  iconRed.iconAnchor = new GPoint(6, 20);
  iconRed.infoWindowAnchor = new GPoint(5, 1);
  
  var iconGreen = new GIcon(); 
  iconGreen.image = 'images/person_1.png';
  iconGreen.iconSize = new GSize(12, 20);
  iconGreen.iconAnchor = new GPoint(6, 20);
  iconGreen.infoWindowAnchor = new GPoint(5, 1);
  
  var iconDot = new GIcon(); 
  iconDot.image = 'images/person_1.png';
  iconDot.iconSize = new GSize(0, 0);
  iconDot.iconAnchor = new GPoint(2, 18);
  iconDot.infoWindowAnchor = new GPoint(1, 1);
  
  var iconCurrent = new GIcon(); 
  iconCurrent.image = 'images/person_1.png';
  iconCurrent.iconSize = new GSize(17, 28);
  iconCurrent.iconAnchor = new GPoint(8, 25);
  iconCurrent.infoWindowAnchor = new GPoint(5, 1);
}

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

//alert("please wait..");
function show_balloon_marker(marker_number)
{ 
  //alert("m="+marker_number);  
  if(marker_number == 1)
  {
    if(document.forms[0].m1.value == 1)
    {    
      document.forms[0].m1.value = 0;
      document.getElementById('m1_on').style.display='none';
      document.getElementById('m1_off').style.display='';      
    }
    else if(document.forms[0].m1.value == 0)
    {
      document.forms[0].m1.value = 1;
      document.getElementById('m1_on').style.display='';
      document.getElementById('m1_off').style.display='none'; 
    }        
    //alert(document.forms[0].m1.value);  
  }
  else if(marker_number == 2)
  {
    if(document.forms[0].m2.value == 1)
    {    
      document.forms[0].m2.value = 0;
      document.getElementById('m2_on').style.display='none';
      document.getElementById('m2_off').style.display='';      
    }
    else if(document.forms[0].m2.value == 0)
    {
      document.forms[0].m2.value = 1;
      document.getElementById('m2_on').style.display='';
      document.getElementById('m2_off').style.display='none'; 
    }        
    //alert(document.forms[0].m2.value);    
  }
  else if(marker_number == 3)
  {
    if(document.forms[0].m3.value == 1)
    {    
      document.forms[0].m3.value = 0;
      document.getElementById('m3_on').style.display='none';
      document.getElementById('m3_off').style.display='';      
    }
    else if(document.forms[0].m3.value == 0)
    {
      document.forms[0].m3.value = 1;
      document.getElementById('m3_on').style.display='';
      document.getElementById('m3_off').style.display='none'; 
    }        
    //alert(document.forms[0].m3.value);  
  }
  else if(marker_number == 4)
  {
    if(document.forms[0].m4.value == 1)
    {    
      document.forms[0].m4.value = 0;
      document.getElementById('m4_on').style.display='none';
      document.getElementById('m4_off').style.display='';      
    }
    else if(document.forms[0].m4.value == 0)
    {
      document.forms[0].m4.value = 1;
      document.getElementById('m4_on').style.display='';
      document.getElementById('m4_off').style.display='none'; 
    }        
    //alert(document.forms[0].m4.value);  
  }    
} 

</script>