<p id="prepage" style="position:absolute; font-family:arial; font-size:16; left:48%; top:290px; layer-background-color:#e5e3df; height:10%; width:20%; visibility:hidden"><img src="../../../images/loading_live_vehicles.gif"><!--<img src="images/load_data.gif">-->		
</p>
<?php	
	//include_once('util_session_variable_consignment.php');
	include_once('util_php_mysql_connectivity.php');
	$docket_no=$_POST['docket_no'];
	//echo "dd=".$docket_no."<br>";
	$query_validate="SELECT  device_imei_no,vehicle_name,from_place,to_place,consignee_name,start_date,end_date FROM consignment_info where docket_no='$docket_no' AND '$date' BETWEEN".
                        " start_date AND end_date AND status=1";
	//echo "query=". $query_validate."<br>";
	$result_validate=mysql_query($query_validate,$DbConnection);
	$num_rows=mysql_num_rows($result_validate);
        $flag=0;
	if($num_rows==0)
	{
               echo "<table align='center' height='45%'>
                            <tr>
                                <td height='15%'>
                                </td>
                             </tr>
                             <tr>
                                <td>
                                    <b>
                                        <font color='red'>
                                            Docket Number Expired
                                        </font>
                                   </b>
                                </td>
                               </tr>
                     </table>";                 
	}
	else
	{
                $flag=1;
		$Row=mysql_fetch_row($result_validate);
		$device_imei_no=$Row[0];
                $vehicle_name=$Row[1];
                $from_place=$Row[2];
                $to_place=$Row[3];
                $consignee_name=$Row[4];
                $start_date=$Row[5];
                $end_date=$Row[6]; 
        }
   if($flag==1) // if docket number not expired then data will bi displayed
   {
include_once("user_type_setting.php"); 
?>

<html>  
  <head>      
     <?php  
        include('main_google_key.php');
       ?>
      <script>
          document.getElementById('prepage').style.visibility='visible';
          </script>
    	<link rel="shortcut icon" href="images/iesicon.ico">
<link rel="StyleSheet" href="src/css/menu.css">	
<link rel="StyleSheet" href="src/css/module_hide_show_div.css">	
<script type="text/javascript" src="src/js/menu.js"></script>
<script type="text/javascript" src="src/js/jquery.js"></script> 

<script language="javascript" src="src/js/ajax.js"></script>

<script language="javascript" src="src/js/elabel.js"></script>

<script type="text/javascript" src="src/dragzoom/gzoom.js"></script> 

<script type="text/javascript">
	document.write('<script type="text/javascript" src="src/js/extlargemapcontrol'+(document.location.search.indexOf('packed')>-1?'_packed':'')+'.js"><'+'/script>');
function initialize() 
{
	//alert("initialize");
  document.getElementById("map").style.display="";
	//alert(document.getElementById("map").style.display);
	
  if (GBrowserIsCompatible())
	{	  
		//alert("is compatible");
    map = new GMap2(document.getElementById("map"));		
	
		//alert("map:"+map);
    //var mining_test=document.getElementById("category").value;
		//alert("test"+mining_test);
		/*if(mining_test=='5' || (document.getElementById("mining_user").value==5))
		{show_milestones();}
		else /////// for other users   */
		//{
			map.setCenter(new GLatLng(23.674712836608773, 77.783203125), 5);
			map.enableContinuousZoom();
		//}			  
		/*if(document.thisform.GEarthStatus.value == 1) //////////for google earth
		{
			var mapui = map.getDefaultUI();
			mapui.maptypes.physical = false;
			map.setUI(mapui);			
			map.removeMapType(G_SATELLITE_MAP);
			map.removeMapType(G_HYBRID_MAP);			
			map.setMapType(G_SATELLITE_3D_MAP);
		}*/	
		//else ///////// for other format
		{		
			map.removeMapType(G_SATELLITE_MAP);			
			map.addControl(new GOverviewMapControl());			
			map.addMapType(G_SATELLITE_MAP);	
			//map.addMapType(G_SATELLITE_3D_MAP);
			var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10,10));			
			var mapControl = new GMapTypeControl();
			map.addControl(mapControl, topRight);				
			var opts2 =
			{
			  zoomInBtnTitle : "zoomIn",
			  zoomOutBtnTitle : "zoomOut",
			  moveNorthBtnTitle : "moveNorth",
			  moveSouthBtnTitle : "moveSouth",
			  moveEastBtnTitle : "moveEast",
			  moveWestBtnTitle : "moveWest",
			  homeBtnTitle : "home"
			};
			
			/*var extLargeMapControl2 = new ExtLargeMapControl(opts2);
			map.addControl(extLargeMapControl2);*/		

			/*var boxStyleOpts1 = 
			{
				opacity: .2,
				border: "2px solid red"
			}

			var otherOpts1 = 
			{
				buttonHTML: "<img src='src/dragzoom/zoom-button.gif' />",
				buttonZoomingHTML: "<img src='src/dragzoom/zoom-button-activated.gif' />",
				buttonStartingStyle: {width: '24px', height: '24px'}
			};

			var callbacks =
			{
				buttonclick: function(){GLog.write("Looks like you activated DragZoom!")},
				dragstart: function(){GLog.write("Started to Drag . . .")},
				dragging: function(x1,y1,x2,y2){GLog.write("Dragging, currently x="+x2+",y="+y2)},
				dragend: function(nw,ne,se,sw,nwpx,nepx,sepx,swpx){GLog.write("Zoom! NE="+ne+";SW="+sw)}
			};	

			map.addControl(new GScaleControl()) ; 
			//alert("before search");
			//var search=map.addControl(new google.maps.LocalSearch(), new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(5,50)));
			var bottomLeft = new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(10,10));	
			map.addControl(new DragZoomControl(boxStyleOpts1, otherOpts1), new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(65,65)));	
			
			//map.addControl(new DragZoomControl(boxStyleOpts, otherOpts), new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(65,65)));
			GEvent.addListener(map, 'zoomend',function (oldzoomlevel,newzoomlevel) 
			{
				var bounds = map.getBounds();
			});		
*/
			if(navigator.userAgent.indexOf("Firefox")==-1)
			{				
				//GSearch.setOnLoadCallback(initialize);
			}			
		} 										  
	}
}


</script>
<script language="javascript" src="src/js/labeledmarker.js"></script>
<script language="javascript" src="src/js/pdmarker.js"></script>
<script language="javascript" src="src/js/toggle_panel.js"></script>


<style type="text/css">

.style1 {
font-size:10px;background-color:green;color:#ffffff;
}

.style2 {
font-size:10px;background-color:orange;color:#ffffff;
}
.style3 {
font-size:10px;background-color:red;color:#ffffff;
}
    
.divm {
 position:absolute;
 top:50%;
 right:50%;
 width:100px;
}

@media print 
	{
		.noprint
		{
			display: none;
		}
	}
	@media screen
	{ 
		.noscreen
		{ 
			display: none; 
		} 
	}

  .normal1 { background-color: #F8F8FF }
  .highlight1 { background-color: #C6DEFF }

  .normal2 { background-color: #FFFDF9 }
  .highlight2 { background-color: #C6DEFF }
 </style>
<script type="text/javascript">
	
  function resize()
	{ 
  		var dv = document.getElementById("map");    
  		divHeight =  $(window).height();
  		dv.style.height = divHeight - 30; 		
	}
</script>

  </head>
 <body onload='javascript:filter_live_vehicle();'>
     <FORM method="GET" name="form1"> 
 
        <input type="hidden" name="lat">
    		<input type="hidden" name="lng">
    		<input type="hidden" name="vid2">
    		<input type="hidden" name="last_marker">
    		<input type="hidden" name="pt_for_zoom">
    		<input type="hidden" name="zoom_level">
    		<input type="hidden" name="current_vehicle">
    		<input type="hidden" name="cvflag">
    		<input type="hidden" name="mapcontrol_startvar">
    		<input type="hidden" name="StartDate">
    		<input type="hidden" name="EndDate">
    		<input type="hidden" name="vehicleSerial">
    		<input type="hidden" name="StartDate1">
    		<input type="hidden" name="EndDate1">  
     </form>
<script type="text/javascript">
//LIVE JS MODULE
var label_type = "<?php echo $report_type; ?>";
var lvIcon1 = new GIcon(); 
lvIcon1.image = '../../../images/live/live_vehicle.gif';
lvIcon1.iconSize = new GSize(8, 8);
lvIcon1.iconAnchor = new GPoint(2, 7);
lvIcon1.infoWindowAnchor = new GPoint(3, 10);

var pt = new Array();
var imei1 = new Array();
var vname1 = new Array();
var marker1 = new Array();
var imei_iotype_arr=new Array();
  
var map;
var marker_type;

var imei_tmp="";
var date_tmp ="";
var moving_status =0;
var imei_data;

var point_prev = new Array();  // FOR RUNNING PATH
var vid_prev = new Array();
var dist_prev = new Array();
var marker_prev = new Array();
var label_prev = new Array();
var date_prev = new Array();
var angle_prev = new Array();

function filter_live_vehicle()
{  
  
   //alert("in function");
  imei_data = "<?php echo $device_imei_no; ?>";
  //alert("imei_data="+imei_data);
  initialize();
  //alert("test");
	if (GBrowserIsCompatible()) 
  {	  			
  		//alert("in GBrowserIsCompatible")
  		map.clearOverlays();	
  }	
  /*if((browser=="Microsoft Internet Explorer") && (version>=4)) // for internet xeplorer
	{
		alert("Retrieving data ..... plz wait!");
	} */ 
  
    
  /*if(s1.length>0)
  {
  	//alert("In Moving V");  		
      
    for(var i=0;i<s1.length;i++)
    {
      var s2 = s1[i].split('#');
      imei_tmp = s2[0];
      date_tmp = s2[1];       
         
      alert("In Moving:"+imei_tmp);
      //alert("date_tmp1="+date_tmp);
      movingVehicle_prev(result);    // FOR MOVING VEHICLE FOR NOW       
      //LP_prev('.$stopped_vimei[$i].')              
    }
  }   */  
  
  pt = null;
  imei1 = null;
  vname1 = null;
  speed1 = null;
  fuel1 = null;
  datetime1 = null;
  marker1 = null;
  day_max_speed1 = null;
  day_max_speed_time1 = null;
  last_halt_time1 = null;  
  
  pt = new Array();        //  RE-Initialise marker array vairables
  imei1 = new Array();
  vname1 = new Array();
  speed1 = new Array();
  fuel1 = new Array();
  datetime1 = new Array();
  marker1 = new Array();
  day_max_speed1 = new Array();
  day_max_speed_time1 = new Array();
  last_halt_time1 = new Array();   
  
  point_prev = null;
  vid_prev = null;
  dist_prev = null;
  marker_prev = null;
  label_prev = null;
  angle_prev = null;
  trail_flag = false;
    
  point_prev = new Array();   //  RE-Initialise trail array vairables
  vid_prev = new Array();
  dist_prev = new Array();
  marker_prev = new Array();
  label_prev = new Array();
  angle_prev = new Array();
  date_prev = new Array(); 
  movingVehicle();

}

//////////////////  MAKE POST REQUEST  ///////////////////////

   var http_request = false;
   function makePOSTRequestMap(url, parameters) 
   {
      //alert("IN POST REQ");
      http_request = false;
      if (window.XMLHttpRequest) 
      { 
         http_request = new XMLHttpRequest();
         if (http_request.overrideMimeType)
         {
         	// set type accordingly to anticipated content type
            //http_request.overrideMimeType('text/xml');
            http_request.overrideMimeType('text/html');
         }
      }
      else if (window.ActiveXObject) 
      { // IE
         try 
         {
            http_request = new ActiveXObject("Msxml2.XMLHTTP");
         }
         catch (e) 
         {
            try 
            {
               http_request = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e)
            {}
         }
      }
      if (!http_request) 
      {
         alert('Cannot create XMLHTTP instance');
         return false;
      }
      
      http_request.onreadystatechange = alertContentsMap;
      http_request.open('POST', url, true);
      http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http_request.setRequestHeader("Content-length", parameters.length);
      http_request.setRequestHeader("Connection", "close");
      http_request.send(parameters);
   }
   
  function alertContentsMap()
  {
   //alert("IN alert CNT");
    if (http_request.readyState == 4) 
    {
       if (http_request.status == 200) 
       {
          result = http_request.responseText;
          //alert("res="+result);
       }
    }
  } 
  
  var startup_var;    
var newurl;      ///////for change url every time
newurl=0;
var delaycnt=0;
var thisdest;
var thismode;
var thisaccess;
var timer;
var TryCnt;
var MAX_TIMELIMIT=1000;


var dist;
dist = 0;
var tmp_dist;
tmp_dist=0;

/// load xml code

var browser=navigator.appName;
var b_version=navigator.appVersion;
var version=parseFloat(b_version);

function loadXML(xmlFile)
{
	//alert("Please wait sending request...");
	
  var xmlhttp=false;
	var status = false;
	var xmlDoc=null;		
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') 
	{
		try
		{
			xmlhttp = new XMLHttpRequest();
		} 
		catch (e)
		{
			xmlhttp=false;
		}
	}
	if (!xmlhttp && window.createRequest)
	{
		try 
		{
			xmlhttp = window.createRequest();
		} 
		catch (e)
		{
			xmlhttp=false;
		}
	}
  
	newurl++;	
	xmlFile=xmlFile+"?newurl="+newurl; 
	xmlhttp.open("GET",xmlFile,false);
	xmlhttp.send(null);
	var finalStr = xmlhttp.responseText	
	//alert("final_str="+finalStr);	
	if (window.DOMParser)
	{
		parser=new DOMParser();
		xmlDoc=parser.parseFromString(finalStr,"text/xml");
	}
	else // Internet Explorer
	{
		try
		{
			xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
			xmlDoc.async="false";
			status = xmlDoc.loadXML(finalStr);
		}
		catch(e1)
		{
		 xmlDoc=null;
		}
		if(status==false)
	    {
			xmlDoc=null;
		}
	}                 
	  return xmlDoc;
};	

function verify()    //for Internet Explorer
{
	if (xmldoc.readyState != 4)
	{
		return false;
	}
}
  

function movingVehicle()
{	
	//alert("in moving2");
	//alert(" DIST IN movingVehicle ="+dist+ " tmp_dist="+tmp_dist);
	tmp_dist = 0;

	var dmode;
	var startdate;
	var enddate;
	var status;
	var pt_for_zoom;
	var zoom_level;
 
 	
 	startdate = "<?php echo $start_date; ?>";
	enddate = "<?php echo $end_date; ?>";
        //alert("start_date="+startdate+"enddate="+enddate);

	// pass zoom parameters
 	if(document.forms[0].pt_for_zoom.value==1 && document.forms[0].zoom_level.value==1)
	{
		status = "ON";
	}
	else
	{
		status = "OFF";
		pt_for_zoom = "0";
		zoom_level = "0";
	}
	
	load_live(dmode,startdate,enddate,pt_for_zoom,zoom_level,status);
 	document.form1.current_vehicle=1;
	//alert("MOVING2");
}

function load_live(dmode,startdate,enddate,pt_for_zoom,zoom_level,status)
{
	//alert("in load");
	marker_type = lvIcon1;
  Load_MovingData(startdate,enddate,pt_for_zoom,zoom_level,status);	
	//alert("MOVING3");
	//auto_refresh();
}

var dist_array = new Array();

function Load_MovingData(startdate,enddate,pt_for_zoom,zoom_level,status)
{  
	//alert("In load moving data");
	
  if (GBrowserIsCompatible()) 
  {	  			
  		//alert("in GBrowserIsCompatible")
  		//map.clearOverlays();	
  		//alert('user_date='+user_dates.length+'vehicleserial='+vehicleSerial);
  		//if(imei_tmp!=null)
  		{
  		 //alert('check');
        var date = new Date();
        // COPY ORIGINAL XML FILE        
        var dest = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
       var exists = isFile(dest);
       // alert("exists="+exists);
        dmode = 1; 
        thisdest = dest;        
        //thisaccess = access;
        //var dest = "xml_tmp/filtered_xml/tmp_1295185453465.xml" ;
        //alert("d="+dest);        
        
        // MAKE FILTERED COPY        
        var poststr = "xml_file=" + encodeURI( dest )+
                "&mode=" + encodeURI( dmode )+
                "&vserial=" + encodeURI( imei_data )+
                "&startdate=" + encodeURI( startdate )+
                "&enddate=" + encodeURI( enddate );                       
                                                                   
        //alert("poststr1="+poststr);
        makePOSTRequestMap('get_consignment_filtered_xml_live.php', poststr);
        
		    //makePOSTRequestMap('src/php/get_filtered_xml.php', poststr);			
			  thisdest = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
                            var exists = isFile(thisdest);
        //alert("exists_1="+exists);
        TryCnt =0;
        clearTimeout(timer);
        timer = setTimeout('displayInfo_live()',1000);        
         	 
      } // if vid closed
   } //is compatible closed
} //function load1 closed


function displayInfo_live()
{  
  //alert("in displayInfo main");
  var lat_arr = new Array();
  var lng_arr = new Array();
  var vid_arr = new Array();
  var vehiclename_arr = new Array();
  var place_arr = new Array();
  var vehicletype_arr = new Array();
 
  

  
  
      
  var xml_data; 
  var DataReceived = false; 
   
  try
  {
    var bname = navigator.appName;
    //alert("bname="+bname);      
    /*if (bname == "Microsoft Internet Explorer")
    {
      alert("Wait for data, please use Mozilla for better compatibility");
    }//alert(bname);   */
              
    var xmlObj = null;        
    //alert("thisdest="+thisdest);
    xmlObj = loadXML(thisdest);
     
    //alert("xmObj="+xmlObj); 
       
    if (bname == "Microsoft Internet Explorer")
    {
      //alert("In IE:"+xmlObj);
      if(xmlObj!=null)	
      {                                      
        xml_data = xmlObj.documentElement.getElementsByTagName("x");
        DataReceived = true;
      } 
      else
      {
        if(TryCnt<=MAX_TIMELIMIT)
        {
          TryCnt++;
          clearTimeout(timer);
          timer = setTimeout('displayInfo()',1000);
        }
      }                                
    }
    else
    {
      //alert("In Mozilla");
      xml_data = xmlObj.documentElement.getElementsByTagName("x");
      //alert("marker length :"+xml_data.length);
      //var xml_data1 = xmlObj.getElementsByTagName("t1");
      var xml_data1 = xmlObj.documentElement.getElementsByTagName("a1");
	    // alert("length 2:"+xml_data1.length);
      if(xml_data1.length>0)
      {
         /* if (GBrowserIsCompatible()) 
          {	  			  		      
  		      map.clearOverlays();
  		    } */
              
        //alert("A");
        DataReceived = true;
      }
      else
      {
        //alert("B:"+TryCnt);
        if(TryCnt<=MAX_TIMELIMIT)
        {
          TryCnt++;
          clearTimeout(timer);
          timer = setTimeout('displayInfo_live()',1000);
        }
      }
    }   
    //alert("xml_data="+xml_data);             
	}
	catch(err)
	{
		//alert("file not found");
	}	
    

	
  if((((xml_data.length==0) || (xml_data.length==undefined)) && (DataReceived==true)) || (TryCnt>=MAX_TIMELIMIT))
	{	
	  //alert("No Data Found");
    document.getElementById('prepage').style.visibility='hidden';	
    clearTimeout(timer);	
    var poststr = "dest=" + encodeURI( thisdest );
	  makePOSTRequest('src/php/del_xml.php', poststr);								
	}
	else  if(DataReceived==true)
	{	
	  
    
    clearTimeout(timer);
		var len2=0;
		
    /////////////// GET IO ///////////////
    var imei = xml_data[0].getAttribute("v")		
		var str = imei+",temperature"; 
       
		
		for (var k = 0; k < xml_data.length; k++) 
		{																													
                    //alert("t11111111==="+xml_data[k].getAttribute("datetime"));												
                    lat_tmp = xml_data[k].getAttribute("d");
                    lng_tmp = xml_data[k].getAttribute("e");	
                        
                    lat_arr[len2] = xml_data[k].getAttribute("d");
                    lng_arr[len2] = xml_data[k].getAttribute("e");
                    vid_arr[len2] = xml_data[k].getAttribute("v");
                    vehiclename_arr[len2] = xml_data[k].getAttribute("w");
                   // alert("vehicle_name="+vehiclename_arr);
                    len2++;		
		}	//XML LEN LOOP CLOSEDhaa
		
	 
    if(len2>0)
    {	      
      //alert("record found");
      clearTimeout(timer);
      //alert("data found");
      //document.form1.status.value = "["+ vid_arr[0]+ "]"+"-("+lat_arr[0]+","+lng_arr[0]+")";
      //alert("LPCOUNT="+lp_count);
      //alert("before plottin markers "+len2+" "+lat_arr+" "+lng_arr+" "+vid_arr+" "+vehiclename_arr+" "+speed_arr+" "+datetime_arr+"  VTYPE="+vehicletype_arr);				
      var flag=1;
      getxml_MovingData(len2, flag, lat_arr, lng_arr, vid_arr, vehiclename_arr);	  //HERE ARR IS JUST NORMAL VARIABLE NOT ARRAY
      //alert("K");
      document.getElementById('prepage').style.visibility='hidden';	
    }					
	} // ELSE CLOSED       		
}
function getxml_MovingData(len2, flag1, lat_arr, lng_arr, vid_arr, vehiclename_arr)
{
	if(vid_arr.length<=0)
	{
		flag1=0;
	}	
	
	if(flag1)
	{
		var point;	
		//alert("startup="+startup_var);
		if(startup_var == 1)
		{
			var bounds = new GLatLngBounds();
			for(var z=0;z<lat_arr.length;z++)
			{
				point = new GLatLng(parseFloat(lat_arr[z]),
							parseFloat(lng_arr[z]));

				bounds.extend(point); 
			}			
			var center = bounds.getCenter(); 			
			
			if(len2>0 && len2<2)
				var zoom = map.getBoundsZoomLevel(bounds)-7; 
			else if(len2>2 && len2<6)
				var zoom = map.getBoundsZoomLevel(bounds)-1; 
			else
				var zoom = map.getBoundsZoomLevel(bounds)-1;							
			/*if(access=="Zone")
			{
				//alert("access="+access+"ms="+ms);
				show_milestones();
				//alert("access="+access);
			//map.setCenter(center,zoom); 
			}
			else
			{*/
				map.setCenter(center,zoom);
			//}
			startup_var = 0;
		}		
		
		//alert("L0");
    Moving_DataMarkers(lat_arr,lng_arr,vid_arr,vehiclename_arr,len2);

		var zoom;
		var event = 0;
		var newzoomlevel=0;		
		
		//alert("L1");
    getLandMark1(event,newzoomlevel);
		
		////////////////////// CALL GET LANDMARK ON EVENT LISTENER FOR LAST POSITION //////////////////////////
		GEvent.addListener(map, 'zoomend',function (oldzoomlevel,newzoomlevel) 
		{
			var event =1;
			getLandMark1(event,newzoomlevel);
		}); //GEvent addListener												
	}//if flag1 closed			
 	//document.getElementById('prepage').style.visibility='visible';								
		//////////////////////////////////////////////////////////////			
} //FUNCTION getxmlDataTrack
 ///////////////////////track markers////////////////////////////////////

var vlist = "";

function Moving_DataMarkers(lat_arr,lng_arr,vid_arr,vehiclename_arr,len2)
{	
//alert("in moving datamarkdere")
        var gmarkersA = new Array();      
	var gmarkersB = new Array();  
	var gmarkersC = new Array();  
	
	var j = 0;
	var colr = ["#00FF66","#0066FF","#FF0000","#33FFFF","#FF33CC","#9966FF","#FF9900","#FFFF00"];
	var i,vehiclename,speed,point,datetime,place,marker,polyline,last,running_status1, day_max_speed,day_max_speed_time,last_halt_time;
  
	var vid1=0;
	var vid2=0;
	var pt = new Array();
	var value = new Array();
	var poly = new Array();
	var dist = 0;
	var lastmarker = 0;
	var p = 0;
	var fuel=0;
  var lat1,lat2,lng1,lng2,coord;
  for (i = 0; i < len2; i++) 
	{		
    var total_dist = 0;
    
    vid = vid_arr[i];
		vehiclename = vehiclename_arr[i];
                point = new GLatLng(parseFloat(lat_arr[i]),parseFloat(lng_arr[i]));
		pt[i] = point;
		place=0;
               map.setCenter(point , 12);                                                                                                        
    marker = Create_MovingDataMarkers(point, vid, vehiclename, len2,gmarkersC,p);
        
    
    ////////////////////////////////
            						
		gmarkersA.push(marker);

		if(i==len2-1)
		{
		  		  
          			
      for(var m=0;m<gmarkersA.length;m++)
			map.addOverlay(gmarkersA[m]);				
			document.getElementById('prepage').style.visibility='hidden';					
		}
		
		
		p++;
	} //for i loop closed	
		
		
} //track markers closed
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////


var lat1 = 0;
var lng1 = 0;
var lat2 = 0;
var lng2 = 0;
var coord;
pt = new Array();
imei1 = new Array();
vname1 = new Array();

//var mm;
var rect;	

function Create_MovingDataMarkers(point, imei, vehiclename, len2, gmarkersC,p) 
{  
   // alert("in function 1")
        vIcon= new GIcon(lvIcon1);
         //alert("in function 2")
        //vIcon = IconArrow;	
        pt[p] = point;
        imei1[p] = imei;
        vname1[p] = vehiclename;
      //alert("vname="+vname1[p])	;
	
	var lat = point.lat(); 
	var lng = point.lng();	

	var marker = new GMarker(point, vIcon);
	marker1[p] = marker;  
  PlotLastMarkerWithAddress(point, vIcon, marker, imei, vehiclename);

  
  GEvent.addListener(marker, 'mouseover', function()
  {			
  	PlotLastMarkerWithAddress(point, vIcon, marker, imei, vehiclename);
  });  

	return marker;		
}				

/////////////////////////////////////////////////////////////////////////////////////////////////////



var ad=0;
var place;
var address1=0;

////////////////////////// PLOT TRACK MARKERS WITH ADDRESSES ////////////////////////////////////////
function PlotLastMarkerWithAddress(point, Icon, marker, imei, vehiclename) 
{
 //alert("IN PLOT:"+point+":"+Icon+":"+marker+":"+imei+":"+vehiclename);
  var window_style1="style='color:#000000;font-family: arial, helvetica, sans-serif; font-size:11px;text-decoration:none;font-weight:bold;'";
var window_style2="style='color:blue;font-family: arial, helvetica, sans-serif; font-size:11px;text-decoration:none;'";
 var accuracy;
 var largest_accuracy;	   
 var delay = 100;
 var geocoder = new GClientGeocoder();
 var address_tmp;
 var address1_tmp;
 var BadAddress=0;

 geocoder.getLocations(point, function (result) {

 if (result.Status.code == G_GEO_SUCCESS) // OR !=200
 {
	 var j;
   //j=0;
   // Loop through the results, looking for the one with Accuracy = 1

   for (var i=0; i<result.Placemark.length; i++)
   {
    accuracy = result.Placemark[i].AddressDetails.Accuracy;
    
    address_tmp = result.Placemark[i];
    address1_tmp = address_tmp.address;

	    //alert("address1_tmp="+address1_tmp+" accuracy="+accuracy);         
		if(i==0)
		{
			largest_accuracy = accuracy; 
			j = i;

			if ((address1_tmp.indexOf("NH") !=-1) || (address1_tmp.indexOf("National Highway") !=-1) || (address1_tmp.indexOf("State Highway") !=-1))
			{
				BadAddress = 1;
			}
		}

		else 
	    {	
		   //alert(" largest accuracy="+largest_accuracy+" accuracy="+accuracy+" i="+i);
			if((largest_accuracy < accuracy) || ((BadAddress == 1) && (accuracy>2)))
			{
				largest_accuracy = accuracy;
				//alert("i="+i);
				j = i;
				//alert("j1========="+j);
				if ((address1_tmp.indexOf("NH") !=-1) || (address1_tmp.indexOf("National Highway") !=-1) || (address1_tmp.indexOf("State Highway") !=-1))
				{
					BadAddress = 1;
				}
				else
				{
					BadAddress = 0;
				}
			}
		}
   }
	   
		var address = result.Placemark[j];
		address1 = address.address;	
		var google_point = new GLatLng(address.Point.coordinates[1],address.Point.coordinates[0]);

		var distance = calculate_distance(point.lat(), google_point.lat(), point.lng(), google_point.lng()); 
		//alert("dist="+distance);
		var address2 = distance+" km from "+address1;
                //alert("address2="+address2);
                var place = address2;
                document.getElementById("text_place_name").innerHTML=place;
                document.getElementById("show_text_location").style.display="";
                var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="125px;" align=left><table cellpadding=0 cellspacing=0><tr><tr><td '+window_style1+'>Place</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+place+'</td></tr></table></div>');
                var infoTabs = [tab1];	
                marker.openInfoWindowTabsHtml(infoTabs);
		
     }
  });
}
 //////////////////////////////////////////////////////////////////////////////////////////////////////

function trim(value) 
{
  value = value.replace(/^\s+/,'');
  value = value.replace(/\s+$/,'');
  return value;
}

 ////////info landmark window table//////////////
function pretty(a) 
{
	return '<table border="0" cellpadding="0" cellspacing="0"><tr><td width="100%" class="EWTitle" nowrap>' + a + '</td></tr>' +
		   '<tr><td nowrap></td></tr></table>';
}
 
///////////Show landmark window marker////////////////
function ShowMarker(point, landmark) 
{		
	//alert("pt="+point);
	//alert("lnmrk="+landmark);
 	var Icon= new GIcon(lnmark);
	var marker = new GMarker(point,Icon);
	var marker2 = new GMarker(point,Icon);
 	var lat = Math.round((point.lat())*100000)/100000;
	var lng = Math.round((point.lng())*100000)/100000;
 	//var iwform = pretty('<center>LANDMARK <br>'
		//+ '  <font color="blue" size=3><strong>'+landmark+'</strong></font></center><br>');
 	var iwform = '<table bgcolor="#EEEFF0" border="0"><tr><td><table border="0" cellpadding=1 cellspacing=0><tr><td>&nbsp;</td></tr><tr><td><font size=3 color=#000000><b>LANDMARK</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=red size=3><b>'+landmark + '</b></font></td><td></td></tr><tr><td>&nbsp;</td></tr><tr><td><font size=2 color=#000000><b>Latitude</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lat+'</font></td></tr><tr><td></td></tr><tr><td><font size=2 color=#000000><b>Longitude</b></font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lng+'</font></td></tr></table></td></tr></table>';
        
	// ========== Open the EWindow instead of a Google Info Window ==========
	GEvent.addListener(marker, "click", function() {
	  //ew.openOnMarker(marker,iwform);
	///////////////////////MINI MAP CODE////////////////////
 	var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=center><br><font color=#000000'+iwform+'</font></div>');
 	//var html = new GInfoWindowTab("Info", '<div id="tab1" class="bubble">Click the "Location" tab to see the minimap</div>');
	var tab2 = new GInfoWindowTab("Location", '<div id="detailmap" style="height:150px;"></div>');
	var infoTabs = [tab1,tab2];
	marker.openInfoWindowTabsHtml(infoTabs);
 	var dMapDiv = document.getElementById("detailmap");
	var detailMap = new GMap2(dMapDiv);
	detailMap.setCenter(point , 12);
 	detailMap.removeMapType(G_SATELLITE_MAP);																
 	var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(0,0));	
	detailMap.addMapType(G_SATELLITE_MAP);
	var mapControl = new GMapTypeControl();
	detailMap.addControl(mapControl, topRight);
 	var topLeft = new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(5,30));
	var mapControl2 = new GSmallMapControl();
	//detailMap.addControl(new GSmallMapControl());
	GEvent.addListener(detailMap, "zoomend", miniMapZoomEnd);
	GEvent.addListener(detailMap, "moveend", miniMapMoveEnd);
	detailMap.addControl(mapControl2, topLeft);
 	var CopyrightDiv = dMapDiv.firstChild.nextSibling;
	var CopyrightImg = dMapDiv.firstChild.nextSibling.nextSibling;
	CopyrightDiv.style.display = "none"; 
	CopyrightImg.style.display = "none";
	detailMap.addOverlay(marker2);
 	showMinimapRect(detailMap,point);	
  /////////////////////////////////////////////////////////////////////////////////////////////////////
	});     
 	 // ========== Close the EWindow if theres a map click ==========
	GEvent.addListener(map, "click", function(overlay,point) {
		if (!overlay) {
		  ew.hide();
		}
	});
 	return marker;
}

function LP_prev(vserial)
{
	//alert("lp prev");
  if (GBrowserIsCompatible()) 
  {	  			
  		//alert("in GBrowserIsCompatible")
  		map.clearOverlays();	
  }	
  if((browser=="Microsoft Internet Explorer") && (version>=4)) // for internet xeplorer
	{
		alert("Retrieving data ..... plz wait!");
	}

	//alert("In Moving Visible="+document.getElementById('prepage').style.visibility);
	document.getElementById('prepage').style.visibility='visible';	
	//alert("In Moving Visible="+document.getElementById('prepage').style.visibility);
  //alert(" DIST IN movingVehicle_prev ="+dist+ " tmp_dist="+tmp_dist);
	//alert("in mprev");
	document.form1.mapcontrol_startvar.value=1;
	startup_var = 1;
	map.clearOverlays();
	
	document.forms[0].vehicleSerial.value = vserial;
	//alert("vserial in moving vehicle ="+document.forms[0].vehicleSerial.value);
  // REFRESH VALUES
	dist= 0;
	tmp_dist = 0;
	document.form1.vid2.value ="";
	document.form1.lat.value ="";
	document.form1.lng.value ="";
	lm = 0;
	
	geocoder = null;
	address = null;
 	ad=0;
	address1=0;	
	//alert("before load")

	LP_Vehicle();

	//alert("MOVING1");
	//auto_refresh();
	//alert("after load");
}

function LP_Vehicle()
{	
	//alert("in LP vehicle");
	//alert(" DIST IN movingVehicle ="+dist+ " tmp_dist="+tmp_dist);
	tmp_dist = 0;

	var dmode;
	var startdate;
	var enddate;
	var status;
	var pt_for_zoom;
	var zoom_level;
 
 	///////////// get date ////////////////////////
 	var currentDate2 = new Date;
 	var yr = currentDate2.getFullYear();
	var mnt =  currentDate2.getMonth()+1;
	var dt =  currentDate2.getDate();
	var hr = currentDate2.getHours();
	var min = currentDate2.getMinutes();
	var sec = currentDate2.getSeconds();
 	if(mnt>0&&mnt<10)
		mnt = "0"+mnt ;
 	if(dt>0&&dt<10)
		dt = "0"+dt;
 	if(hr>0&&hr<10)
		hr = "0"+hr;
 	if(min>0&&min<10)
		min = "0"+min;
 	if(sec>0&&sec<10)
		sec = "0"+sec;
 	startdate = yr+"-"+mnt+"-"+dt+" 00:00:00";
	enddate = yr+"-"+mnt+"-"+dt+" "+hr+":"+min+":"+sec;

	// pass zoom parameters
 	if(document.forms[0].pt_for_zoom.value==1 && document.forms[0].zoom_level.value==1)
	{
		status = "ON";
	}
	else
	{
		status = "OFF";
		pt_for_zoom = "0";
		zoom_level = "0";
	}
	
	var vid;
	vid = document.forms[0].vehicleSerial.value;
	//alert(vid+".xml");
 	////////// CALL MAIN FUNCTION ///////////////
	//alert("before load call");
	//alert("st="+startdate+" ed="+enddate);
	//var access = document.forms[0].access.value;
	load_lp(vid,dmode,startdate,enddate,pt_for_zoom,zoom_level,status);
 	document.form1.current_vehicle=1;

	//alert("MOVING2");
}

 ////////////////function load /////////////////////////////////////////////
function load_lp(vid,dmode,startdate,enddate,pt_for_zoom,zoom_level,status)
{
	//alert("in load");
	marker_type = lvIcon2;
  Load_MovingData(vid,startdate,enddate,pt_for_zoom,zoom_level,status);
  clearTimeout(timer);		
}


//////////////////////////////////////////////////////////////////////////////////////////////////////
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
 
 function isFile(str){
var O= AJ();
if(!O) return false;
try
{
O.open("HEAD", str, false);
O.send(null);
return (O.status==200) ? true : false;
}
catch(er)
{
return false;
}
}
function AJ()
{
var obj;
if (window.XMLHttpRequest)
{
obj= new XMLHttpRequest();
}
else if (window.ActiveXObject)
{
try
{
obj= new ActiveXObject('MSXML2.XMLHTTP.3.0');
}
catch(er)
{
obj=false;
}
}
return obj;
}

function miniMapZoomEnd(oldZ,newZ) 
{
	showMinimapRect(this);
}

function miniMapMoveEnd() 
{
	showMinimapRect(this);
}

function showMinimapRect(detailMap,point) 
{
	if (rect)
	{
		map.removeOverlay(rect);
	}
	var bounds = detailMap.getBounds();
	var polyPoints = [	bounds.getSouthWest(),
						new GLatLng(bounds.getSouthWest().lat(),bounds.getNorthEast().lng()),
						bounds.getNorthEast(),
						new GLatLng(bounds.getNorthEast().lat(),bounds.getSouthWest().lng()),
						bounds.getSouthWest()
					]

	rect = new GPolygon(polyPoints, '#ff0000', 2, 1, '', 0.5);	
	map.addOverlay(rect);

}

function mapIWClose() 
{
	if (rect)
	{
		map.removeOverlay(rect);
	}
}

function getXMLHTTP()
{
	http_request=false;
	if (window.XMLHttpRequest)
	{
		http_request = new XMLHttpRequest();
	} 
	else if (window.ActiveXObject) 
	{
		http_request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return http_request;
}


/*function checkbox_selection(obj)
{
	var result_1 = {};
	var flag=0;
	var cnt=0;
	var value_str="";
	if(obj.length!=undefined)
	{
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].checked==true)
			{				
				if(cnt==0)
				{			
					var value_tmp=obj[i].value;
					result_1[value_tmp]=value_tmp;
					cnt=1;
				}
				else
				{			
					var value_tmp=obj[i].value;
					result_1[value_tmp]=value_tmp;					
				}
				flag=1;
			}	  
		}
	}
	else
	{
		if(obj.checked==true)
		{
			value_str=obj.value;
			flag=1;
		}
	}
	if(flag==0)
	{
		alert("Please Select Atleast One Option");
		return false;
	}
	else
	{
		if(cnt==1)
		{
			for(id in result_1) 
			{				
				if(result_1.hasOwnProperty(id)) 
				{ 
					value_str=value_str+result_1[id]+",";
				}
			}
			var strLen = value_str.length;
			value_str = value_str.slice(0,strLen-1);		
		}
		//alert("value_str="+value_str);
		return value_str;
	}
}*/
function checkbox_selection(obj)
{
	imei_iotype_arr.length=0;
	var result_1 = {};
	var flag=0;
	var cnt=0;
	var value_str="";
	if(obj.length!=undefined)
	{
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].checked==true)
			{				
				if(cnt==0)
				{			
					var value_1=obj[i].value;				
					result_1[value_1]=value_1;
					cnt=1;
				}
				else
				{			
					var value_1=obj[i].value;
				//	aler("value_str="+obj[i].value);
					result_1[value_1]=value_1;					
				}
				flag=1;
			}	  
		}
	}
	else
	{
		if(obj.checked==true)
		{
			var value_1=obj.value;
			result_1[value_1]=value_1;			
			flag=1;	
		}
	}
	if(flag==0)
	{
		alert("Please Select Atleast One Option");
		return false;
	}
	else
	{
		if(flag==1)
		{
			for(id in result_1) 
			{				
				if(result_1.hasOwnProperty(id)) 
				{			
					var vid_local=(result_1[id]).split("*");
					if(vid_local[1]!="tmp_str")
					{
						imei_iotype_arr[vid_local[0]]=vid_local[1];				
					}					
					//vid=vid+vid_local[0]+",";
					value_str=value_str+vid_local[0]+",";
				}
			}
			var strLen = value_str.length;
			value_str = value_str.slice(0,strLen-1);		
		}
		return value_str;
	}
}

/*function checkbox_selection(obj)
{
	var flag=0;
	var cnt=0;
	var id="";
	if(obj.length!=undefined)
	{
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].checked==true)
			{				
				if(cnt==0)
				{
					id= id + obj[i].value;
					cnt=1;
				}
				else
				{
					id=id +","+ obj[i].value;
				}
				flag=1;
			}	  
		}
	}
	else
	{
		if(obj.checked==true)
		{
			id=obj.value;
			flag=1;
		}
	}
	if(flag==0)
	{
		alert("Please Select Atleast One Option");
		return false;
	}
	else
	{	  
		return id;
	}
}*/


//////////////////  MAKE POST REQUEST  ///////////////////////

   
  
  
  /////////// CODE FOR SELECTING LANDMARK ON ZOOM ///////////////////////////////////////

function getLandMark1(event,newzoomlevel)
{
	//alert("landmark");
  var newzoomlevel= map.getZoom();				
	GDownloadUrl("src/php/select_landmark.php", function(data)
    {								
		var xml = GXml.parse(data);
		var lnmark_data = xml.documentElement.getElementsByTagName("marker");	
		//alert("landmark length="+lnmark_data.length);
		var i;
		var landmark;	
		var markerL;
		var zoomlevel;
		var point;	
																							
		for(i=0; i <lnmark_data.length; i++) 
		{									 					
			point = new GLatLng(parseFloat(lnmark_data[i].getAttribute("lat")),
			parseFloat(lnmark_data[i].getAttribute("lng")));				

			zoomlevel = lnmark_data[i].getAttribute("zoomlevel");					
			landmark = lnmark_data[i].getAttribute("landmark");
													
			//alert("zoomlevel="+zoomlevel+" , newzoomlevel="+newzoomlevel);
			if(zoomlevel == newzoomlevel || zoomlevel<newzoomlevel)
			{
				markerL = ShowMarker(point, landmark);	
				map.addOverlay(markerL);
			}			
		}																																
	});	 // GDownload url closed		
}  

</script>
 <style>
          table.main
            {
                font-size: 10pt;
                margin: 0px;
                padding: 0px;
                font-weight: normal;             
            }
          </style>
<table width="70%" align="center" cellspacing="0" cellpadding="0">
    <tr>
        <td>
           <table width="100%" cellspacing="0" cellpadding="0" bgcolor="#C6C3FF">
                <tr>
                    <td>
                        <table class="main" >
                            <tr>
                                <td align="left">
                                    <b>Vehicle Name </b>
                                </td>
                                <td>
                                    :
                                </td>
                                <td align="left">
                                    <?php echo $vehicle_name ?>
                                </td>
                            </tr>
                             <tr>
                                <td align="left">
                                   <b> Consignee Name </b>
                                </td>
                                <td>
                                    :
                                </td>
                                <td align="left">
                                    <?php echo $consignee_name ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table class="main">
                            <tr>
                                <td align="left">
                                   <b> From Place </b>
                                </td>
                                <td>
                                    :
                                </td>
                                <td align="left">
                                    <?php echo $from_place ?>
                                </td>
                            </tr>
                             <tr>
                                <td align="left">
                                  <b>  To Place </b>
                                </td>
                                <td>
                                    :
                                </td>
                                <td align="left">
                                    <?php echo $to_place ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table class="main">
                            <tr>
                                <td align="left">
                                  <b>  From Date </b>
                                </td>
                                <td>
                                    :
                                </td>
                                <td align="left">
                                    <?php echo $start_date ?>
                                </td>
                            </tr>
                             <tr>
                                <td align="left">
                                  <b>  To Date </b>
                                </td>
                                <td>
                                    :
                                </td>
                                <td align="left">
                                    <?php echo $end_date ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
           </table>
        </td>
    </tr>
     <tr>
        <td>
           <div id="map" style="height:400px;width:100%"></div>
        </td>
    </tr>
     <tr id="show_text_location" style="display:none" bgcolor="#C6C3FF">
         <td align="center">   
             <table class="main" border="0" >
                 <tr>
                       <td align="right">
                           <b> Last Location </b>
                       </td>
                       <td>
                           :
                       </td>
                       <td id="text_place_name" align="left">
                           &nbsp;
                       </td>
                 </tr>
             </table>
         </td>
    </tr>
</table>


</body>
</html>
</html>
</html>
<?php
   }
  ?>